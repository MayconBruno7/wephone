<?php

use App\Library\ModelMain;
use App\Library\Session;

Class LogModel extends ModelMain
{
    public $table = "logs";
    
    /**
     * lista
     *
     * @param string $orderBy 
     * @return void
     */
    public function lista($orderBy = 'id')
    {
        if (Session::get('usuarioNivel') == 1) {
            $rsc = $this->db->dbSelect("SELECT * FROM logs ORDER BY {$orderBy} DESC");
            
        } 

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
    
}