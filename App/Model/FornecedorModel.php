<?php

use App\Library\ModelMain;
use App\Library\Session;

Class FornecedorModel extends ModelMain
{
    public $table = "fornecedor";

    public $validationRules = [
        'nome' => [
            'label' => 'nome',
            'rules' => 'required|min:3|max:144'
        ],
        'telefone' => [
            'label' => 'telefone',
            'rules' => 'required|min:9|max:14'
        ],

        'statusRegistro' => [
            'label' => 'Status',
            'rules' => 'required|int'
        ]
    ];

    public function lista($orderBy = 'id')
    {
        if (Session::get('usuarioNivel') == 1) {
            // $rsc = $this->db->dbSelect("SELECT * FROM {$this->table} ORDER BY {$orderBy}");
            $rsc = $this->db->dbSelect("SELECT * FROM {$this->table} ORDER BY {$orderBy}");
            
        } else {
            $rsc = $this->db->dbSelect("SELECT * FROM {$this->table} WHERE statusRegistro = 1 ORDER BY {$orderBy}");
            
        }

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    public function requireAPI($cnpj)
    {

        $cnpj_limpo = preg_replace("/[^0-9]/", "", $cnpj);
        $url = "https://www.receitaws.com.br/v1/cnpj/{$cnpj_limpo}";

        $options = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];

        $context = stream_context_create($options);

        $response = file_get_contents($url, false, $context);

        if ($response !== false) {
            $data = json_decode($response, true);
            
            if ($data !== null && isset($data['status']) && $data['status'] == 'OK') { // Verifica se a resposta é válida
                return $data;
            } else {
                return ['error' => 'Erro ao consultar a API: ' . (isset($data['message']) ? $data['message'] : 'Resposta inválida')];
            }
        } else {
            return ['error' => 'Erro ao consultar a API.'];
        }
    }

    /**
     * getProdutoCombobox
     *
     * @param int $estado 
     * @return array
     */
    public function getCidadeCombobox($estado) 
    {
        $rsc = $this->db->dbSelect("SELECT c.id, c.nome 
                                    FROM estado as e
                                    INNER JOIN cidade as c ON c.estado = e.id
                                    WHERE c.estado = ?
                                    ORDER BY c.nome",
                                    [$estado]);

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
}