<?php
namespace App\Controller;
 
use App\Auth;
use App\Model\ReservaModel;
use App\Model\VeiculoModel;
 
class CheckoutController {
    public function __construct() {
        // Garante que a sessão está ativa antes de qualquer lógica
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function ver(): void {
        // Guarda a URL atual para que, após o login, o utilizador volte para aqui
        $_SESSION['url_pretendida'] = $_SERVER['REQUEST_URI'] ?? (function_exists('url') ? url('checkout') : '/checkout');

        Auth::verificar();
 
        $ids = $_SESSION['carrinho'] ?? [];
        if (empty($ids)) {
            $destino = function_exists('url') ? url('carrinho') : '/carrinho';
            header('Location: ' . $destino);
            exit;
        }
 
        $model    = new VeiculoModel();
        // Obtém os veículos e garante que filtramos apenas os que ainda estão disponíveis
        $veiculos = array_map(fn($id) => $model->getById($id), $ids);
        $veiculos = array_filter($veiculos, fn($v) => $v && (int)$v['disponivel'] === 1);

        $titulo   = 'Confirmar reserva';
        require dirname(__DIR__, 2) . '/templates/checkout/ver.php';
    }
 
    public function confirmar(): void {
        Auth::verificar();
        csrf_validar();
 
        $ids = $_SESSION['carrinho'] ?? [];
        if (empty($ids)) {
            header('Location: ' . (function_exists('url') ? url('carrinho') : '/carrinho'));
            exit;
        }

        $clienteId = $_SESSION['cliente_id'] ?? null;
        $mensagem  = trim($_POST['mensagem'] ?? '');
 
        $reservaModel = new ReservaModel();
        $confirmadas  = 0;
 
        foreach ($ids as $veiculoId) {
            try {
                $reservaModel->criar($clienteId, (int)$veiculoId, $mensagem);
                $confirmadas++;
            } catch (\Exception $e) {
                // Veículo pode já não estar disponível — ignorar
                error_log('Erro ao criar reserva: '.$e->getMessage());
            }
        }
 
        // Limpar carrinho após checkout:
        $_SESSION['carrinho'] = [];
        $_SESSION['msg_ok']   = "$confirmadas reserva(s) confirmada(s)! A nossa equipa entrará em contacto.";
        
        $destino = function_exists('url') ? url('conta') : '/conta';
        header('Location: ' . $destino);
        exit;
    }
}
