<?php

use App\Library\ModelMain;
use App\Library\Session;

Class MovimentacaoModel extends ModelMain
{
    public $table = "movimentacao";

    public $validationRules = [
        'fornecedor_id' => [
            'label' => 'Fornecedor',
            'rules' => 'required|int'
        ],
        'tipo' => [
            'label' => 'Tipo',
            'rules' => 'required|int'
        ],
        'motivo' => [
            'label' => 'Motivo',
            'rules' => 'required'
        ],
        'data_pedido' => [
            'label' => 'Data pedido',
            'rules' => 'required|DATE'
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
        if (Session::get('usuarioNivel') == 1) {
            $rsc = $this->db->dbSelect("SELECT DISTINCT
                m.id AS id_movimentacao,
                f.nome AS nome_fornecedor,
                m.tipo AS tipo_movimentacao,
                m.data_pedido,
                m.data_chegada
            FROM
                {$this->table} m
            LEFT JOIN
                fornecedor f ON f.id = m.id_fornecedor
            LEFT JOIN
                movimentacao_item mi ON mi.id_movimentacoes = m.id
            LEFT JOIN
                produto p ON p.id = mi.id_produtos");

        } else {
            $rsc = $this->db->dbSelect("SELECT DISTINCT
                m.id AS id_movimentacao,
                f.nome AS nome_fornecedor,
                m.tipo AS tipo_movimentacao,
                m.data_pedido,
                m.data_chegada
            FROM
                {$this->table} m
            LEFT JOIN
                fornecedor f ON f.id = m.id_fornecedor
            LEFT JOIN
                movimentacao_item mi ON mi.id_movimentacoes = m.id
            LEFT JOIN
                produto p ON p.id = mi.id_produtos
            WHERE
                m.statusRegistro = 1;");
        }

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    public function idUltimaMovimentacao()
    {

        $rsc = $this->db->dbSelect("SELECT MAX(id) AS ultimo_id FROM movimentacao");

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    /**
     * insertMovimentacao
     *
     * @param array $movimentacao
     * @param array $aProdutos
     * @return void
     */
    public function insertMovimentacao($movimentacao, $aProdutos)
    {

        $ultimoRegistro = $this->db->insert($this->table, $movimentacao);

        if ($ultimoRegistro > 0) {

            if($aProdutos[0]['id_produtos'] != '') {
                foreach ($aProdutos as $item) {

                    $item["id_movimentacoes"] = $ultimoRegistro;

                    $this->db->insert("movimentacao_item", $item);
                }
            }

            return true;

        } else {
            return false;
        }
    }

    /**
     * updateMovimentacao
     *
     * @param array $movimentacao
     * @param array $aProdutos
     * @return void
     */
    public function updateMovimentacao($idMovimentacao, $movimentacao, $aProdutos, $prod_info_mov_atualizado)
    {

        $tipo_movimentacao = $movimentacao['tipo'];

        if($idMovimentacao) {

            $condWhere = $idMovimentacao['id_movimentacao'];

            $atualizaInformacoesMovimentacao = $this->db->update($this->table, ['id' => $condWhere], $movimentacao);

            if($atualizaInformacoesMovimentacao || $prod_info_mov_atualizado) {
                unset($_SESSION['produto_mov_atualizado']);
                return true;
            }

        } else {
            return false;
        }
    }

    public function updateInformacoesProdutoMovimentacao($id_movimentacao, $aProdutos, $acao, $quantidade_produto, $quantidade_movimentacao = null)
    {

        $id_produto = isset($aProdutos[0]['id_produtos']) ? $aProdutos[0]['id_produtos'] : "";

        if($id_movimentacao && $id_produto != "") {
            $condWhere = $id_movimentacao['id_movimentacao'];

            foreach ($aProdutos as $item) {
                if($acao['acaoProduto'] == 'update') {
                    $item['quantidade'] = $quantidade_movimentacao;

                    $atualizaProdutosMovimentacao = $this->db->update("movimentacao_item", ['id_movimentacoes' => $condWhere, 'id_produtos' => $id_produto], $item);

                    if($atualizaProdutosMovimentacao) {
                        return true;
                    }
                }

                else if($acao['acaoProduto'] == 'insert'){

                    $item['id_movimentacoes'] = $id_movimentacao['id_movimentacao'];
                    $item['quantidade'] = $quantidade_movimentacao;

                    $insereProdutosMovimentacao = $this->db->insert("movimentacao_item", $item);

                    if($insereProdutosMovimentacao) {
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

    public function deleteInfoProdutoMovimentacao($id_movimentacao, $aProdutos, $tipo_movimentacao, $quantidadeRemover)
    {
        
        $item_movimentacao = $this->db->select(
            "movimentacao_item",
            "all",
            [
            "where" => ["id_movimentacoes" => $id_movimentacao, "id_produtos" => $aProdutos[0]["id"]]
            ]
        );

        if ($item_movimentacao) {

            // recupera a quantidade atual do item na movimentação
            $quantidadeAtual = $item_movimentacao[0]['quantidade'];

            // Verifica se a quantidade a ser removida não ultrapassa a quantidade atual na comanda
            if ($quantidadeRemover <= $quantidadeAtual) {
                // Subtrai a quantidade a ser removida da quantidade atual na comanda
                $novaQuantidadeMovimentacao = ($quantidadeAtual - $quantidadeRemover);

                // Atualiza a tabela movimetacao_itens com a nova quantidade
                $atualizaInfoProdutosMovimentacao = $this->db->update("movimentacao_item", ['id_movimentacoes' => $id_movimentacao, 'id_produtos' => $item_movimentacao[0]['id_produtos']], ['quantidade' => $novaQuantidadeMovimentacao]);

                //Verifica se o produto existe
                if ($atualizaInfoProdutosMovimentacao) {
                    // Remove os produtos com quantidade igual a zero da movimentação
                    $qtdZero = $this->db->delete('movimentacao_item', ['id_movimentacoes' => $id_movimentacao, 'id_produtos' =>  $item_movimentacao[0]['id_produtos'], 'quantidade' => 0]);
                    
                    return true;

                } else {
                    exit("msgError Erro ao atualizar produto na movimentação.");
                    Session::set("msgError", "Erro ao atualizar produto na movimentação.");
                    return false;
                }
            } else {
                exit("msgError Quantidade maior que a da movimentação..");
                Session::set("msgError", "Quantidade maior que a da movimentação.");
                return false;
                
            }
        } else {
            exit("msgError Item não encontrado na movimentação.");
            Session::set("msgError", "Item não encontrado na movimentação.");
            return false;
        }
    }


    /**
     * getProdutoCombobox
     *
     * @param int $estado 
     * @return array
     */
    public function getProdutoCombobox($termo, $tipo_produto)
    {
        // Verifica se foi fornecido um termo de pesquisa válido
        if (!empty($termo)) {
            // Realiza a consulta no banco de dados
            $rsc = $this->db->select(
                "produto",
                "all",
                [
                    'where' => [
                        'statusRegistro' => 1,
                        'tipo_produto' => $tipo_produto,
                        'nome' => ['LIKE', $termo],
                    ]
                ]
            );

            // var_dump($rsc);
            // exit;

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
}