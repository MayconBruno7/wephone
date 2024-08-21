<?php

namespace App\Library;

use PDO;
use PDOException;
use Exception;

use App\Library\Formulario as LibraryFormulario;

class Formulario
{
    static public function titulo($titulo, $btNew = true, $btVoltar = false) 
    {
        $service = new Service();
        $html = '';

        if ($service->getAcao() == 'insert') {
            $titulo .= " - Inclusão";
        } elseif ($service->getAcao() == 'update') {
            $titulo .= " - Alteração";
        } elseif ($service->getAcao() == 'delete') {
            $titulo .= " - Exclusão";
        } elseif ($service->getAcao() == 'view') {
            $titulo .= " - Visualização";
        }

        $html .= '<div class="text-center mt-4"><h2>' . $titulo . '</h2></div>';

            if ($btNew) {
                $html .= '  <div class="row">
                                    <div class="col-12 d-flex justify-content-start">
                                    '
                                         . Formulario::botao('insert') .
                                    '
                                    </div>
                            </div>';
            }

            if ($btVoltar) {
                $html .= Formulario::botao('voltar');
            }


        $html .= Formulario::mensagem();

        return $html;
    }

    /**
     * botao
     *
     * @param string $tipo 
     * @param mixed $id 
     * @return string
     */
    static public function botao($tipo, $id = null)
    {
        $service = new Service();

        $htmlBt = "";
        $url = baseUrl() . $service->getController();

        if ($tipo == 'insert') {
            $htmlBt = '<a href="' . $url . '/form/insert/0" class="btn btn-outline-primary btn-sm" title="Inserção">Inserir</a>&nbsp;';
        } elseif ($tipo == 'update') {
            $htmlBt = '<a href="' . $url . '/form/update/' . $id . '" class="btn btn-outline-primary btn-sm" title="Alteração">Alterar</a>&nbsp;';
        } elseif ($tipo == 'delete') {
            $htmlBt = '<a href="' . $url . '/form/delete/' . $id . '" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp;';
        } elseif ($tipo == 'view') {
            $htmlBt = '<a href="' . $url . '/form/view/' . $id . '" class="btn btn-outline-secondary btn-sm" title="Visualização">Visualizar</a>';
        } elseif ($tipo == 'voltar') {
            $htmlBt = '<a href="' . $url . '" class="btn btn-outline-secondary" title="Voltar">Voltar</a>';
        }

        return $htmlBt;
    }

    /**
     * exibeMsgSucesso
     *
     * @return string
     */
    static public function exibeMsgSucesso()
    {
        $html = "";

        if (Session::get("msgSuccess") != false) {
            $html .= '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        ' . Session::getDestroy("msgSuccess") . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        }

        return $html;
    }

    /**
     * exibeMsgError
     *
     * @return string
     */
    static public function exibeMsgError()
    {
        $html = "";

        if (Session::get("msgError") != false) {
            $html .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ' . Session::getDestroy("msgError") . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        }

        return $html;
    }

    static public function getCondicao($status)
    {
        if ($status == 1) {
            return "Novo";
        } elseif ($status == 2) {
            return "Usado";
        } else {
            return "...";
        }
    }

    static public function getStatusDescricao($status)
    {
        if ($status == 1) {
            return "Ativo";
        } elseif ($status == 2) {
            return "Inativo";
        } else {
            return "...";
        }
    }

    static public function getNivelDescricao($nivel)
    {
        if ($nivel == 1) {
            return "Administrador";
        } elseif ($nivel == 2) {
            return "Usuário";
        } else {
            return "...";
        }
    }

    static public function getTipoMovimentacao($tipo)
    {
        if ($tipo == 1) {
            return "Entrada";
        } elseif ($tipo == 2) {
            return "Saída";
        } else {
            return "...";
        }
    }

    static public function getTipo($tipo)
    {
        if ($tipo == 1) {
            return "Entrada";
        } elseif ($tipo == 2) {
            return "Saida";
        } else {
            return "...";
        }
    }

    static public function formatarCNPJInput($cnpj) {
        // Remove caracteres especiais
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
    
        // Adiciona pontos e traço
        $cnpjFormatado = substr($cnpj, 0, 2) . '.';
        $cnpjFormatado .= substr($cnpj, 2, 3) . '.';
        $cnpjFormatado .= substr($cnpj, 5, 3) . '/';
        $cnpjFormatado .= substr($cnpj, 8, 4) . '-';
        $cnpjFormatado .= substr($cnpj, 12, 2);
    
        return $cnpjFormatado;
    }    

    static public function formatarCPF($cpf) {
        // Remove caracteres indesejados do CPF
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
        // Adiciona pontos e traço ao CPF
        if(strlen($cpf) == 11) {
            return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9);
        }
    
        return $cpf; // Retorna o CPF não formatado se não tiver 11 dígitos
    }

