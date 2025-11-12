<?php
namespace app\controllers;

use League\Plates\Engine;
use app\repositories\RepoEmpresa;
use app\repositories\RepoUser;
use app\helpers\Login;
use app\helpers\Session;
use app\helpers\Paginator;
use app\helpers\Validator;
use app\models\User;
use app\models\Empresa;

/**
 * Controlador encargado de gestionar las operaciones CRUD y vistas relacionadas con las empresas.
 * 
 * @package app\controllers
 */
class EmpresaController {
    /** @var Engine Motor de plantillas Plates */
    private $templates;

    /** @var User Usuario actualmente logueado */
    private $user;

    /** @var string Página actual (obtenida desde GET) */
    private $page;

    /**
     * Constructor: inicializa el motor de plantillas, la sesión del usuario y la página actual.
     * 
     * @param string $platePath Ruta a las plantillas de Plates
     */
    public function __construct($platePath) {
        $this->templates = new Engine($platePath);
        $this->user = Session::readSession('user');
        $this->page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'empresas';
    }

    /**
     * Método principal del controlador: determina qué acción ejecutar según el parámetro GET['accion'].
     * Redirige si el usuario no tiene permisos.
     * 
     * @return void
     */
    public function index() {
        if (Login::isLogin() && $this->user->getRol() == 1) {
            $accion = filter_input(INPUT_GET, 'accion', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'listado';

            switch ($accion) {
                case 'listado':
                    $this->manejarListado($accion);
                    break;
                case 'solicitudes':
                    $this->manejarSolicitudes($accion);
                    break;
                case 'inscribir':
                    $this->manejarNewEmpresa($accion);
                    break;
                default:
                    header('location:?page=empresas');
                    exit;
            }
        } else {
            header('location:?page=home');
            exit;
        }
    }

    // =====================================================
    // MÉTODOS DE MANEJO DE ACCIONES
    // =====================================================

    /**
     * Muestra el listado de empresas activas o procesa acciones POST sobre ellas.
     * 
     * @param string $accion Acción actual
     * @return void
     */
    private function manejarListado($accion) {
        $repo = new RepoEmpresa();
        $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) ?? [];

        if (isset($postData['accion'])) {
            $this->chooseAction($repo, $accion, $postData);
        } else {
            $this->paginacionListadoEmpresas(true, $repo, $accion);
        }
    }

    /**
     * Muestra y gestiona las solicitudes de empresas (pendientes de aprobación).
     * 
     * @param string $accion Acción actual
     * @return void
     */
    private function manejarSolicitudes($accion) {
        $repo = new RepoEmpresa();
        $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) ?? [];

