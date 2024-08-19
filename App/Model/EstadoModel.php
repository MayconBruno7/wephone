<?php

use App\Library\ModelMain;
use App\Library\Session;

Class EstadoModel extends ModelMain
{

    public $table = "estado";

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
                       *
                    FROM 
                    {$this->table}"
            
            );
            
        } else {

            $rsc = $this->db->dbSelect(
                "SELECT 
                       * 
                    FROM 
                    {$this->table}
                ");
  
        }
        
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
}