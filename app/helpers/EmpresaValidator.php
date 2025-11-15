<?php
namespace app\helpers;
use app\repositories\RepoUser; 
class EmpresaValidator {

     /**
     * Valida los campos de una empresa.
     * 
     * @param Validator $validator Instancia del validador
     * @param array $data Datos del formulario
     * @param array|null $fileData Archivo subido
     * @return void
     */
    public static function validarEmpresa(Validator $validator, array $data, ?array $fileData = null): void {
        $validator->validarCorreo('correo', $data);
        $validator->validarCorreo('correo_contacto', $data);
        $validator->validarTelefono('telefono_contacto', $data);
        $validator->validarNombre('nombre', $data);
        $validator->requiredFile('foto');
        $validator->required('direccion', $data);
        $validator->required('descripcion', $data);
        $validator->required('passwd',$data);

        $repo = new RepoUser();
        if (!empty($data['correo']) && $repo->correoExiste($data['correo'])) {
            $validator->insertarError('correo', "Este correo ya existe");
        }
    }
}

?>