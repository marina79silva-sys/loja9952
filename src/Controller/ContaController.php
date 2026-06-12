<?php
namespace App\Controller;
 
use App\Auth;
use App\Model\ClienteModel;
use App\Model\ReservaModel; // a criar na FT05
 
class ContaController {
    public function __construct() {
        // Essencial para que o Auth::verificar() e o $_SESSION funcionem
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function ver(): void {
        Auth::verificar(); // redireciona para /login se não autenticado

        $clienteId = $_SESSION['cliente_id'] ?? 0;
        $cliente  = (new ClienteModel())->getById($clienteId);
        $reservas = (new ReservaModel())->getByCliente($clienteId);
        
        $titulo   = 'A minha conta';
        require dirname(__DIR__, 2) . '/templates/conta/ver.php';
    }

    public function anularReserva(int $reservaId): void {
        Auth::verificar();
        csrf_validar();

        $clienteId = $_SESSION['cliente_id'] ?? 0;
        $model = new ReservaModel();
        
        if ($model->anular($reservaId, $clienteId)) {
            header('Location: '.url('conta'));
            exit;
        } else {
            http_response_code(403);
            echo 'Não foi possível anular a reserva.';
        }
    }
}