        if (isset($postData['accion'])) {
            $this->chooseAction($repo, $accion, $postData);
            if ($postData['accion'] === 'aceptar') {
                $this->activateEmpresa($repo, $accion, $postData);
            }
        } else {
            $this->paginacionListadoEmpresas(false, $repo, $accion);
        }
    }

    /**
     * Procesa y valida los datos de una nueva empresa antes de guardarla.
     * 
     * @param Empresa $empresa Objeto empresa a procesar
     * @param Validator $validator Instancia del validador
     * @param array $postData Datos enviados por formulario
     * @param array|null $fileData Datos del archivo subido
     * @return void
     */
    private function procesarNuevaEmpresa(Empresa $empresa, Validator $validator, array $postData, ?array $fileData): void {
        $validator->requiredFile('foto');
        $this->validarEmpresa($validator, $postData, $fileData);
        $this->actualizarEmpresa($empresa, $postData, $fileData);

        if ($validator->validacionPasada()) {
            $repo = new RepoEmpresa();
            $empresa->setActivo(isset($postData['activo']));
            $repo->save($empresa);
            $validator->mensajeExito();
        }
    }

    /**
     * Renderiza el formulario de creación o edición de una empresa.
     * 
     * @param Empresa $empresa Empresa actual
     * @param Validator $validator Validador de campos
     * @param string $accion Acción actual
     * @return void
     */
    private function renderFormularioEmpresa(Empresa $empresa, Validator $validator, string $accion): void {
        echo $this->templates->render('Admin/AdminFormEmpresa', [
            'empresa' => $empresa,
            'validator' => $validator,
            'page' => $this->page,
            'accion' => $accion,
            'action' => $accion
        ]);
    }

    /**
     * Controla la lógica completa de registro de una nueva empresa.
     * 
     * @param string $accion Acción actual
     * @return void
     */
    private function manejarNewEmpresa(string $accion) {
        $validator = new Validator();
        $empresa = new Empresa();
        $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) ?? [];
        $fileData = $_FILES['foto'] ?? null;

        if (isset($postData['action'])) {
            $this->procesarNuevaEmpresa($empresa, $validator, $postData, $fileData);
        }

        echo $this->renderFormularioEmpresa($empresa, $validator, $accion);
    }

    // =====================================================
    // MÉTODOS AUXILIARES DE ACCIÓN (EDITAR, ELIMINAR, VER, GUARDAR)
    // =====================================================

    /**
     * Edita una empresa existente.
     * 
     * @param RepoEmpresa $repo Repositorio de empresas
     * @param string $pageAccion Página actual
     * @param array $postData Datos del formulario
     * @param string $accion Acción actual
     * @return void
     */
    private function editarEmpresa($repo, $pageAccion, $postData, $accion) {
        $id = filter_var($postData['id'] ?? null, FILTER_VALIDATE_INT);
        $empresa = $repo->findById($id);
        $validator = new Validator();

        if (isset($postData['action'])) {
            switch ($postData['action']) {
                case 'guardar':
                    $this->manejarGuardar($empresa, $validator, $repo, $postData, $_FILES['foto'] ?? null);
                    break;
                case 'cancelar':
                    header("location:?page=empresas&accion=".$pageAccion);
                    exit;
            }
        }

        echo $this->templates->render('Admin/AdminFormEmpresa', [
            'empresa' => $empresa,
            'validator' => $validator,
            'page' => $this->page,
            'accion' => $pageAccion,
            'action' => $accion
        ]);
    }

    /**
     * Muestra la vista de confirmación o elimina una empresa.
     * 
     * @param RepoEmpresa $repo Repositorio de empresas
     * @param string $pageAccion Página actual
     * @param array $postData Datos del formulario
     * @return void
     */
    private function eliminarEmpresa($repo, $pageAccion, $postData) {
        $id = filter_var($postData['id'] ?? null, FILTER_VALIDATE_INT);

        if (isset($postData['action'])) {
            switch ($postData['action']) {
                case 'eliminar':
                    if ($id) $repo->delete($id);
                case 'cancelar':
                    header("location:?page=".$this->page."&accion=".$pageAccion);
                    exit;
            }
        } else {
            $empresa = $repo->findById($id);
            echo $this->templates->render('Admin/AdminEliminarEmpresa', [
                'empresa' => $empresa,
                'page' => $this->page,
                'accion' => $pageAccion
            ]);
        }
    }

    /**
     * Muestra los detalles de una empresa seleccionada.
     * 
     * @param RepoEmpresa $repo Repositorio de empresas
     * @param string $pageAccion Página actual
     * @param array $postData Datos del formulario
     * @return void
     */
    private function verEmpresa($repo, $pageAccion, $postData) {
        if (isset($postData['action']) && $postData['action'] === 'cancelar') {
            header("location:?page=".$this->page."&accion=".$pageAccion);
            exit;
        } else {
            $id = filter_var($postData['id'] ?? null, FILTER_VALIDATE_INT);
            $empresa = $repo->findById($id);
            echo $this->templates->render('Admin/AdminVerEmpresa', [
                'empresa' => $empresa,
                'page' => $this->page,
                'accion' => $pageAccion
            ]);
        }
    }

    /**
     * Guarda los cambios realizados sobre una empresa validando los datos.
     * 
     * @param Empresa $empresa Empresa a modificar
     * @param Validator $validator Validador de campos
     * @param RepoEmpresa $repo Repositorio de empresas
     * @param array $postData Datos del formulario
     * @param array|null $fileData Archivo subido
     * @return void
     */
    private function manejarGuardar($empresa, $validator, $repo, $postData, $fileData) {
        $this->validarEmpresa($validator, $postData, $fileData);
        if ($empresa->getCorreo() == ($postData['correo'] ?? '')) {
            $validator->remove('correo');
        }
        $this->actualizarEmpresa($empresa, $postData, $fileData);
        if ($validator->validacionPasada()) {
            $validator->mensajeExito();
            $repo->update($empresa);
        }
    }

    // =====================================================
    // MÉTODOS DE VALIDACIÓN Y ACTUALIZACIÓN
    // =====================================================

    /**
     * Valida los campos de una empresa.
     * 
     * @param Validator $validator Instancia del validador
     * @param array $data Datos del formulario
     * @param array|null $fileData Archivo subido
     * @return void
     */
    private function validarEmpresa($validator, $data, $fileData) {
        $validator->validarCorreo('correo', $data);
        $validator->validarCorreo('correo_contacto', $data);
        $validator->validarTelefono('telefono_contacto', $data);
        $validator->validarNombre('nombre', $data);
        $validator->required('direccion', $data);
        $validator->required('descripcion', $data);

        $repo = new RepoUser();
        if (!empty($data['correo']) && $repo->correoExiste($data['correo'])) {
            $validator->insertarError('correo', "Este correo ya existe");
        }

        if ($fileData && $fileData['error'] === UPLOAD_ERR_OK) {
            $validator->isImagen($fileData['tmp_name']);
        }
    }

    /**
     * Actualiza los datos de una empresa con los valores recibidos.
     * 
     * @param Empresa $empresa Objeto empresa a actualizar
     * @param array $data Datos del formulario
     * @param array|null $fileData Archivo subido
     * @return void
     */
    private function actualizarEmpresa($empresa, $data, $fileData) {
        $foto_url = $this->guardarFoto($empresa, $fileData);
        $empresa->setNombre($data['nombre'] ?? $empresa->getNombre());
        $empresa->setCorreo($data['correo'] ?? $empresa->getCorreo());
        $empresa->setCorreoContacto($data['correo_contacto'] ?? $empresa->getCorreoContacto());
        $empresa->setTelefonoContacto($data['telefono_contacto'] ?? $empresa->getTelefonoContacto());
        $empresa->setDireccion($data['direccion'] ?? $empresa->getDireccion());
        $empresa->setDescripcion($data['descripcion'] ?? $empresa->getDescripcion());
        $empresa->setFoto($foto_url ?? $empresa->getFoto());
    }

    // =====================================================
    // MÉTODOS DE UTILIDAD
    // =====================================================

    /**
     * Guarda la imagen subida en la carpeta de assets.
     * 
     * @param Empresa $empresa Empresa relacionada con la imagen
     * @param array|null $fileData Datos del archivo subido
     * @return string Nombre final del archivo guardado
     */
    private function guardarFoto($empresa, $fileData) {
        $directorio = "./assets/img/";
        $validator = new Validator();
        $nombreFinal = $empresa->getFoto();

        if ($fileData && $fileData['error'] === UPLOAD_ERR_OK) {
            $nombreTemp = $fileData['tmp_name'];
            if ($validator->isImagen($nombreTemp)) {
                $extension = strtolower(pathinfo($fileData['name'], PATHINFO_EXTENSION));
                $nombreFinal = "empresa_" . $empresa->getId() . "." . $extension;
                $rutaDestino = $directorio . $nombreFinal;
                move_uploaded_file($nombreTemp, $rutaDestino);
            }
        }

        return $nombreFinal;
    }

    /**
     * Obtiene las empresas paginadas según estado (activas o no).
     * 
     * @param bool $activo Define si se buscan empresas activas
     * @param RepoEmpresa $repo Repositorio de empresas
     * @return array Datos paginados con empresas y metainformación
     */
    private function obtenerEmpresasPaginadas(bool $activo, $repo, $accion) {
        $total = $repo->getCount($activo);
        $page = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT, [
            'options' => ['default' => 1, 'min_range' => 1]
        ]);
        $size = filter_input(INPUT_GET, 'size', FILTER_VALIDATE_INT, [
            'options' => ['default' => 10, 'min_range' => 1]
        ]);

        $index = Paginator::getIndex($size, $page);
        $pages = Paginator::getPages($total, $size);
        if ($page > $pages) $page--;

        $empresas = $repo->findAllLimitWActive($index, $size, $activo);
        $paginator = Paginator::renderPagination($page, $size, $pages, $accion, $this->page);

        return [
            'empresas' => $empresas,
            'paginator' => $paginator
        ];
    }

    /**
     * Renderiza la vista del listado de empresas.
     * 
     * @param array $datos Datos de las empresas y paginación
     * @param bool $activo Indica si se muestran activas
     * @param string $accion Acción actual
     * @return void
     */
    private function mostrarListadoEmpresas(array $datos, bool $activo, string $accion) {
        echo $this->templates->render('Admin/AdminListado', [
            'empresas' => $datos['empresas'],
            'paginator' => $datos['paginator'],
            'page' => $this->page,
            'activo' => $activo,
            'accion' => $accion
        ]);
    }

    /**
     * Combina la obtención de empresas paginadas y su renderizado.
     * 
     * @param bool $activo Si las empresas deben ser activas
     * @param RepoEmpresa $repo Repositorio de empresas
     * @param string $accion Acción actual
     * @return void
     */
    private function paginacionListadoEmpresas(bool $activo, $repo, $accion) {
        $datos = $this->obtenerEmpresasPaginadas($activo, $repo, $accion);
        $this->mostrarListadoEmpresas($datos, $activo, $accion);
    }

    /**
     * Determina qué acción ejecutar según el valor POST['accion'].
     * 
     * @param RepoEmpresa $repo Repositorio de empresas
     * @param string $pageAccion Página actual
     * @param array $postData Datos del formulario
     * @return void
     */
    private function chooseAction($repo, $pageAccion, $postData) {
        $accion = $postData['accion'] ?? '';
        switch ($accion) {
            case 'editar':
                $this->editarEmpresa($repo, $pageAccion, $postData, $accion);
                break;
            case 'eliminar':
                $this->eliminarEmpresa($repo, $pageAccion, $postData);
                break;
            case 'ver':
                $this->verEmpresa($repo, $pageAccion, $postData);
                break;
        }
    }

    /**
     * Activa una empresa pendiente de aprobación.
     * 
     * @param RepoEmpresa $repo Repositorio de empresas
     * @param string $pageAccion Página actual
     * @param array $postData Datos del formulario
     * @return void
     */
    private function activateEmpresa($repo, $pageAccion, $postData) {
        $id = filter_var($postData['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id) {
            $empresa = $repo->findById($id);
            $empresa->setActivo(true);
            $repo->update($empresa);
        }
        header('location?page='.$this->page.'&accion='.$pageAccion);
        exit;
    }
}
?>
