     <?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\Validator;
use App\Library\Session;
use app\Library\Database;

class Movimentacao extends ControllerMain
{
    /**
     * construct
     *
     * @param array $dados 
     */
    public function __construct($dados)
    {
        $this->auxiliarConstruct($dados);

        // Só acessa se tiver logado
        if (!$this->getUsuario()) {
            return Redirect::page("Home");
        }
    }
 
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $this->loadView("restrita/listaMovimentacao", $this->model->lista("id"));
    }

    /**
     * form
     *
     * @return void
     */
    public function form()
    {
        $dados = [];

        if ($this->getAcao() != "insert") {
            $dados = $this->model->getById($this->getId());
        }

        $MovimentacaoItemModel = $this->loadModel("MovimentacaoItem");
        $dados['aItemMovimentacao'] = $MovimentacaoItemModel->listaProdutos($this->getId());

        $FornecedorModel = $this->loadModel("Fornecedor");
        $dados['aFornecedorMovimentacao'] = $FornecedorModel->lista('id');

        $ProdutoModel = $this->loadModel("Produto");
        $dados['aProduto'] = $ProdutoModel->lista('id');

        return $this->loadView("restrita/formMovimentacao", $dados);
    }

    public function insert()
    {

        $post = $this->getPost();

        // Verifica se todos os campos do formulário foram enviados
        if (
            isset($post['tipo'], $post['statusRegistro'],
            $post['data_pedido'], $post['motivo'], $post['statusRegistro'])
        ) {
        
            // Dados da movimentação
            $fornecedor_id = isset($post['fornecedor_id']) ? (int)$post['fornecedor_id'] : "";
            $data_pedido = $post['data_pedido'];
            $data_chegada = $post['data_chegada'];
            $motivo = $post['motivo'];
            $statusRegistro = (int)$post['statusRegistro'];
            $tipo_movimentacao = (int)$post['tipo'];

            // Dados do produto
            $quantidade = isset($post['quantidade']) ? (int)$post['quantidade'] : '';
            $id_produto = isset($post['id_produto']) ? (int)$post['id_produto'] : '';
            $valor_produto = isset($post['valor']) ? (float)$post['valor'] : '';
            $valor_venda = isset($post['valor_venda']) ? (float)$post['valor_venda'] : '';

            $ProdutoModel = $this->loadModel("Produto");
            $dadosProduto = $ProdutoModel->recuperaProduto($id_produto);
          
            if ($this->getAcao() == 'update') {
                
                // Verificar se há uma sessão de movimentação
                if (!isset($_SESSION['movimentacao'])) {
                    $_SESSION['movimentacao'] = array();
                }
            
                // Verificar se há produtos na sessão de movimentação
                if (!isset($_SESSION['movimentacao'][0]['produtos'])) {
                    $_SESSION['movimentacao'][0]['produtos'] = array();
                }
            
                // Verificar se o produto já está na sessão de movimentação
                $produtoEncontrado = false;
                foreach ($_SESSION['movimentacao'][0]['produtos'] as &$produto_sessao) {
                    if ($produto_sessao['id_produto'] == $id_produto) {
                        // Atualizar a quantidade do produto na sessão
                        $produto_sessao['quantidade'] += $quantidade;
                        $produtoEncontrado = true;
                        break;
                    }
                }
            } 
        
            $verificaQuantidadeEstoqueNegativa = false;
       
            if(isset($id_produto) && $id_produto != '') {
                if ($dadosProduto[0]['quantidade'] >= $quantidade && $tipo_movimentacao == '2') {
                    $verificaQuantidadeEstoqueNegativa = true;
                } else if ($dadosProduto[0]['quantidade'] <= $quantidade && $tipo_movimentacao == '2') {
                    $verificaQuantidadeEstoqueNegativa = false;
                } 
            }

            if ($tipo_movimentacao == '1') {
                $verificaQuantidadeEstoqueNegativa = true;
            }
            
            if ($verificaQuantidadeEstoqueNegativa) {

                // Prepara os dados da movimentação
                $movimentacaoData = [
                    "tipo"              => $tipo_movimentacao,
                    "statusRegistro"    => $statusRegistro,
                    "data_pedido"       => $data_pedido,
                    "data_chegada"      => $data_chegada,
                    "motivo"            => $motivo
                ];

                // Adiciona o id_fornecedor se não estiver vazio
                if (!empty($fornecedor_id)) {
                    $movimentacaoData["id_fornecedor"] = $fornecedor_id;
                }
                // parte da inserção de movimentações e produtos
                $inserindoMovimentacaoEProdutos = $this->model->insertMovimentacao(
                
                    $movimentacaoData,
                [
                    [
                        // "id_movimentacoes"  => '',
                        "id_produtos"       => $id_produto,
                        "quantidade"        => $quantidade,
                        "valor"             => $valor_produto,
                        "valor_venda"       => $valor_venda
                    ]
                ]
                );
                            
                if($inserindoMovimentacaoEProdutos) {
                    Session::destroy('movimentacao');
                    Session::destroy('produtos');
                    Session::set("msgSuccess", "Movimentação adicionada com sucesso.");
                    Redirect::page("Movimentacao");
                }
            } else {
                Session::set("msgError", "Quantidade da movimentação de saída maior que a do produto em estoque.");
                Redirect::page("Movimentacao/form/insert/0");
            }
        } else {
            Session::set("msgError", "Dados do formulário insuficientes.");
            Redirect::page("Movimentacao/form/insert/0");
        }
    }

    /**
     * insertProdutoMovimentacao
     *
     * @return void
     */
    public function insertProdutoMovimentacao()
    {
        $post = $this->getPost();

        $id_movimentacao = isset($post['id_movimentacoes']) ? (int)$post['id_movimentacoes'] : ""; 
        $quantidade = (int)$post['quantidade'];
        $id_produto = (int)$post['id_produto'];
        $valor_produto = (float)$post['valor'];
        $valor_venda = (float)$post['valor_venda'];

        $ProdutoModel = $this->loadModel("Produto");
        $dadosProduto['aProduto'] = $ProdutoModel->recuperaProduto($id_produto);

        // Verificar se há uma sessão de movimentação
        if (!isset($_SESSION['movimentacao'])) {
            $_SESSION['movimentacao'] = array();
        }

        // Verificar se há produtos na sessão de movimentação
        if (!isset($_SESSION['movimentacao'][0]['produtos'])) {
            $_SESSION['movimentacao'][0]['produtos'] = array();
        }
    
        // Verificar se o produto já está na sessão de movimentação
        $produtoEncontrado = false;
        foreach ($_SESSION['movimentacao'][0]['produtos'] as &$produto_sessao) {
            if ($produto_sessao['id_produto'] == $id_produto) {
                // Atualizar a quantidade do produto na sessão
                $produto_sessao['quantidade'] += $quantidade;
                $produtoEncontrado = true;
                break;
            }
        }
   
        // Se o produto não estiver na sessão de movimentação, adicioná-lo
        if (!$produtoEncontrado) {
            $_SESSION['movimentacao'][0]['produtos'][] = array(
                'nome_produto' => $dadosProduto['aProduto'][0]['nome'],
                'id_produto' => $id_produto,
                'quantidade' => $quantidade,
                'valor' => $valor_produto,
                "valor_venda"       => $valor_venda

            );
        }

        Session::set("msgSuccess", "Produto adicionado a movimentação.");
        Redirect::page("Movimentacao/form/insert/0");
    }

    /**
     * deleteProdutoMovimentacao
     *
     * @return void
     */
    public function deleteProdutoMovimentacao()
    {
        $post = $this->getPost();

        $id_movimentacao = isset($post['id_movimentacao']) ? (int)$post['id_movimentacao'] : ""; 
        $quantidadeRemover = (int)$post['quantidadeRemover'];
        $id_produto = (int)$post['id_produto'];
        $tipo_movimentacao = (int)$post['tipo'];

        if(isset($_SESSION['movimentacao'][0]['produtos']) && $this->getAcao() == 'delete') {
            // Verificar se o produto já está na sessão de movimentação
            $produtoEncontrado = false;
            foreach ($_SESSION['movimentacao'][0]['produtos'] as $key => &$produto_sessao) {
                if ($produto_sessao['id_produto'] == $id_produto) {
                    // Atualizar a quantidade do produto na sessão
                    $produto_sessao['quantidade'] -= $quantidadeRemover;

                    if ($produto_sessao['quantidade'] <= 0) {
                        // Remover o produto do array na sessão
                        unset($_SESSION['movimentacao'][0]['produtos'][$key]);
                    }
                    $produtoEncontrado = true;

                    Session::set("msgSuccess", "Produto excluído da movimentação.");
                    Redirect::page("Movimentacao/form/insert/0");
                    break;
                }
            }
        }
   
        if(!isset($_SESSION['movimentacao']) && $this->getAcao() == 'delete') {
            $ProdutoModel = $this->loadModel("Produto");
            $dadosProduto = $ProdutoModel->recuperaProduto($id_produto);

            $deletaProduto =  $this->model->deleteInfoProdutoMovimentacao($id_movimentacao, $dadosProduto, $tipo_movimentacao, $quantidadeRemover);

            if($deletaProduto) {
                Session::set("msgSuccess", "Item deletado da movimentação.");
                Redirect::page("Movimentacao/form/update/" . $id_movimentacao);
            }
        }
        
    }

    /**
     * update
     *
     * @return void
     */
    public function update()
    {
        $post = $this->getPost();
    
        if (
            isset($post['id']) || 
            isset($post['id_movimentacao']) || 
            isset($post['id_produto']) || 
            isset($post['fornecedor_id']) || 
            isset($post['tipo']) || 
            isset($post['statusRegistro']) || 
            isset($post['data_pedido']) || 
            isset($post['data_chegada']) || 
            isset($post['motivo'])
        ) {

            // Dados da movimentação
            $id_movimentacao = isset($post['id']) ? $post['id'] : (isset($post['id_movimentacao']) ? $post['id_movimentacao'] : "");
            $fornecedor_id = isset($post['fornecedor_id']) ? (int)$post['fornecedor_id'] : '';
            $data_pedido = isset($post['data_pedido']) ? $post['data_pedido'] : "";
            $data_chegada = isset($post['data_chegada']) ? $post['data_chegada'] : "";
            $motivo = isset($post['motivo']) ? $post['motivo'] : "";
            $statusRegistro = isset($post['statusRegistro']) ? (int)$post['statusRegistro'] : '';
            $tipo_movimentacao = isset($post['tipo']) ? (int)$post['tipo'] : '';
    
            // Dados do produto
            $id_produto = isset($post['id_produto']) ? $post['id_produto'] : '';
            (int)$quantidades = isset($post['quantidade']) ? $post['quantidade'] : '';
            $valores_produtos = isset($post['valor']) ? $post['valor'] : "";
            $valor_venda = isset($post['valor_venda']) ? $post['valor_venda'] : "";

            $produtoMovAtualizado = isset($_SESSION['produto_mov_atualizado']) ? $_SESSION['produto_mov_atualizado'] : [];

            $found = false;

            (int)$quantidade_produto = (int)$quantidades; 

            $MovimentacaoItemModel = $this->loadModel("MovimentacaoItem");
            $dadosItensMovimentacao = $MovimentacaoItemModel->listaProdutos($id_movimentacao);

            $quantidade_movimentacao = 0;

            foreach ($dadosItensMovimentacao as $index => $item) {
                if ($id_produto == $item['id_prod_mov_itens'] && $id_movimentacao == $item['id_movimentacoes']) {
                    if ($tipo_movimentacao == 1) {
                        $quantidade_movimentacao = $item['quantidade'] + (int)$quantidades;
                    } else if ($tipo_movimentacao == 2) {
                        $quantidade_movimentacao =  $item['quantidade'] - (int)$quantidades;
                    }
                    break;
                } else {
                    $quantidade_movimentacao = (int)$quantidades;
                }
            }

            if (!empty($dadosItensMovimentacao)) {
             
                foreach ($dadosItensMovimentacao as $item) {

                    if ($id_produto == $item['id_prod_mov_itens'] && $id_movimentacao == $item['id_movimentacoes']) {
                        $acaoProduto = 'update';
                        $found = true;

                        break;
                    }
                }     

                if (!$found) {
                    $acaoProduto = 'insert';
                }
         
            } else {
                $quantidade_movimentacao = (int)$quantidades;
                if(isset($post['id_produto'])) {
                    if ($id_produto == $post['id_produto'] && $id_movimentacao == $post['id_movimentacao']) {
                        $acaoProduto = 'insert';
                    }
                }   
            }

            if (!empty($dadosProduto)) {
                if ($dadosProduto[0]['quantidade'] >= $quantidades && $tipo_movimentacao == '2') {
                    $verificaQuantidadeEstoqueNegativa = true;
                } else if ($dadosProduto[0]['quantidade'] < $quantidades && $tipo_movimentacao == '2') {
                    $verificaQuantidadeEstoqueNegativa = false;
                } 
            }
            if ($tipo_movimentacao == '1') {
                $verificaQuantidadeEstoqueNegativa = true;
            } 
            
            if ($this->getAcao() != 'updateProdutoMovimentacao') {

                // Prepara os dados de movimentação
                $movimentacaoData = [
                    "tipo"              => $tipo_movimentacao,
                    "statusRegistro"    => $statusRegistro,
                    "data_pedido"       => $data_pedido,
                    "data_chegada"      => $data_chegada,
                    "motivo"            => $motivo
                ];
            
                // Adiciona o id_fornecedor se não estiver vazio
                if (!empty($fornecedor_id)) {
                    $movimentacaoData["id_fornecedor"] = $fornecedor_id;
                }
            
                $AtualizandoMovimentacaoEProdutos = $this->model->updateMovimentacao(
                    [
                        "id_movimentacao" => $id_movimentacao
                    ],
                    $movimentacaoData,
                    [
                        [
                            "id_produtos"       => $id_produto,
                            "quantidade"        => $quantidade_produto, 
                            "valor"             => $valores_produtos,
                            "valor_venda"       => $valor_venda
                        ]
                    ],
                    [
                        $produtoMovAtualizado
                    ]
                );
            
                if ($AtualizandoMovimentacaoEProdutos) {
                    Session::destroy('movimentacao');
                    Session::destroy('produtos');
                    Session::set("msgSuccess", "Movimentação alterada com sucesso.");
                    return Redirect::page("Movimentacao");
                } else {
                    Session::set("msgError", "Falha ao tentar alterar a Movimentação.");
                }
            
            } else if ($this->getAcao() == 'updateProdutoMovimentacao') {
            
                if ($verificaQuantidadeEstoqueNegativa) {
                    $AtualizandoInfoProdutoMovimentacao = $this->model->updateInformacoesProdutoMovimentacao(
                        [
                            "id_movimentacao" => $id_movimentacao
                        ],
                        [
                            [
                                "id_produtos"       => $id_produto,
                                "valor"             => $valores_produtos,
                                "valor_venda"       => $valor_venda
                            ]
                        ],
                        [
                            'acaoProduto' => $acaoProduto
                        ],
                        $quantidade_produto,
                        $quantidade_movimentacao
                    );
            
                    if ($AtualizandoInfoProdutoMovimentacao) {
                        if (!isset($_SESSION['produto_mov_atualizado'])) {
                            $_SESSION['produto_mov_atualizado'] = true;
                        }
                        
                        Session::destroy('movimentacao');
                        Session::destroy('produtos');
                        Session::set("msgSuccess", "Movimentação alterada com sucesso.");
                        return Redirect::page("Movimentacao/form/update/" . $id_movimentacao); 
                    }
            
                } else {
                    Session::set("msgError", "Quantidade da movimentação de saída maior que a do produto em estoque.");
                    return Redirect::page("Movimentacao/form/update/" . $id_movimentacao);
                }
            } else {
                Session::set("msgError", "Falha ao tentar alterar a Movimentação.");
                return Redirect::page("Movimentacao");
            }
            
        }
    }
    

    /**
     * delete
     *
     * @return void
     */
    public function delete()
    {

        $post = $this->getPost();

        // Dados do produto
        $id_produto = isset($post['id_produto']) ? $post['id_produto'] : '';
        $quantidades = isset($post['quantidade']) ? $post['quantidade'] : '';
        $valor_produto = isset($post['valor']) ? $post['valor'] : "";
        $valor_venda = isset($post['valor_venda']) ? $post['valor_venda'] : "";

        $dadosMovimentacao = $this->model->lista('id');

        $ItemModel = $this->loadModel("Produto");
        $dadosProduto = $ItemModel->recuperaProduto($id_produto);

        $quantidadeProduto = isset($dadosProduto[0]['quantidade']) ? $dadosProduto[0]['quantidade'] : 0;

        // Loop através de cada movimentação
        foreach ($dadosMovimentacao as $movimentacao) {
            if ($this->getPost('id') == $movimentacao['id_movimentacao']) {}
                $tipo_movimentacao = $movimentacao['tipo_movimentacao'];
        }

        if ($tipo_movimentacao == 1) {
            (int)$quantidade_produto = (int)$quantidadeProduto - (int)$quantidades;
        } else if ($tipo_movimentacao == 2) {
            (int)$quantidade_produto = (int)$quantidadeProduto + (int)$quantidades;
       
        }

        if ($this->model->delete(["id" => $this->getPost('id')])) {
       
            if (isset($id_produto) || $quantidade_produto) {
                $AtualizandoInfoProdutoMovimentacao = $this->model->updateInformacoesProdutoMovimentacao(
                    [
                        "id_movimentacao" => $this->getPost('id')
                    ],
                    [
                        [
                            "id_produtos"           => $id_produto,
                            "valor"                 => $valor_produto,
                            "valor_venda"           => $valor_venda

                        ]
                    ],
                    [
                        'acaoProduto' => 'update'
                    ],
                    $quantidade_produto
                );
            }

            Session::set("msgSuccess", "Movimentacao excluída com sucesso.");

        } else {
        
            Session::set("msgError", "Falha tentar excluir a Movimentacao.");
        }

        Redirect::page("Movimentacao");
    }

    public function getProdutoComboBox()
    {

        $dados = $this->model->getProdutoCombobox($this->getOutrosParametros(2), $this->getOutrosParametros(3)); 
    

        echo json_encode($dados);

    
    }

}