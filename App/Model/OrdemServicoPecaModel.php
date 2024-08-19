<?php

use App\Library\ModelMain;

Class OrdemServicoPecaModel extends ModelMain
{
    public $table = "ordens_servico_pecas";

    /**
     * lista
     *
     * @param string $orderBy 
     * @return void
     */
    public function listaPecas($id_ordem_servico)
    {

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
                osp.id AS ordem_peca_id,
                osp.id_peca,
                osp.quantidade
            FROM 
                {$this->table} osp
            LEFT JOIN 
                ordens_servico os ON os.id = osp.id_ordem_servico
            WHERE 
                osp.id_ordem_servico = ?
            ORDER BY 
                os.id;
                ",
                $id_ordem_servico);

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    public function recuperaPecaOS($idPeca, $id_ordem_servico)
    {

        $rsc = $this->db->dbSelect("SELECT * FROM {$this->table} WHERE id_peca = ? AND id_ordem_servico = ?", [$idPeca, $id_ordem_servico]);
            
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
}