<?php

use App\Library\ModelMain;

Class HistoricoProdutoModel extends ModelMain
{
    public $table = "historico_produto";

    public function historicoProduto($idProduto, $orderBy = 'id')
    {
        $rsc = $this->db->dbSelect("SELECT * FROM {$this->table} WHERE id_produtos = ? ORDER BY {$orderBy}", [$idProduto]);
            
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

       /**
     * getProdutoCombobox
     *
     * @param int $estado 
     * @return array
     */
    public function getHistoricoProduto($termo)
    {
        // Verifica se foi fornecido um termo de pesquisa válido
        if (!empty($termo)) {
            // Realiza a consulta no banco de dados
            $rsc = $this->db->select(
                "historico_produto",
                "all",
                [
                    'where' => [
                        'dataMod' => ['LIKE', $termo]
                    ]
                ]
            );

            // Array para armazenar os resultados
            $historico = [];
            foreach ($rsc as $historico_produto) {
                $historico[] = [
                    'id' => $historico_produto['id'],
                    'id_produtos' => $historico_produto['id_produtos'],
                    'nome_produtos' => $historico_produto['nome_produtos'],
                    'descricao_anterior' => $historico_produto['descricao_anterior'],
                    'quantidade_anterior' => $historico_produto['quantidade_anterior'],
                    'status_anterior' => $historico_produto['status_anterior'],
                    'statusItem_anterior' => $historico_produto['statusItem_anterior'],
                    'dataMod' => $historico_produto['dataMod']
                ];
            }

            return $historico;
        }

        return [];
    }
}