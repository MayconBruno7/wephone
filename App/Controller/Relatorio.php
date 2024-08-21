<?php

use App\Library\ControllerMain;
use App\Library\Redirect;

use App\Library\Formulario;

class Relatorio extends ControllerMain
{
    public function __construct($dados)
    {
        $this->auxiliarConstruct($dados);

        // Só acessa se tiver logado
        if (!$this->getAdministrador()) {
            return Redirect::page("Home");
        }
    }

    public function index()
    {
        $this->loadView("restrita/formRelatorio");
    }

    public function relatorioMovimentacoes()
    {
        $this->loadView("restrita/formRelatorio");
    }

    public function relatorioItensPorFornecedor()
    {

        $dados = [];

        $FornecedorModel = $this->loadModel('Fornecedor');
        $dados = $FornecedorModel->lista('id');

        return $this->loadView("restrita/formRelatorio", $dados);
    }

    public function getDados()
    {

        $tipo = $this->getOutrosParametros(2);
        $dataInicio = $this->getOutrosParametros(3);
        $dataFinal = $this->getOutrosParametros(4);
        $id_fornecedor = (int)$this->getOutrosParametros(5);

        $dados = [];

        if (!empty($id_fornecedor)) {
            switch ($tipo) {
                case 'dia':
                    $dados = $this->model->RelatorioDiaItemFornecedor($dataInicio, $id_fornecedor);
                    break;
                case 'semana':
                    $dados = $this->model->RelatorioSemanaItemFornecedor($dataInicio, $dataFinal, $id_fornecedor);
                    break;
                case 'mes':
                    $dados = $this->model->RelatorioMesItemFornecedor($dataInicio, $id_fornecedor);
                    break;
                case 'ano':
                    $dados = $this->model->RelatorioAnoItemFornecedor($dataInicio, $id_fornecedor);
                    break;
                default:
                    $dados = [];
            }

        } else {
            switch ($tipo) {
                case 'dia':
                    $dados = $this->model->RelatorioDia($dataInicio);
                    break;
                case 'semana':
                    $dados = $this->model->RelatorioSemana($dataInicio, $dataFinal);
                    break;
                case 'mes':
                    $dados = $this->model->RelatorioMes($dataInicio);
                    break;
                case 'ano':
                    $dados = $this->model->RelatorioAno($dataInicio);
                    break;
                default:
                    $dados = [];
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($this->formatarDadosParaGrafico($dados));
    }

    private function formatarDadosParaGrafico($dados)
    {

        $id_movimentacao = [];
        $entradas = [];
        $saidas = [];
        $labels = [];
        $descricoes = [];
        $valores = [];

        foreach ($dados as $dado) {

            $labels[] = Formulario::formatarDataBrasileira($dado['data_pedido']);
            $descricoes[] = $dado['descricao'];
            $valores[] = number_format($dado['valor'], 2, ",", ".");
            $valores_venda[] = number_format($dado['valor_venda'], 2, ",", ".");
            $id_movimentacao[] = isset($dado['id_movimentacoes']) ? $dado['id_movimentacoes'] : $dado['id'];
            
            if ($dado['tipo'] == 1) { // Entrada
                $entradas[] = $dado['quantidade'];
                $saidas[] = 0;
            } else { // Saída
                $entradas[] = 0;
                $saidas[] = $dado['quantidade'];
            }
        }

        return [
            'labels' => $labels,
            'descricoes' => $descricoes,
            'valores' => $valores,
            'valores_venda' => $valores_venda,
            'entradas' => $entradas,
            'saidas' => $saidas,
            'id_movimentacao' => $id_movimentacao
        ];
    }
}
?>
