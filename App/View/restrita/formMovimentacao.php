<?php

    use App\Library\Formulario;
    use App\Library\Session;

    // Verificar se há uma sessão de movimentação
    if (!Session::get('movimentacao')) {
        Session::get('movimentacao');
    }

    // Verificar se há uma sessão de produtos
    if (!Session::get('produtos')) {
        Session::get('produtos');
    }

    if ($this->getAcao() == 'insert') {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Decodifica os dados recebidos do JavaScript
            $movimentacao = json_decode(file_get_contents("php://input"), true);
    
            // Verificar se há produtos a serem adicionados
            if (Session::get('produtos') && count(Session::get('produtos')) > 0) {
                // Adicionar os produtos à sessão de movimentação
                $movimentacao['produtos'] = Session::get('produtos');
            }
    
            // Adiciona os dados à sessão
            if (isset($movimentacao)) {
                $_SESSION['movimentacao'][] = $movimentacao;
            }
    
            // Limpar a sessão de produtos
            Session::destroy('produtos');
        }
    }
    
    $dadosMovimentacao = isset($_SESSION['movimentacao'][0]) ? $_SESSION['movimentacao'][0] : [];
    $total = 0;

?>

<main class="container mt-5">

    <div class="modal fade" id="modalAdicionarProduto" tabindex="-1" aria-labelledby="modalAdicionarProdutoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdicionarProdutoLabel">Adicionar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= ($this->getAcao() == 'update') ? baseUrl() . 'Movimentacao/update/updateProdutoMovimentacao/' . $this->getId() : baseUrl() . 'Movimentacao/insertProdutoMovimentacao/' . $this->getAcao() ?>" id="formAdicionarProduto" method="POST">
                        
                        <div class="col-12 mb-3">
                            <label for="tipo_produto" class="form-label">Tipo de produto</label>
                            <select name="tipo_produto" id="tipo_produto" class="form-control" required <?=  $this->getAcao() != 'insert' &&  $this->getAcao() != 'update' ? 'disabled' : ''?>>
                                <option value="">...</option>    
                                <option value="1" >Produto</option>
                                <option value="2" >Peça</option>
                            </select>
                        </div>    
                        
                        <div class="mb-3">
                            <div class="mb-3">
                                <label for="id_produto" class="form-label">Produto</label>
                                <input type="text" class="form-control" id="search_produto" placeholder="Pesquisar produto">
                                <select class="form-control" name="id_produto" id="id_produto" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                                    <option value="" selected disabled>Vazio</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="quantidade" class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor Unitário</label>
                            <input type="number" step="0.01" class="form-control" id="valor" name="valor" required>
                        </div>

                        <input type="hidden" name="id_movimentacao" value="<?= $this->getId() ?>">
                        <input type="hidden" name="tipo" value="<?= setValor('tipo') ?>">
                        <button type="submit" class="btn btn-primary">Adicionar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 200px;">
        <?= Formulario::titulo('Movimentação', false, false) ?>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <?= Formulario::exibeMsgError() ?>
        </div>
        <div class="col-12">
            <?= Formulario::exibeMsgSucesso() ?>
        </div>
    </div>

    <!-- <a href="<?= baseUrl() ?>/Movimentacao/getProdutoComboBox/a/2"> Testar </a> -->

    <!-- pega se é insert, delete ou update a partir do metodo get assim mandando para a página correspondente -->
    <form class="g-3" action="<?= baseUrl() ?>Movimentacao/<?= $this->getAcao() ?>" method="POST" id="form">

        <!--  verifica se o id está no banco de dados e retorna esse id -->
        <input type="hidden" name="id" id="id" value="<?= setValor('id') ?>">

        <?php if ( $this->getAcao() == 'insert') : ?>
        <div class="row justify-content-center">
            <div class="col-6 mt-3">
                <label for="fornecedor_id" class="form-label">Fornecedor</label>
                <select name="fornecedor_id" id="fornecedor_id" class="form-control" required <?=  $this->getAcao() != 'insert' &&  $this->getAcao() != 'update' ? 'disabled' : ''?>>
                    <option value="">...</option>
                    <?php foreach($dados['aFornecedorMovimentacao'] as $fornecedor) : ?>
                        <option value="<?= $fornecedor['id'] ?>" <?= isset($dadosMovimentacao['fornecedor_id']) && $dadosMovimentacao['fornecedor_id'] == $fornecedor['id'] ? 'selected' : '' ?>>
                            <?= $fornecedor['nome'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-3 mt-3">
                <label for="tipo" class="form-label">Tipo de Movimentação</label>
                <select name="tipo" id="tipo" class="form-control" required <?=  $this->getAcao() != 'insert' &&  $this->getAcao() != 'update' ? 'disabled' : ''?>>
                    <option value="">...</option>
                    <option value="1" <?= isset($dadosMovimentacao['tipo_movimentacao']) && $dadosMovimentacao['tipo_movimentacao'] == 1 ? 'selected' : '' ?>>Entrada</option>
                    <option value="2" <?= isset($dadosMovimentacao['tipo_movimentacao']) && $dadosMovimentacao['tipo_movimentacao'] == 2 ? 'selected' : '' ?>>Saída</option>
                </select>
            </div>

            <div class="col-3 mt-3">
                <label for="statusRegistro" class="form-label">Status da Movimentação</label>
                <select name="statusRegistro" id="statusRegistro" class="form-control" required <?=  $this->getAcao() != 'insert' &&  $this->getAcao() != 'update' ? 'disabled' : ''?>>
                    <option value="">...</option>
                    <option value="1" <?= isset($dadosMovimentacao['statusRegistro']) && $dadosMovimentacao['statusRegistro'] == 1 ? 'selected' : '' ?>>Ativo</option>
                    <option value="2" <?= isset($dadosMovimentacao['statusRegistro']) && $dadosMovimentacao['statusRegistro'] == 2 ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>

            <div class="col-4 mt-3">
                <label for="setor_id" class="form-label">Setor</label>
                <select name="setor_id" id="setor_id" class="form-control" required <?=  $this->getAcao() != 'insert' &&  $this->getAcao() != 'update' ? 'disabled' : '' ?>>
                    <option value="">...</option>
                    <?php foreach ($dados['aSetorMovimentacao'] as $setor): ?>
                        <option value="<?= $setor['id'] ?>" <?= (isset($dadosMovimentacao['setor_id']) && $dadosMovimentacao['setor_id'] == $setor['id']) ? 'selected' : '' ?>>
                            <?= $setor['nome'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-4 mt-3">
                <label for="data_pedido" class="form-label">Data do Pedido</label>
                <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                <input type="date" class="form-control" name="data_pedido" id="data_pedido" placeholder="data_pedido do item" required autofocus value="<?= isset($dadosMovimentacao['data_pedido']) ? $dadosMovimentacao['data_pedido'] : "" ?>" max="<?= date('Y-m-d') ?>" <?=  $this->getAcao() && ( $this->getAcao() == 'delete' ||  $this->getAcao() == 'view') ? 'disabled' : '' ?>>
            </div>

            <div class="col-4 mt-3">
                <label for="data_chegada" class="form-label">Data de Chegada</label>
                <!-- verifica se a data_chegada está no banco de dados e retorna essa data -->
                <input type="date" class="form-control" name="data_chegada" id="data_chegada" placeholder="data_chegada do item" value="<?= isset($dadosMovimentacao['data_chegada']) ? $dadosMovimentacao['data_chegada'] : "" ?>" max="<?= date('Y-m-d') ?>" min="<?= setValor('data_pedido') ?>" <?= $this->getAcao() && ( $this->getAcao() == 'delete' ||  $this->getAcao() == 'view') ? 'disabled' : '' ?>>
            </div>

            <div class="col-12 mt-3">
                <label for="motivo" class="form-label">Motivo</label>
                <textarea class="form-control" name="motivo" id="motivo" placeholder="Detalhe o motivo" <?= $this->getAcao() != 'insert' && $this->getAcao() != 'update' ? 'readonly' : ''?>><?= isset($dadosMovimentacao['motivo']) ? htmlspecialchars($dadosMovimentacao['motivo']) : '' ?></textarea>
            </div>
        </div>
        <?php endif; ?>
            
        <?php if ($this->getAcao() != 'insert') : ?>
        <div class="row justify-content-center">
            <div class="col-6 mt-3">
                <label for="fornecedor_id" class="form-label">Fornecedor</label>
                <select name="fornecedor_id" id="fornecedor_id" class="form-control" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value="">...</option>
                    <?php foreach($dados['aFornecedorMovimentacao'] as $fornecedor) : ?>
                        <option value="<?= $fornecedor['id'] ?>" <?= setValor('id_fornecedor') == $fornecedor['id'] ? 'selected' : '' ?>>
                            <?= $fornecedor['nome'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-3 mt-3">
                <label for="tipo" class="form-label">Tipo de Movimentação</label>
                <select name="tipo" id="tipo" class="form-control" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value="">...</option>
                    <option value="1" <?= setValor('tipo') == 1 ? 'selected' : '' ?>>Entrada</option>
                    <option value="2" <?= setValor('tipo') == 2 ? 'selected' : '' ?>>Saída</option>
                </select>
            </div>

            <div class="col-3 mt-3">
                <label for="statusRegistro" class="form-label">Status da Movimentação</label>
                <select name="statusRegistro" id="statusRegistro" class="form-control" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value="">...</option>
                    <option value="1" <?= setValor('statusRegistro') == 1 ? 'selected' : '' ?>>Ativo</option>
                    <option value="2" <?= setValor('statusRegistro') == 2 ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>

            <div class="col-8 mt-3">
                <label for="setor_id" class="form-label">Setor</label>
                <select name="setor_id" id="setor_id" class="form-control" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value="">...</option>
                    <?php foreach ($dados['aSetorMovimentacao'] as $setor): ?>
                        <option value="<?= $setor['id'] ?>" <?= setValor('id_setor') ? 'selected' : '' ?>>
                            <?= $setor['nome'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-2 mt-3">
                <label for="data_pedido" class="form-label">Data do Pedido</label>
                <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                <input type="date" class="form-control" name="data_pedido" id="data_pedido" placeholder="data_pedido do item" required autofocus value="<?= setValor('data_pedido') ?>" max="<?= date('Y-m-d') ?>" <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
            </div>

            <div class="col-2 mt-3">
                <label for="data_chegada" class="form-label">Data de Chegada</label>
                <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                <input type="date" class="form-control" name="data_chegada" id="data_chegada" placeholder="data_chegada do item" value="<?= setValor('data_chegada') ?>" min="<?= setValor('data_pedido') ?>" max="<?= date('Y-m-d') ?>" <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
            </div>


            <div class="col-12 mt-3">
                <label for="motivo" class="form-label">Motivo</label>
                <textarea class="form-control" name="motivo" id="motivo" placeholder="Detalhe o motivo" <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>><?= setValor('motivo') ?></textarea>
            </div>
            <?php endif; ?>

            <div class="col mt-4">
                <div class="col-md-8">
                    <h3 class="d-inline">Produtos do pedido</h3>
                </div>
            </div>

            <div class="col mt-4">
                <div class="col-auto text-end ml-2">
                <?php if ($this->getAcao() != "view" && $this->getAcao() != "delete"): ?>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="<?= ($this->getAcao() == 'insert') ? 'btnSalvar' : '' ?>" <?= ($this->getAcao() != 'insert') ? 'data-bs-toggle="modal" data-bs-target="#modalAdicionarProduto"' : '' ?>>
                        Adicionar Produtos
                    </button>
                <?php endif; ?>
                </div>
            </div>

            <table id="tbListaProduto" class="table table-striped table-hover table-bordered table-responsive-sm mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Id</th>
                    <th>Produto</th>
                    <th>Valor Unitário</th>
                    <th>Quantidade</th>
                    <th>Valor Total</th>
                    <th>Opções</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($_SESSION['movimentacao']) && isset($_SESSION['movimentacao'][0]['produtos']) && $this->getAcao() == 'insert' ? $this->getAcao() : "") : "" ?>
                    <?php foreach ($_SESSION['movimentacao'][0]['produtos'] as $produto) : ?>
                        <tr>
                            <td><?= $produto['id_produto'] ?></td>
                            <td><?= $produto['nome_produto'] ?></td>
                            <td><?= number_format($produto['valor'], 2, ",", ".") ?></td>
                            <td><?= $produto['quantidade'] ?></td>
                            <td><?= number_format(($produto['quantidade'] * $produto['valor']), 2, ",", ".") ?></td>
                            <td>
                                <?php if($this->getAcao() != 'delete' && $this->getAcao() != 'view') : ?>
                                    <a href="<?= baseUrl() ?>Produto/index/delete/<?= $this->getId() ?>/<?= $produto['id_produto'] ?>/<?= $produto['quantidade'] ?>/<?= setValor('tipo') ?>" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp;
                                    <!-- <a href="viewEstoque.php?acao=delete&id=<?= $produto['id_produto'] ?>&id_movimentacoes=<?= isset($idMovimentacaoAtual) ? $idMovimentacaoAtual : "" ?>&qtd_produto=<?=  isset($produto['quantidade']) ? $produto['quantidade'] : '' ?>&tipo=<?= isset($dadosMovimentacao['tipo_movimentacao']) ? $dadosMovimentacao['tipo_movimentacao'] : '' ?>" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp; -->
                                <?php endif; ?>
                                    <a href="formProdutos.php?acao=view&id=<?= $produto['id_produto'] ?>&id_movimentacoes=<?= isset($idMovimentacaoAtual) ? $idMovimentacaoAtual : "" ?>" class="btn btn-outline-secondary btn-sm" title="Visualização">Visualizar</a>
                            </td>
                        </tr>

                        <input type="hidden" name="quantidade" id="quantidade" value="<?= $produto['quantidade'] ?>">
                        <input type="hidden" name="id_produto" id="id_produto" value="<?= $produto['id_produto'] ?>">
                        <input type="hidden" name="valor" id="valor" value="<?= $produto['valor'] ?>">
                        <!-- <input type="hidden" name="tipo_movimentacoes" id="tipo_movimentacoes" value="<?= isset($dadosMovimentacao['tipo_movimentacao']) ? $dadosMovimentacao['tipo_movimentacao'] : '' ?>"> -->

                        <?php
                            $total += $produto['quantidade'] * $produto['valor'];
                        ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            
                <?php if($this->getAcao() != 'insert') : ?>
                    <?php

                            foreach ($dados['aItemMovimentacao'] as $row) {
                        ?>
                        <tr>
                            <td><?= $row['id_prod_mov_itens'] ?></td>
                            <td><?= $row['nome'] ?></td>
                            <td><?= number_format($row['valor'], 2, ",", ".")  ?> </td>
                            <td><?= $row['mov_itens_quantidade'] ?></td>
                            <td><?= number_format(($row["mov_itens_quantidade"] * $row["valor"]), 2, ",", ".") ?></td>
                            <td>
                            <?php if($this->getAcao() != 'delete' && $this->getAcao() != 'view') : ?>
                                <a href="<?= baseUrl() ?>Produto/index/delete/<?= $this->getId() ?>/<?= $row['id_prod_mov_itens'] ?>/<?= $row['mov_itens_quantidade'] ?>/<?= setValor('tipo') ?>" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp;
                                <!-- <a href="viewEstoque.php?acao=delete&id=<?= $row['id'] ?>&id_movimentacoes=<?= $row['id_movimentacoes'] ?>&qtd_produto=<?= $row['mov_itens_quantidade'] ?>&tipo=<?= isset($dados->tipo) ? $dados->tipo : ""?>" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp; -->
                            <?php endif; ?>
                                <a href="<?= baseUrl() ?>Produto/form/view/<?= $row['id_prod_mov_itens'] ?>" class="btn btn-outline-secondary btn-sm" title="Visualização">Visualizar</a>
                                <!-- <a href="formProdutos.php?acao=view&id=<?= $row['id'] ?>&id_movimentacoes=<?= $row['id_movimentacoes'] ?>" class="btn btn-outline-secondary btn-sm" title="Visualização">Visualizar</a> -->
                            </td>
                        </tr>

                        <input type="hidden" name="quantidade" id="quantidade" value="<?= $row['mov_itens_quantidade'] ?>">
                        <input type="hidden" name="id_produto" id="id_produto" value="<?= $row['id_prod_mov_itens'] ?>">
                        <input type="hidden" name="valor" id="valor" value="<?= $row['valor'] ?>">
                        <input type="hidden" name="tipo_movimentacoes" id="tipo_movimentacoes" value="<?= isset($dadosMovimentacao['tipo_movimentacao']) ? $dadosMovimentacao['tipo_movimentacao'] : '' ?>">

                        <?php

                            $total = $total + ($row["mov_itens_quantidade"] * $row["valor"]);

                            }
                        ?>
                <?php endif; ?>
            </tbody>
        </table>

        <p>
            <h2 align="center">
                Valor Total: R$ <?= number_format($total, 2, ',', '.')?>
            </h2>
        </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-6 d-flex justify-content-center mt-3">

            <?php if ($this->getOutrosParametros(4) == "home"): ?>
                <a href="<?= baseUrl() . Formulario::retornaHomeAdminOuHome() ?>" class="btn btn-primary btn-sm">Voltar</a>
            <?php endif; ?>

            <?php if ($this->getAcao() != "view"): ?>
                <button type="submit" value="submit" id="btGravar" class="btn btn-primary btn-sm">Gravar</button>
                <?= Formulario::botao('voltar') ?>
            <?php endif; ?>
            </div>
        </div>
    </form>

    <!-- <button onclick="capturarValores()">Salvar na Sessão</button> -->
</main>

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

<script>

    $(function() {
        // Evento de change no select de tipo_produto
        $('#tipo_produto').change(function() {
            var tipo_produto = $(this).val(); // Obtém o valor selecionado (2 para Produto ou Peça)
            
            // Agora vamos modificar a função de busca para considerar esse tipo de produto
            $('#search_produto').keyup(function() {
                var termo = $(this).val().trim();

                console.log(termo);

                if (termo.length > 0) {
                    $('#id_produto').hide();
                    $('.carregando').show();

                    $.getJSON('/Movimentacao/getProdutoComboBox/' + termo + '/' + tipo_produto, 
                    function(data) {
                        console.log(data);
                        var options = '<option value="" selected disabled>Escolha o produto</option>';
                        if (data.length > 0) {
                            for (var i = 0; i < data.length; i++) {
                                options += '<option value="' + data[i].id + '">' + data[i].id + ' - ' + data[i].nome + '</option>';
                            }
                        } else {
                            options = '<option value="" selected disabled>Nenhum produto encontrado</option>';
                        }
                        $('#id_produto').html(options).show();
                    })
                    .fail(function() {
                        console.error("Erro ao carregar produtos.");
                        $('#id_produto').html('<option value="" selected disabled>Erro ao carregar produtos</option>').show();
                    })
                    .always(function() {
                        $('.carregando').hide();
                    });
                } else {
                    $('#id_produto').html('<option value="" selected disabled>Escolha um produto</option>').show();
                }
            });
        });
    });


    document.addEventListener("DOMContentLoaded", function() {
 
        // Chama a função capturarValores quando o link for clicado
        document.getElementById('btnSalvar').addEventListener('click', function(event) {
            event.preventDefault(); // Previne o comportamento padrão de redirecionamento do link
            capturarValores();
        });

        function capturarValores() {
            var fornecedor_id = document.getElementById('fornecedor_id').value;
            var tipo_movimentacao = document.getElementById('tipo').value;
            var statusRegistro = document.getElementById('statusRegistro').value;
            var setor_id = document.getElementById('setor_id').value;
            var data_pedido = document.getElementById('data_pedido').value;
            var data_chegada = document.getElementById('data_chegada').value;
            var motivo = document.getElementById('motivo').value;

            // Array para armazenar os produtos
            var produtos = [];

            // Iterar sobre os campos de produto e capturar seus valores
            var produtosCampos = document.querySelectorAll('.produto-campo');
            produtosCampos.forEach(function(campo) {
                var id_produto = campo.querySelector('.id_produto').value;
                var nome_produto = campo.querySelector('.nome_produto').value;
                var valor = campo.querySelector('.valor').value;
                var quantidade = campo.querySelector('.quantidade').value;

                produtos.push({
                    'id_produto': id_produto,
                    'nome_produto': nome_produto,
                    'valor': valor,
                    'quantidade': quantidade
                });
            });

            // Criação do objeto movimentacao
            var movimentacao = {
                'fornecedor_id': fornecedor_id,
                'tipo_movimentacao': tipo_movimentacao,
                'statusRegistro': statusRegistro,
                'setor_id': setor_id,
                'data_pedido': data_pedido,
                'data_chegada': data_chegada,
                'motivo': motivo,
                'produtos': produtos 
            };

            // Função para abrir o modal
            function abrirModal() {
                var modal = new bootstrap.Modal(document.getElementById('modalAdicionarProduto'));
                modal.show();
            }

            // Envia os dados para o PHP usando AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'formMovimentacoes.php?acao=insert', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var idMovimentacoes = ''; // Defina o valor corretamente
                    var tipo = tipo_movimentacao;
                    abrirModal();
                } else {
                    console.log('Erro ao salvar informações');
                }
            };
            xhr.send(JSON.stringify(movimentacao));
        }
    });

</script>
