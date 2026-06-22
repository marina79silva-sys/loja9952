<?php
require_once dirname(__DIR__).'/vendor/autoload.php';
require_once dirname(__DIR__).'/src/Helper.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_validar(): void {
    $token = $_POST['csrf_token'] ?? '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';

    if (!is_string($token) || $sessionToken === '' || !hash_equals($sessionToken, $token)) {
        http_response_code(403);
        exit('Token CSRF inválido.');
    }
}

$env = parse_ini_file(dirname(__DIR__).'/.env');
if ($env !== false) {
    foreach ($env as $k => $v) {
        $_ENV[$k] = $v;
    }
}
 
use App\Controller\AdminController;
use App\Controller\AuthController;
use App\Controller\CarrinhoController;
use App\Controller\CheckoutController;
use App\Controller\ContaController;
use App\Controller\VeiculoController;
 
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$base = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if (str_ends_with($base, '/public')) {
    $base = substr($base, 0, -7);
}
if ($base !== '' && str_starts_with($uri, $base.'/')) {
    $uri = substr($uri, strlen($base) + 1);
}
if ($uri === 'index.php') {
    $uri = '';
} elseif (str_starts_with($uri, 'index.php/')) {
    $uri = substr($uri, strlen('index.php/'));
}

$partes = explode('/', $uri);
$recurso = $partes[0] ?? '';
$acao    = $partes[1] ?? '';
$id      = (int)($partes[2] ?? 0);
 
match("$recurso/$acao") {
    '/' => (new VeiculoController())->catalogo(),
    'admin/' => (new AdminController())->dashboard(),
    'admin/dashboard' => (new AdminController())->dashboard(),
    'admin/login' => (new AuthController())->adminLogin(),
    'veiculo/detalhe' => (new VeiculoController())->detalhe($id),
    'carrinho/' => (new CarrinhoController())->ver(),
    'carrinho/adicionar' => (new CarrinhoController())->adicionar(),
    'carrinho/remover' => (new CarrinhoController())->remover(),
    'checkout/' => (new CheckoutController())->ver(),
    'checkout/confirmar' => (new CheckoutController())->confirmar(),
    'conta/' => (new ContaController())->ver(),
    'reserva/anular' => (new ContaController())->anularReserva($id),
    'login/' => (new AuthController())->login(),
    'registar/' => (new AuthController())->registar(),
    'logout/' => (new AuthController())->logout(),
    'admin/veiculos' => (new AdminController())->veiculosLista(),
    'admin/veiculos/criar' => (new AdminController())->veiculoCriar(),
    'admin/veiculos/editar' => (new AdminController())->veiculoEditar($id),
    'admin/veiculos/apagar' => (new AdminController())->veiculoApagar($id),
    'admin/reservas' => (new AdminController())->reservasLista(),
    'admin/reservas/estado' => (new AdminController())->reservaEstado(),
    default => (new VeiculoController())->catalogo(),
};
