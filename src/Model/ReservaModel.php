<?php
namespace App\Model;
 
use App\Database;
use PDO;
 
class ReservaModel {
    private PDO $db;
 
    public function __construct() {
        $this->db = Database::getConnection();
    }
 
    // Criar reserva + marcar veículo como indisponível (transação)
    public function criar(int $clienteId, int $veiculoId, string $msg = ''): int {
        try {
            $this->db->beginTransaction();
 
            // Inserir reserva:
            $stmt = $this->db->prepare(
                'INSERT INTO reservas (cliente_id, veiculo_id, mensagem)
                 VALUES (:cid, :vid, :msg)'
            );
            $stmt->execute([':cid'=>$clienteId,':vid'=>$veiculoId,':msg'=>$msg]);
            $id = (int) $this->db->lastInsertId();
 
            // Marcar veículo como indisponível:
            $stmt = $this->db->prepare(
                'UPDATE veiculos SET disponivel = 0 WHERE id = :id'
            );
            $stmt->execute([':id' => $veiculoId]);
 
            $this->db->commit();
            return $id;
 
        } catch (\PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
 
    // Reservas de um cliente com detalhe do veículo:
    public function getByCliente(int $clienteId): array {
        $stmt = $this->db->prepare(
            'SELECT r.*, v.modelo, v.ano, v.preco, m.nome AS marca
             FROM reservas r
             JOIN veiculos v ON v.id = r.veiculo_id
             JOIN marcas m ON m.id = v.marca_id
             WHERE r.cliente_id = :cid
             ORDER BY r.criado_em DESC'
        );
        $stmt->execute([':cid' => $clienteId]);
        return $stmt->fetchAll();
    }

    // Anular reserva + marcar veículo como disponível (transação)
    public function anular(int $reservaId, int $clienteId): bool {
        try {
            $this->db->beginTransaction();

            // Obter ID do veículo
            $stmt = $this->db->prepare('SELECT veiculo_id FROM reservas WHERE id = :id AND cliente_id = :cid');
            $stmt->execute([':id' => $reservaId, ':cid' => $clienteId]);
            $reserva = $stmt->fetch();
            if (!$reserva) {
                return false;
            }

            // Apagar reserva:
            $stmt = $this->db->prepare('DELETE FROM reservas WHERE id = :id AND cliente_id = :cid');
            $stmt->execute([':id' => $reservaId, ':cid' => $clienteId]);

            // Marcar veículo como disponível:
            $stmt = $this->db->prepare('UPDATE veiculos SET disponivel = 1 WHERE id = :id');
            $stmt->execute([':id' => $reserva['veiculo_id']]);

            $this->db->commit();
            return true;

        } catch (\PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
