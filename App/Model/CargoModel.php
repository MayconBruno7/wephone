<?php

use App\Library\ModelMain;
use App\Library\Session;

Class CargoModel extends ModelMain
{
    public $table = "cargo";

    public $validationRules = [
        'nome' => [
            'label' => 'nome',
            'rules' => 'required|min:3|max:50'
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
            $rsc = $this->db->dbSelect("SELECT * FROM cargo ORDER BY {$orderBy} DESC");
            
        } else {
            $rsc = $this->db->dbSelect("SELECT * FROM cargo WHERE statusRegistro = 1 ORDER BY {$orderBy} DESC");
            
        }

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
    
}