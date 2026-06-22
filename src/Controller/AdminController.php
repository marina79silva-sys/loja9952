<?php
namespace App\Controller;
 
use App\Model\VeiculoModel;
use App\Model\ReservaModel;
use function App\url;
 
class AdminController {
 
    private function auth(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!($_SESSION['admin_logado'] ?? false)) {
            $destino = url('admin/login');
            header('Location: '.$destino);
            exit;
        }
    }
 
    public function dashboard(): void {
        $this->auth();
        $pdo        = \App\Database::getConnection();
        $totalVeic  = $pdo->query('SELECT COUNT(*) FROM veiculos')->fetchColumn();
        $totalRes   = $pdo->query('SELECT COUNT(*) FROM reservas')->fetchColumn();
        $pendentes  = $pdo->query('SELECT COUNT(*) FROM reservas WHERE estado="pendente"')->fetchColumn();
        $titulo     = 'Dashboard — AutoShop Admin';
        require '../templates/admin/dashboard.php';
    }
 
    public function veiculosLista(): void {
        $this->auth();
        $model    = new VeiculoModel();
        // Listar TODOS os veículos (incluindo indisponíveis):
        $pdo      = \App\Database::getConnection();
        $veiculos = $pdo->query(
            'SELECT v.*, m.nome AS marca FROM veiculos v
             JOIN marcas m ON m.id = v.marca_id ORDER BY v.id DESC'
        )->fetchAll();
        $titulo = 'Gerir Veículos';
        require '../templates/admin/veiculos.php';
    }
 
    public function veiculoCriar(): void {
        $this->auth();
        $erros  = [];
        $marcas = (new VeiculoModel())->getMarcas();
 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_validar();
            $dados = $this->validarVeiculo($_POST, $erros);
            $imagem = $this->processarImagem($erros);
            if ($imagem) $dados[':imagem'] = $imagem;
 
            if (empty($erros)) {
                (new VeiculoModel())->criar($dados);
                $_SESSION['msg_ok'] = 'Veículo adicionado!';
                $destino = url('admin/veiculos');
                header('Location: '.$destino); exit;
            }
        }
        $titulo = 'Adicionar Veículo';
        require '../templates/admin/veiculo_form.php';
    }
 
    private function validarVeiculo(array $post, array &$erros): array {
        $dados = [];
        if (empty($post['marca_id']))  $erros[] = 'Seleciona uma marca.';
        if (empty($post['modelo']))    $erros[] = 'Modelo obrigatório.';
        if (empty($post['ano']))       $erros[] = 'Ano obrigatório.';
        if (!is_numeric($post['preco']) || $post['preco'] <= 0)
                                       $erros[] = 'Preço inválido.';
        if (empty($erros)) {
            $dados = [
                ':marca_id'    => (int) $post['marca_id'],
                ':modelo'      => trim($post['modelo']),
                ':ano'         => (int) $post['ano'],
                ':quilometros' => (int) ($post['quilometros'] ?? 0),
                ':combustivel' => $post['combustivel'],
                ':cilindrada'  => trim($post['cilindrada'] ?? '') ?: null,
                ':preco'       => (float) $post['preco'],
                ':descricao'   => trim($post['descricao'] ?? ''),
                ':imagem'      => null,
            ];
        }
        return $dados;
    }
 
    private function processarImagem(array &$erros): ?string {
        if (empty($_FILES['imagem']['name'])) return null;
        $f       = $_FILES['imagem'];
        $tipos   = ['image/jpeg','image/png','image/webp'];
        $finfo   = new \finfo(FILEINFO_MIME_TYPE);
        $tipo    = $finfo->file($f['tmp_name']);
        if (!in_array($tipo, $tipos)) { $erros[] = 'Imagem inválida (só JPG/PNG/WEBP).'; return null; }
        if ($f['size'] > 5*1024*1024)  { $erros[] = 'Imagem demasiado grande (máx. 5MB).'; return null; }
        $ext    = pathinfo($f['name'], PATHINFO_EXTENSION);
        $nome   = uniqid('veiculo_',true).'.'.strtolower($ext);
        $dest   = '../public/uploads/'.$nome;
        if (!move_uploaded_file($f['tmp_name'], $dest)) { $erros[] = 'Erro ao guardar imagem.'; return null; }
        // Redimensionar para 800x600:
        $this->redimensionar($dest, $dest, 800, 600);
        return $nome;
    }
 
    private function redimensionar(string $src, string $dst, int $mw, int $mh): void {
        [$w,$h,$t] = getimagesize($src);
        $r  = min($mw/$w, $mh/$h);
        $nw = (int)($w*$r); $nh = (int)($h*$r);
        $im = match($t) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($src),
            IMAGETYPE_PNG  => imagecreatefrompng($src),
            IMAGETYPE_WEBP => imagecreatefromwebp($src),
            default => null,
        };
        if (!$im) return;
        $nd = imagecreatetruecolor($nw,$nh);
        imagecopyresampled($nd,$im,0,0,0,0,$nw,$nh,$w,$h);
        match($t) {
            IMAGETYPE_JPEG => imagejpeg($nd,$dst,85),
            IMAGETYPE_PNG  => imagepng($nd,$dst,6),
            IMAGETYPE_WEBP => imagewebp($nd,$dst,85),
            default => null,
        };
        imagedestroy($im); imagedestroy($nd);
    }
 
    public function veiculoEditar(int $id): void {
        $this->auth();
        $erros  = [];
        $marcas = (new VeiculoModel())->getMarcas();
        $model  = new VeiculoModel();
        $veiculo = $model->getById($id);
        
        if (!$veiculo) {
            $destino = url('admin/veiculos');
            header('Location: '.$destino); exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_validar();
            $dados = $this->validarVeiculo($_POST, $erros);
            $imagem = $this->processarImagem($erros);
            if ($imagem) $dados[':imagem'] = $imagem;

            if (empty($erros)) {
                $pdo = \App\Database::getConnection();
                if ($imagem) {
                    $stmt = $pdo->prepare(
                        'UPDATE veiculos SET marca_id=:marca_id, modelo=:modelo, ano=:ano, 
                         quilometros=:quilometros, combustivel=:combustivel, cilindrada=:cilindrada, 
                         preco=:preco, descricao=:descricao, imagem=:imagem WHERE id=:id'
                    );
                    $dados[':id'] = $id;
                } else {
                    $stmt = $pdo->prepare(
                        'UPDATE veiculos SET marca_id=:marca_id, modelo=:modelo, ano=:ano, 
                         quilometros=:quilometros, combustivel=:combustivel, cilindrada=:cilindrada, 
                         preco=:preco, descricao=:descricao WHERE id=:id'
                    );
                    $dados[':id'] = $id;
                }
                $stmt->execute($dados);
                $_SESSION['msg_ok'] = 'Veículo atualizado!';
                $destino = url('admin/veiculos');
                header('Location: '.$destino); exit;
            }
        }
        $titulo = 'Editar Veículo';
        require '../templates/admin/veiculo_form.php';
    }

    public function veiculoApagar(int $id): void {
        $this->auth();
        $model = new VeiculoModel();
        $veiculo = $model->getById($id);
        
        if (!$veiculo) {
            $destino = url('admin/veiculos');
            header('Location: '.$destino); exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_validar();
            $pdo = \App\Database::getConnection();
            $stmt = $pdo->prepare('DELETE FROM veiculos WHERE id = :id');
            $stmt->execute([':id' => $id]);
            $_SESSION['msg_ok'] = 'Veículo apagado com sucesso!';
            $destino = url('admin/veiculos');
            header('Location: '.$destino); exit;
        }

        $titulo = 'Confirmar Eliminação';
        require '../templates/admin/veiculo_apagar.php';
    }

    public function reservasLista(): void {
        $this->auth();
        $pdo = \App\Database::getConnection();
        $reservas = $pdo->query(
            'SELECT r.id, r.cliente_id, r.veiculo_id, r.estado, r.criado_em,
                    c.nome AS cliente, c.email, c.telefone,
                    v.marca_id, v.modelo, v.ano, v.preco,
                    m.nome AS marca
             FROM reservas r
             LEFT JOIN clientes c ON c.id = r.cliente_id
             LEFT JOIN veiculos v ON v.id = r.veiculo_id
             LEFT JOIN marcas m ON m.id = v.marca_id
             ORDER BY r.criado_em DESC'
        )->fetchAll();
        
        $titulo = 'Gerir Reservas';
        require '../templates/admin/reservas.php';
    }

    public function reservaEstado(): void {
        $this->auth();
        csrf_validar();
        $id     = (int) ($_POST['reserva_id'] ?? 0);
        $estado = $_POST['estado'] ?? '';
        $validos = ['pendente','confirmada','cancelada'];
        if ($id > 0 && in_array($estado, $validos)) {
            $pdo = \App\Database::getConnection();
            $stmt = $pdo->prepare('UPDATE reservas SET estado=:e WHERE id=:id');
            $stmt->execute([':e'=>$estado,':id'=>$id]);
            // Se cancelada, repõe disponibilidade do veículo:
            if ($estado === 'cancelada') {
                $stmt2 = $pdo->prepare(
                    'UPDATE veiculos v
                     JOIN reservas r ON r.veiculo_id = v.id
                     SET v.disponivel = 1 WHERE r.id = :id'
                );
                $stmt2->execute([':id'=>$id]);
            }
        }
        $destino = url('admin/reservas');
        header('Location: '.$destino); exit;
    }
}
