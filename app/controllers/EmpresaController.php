<?php
namespace app\controllers;

use League\Plates\Engine;
use app\repositories\RepoEmpresa;
use app\repositories\RepoUser;
use app\helpers\Login;
use app\helpers\Session;
use app\helpers\Paginator;
use app\helpers\Validator;
use app\helpers\Security;
use app\helpers\EmpresaValidator;
use app\helpers\Correo;
use app\models\User;
use app\models\Empresa;
/* 
    Agregar filtro por ofertas de una familia en concreto
*/
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
                    
            }
        } else {
            header('location:?page=home');
            
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

        if (isset($postData['btn-accion'])) {
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
        if (isset($postData['btn-accion'])) {
            $this->chooseAction($repo, $accion, $postData);
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
        EmpresaValidator::validarEmpresa($validator,$postData,$fileData);

        $empresa->actualizarEmpresa( $postData, $fileData);
        if ($empresa->getFoto()!=''){
            $validator->remove('foto');
        }

        if ($validator->validacionPasada()) {
            $repo = new RepoEmpresa();
            $empresa->setActivo(isset($postData['activo']));
            $empresa->setPassword(Security::passwdToHash($empresa->getPassword()));
            $repo->save($empresa);
            $validator->mensajeExito();
            /* TO-DO Enviar correo con la contraseña */
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
            'btnAction'=>'',
            'user'=>$this->user
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

        $this->renderFormularioEmpresa($empresa, $validator, $accion);
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
    private function editarEmpresa($repo, $pageAccion, $postData, $btnAction) {
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
                   
            }
        }
            echo $this->templates->render('Admin/AdminFormEmpresa', [
            'empresa' => $empresa,
            'validator' => $validator,
            'page' => $this->page,
            'accion' => $pageAccion,
            'btnAction' => $btnAction,
            'user'=>$this->user
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
    private function eliminarEmpresa($repo, $pageAccion, $postData, $btnAction) {
        $id = filter_var($postData['id'] ?? null, FILTER_VALIDATE_INT);

        if (isset($postData['action'])) {
            switch ($postData['action']) {
                case 'eliminar':
                    if ($id) $repo->delete($id);
                case 'cancelar':
                     header("location:?page=".$this->page."&accion=".$pageAccion);  
                    
                    break;
            }
        } else {
            $empresa = $repo->findById($id);
            echo $this->templates->render('Admin/AdminEliminarEmpresa', [
                'empresa' => $empresa,
                'page' => $this->page,
                'accion' => $pageAccion,
                'btnAction'=>$btnAction,
                'user'=>$this->user
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
            
        } else {
            $id = filter_var($postData['id'] ?? null, FILTER_VALIDATE_INT);
            $empresa = $repo->findById($id);
            echo $this->templates->render('Admin/AdminVerEmpresa', [
                'empresa' => $empresa,
                'page' => $this->page,
                'accion' => $pageAccion,
                'user'=>$this->user
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
        EmpresaValidator::validarEmpresa($validator,$postData,$fileData);
        $validator->remove('passwd');
        if ($empresa->getCorreo() == ($postData['correo'] ?? '')) {
            $validator->remove('correo');
        }
        if ($empresa->getFoto()!=''){
                $validator->remove('foto');
        }
        $empresa->actualizarEmpresa($postData, $fileData);
        if ($validator->validacionPasada()) {
            $empresa->setPassword(Security::passwdToHash($empresa->getPassword()));
            $validator->mensajeExito();
            $repo->update($empresa);
        }
    }

   

    /**
     * Obtiene las empresas paginadas según estado (activas o no).
     * 
     * @param bool $activo Define si se buscan empresas activas
     * @param RepoEmpresa $repo Repositorio de empresas
     * @return array Datos paginados con empresas y metainformación
     */
    private function obtenerEmpresasPaginadas(bool $activo, $repo, $accion, $nombre) {
        $total=0;
        $filtro=false;
        if (isset($_GET['filtrado'])&& ($_GET['filtrado'] === 'true') && $nombre!=''){
            $total=$repo->getCountFiltr($activo,$nombre);
            $filtro=true;
        }else{
            $total = $repo->getCount($activo);
        }
        
        $page = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT, [
            'options' => ['default' => 1, 'min_range' => 1]
        ]);
        $size = filter_input(INPUT_GET, 'size', FILTER_VALIDATE_INT, [
            'options' => ['default' => 10, 'min_range' => 1]
        ]);

        $index = Paginator::getIndex($size, $page);
        $pages = Paginator::getPages($total, $size);
        if ($page > $pages) $page--;
        $empresas=$filtro
            ?$repo->findAllLimitWActiveFiltr($index, $size, $activo,$nombre)
            :$repo->findAllLimitWActive($index, $size, $activo);
        $paginator = Paginator::renderPagination($page, $size, $pages, $accion, $this->page, $filtro,$nombre);

        return [
            'empresas' => $empresas,
            'paginator' => $paginator,
            'size'=>$size
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
    private function mostrarListadoEmpresas(array $datos, bool $activo, string $accion, string $nombre) {
        echo $this->templates->render('Admin/AdminListado', [
            'empresas' => $datos['empresas'],
            'paginator' => $datos['paginator'],
            'page' => $this->page,
            'activo' => $activo,
            'accion' => $accion,
            'nombre'=>$nombre,
            'size'=>$datos['size']??10,
            'user'=>$this->user
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
        $nombre = trim($_GET['nombre_empresa'] ?? '');
        $nombre = filter_var($nombre, FILTER_SANITIZE_SPECIAL_CHARS);
        $datos = $this->obtenerEmpresasPaginadas($activo, $repo, $accion, $nombre);
        $this->mostrarListadoEmpresas($datos, $activo, $accion, $nombre);
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
        $btnAction = $postData['btn-accion'] ?? '';
        switch ($btnAction) {
            case 'editar':
                $this->editarEmpresa($repo, $pageAccion, $postData, $btnAction);
                break;
            case 'eliminar':
                $this->eliminarEmpresa($repo, $pageAccion, $postData, $btnAction);
                break;
            case 'ver':
                $this->verEmpresa($repo, $pageAccion, $postData, $btnAction);
                break;
            case 'aceptar':
                $this->activateEmpresa($repo,$pageAccion,$postData);
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
            $correo = new Correo();
            $correo->emailEmpresaActiva($empresa);
        }
        header('location: ?page='.$this->page.'&accion='.$pageAccion);
        
    }
}
?>
