<?php
namespace app\controllers;

use League\Plates\Engine;
use app\repositories\RepoEmpresa;
use app\repositories\RepoUser;
use app\helpers\Login;
use app\helpers\Session;
use app\helpers\Paginator;
use app\helpers\Validator;
use app\models\Empresa;

class EmpresaController {
    private Engine $templates;
    private $user;
    private string $page;

    public function __construct(string $platePath) {
        $this->templates = new Engine($platePath);
        $this->user = Session::readSession('user');
        $this->page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING) ?? 'empresas';
    }

    public function index(): void {
        if (!Login::isLogin() || $this->user->getRol() !== 1) {
            header('Location: ?page=home');
            exit;
        }

        $accion = filter_input(INPUT_GET, 'accion', FILTER_SANITIZE_STRING) ?? 'listado';

        $acciones = [
            'listado' => fn() => $this->manejarListado($accion),
            'solicitudes' => fn() => $this->manejarSolicitudes($accion),
            'inscribir' => fn() => $this->manejarNewEmpresa($accion),
        ];

        if (isset($acciones[$accion])) {
            $acciones[$accion]();
        } else {
            header('Location: ?page=empresas');
            exit;
        }
    }

    private function manejarNewEmpresa(string $accion): void {
        $validator = new Validator();
        $empresa = new Empresa();

        $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) ?? [];
        $fileData = $_FILES['foto'] ?? null;

        if (!empty($postData['action'])) {
            $validator->requiredFile('foto');
            $this->validarEmpresa($validator, $postData, $fileData);
            $this->actualizarEmpresa($empresa, $postData, $fileData);

            if ($validator->validacionPasada()) {
                $repo = new RepoEmpresa();
                $empresa->setActivo(!empty($postData['activo']));
                $repo->save($empresa);
                $validator->mensajeExito();
            }
        }

        echo $this->templates->render('Admin/AdminFormEmpresa', [
            'empresa' => $empresa,
            'validator' => $validator,
            'page' => $this->page,
            'accion' => $accion,
            'action' => $accion
        ]);
    }

    private function manejarSolicitudes(string $accion): void {
        $repo = new RepoEmpresa();
        $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) ?? [];

        if (!empty($postData['accion'])) {
            $this->chooseAction($repo, $accion, $postData);
            if ($postData['accion'] === 'aceptar') {
                $this->activateEmpresa($repo, $accion, $postData);
            }
        } else {
            $this->paginacionListadoEmpresas(false, $repo, $accion);
        }
    }

    private function manejarListado(string $accion): void {
        $repo = new RepoEmpresa();
        $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) ?? [];

        if (!empty($postData['accion'])) {
            $this->chooseAction($repo, $accion, $postData);
        } else {
            $this->paginacionListadoEmpresas(true, $repo, $accion);
        }
    }

    private function activateEmpresa(RepoEmpresa $repo, string $accionPagina, array $postData): void {
        $id = filter_var($postData['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id) {
            $empresa = $repo->findById($id);
            $empresa->setActivo(true);
            $repo->update($empresa);
        }
        header('Location: ?page=empresas&accion=' . $accionPagina);
        exit;
    }

    private function chooseAction(RepoEmpresa $repo, string $accionPagina, array $postData): void {
        switch ($postData['accion'] ?? '') {
            case 'editar':
                $this->editarEmpresa($repo, $accionPagina, $postData);
                break;
            case 'eliminar':
                $this->eliminarEmpresa($repo, $accionPagina, $postData);
                break;
            case 'ver':
                $this->verEmpresa($repo, $accionPagina, $postData);
                break;
        }
    }

    private function paginacionListadoEmpresas(bool $activo, RepoEmpresa $repo, string $accion): void {
        $total = $repo->getCount($activo);

        $page = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]);
        $size = filter_input(INPUT_GET, 'size', FILTER_VALIDATE_INT, ['options' => ['default' => 10, 'min_range' => 1]]);
        $index = Paginator::getIndex($size, $page);
        $pages = Paginator::getPages($total, $size);
        if ($page > $pages) $page = $pages;

        $empresas = $repo->findAllLimitWActive($index, $size, $activo);
        $paginator = Paginator::renderPagination($page, $size, $pages, $accion);

        echo $this->templates->render('Admin/AdminListado', [
            'empresas' => $empresas,
            'paginator' => $paginator,
            'page' => $this->page,
            'activo' => $activo,
            'accion' => $accion
        ]);
    }

    private function editarEmpresa(RepoEmpresa $repo, string $accionPagina, array $postData): void {
        $id = filter_var($postData['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) {
            header('Location: ?page=' . $this->page . '&accion=' . $accionPagina);
            exit;
        }

        $empresa = $repo->findById($id);
        $validator = new Validator();

        if (!empty($postData['action'])) {
            switch ($postData['action']) {
                case 'guardar':
                    $this->manejarGuardar($empresa, $validator, $repo, $postData, $_FILES['foto'] ?? null);
                    break;
                case 'cancelar':
                    header("Location:?page=empresas&accion=" . $accionPagina);
                    exit;
            }
        }

        echo $this->templates->render('Admin/AdminFormEmpresa', [
            'empresa' => $empresa,
            'validator' => $validator,
            'page' => $this->page,
            'accion' => $accionPagina,
            'action' => $postData['accion'] ?? ''
        ]);
    }

    private function eliminarEmpresa(RepoEmpresa $repo, string $accionPagina, array $postData): void {
        $id = filter_var($postData['id'] ?? null, FILTER_VALIDATE_INT);

        if (!empty($postData['action'])) {
            switch ($postData['action']) {
                case 'eliminar':
                    if ($id) $repo->delete($id);
                    // fallthrough
                case 'cancelar':
                    header("Location:?page=" . $this->page . "&accion=" . $accionPagina);
                    exit;
            }
        } else if ($id) {
            $empresa = $repo->findById($id);
            echo $this->templates->render('Admin/AdminEliminarEmpresa', [
                'empresa' => $empresa,
                'page' => $this->page,
                'accion' => $accionPagina
            ]);
        }
    }

    private function verEmpresa(RepoEmpresa $repo, string $accionPagina, array $postData): void {
        if (!empty($postData['action']) && $postData['action'] === 'cancelar') {
            header("Location:?page=" . $this->page . "&accion=" . $accionPagina);
            exit;
        }

        $id = filter_var($postData['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id) {
            $empresa = $repo->findById($id);
            echo $this->templates->render('Admin/AdminVerEmpresa', [
                'empresa' => $empresa,
                'page' => $this->page,
                'accion' => $accionPagina
            ]);
        }
    }

    private function manejarGuardar(Empresa $empresa, Validator $validator, RepoEmpresa $repo, array $postData, ?array $fileData): void {
        $this->validarEmpresa($validator, $postData, $fileData);

        if ($empresa->getEmail() === ($postData['correo'] ?? '')) {
            $validator->remove('correo');
        }

        $this->actualizarEmpresa($empresa, $postData, $fileData);

        if ($validator->validacionPasada()) {
            $validator->mensajeExito();
            $repo->update($empresa);
        }
    }

    private function validarEmpresa(Validator $validator, array $data, ?array $fileData): void {
        $validator->validarEmail('correo', $data);
        $validator->validarEmail('correo_contacto', $data);
        $validator->validarTelefono('telefono_contacto', $data);
        $validator->validarNombre('nombre', $data);
        $validator->required('direccion', $data);
        $validator->required('descripcion', $data);

        $repoUser = new RepoUser();
        if (!empty($data['correo']) && $repoUser->correoExiste($data['correo'])) {
            $validator->insertarError('correo', "Este correo ya existe");
        }

        if ($fileData && $fileData['error'] === UPLOAD_ERR_OK) {
            $validator->isImagen($fileData['tmp_name']);
        }
    }

    private function actualizarEmpresa(Empresa $empresa, array $data, ?array $fileData): void {
        $fotoUrl = $this->guardarFoto($empresa, $fileData);

        $empresa->setNombre($data['nombre'] ?? $empresa->getNombre());
        $empresa->setEmail($data['correo'] ?? $empresa->getEmail());
        $empresa->setCorreoContacto($data['correo_contacto'] ?? $empresa->getCorreoContacto());
        $empresa->setTelefonoContacto($data['telefono_contacto'] ?? $empresa->getTelefonoContacto());
        $empresa->setDireccion($data['direccion'] ?? $empresa->getDireccion());
        $empresa->setDescripcion($data['descripcion'] ?? $empresa->getDescripcion());
        $empresa->setFoto($fotoUrl ?? $empresa->getFoto());
    }

    private function guardarFoto(Empresa $empresa, ?array $fileData): ?string {
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
}
?>
