<?php
namespace api;
require __DIR__ . '/../bootstrap.php';
require PROJECT_ROOT . 'vendor/autoload.php';

use app\repositories\RepoUser;
use app\repositories\RepoToken;
use app\helpers\Security;
use app\models\Token;
use DateTime;

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    loginUsuario();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}

/**
 * Maneja el login de un usuario.
 */
function loginUsuario() {
    header('Content-Type: application/json');

    $input = json_decode(file_get_contents('php://input'), true);
    $correo = $input['email'] ?? '';
    $passwd = $input['pass'] ?? '';

    if (!$correo || !$passwd) {
        http_response_code(400);
        echo json_encode(['error' => 'Email y contraseña requeridos']);
        return;
    }

    $repoUser = new RepoUser();
    $user = $repoUser->findUser($correo);

    if (!$user) {
        http_response_code(404);
        echo json_encode(['error' => 'Usuario no encontrado']);
        return;
    }

    if (!Security::validatePasswd($passwd, $user->getPassword())) {
        http_response_code(401);
        echo json_encode(['error' => 'Contraseña incorrecta']);
        return;
    }

    $usuarioCompleto = Security::getUser($user);

    if (!$usuarioCompleto) {
        http_response_code(403);
        echo json_encode(['error' => 'Usuario no activo o rol inválido']);
        return;
    }

    // Generar y guardar token
    $token = generarYGuardarTokenRepo($usuarioCompleto);

    $response = [
        'token' => $token->getCodigo(),
        'user' => [
            'id' => $usuarioCompleto->getId(),
            'nombre' => $usuarioCompleto->getNombre(),
            'rol' => $usuarioCompleto->getRol(),
            'direccion' => $usuarioCompleto->getDireccion(),
            'foto' => $usuarioCompleto->getFoto() ?? '',
        ]
    ];

    echo json_encode($response);
}

/**
 * Genera un token para el usuario y lo guarda en la base de datos.
 * Si el usuario ya tiene token_id, lo actualiza; si no, lo crea y actualiza el user.
 *
 * @param User $usuarioCompleto
 * @return Token
 */
function generarYGuardarTokenRepo($usuarioCompleto): Token {
    $repoToken = new RepoToken();
    $repoUser = new RepoUser();

    $nuevoTokenCodigo = Security::generateToken();
    $token = new Token(
        id: $usuarioCompleto->getToken(), // token_id actual o null
        codigo: $nuevoTokenCodigo,
        expiresAt: new DateTime('+1 day') // opcional, expiración
    );

    if ($usuarioCompleto->getToken()) {
        // Actualizar token existente
        $repoToken->update($token);
    } else {
        // Insertar token nuevo
        $newTokenId = $repoToken->save($token);

        // Actualizar token_id en user
        $usuarioCompleto->setToken($newTokenId); // tu User debe tener setToken()
        $repoUser->update($usuarioCompleto);

        // Reasignar id al token
        $token = new Token(
            id: $newTokenId,
            codigo: $nuevoTokenCodigo,
            expiresAt: $token->getExpiresAt()
        );
    }

    return $token;
}
