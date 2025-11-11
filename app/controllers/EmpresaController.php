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

class EmpresaController {
    private $templates; // Motor de templates Plates
    private $user;      // Usuario logueado
    private $page;      // Página actual (sanitizada desde GET)

    /**
     * Constructor: inicializa plates, usuario y página actual
     */
    public function __construct($platePath) {
        $this->templates = new Engine($platePath);
        $this->user = Session::readSession('user');
        $this->page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'empresas';
    }

    /**
     * Método principal: decide acción a ejecutar según GET['accion']
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
     * Listado de empresas activas
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
     * Maneja solicitudes de empresas (aprobación)
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
     * Valida y guarda una nueva empresa.
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
     * Renderiza el formulario para nueva empresa.
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
     * Maneja la lógica completa de creación de empresa.
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

    private function manejarGuardar($empresa, $validator, $repo, $postData, $fileData) {
        $this->validarEmpresa($validator, $postData, $fileData);
        if ($empresa->getEmail() == ($postData['correo'] ?? '')) {
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

    private function validarEmpresa($validator, $data, $fileData) {
        $validator->validarEmail('correo', $data);
        $validator->validarEmail('correo_contacto', $data);
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

    private function actualizarEmpresa($empresa, $data, $fileData) {
        $foto_url = $this->guardarFoto($empresa, $fileData);
        $empresa->setNombre($data['nombre'] ?? $empresa->getNombre());
        $empresa->setEmail($data['correo'] ?? $empresa->getEmail());
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
     * Guarda la foto subida en carpeta assets/img
     */
    private function guardarFoto($empresa, $fileData) {
        $directorio = "./assets/img/";
        $validator = new Validator();
        $nombreFinal = $empresa->getFoto(); // Mantener nombre anterior por defecto

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
     * Obtiene los datos paginados de empresas.
     */
    private function obtenerEmpresasPaginadas(bool $activo, $repo) {
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
        $paginator = Paginator::renderPagination($page, $size, $pages, $this->page);

        return [
            'empresas' => $empresas,
            'paginator' => $paginator,
            'page' => $page,
            'size' => $size,
            'pages' => $pages,
        ];
    }

    /**
     * Renderiza la vista de listado de empresas.
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
     * Orquesta la paginación y el renderizado.
     */
    private function paginacionListadoEmpresas(bool $activo, $repo, $accion) {
        $datos = $this->obtenerEmpresasPaginadas($activo, $repo);
        $this->mostrarListadoEmpresas($datos, $activo, $accion);
    }

    /**
     * Determina acción según POST['accion']
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
     * Activa una empresa
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
