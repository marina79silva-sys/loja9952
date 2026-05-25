<?php
namespace App\Controller;

use App\Model\VeiculoModel;

class VeiculoController {
    private VeiculoModel $model;

    public function __construct() {
        $this->model = new VeiculoModel();
    }

    public function catalogo(): void {
        $filtros = [
            'marca_id'    => (int) ($_GET['marca_id'] ?? 0) ?: null,
            'combustivel' => $_GET['combustivel'] ?? null,
            'preco_max'   => (float) ($_GET['preco_max'] ?? 0) ?: null,
            'ano_min'     => (int) ($_GET['ano_min'] ?? 0) ?: null,
            'pesquisa'    => trim($_GET['pesquisa'] ?? ''),
        ];

        $filtros = array_filter($filtros);

        $veiculos = $this->model->listar($filtros);
        $marcas = $this->model->getMarcas();
        $titulo = 'Catálogo de Veículos';

        require dirname(__DIR__, 2).'/templates/veiculos/catalogo.php';
    }

    public function detalhe(int $id): void {
        if ($id <= 0) {
            http_response_code(404);
            echo 'Veículo não encontrado.';
            return;
        }

        $veiculo = $this->model->getById($id);

        if (!$veiculo) {
            http_response_code(404);
            echo 'Veículo não encontrado.';
            return;
        }

        $titulo = $veiculo['marca'].' '.$veiculo['modelo'];

        require dirname(__DIR__, 2).'/templates/veiculos/detalhe.php';
    }
}
