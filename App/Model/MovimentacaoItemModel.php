<?php

use App\Library\ModelMain;

Class MovimentacaoItemModel extends ModelMain
{
    public $table = "movimentacao_item";

    /**
     * lista
     *
     * @param string $orderBy 
     * @return void
     */
    public function listaProdutos($id_movimentacao)
    {

        $rsc = $this->db->dbSelect("SELECT mi.id_movimentacoes,
                    mi.id_produtos AS id_prod_mov_itens,
                    mi.quantidade AS mov_itens_quantidade,
                    mi.valor,
                    mi.valor_venda,
                    p.*
                FROM {$this->table} mi
                INNER JOIN produto p ON p.id = mi.id_produtos
                WHERE mi.id_movimentacoes = ?
                    OR mi.id_movimentacoes IS NULL
                ORDER BY p.descricao;
                ",
                $id_movimentacao);

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
}