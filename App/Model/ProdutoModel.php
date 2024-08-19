<?php

use App\Library\ModelMain;
use App\Library\Session;
use App\Library\ControllerMain;


Class ProdutoModel extends ModelMain
{

    public $table = "produto";

    public $validationRules = [
        'descricao' => [
            'label' => 'Descrição',
            'rules' => 'required|min:3|max:50'
        ],
        'condicao' => [
            'label' => 'Condição',
            'rules' => 'required'
        ],
        'nome' => [
            'label' => 'Nome',
            'rules' => 'required'
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
            $rsc = $this->db->dbSelect(

                "SELECT 
                        produto.*, 
                        (SELECT valor FROM movimentacao_item WHERE id_produtos = produto.id LIMIT 1) AS valor
                    FROM 
                    {$this->table}"
            
            );
            
        } else {

            $rsc = $this->db->dbSelect("SELECT 
                    produto.*, 
                    (SELECT valor FROM movimentacao_item WHERE id_produtos = produto.id LIMIT 1) AS valor
                FROM 
                {$this->table}
                WHERE statusRegistro = 1 AND quantidade > 0");
  
        }
        
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    /**
     * lista
     *
     * @param string $orderBy 
     * @return void
     */
    public function listaDeleteProduto($id_produto)
    {

        if (Session::get('usuarioNivel') == 1) {
            $rsc = $this->db->dbSelect(
                "SELECT 
                    produto.*, 
                    (SELECT valor FROM movimentacao_item WHERE id_produtos = produto.id LIMIT 1) AS valor
                FROM 
                    {$this->table}
                WHERE 
                    produto.id = ?", [$id_produto]
            );
            
        } else {

            $rsc = $this->db->dbSelect(
                "SELECT 
                    produto.*, 
                    (SELECT valor FROM movimentacao_item WHERE id_produtos = produto.id LIMIT 1) AS valor
                FROM 
                    {$this->table}
                WHERE 
                    produto.statusRegistro = 1 AND produto.id = ?", [$id_produto]
            );
  
        }
        
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    public function recuperaProduto($idProduto)
    {

        $rsc = $this->db->dbSelect("SELECT * FROM {$this->table} WHERE id = ?", [$idProduto]);
            
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    public function recuperaPeca($idPeca)
    {

        $rsc = $this->db->dbSelect("SELECT * FROM {$this->table} WHERE id = ? AND tipo_produto = 2", [$idPeca]);
            
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    /**
     * updateMovimentacao
     *
     * @param array $movimentacao
     * @param array $aProdutos
     * @return void
     */
    public function updateProduto($id_produto, $id_movimentacao, $tipo_movimentacao, $infoProduto)
    {

        if($id_produto) {

            $condWhere = $id_produto;

            $produto = $this->recuperaProduto($id_produto);
            $quantidadeAtual = $produto[0]['quantidade'];

            if($tipo_movimentacao == 1) {
                $attquantidade = ($quantidadeAtual + $infoProduto['quantidade']);
            } else if ($tipo_movimentacao == 2) {
                $attquantidade = ($quantidadeAtual - $infoProduto['quantidade']);
            }

            $atualizaInformacoesProduto = $this->db->update($this->table, ['id' => $condWhere], ["quantidade" => $attquantidade]);

            foreach ($infoProduto as $item) {
                
                $atualizaProdutosMovimentacao = $this->db->update("movimentacao_item", ['id_movimentacoes' => $id_movimentacao, 'id_produtos' => $id_produto], ['quantidade' => $item]);
            }

            if($atualizaInformacoesProduto || $atualizaProdutosMovimentacao) {
                return true;
            }

        } else {
            return false;
        }
    }

        /**
     * lista
     *
     * @param string $orderBy 
     * @return void
     */
    public function listaPeca($id_ordem_servico)
    {

        $rsc = $this->db->dbSelect("SELECT 
                osp.id_ordem_servico,
                osp.id_peca,
                osp.quantidade AS quantidade_peca_ordem,
                p.*,
                mi.valor AS valor_peca -- Inclui o valor do produto da tabela movimentacao_item
            FROM 
                {$this->table} p
            INNER JOIN 
                ordens_servico_pecas osp ON p.id = osp.id_peca
            INNER JOIN 
                movimentacao_item mi ON osp.id_peca = mi.id_produtos
            WHERE 
                osp.id_ordem_servico = ?
                OR osp.id_ordem_servico IS NULL
            ORDER BY 
                p.id;
            ",
        $id_ordem_servico);

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
    
    public function insertHistoricoProduto($item)
    {
       
        $this->db->insert("historico_produto", $item);
    }
}