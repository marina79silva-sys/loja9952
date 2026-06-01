<?php
namespace App\Controller;
 
use App\Model\VeiculoModel;
 
class CarrinhoController {
    private VeiculoModel $model;
 
    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $this->model = new VeiculoModel();
    }
 
    // Mostrar o carrinho
    public function ver(): void {
        $ids      = $_SESSION['carrinho'] ?? [];
        $veiculos = array_map(fn($id) => $this->model->getById($id), $ids);
        $veiculos = array_filter($veiculos); // remover IDs inválidos
        $titulo   = 'A minha lista de reservas';
        require dirname(__DIR__, 2).'/templates/carrinho/ver.php';
    }
 
    // Adicionar ao carrinho
    public function adicionar(): void {
        csrf_validar();
        $id = (int) ($_POST['veiculo_id'] ?? 0);
        if ($id > 0) {
            $carrinho = $_SESSION['carrinho'] ?? [];
            if (!in_array($id, $carrinho)) {
                $carrinho[] = $id;
                $_SESSION['carrinho'] = $carrinho;
                $_SESSION['msg_ok']   = 'Veículo adicionado à lista!';
            } else {
                $_SESSION['msg_info'] = 'Este veículo já está na tua lista.';
            }
        }
        $destino = function_exists('url') ? url('carrinho') : '/carrinho';
        header('Location: '.$destino);
        exit;
    }
 
    // Remover do carrinho
    public function remover(): void {
        csrf_validar();
        $id = (int) ($_POST['veiculo_id'] ?? 0);
        $carrinho = $_SESSION['carrinho'] ?? [];
        $carrinho = array_values(array_filter($carrinho, fn($i) => (int) $i !== $id));
        $_SESSION['carrinho'] = $carrinho;
        $destino = function_exists('url') ? url('carrinho') : '/carrinho';
        header('Location: '.$destino);
        exit;
    }
}
