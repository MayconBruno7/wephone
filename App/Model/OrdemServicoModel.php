<?php

use App\Library\ModelMain;
use App\Library\Session;

use App\Library\Formulario;

Class OrdemServicoModel extends ModelMain
{
    public $table = "ordens_servico";

    public $validationRules = [
        'cliente_nome' => [
            'label' => 'cliente_nome',
            'rules' => 'required|min:3|max:255'
        ],

        'statusRegistro' => [
            'label' => 'Status',
            'rules' => 'required|int'
        ]
    ];

    /**
     * lista
     *
     * @param string $orderBy 
     * @return void
     */
    public function lista($orderBy = 'id')
    {
        // if (Session::get('usuarioNivel') == 1) {
            $rsc = $this->db->dbSelect("SELECT
                os.id AS ordem_id,
                os.cliente_nome,
                os.telefone_cliente,
                os.modelo_dispositivo,
                os.imei_dispositivo,
                os.descricao_servico,
                os.tipo_servico,
                os.problema_reportado,
                os.data_abertura,
                os.data_fechamento,
                os.status,
                os.observacoes,
                osp.quantidade AS quantidade_peca_ordem,
                p.id AS peca_id,
                p.nome AS nome_peca,
                p.descricao,
                mi.valor_venda AS valor_peca -- Coluna de valor da tabela movimentacao_item
            FROM
                ordens_servico os
            LEFT JOIN
                ordens_servico_pecas osp ON os.id = osp.id_ordem_servico
            LEFT JOIN
                produto p ON osp.id_peca = p.id
            LEFT JOIN
                movimentacao_item mi ON osp.id_peca = mi.id_produtos
            WHERE
                p.tipo_produto = 2 OR p.id IS NULL;
            ");
            
        // } 
        // else {
        //     $rsc = $this->db->dbSelect("SELECT * FROM logs ORDER BY {$orderBy}");
            
        // }

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
    
    /**
     * getPecaCombobox
     *
     * @param int $estado 
     * @return array
     */
    public function getPecaCombobox($termo)
    {
        // Verifica se foi fornecido um termo de pesquisa válido
        if (!empty($termo)) {
            // Realiza a consulta no banco de dados
            $rsc = $this->db->select(
                "produto",
                "all",
                [
                    'where' => [
                        'nome' => ['LIKE', $termo],
                        'tipo_produto' => 2
                    ]
                ]
            );

            // Array para armazenar os resultados
            $produtos = [];
            foreach ($rsc as $produto) {
                $produtos[] = [
                    'id' => $produto['id'],
                    'nome' => $produto['nome']
                ];
            }

            return $produtos;
        }

        return [];
    }

    /**
     * insertOrdemServico
     *
     * @param array $OrdemServico
     * @param array $aProdutos
     * @return void
     */
    public function insertOrdemServico($ordem_servico, $aPecas)
    {

        $ultimoRegistro = $this->db->insert($this->table, $ordem_servico);
        
        if ($ultimoRegistro > 0) {

            if($aPecas[0]['id_peca'] != '') {
                foreach ($aPecas as $item) {

                    $item["id_ordem_servico"] = $ultimoRegistro;

                    $this->db->insert("ordens_servico_pecas", $item);
                }
            }

            return true;

        } else {
            return false;
        }
    }

    /**
     * updateOrdemServico
     *
     * @param array $OrdemServico
     * @param array $aProdutos
     * @return void
     */
    public function updateOrdemServico($id_ordem_servico, $ordem_servico, $aProdutos)
    {

        if($id_ordem_servico) {

            $condWhere = $id_ordem_servico['id'];
        
            $atualizaInformacoesOrdemServico = $this->db->update($this->table, ['id' => $condWhere], $ordem_servico);   

            if($atualizaInformacoesOrdemServico || isset($_SESSION['produto_mov_atualizado']) && $_SESSION['produto_mov_atualizado'] == true) {
                return true;
            } 

        } else {
            return false;
        }
    }

    public function updateInformacoesProdutoOrdemServico($id_ordem_servico, $aProdutos, $acao, $quantidade_ordem_servico = null)
    {

        $id_produto = isset($aProdutos[0]['id_peca']) ? $aProdutos[0]['id_peca'] : "";

        if($id_ordem_servico && $id_produto != "") {
            $condWhere = (int)$id_ordem_servico['id_ordem_servico'];

            foreach ($aProdutos as $item) {
                
                if($acao['acaoProduto'] == 'update') {

                    $item['quantidade'] = $aProdutos[0]['quantidade'] + $quantidade_ordem_servico;

                    $atualizaProdutosOrdemServico = $this->db->update("ordens_servico_pecas", ['id_ordem_servico' => $condWhere, 'id_peca' => $id_produto], $item);
                    
                    if($atualizaProdutosOrdemServico) {
                        return true;
                    }
                } else if($acao['acaoProduto'] == 'insert'){

                    $item['id_ordem_servico'] = $id_ordem_servico['id_ordem_servico'];
                    $item['quantidade'] = $aProdutos[0]['quantidade'];

                    $insereProdutosOrdemServico = $this->db->insert("ordens_servico_pecas", $item);

                    if($insereProdutosOrdemServico) {
                        return true;
                    }
                    
                } else {
                    echo "erro";
                }
            }

        } else {
            return false;
        }
    }

    public function deleteInfoProdutoOrdemServico($id_ordem_servico, $aProdutos, $tipo_ordem_servico, $quantidadeRemover)
    {

        $item_ordem_servico = $this->db->select(
            "ordens_servico_pecas",
            "all",
            [
            "where" => ["id_ordem_servico" => $id_ordem_servico, "id_peca" => $aProdutos[0]["id"]]
            ]
        );

        if ($item_ordem_servico) {

            // recupera a quantidade atual do item na ordem de serviço
            $quantidadeAtual = $item_ordem_servico[0]['quantidade'];

            // Verifica se a quantidade a ser removida não ultrapassa a quantidade atual na ordem de serviço
            if ($quantidadeRemover <= $quantidadeAtual) {
                // Subtrai a quantidade a ser removida da quantidade atual na ordem de serviço
                $novaQuantidadeMovimentacao = ($quantidadeAtual - $quantidadeRemover);

                // Atualiza a tabela movimetacao_itens com a nova quantidade
                $atualizaInfoProdutosMovimentacao = $this->db->update("ordens_servico_pecas", ['id_ordem_servico' => $id_ordem_servico, 'id_peca' => $item_ordem_servico[0]['id_peca']], ['quantidade' => $novaQuantidadeMovimentacao]);

                //Verifica se o produto existe
                if ($atualizaInfoProdutosMovimentacao) {
                    // Remove os produtos com quantidade igual a zero da ordem de servico
                    $qtdZero = $this->db->delete('ordens_servico_pecas', ['id_ordem_servico' => $id_ordem_servico, 'id_peca' =>  $item_ordem_servico[0]['id_peca'], 'quantidade' => 0]);
                    
                    return true;

                } else {
                    exit("msgError Erro ao atualizar produto na ordem de serviço.");
                    Session::set("msgError", "Erro ao atualizar produto na ordem de serviço.");
                    return false;
                }
            } else {
                exit("msgError Quantidade maior que a da ordem de serviço.");
                Session::set("msgError", "Quantidade maior que a da ordem de serviço.");
                return false;
                
            }
        } else {
            exit("msgError Item não encontrado na ordem de serviço.");
            Session::set("msgError", "Item não encontrado na ordem de serviço.");
            return false;
        }
    }

    public function delete_pecas_ordem($id_ordem_servico) {
        $qtdZero = $this->db->delete('ordens_servico_pecas', ['id_ordem_servico' => $id_ordem_servico]);
    }

    public function imprimirOS($id_ordem_servico) {

        require('assets/vendors/vendor/autoload.php');

        $result = $this->db->select(
            "ordens_servico",
            "all",
            [
            "where" => ["id" => $id_ordem_servico]
            ]
        );

        $resultA = $this->db->select(
            "ordens_servico_pecas",
            "all",
            [
            "where" => ["id_ordem_servico" => $id_ordem_servico]
            ]
        );

        $id_pecas = [];

        foreach ($resultA as $row) {
            if(!empty($row['id_peca'])) {
                $id_pecas[] = $row['id_peca'];
            }
        }
       
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Adicionar a imagem
        $pdf->Image(baseUrl() . 'assets/img/wephonelogo.jpg', 98, 10, 15);

        // Adicionar espaço abaixo da imagem
        $pdf->Ln(20);

        // Título
        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Ordem de Serviço'), 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);

        // // Cabeçalho da tabela principal
        // $pdf->Cell(60, 10, iconv('UTF-8', 'ISO-8859-1', 'Campo'), 1, 0, 'C');
        // $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Valor'), 1, 1, 'C');

        // Função para alternar cores
        function alternaCor($pdf, $line_number) {
            if ($line_number % 2 == 0) {
                $pdf->SetFillColor(230, 230, 230);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }
        }

        // Dados da tabela principal com linhas zebrada
        $linhas = [
            // ['ID:', $result[0]['id']],
            [iconv('UTF-8', 'ISO-8859-1', 'Nome do Cliente:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['cliente_nome'])],
            [iconv('UTF-8', 'ISO-8859-1', 'Telefone:'), iconv('UTF-8', 'ISO-8859-1', Formulario::formatarTelefone($result[0]['telefone_cliente']))],
            [iconv('UTF-8', 'ISO-8859-1', 'Modelo do Dispositivo:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['modelo_dispositivo'])],
            [iconv('UTF-8', 'ISO-8859-1', 'IMEI:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['imei_dispositivo'])],
            [iconv('UTF-8', 'ISO-8859-1', 'Descrição do Serviço:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['descricao_servico'])],
            [iconv('UTF-8', 'ISO-8859-1', 'Tipo de Serviço:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['tipo_servico'])],
            [iconv('UTF-8', 'ISO-8859-1', 'Problema Reportado:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['problema_reportado'])],
            [iconv('UTF-8', 'ISO-8859-1', 'Data de Abertura:'), iconv('UTF-8', 'ISO-8859-1', Formulario::formatarDataBrasileira($result[0]['data_abertura']))],
            [iconv('UTF-8', 'ISO-8859-1', 'Status:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['status'])],
            [iconv('UTF-8', 'ISO-8859-1', 'Observações:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['observacoes'])],
        ];

        // Largura das colunas
        $largura_campo = 60;
        $largura_valor = $pdf->GetPageWidth() - $largura_campo - 20;

        $line_number = 0;
        foreach ($linhas as $linha) {
            alternaCor($pdf, $line_number);
            $pdf->SetX(10);
            $pdf->Cell($largura_campo, 10, $linha[0], 1, 0, 'L', true);
            $pdf->SetX(10 + $largura_campo);
            $pdf->MultiCell($largura_valor, 10, $linha[1], 1, 'L', true);
            $line_number++;
        }

        // Adicionar espaço antes da seção de peças
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Orçamento'), 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);

        // Cabeçalho da tabela de peças
        $pdf->Cell(60, 10, iconv('UTF-8', 'ISO-8859-1', 'Peça'), 1, 0, 'C');
        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Valor'), 1, 1, 'C');

        $total = 0;

        foreach ($id_pecas as $id_peca) {
            if(!empty($id_peca)) {
                $result_pecas = $this->db->dbSelect("SELECT 
                    p.*,
                    mi.valor_venda
                FROM 
                    produto p
                INNER JOIN 
                    movimentacao_item mi ON p.id = mi.id_produtos
                WHERE 
                    p.id = ?;"
                    ,
                    [$id_peca]
                   
                );

                // Dados das peças
                foreach ($result_pecas as $peca) {

                    $peca_nome = isset($peca['nome']) ? trim($peca['nome']) : 'N/A';
                    $valor = isset($peca['valor_venda']) ? (float)$peca['valor_venda'] : 0.0;

                    $pdf->Cell(60, 10, iconv('UTF-8', 'ISO-8859-1', $peca_nome), 1, 0, 'L');
                    $pdf->Cell(0, 10,'R$ ' . number_format($valor, 2, ',', '.'), 1, 1, 'R');

                    $total += $valor;
                }
            }
        }

        // Adicionando uma linha para o total
        $pdf->Ln(10);
        $pdf->Cell(60, 10, 'Total', 1, 0, 'L');
        $pdf->Cell(0, 10, 'R$ ' . number_format($total, 2, ',', '.'), 1, 1, 'R');

        $pdf->Ln(20);
        $pdf->Cell(0, 10, '___________________________________________', 0, 1, 'C'); // Centralizado na página
        $pdf->Cell(0, 10, $result[0]['cliente_nome'], 0, 1, 'C'); // Centralizado na página

        $pdf->Output();
    }
}