    static public function formatarTelefone($telefone) {
        // Remove todos os caracteres não numéricos
        $telefone = preg_replace('/\D/', '', $telefone);
    
        // Verifica se o telefone possui 11 dígitos (incluindo o DDD) e formata de acordo
        if (strlen($telefone) == 11) {
            $telefoneFormatado = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
        } else {
            // Se não tiver 11 dígitos, trata como um telefone comum (DDD sem o 9)
            $telefoneFormatado = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
        }
    
        return $telefoneFormatado;
    }

    static public function formatarDataBrasileira($data) {
        // Converte a data para o formato timestamp
        $timestamp = strtotime($data);
    
        // Verifica se a string contém um horário
        $temHorario = (strpos($data, ':') !== false);
    
        // Formata a data no padrão brasileiro
        $formato = $temHorario ? 'd/m/Y H:i:s' : 'd/m/Y';
        $dataFormatada = date($formato, $timestamp);
        
        return $dataFormatada;
    }

    static public function formatarSalario($salario, $moeda = 'R$', $decimais = 2) {
        // Substituir vírgulas por ponto para padronizar o separador decimal
        $salario = str_replace(',', '.', $salario);
    
        // Remover caracteres não numéricos exceto ponto
        $salario = preg_replace("/[^0-9.]/", "", $salario);
    
        // Converter para número de ponto flutuante
        $salario = (float)$salario;
    
        // Formatar o número com vírgula como separador de milhar e precisão de casas decimais
        $salario_formatado = number_format($salario, $decimais, ',', '.');
    
        // Retornar o salário formatado com o símbolo da moeda
        return $moeda . ' ' . $salario_formatado;
    }

    /**
     * mensagem
     *
     * @return string
     */
    static public function mensagem()
    {
        $html = "";

        $html .= Formulario::exibeMsgSucesso();
        $html .= Formulario::exibeMsgError();

        if (Session::get("errors") != false) {
            
            $aErrors = Session::getDestroy('errors');
            $textoErros = "";

            foreach ($aErrors AS $value) {
                $textoErros .= ($textoErros != "" ? "<br />" : "") . $value;
            }

            $html .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ' . $textoErros . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
        }

        return $html;
    }

    static public function retornaHomeAdminOuHome() {
        if (Session::get('usuarioNivel') == 1) {
            $redirectUrl = 'Home/homeAdmin';
        } elseif (Session::get('usuarioNivel') == 11) {
            $redirectUrl = 'Home/home';
        } 

        return $redirectUrl;
    }


    /**
     * getDataTables
     *
     * @param string $table_id 
     * @return string
     */
    static public function getDataTables($table_id)
    {
        return '
     
            <style>
                .dataTables_wrapper {
                    position: relative;
                    clear: both;
                }
                
                .dataTables_filter {
                    float: right;
                    margin-bottom: 5px;
                }
                
                .dataTables_paginate {
                    float: right;
                    margin: 0;
                }
                
                .dataTables_paginate .pagination {
                    margin: 0;
                    padding: 0;
                    list-style: none;
                    white-space: nowrap; /* Evita que a paginação quebre em várias linhas */
                }
                
                .dataTables_paginate .pagination .page-link {
                    border: none;
                    outline: none;
                    box-shadow: none;
                    margin: 0 2px; /* Espaçamento entre os botões de paginação */
                }
                
                .dataTables_paginate .pagination .page-item.disabled .page-link {
                    pointer-events: none;
                    color: #aaa;
                }
                
                .dataTables_paginate .pagination .page-item.active .page-link {
                    background-color: #007bff;
                    color: #fff;
                }
                
                .dataTables_paginate .pagination .page-link:hover {
                    background-color: #0056b3;
                    color: #fff;
                }
            </style>
    
            <script>
                $(document).ready( function() {
                    $("#' . $table_id . '").DataTable( {
                        "order": [],
                        "columnDefs": [{
                            "targets": "no-sort",
                            "orderable": false,                       
                        }],
                        language: {
                            "sEmptyTable":      "Nenhum registro encontrado",
                            "sInfo":            "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                            "sInfoEmpty":       "Mostrando 0 até 0 de 0 registros",
                            "sInfoFiltered":    "(Filtrados de _MAX_ registros)",
                            "sInfoPostFix":     "",
                            "sInfoThousands":   ".",
                            "sLengthMenu":      "_MENU_ resultados por página",
                            "sLoadingRecords":  "Carregando...",
                            "sProcessing":      "Processando...",
                            "sZeroRecords":     "Nenhum registro encontrado",
                            "sSearch":          "Pesquisar",
                            "oPaginate": {
                                "sNext":        "Próximo",
                                "sPrevious":    "Anterior",
                                "sFirst":       "Primeiro",
                                "sLast":        "Último"
                            },
                            "oAria": {
                                "sSortAscending":   ": Ordenar colunas de forma ascendente",
                                "sSortDescending":  ": Ordenar colunas de forma descendente"
                            }
                        }
                    });
                });
            </script>
        ';
    }
}