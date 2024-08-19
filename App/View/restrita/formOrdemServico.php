<?php

    use App\Library\Formulario;
    use App\Library\Session;

    // Verificar se há uma sessão de movimentação
    if (!Session::get('ordem_servico')) {
        Session::get('ordem_servico');
    }

    // Verificar se há uma sessão de produtos
    if (!Session::get('produtos')) {
        Session::get('produtos');
    }

    if ($this->getAcao() == 'insert') {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Decodifica os dados recebidos do JavaScript
            $ordem_servico = json_decode(file_get_contents("php://input"), true);

            // Verificar se há produtos a serem adicionados
            if (Session::get('produtos') && count(Session::get('produtos')) > 0) {
                // Adicionar os produtos à sessão de movimentação
                $ordem_servico['produtos'] = Session::get('produtos');
            }

            // Adiciona os dados à sessão
            if (isset($ordem_servico)) {
                $_SESSION['ordem_servico'][] = $ordem_servico;
            }

            // Limpar a sessão de produtos
            Session::destroy('produtos');
        }
    }

    $dadosMovimentacao = isset($_SESSION['ordem_servico'][0]) ? $_SESSION['ordem_servico'][0] : [];
    $total = 0;
?>

<main class="container mt-5">

    <div class="modal fade" id="modalAdicionarProduto" tabindex="-1" aria-labelledby="modalAdicionarProdutoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdicionarProdutoLabel">Adicionar peça</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= ($this->getAcao() == 'update') ? baseUrl() . 'OrdemServico/update/updateProdutoOrdemServico/' . $this->getId() : baseUrl() . 'OrdemServico/insertProdutoOrdemServico/' . $this->getAcao() ?>" id="formAdicionarProduto" method="POST">
                        <div class="mb-3">
                            <div class="mb-3">
                                <label for="id_peca" class="form-label">Produto</label>
                                <input type="text" class="form-control" id="search_peca" placeholder="Pesquisar peça">
                                <select class="form-control" name="id_peca" id="id_peca" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                                    <option value="" selected disabled>Vazio</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="quantidade" class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade" required>
                        </div>

                        <input type="hidden" name="id_ordem_servico" value="<?= $this->getId() ?>">
                        
                        <button type="submit" class="btn btn-primary">Adicionar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 200px;">
        <?= Formulario::titulo('Ordem de serviço', false, false) ?>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <?= Formulario::exibeMsgError() ?>
        </div>
        <div class="col-12">
            <?= Formulario::exibeMsgSucesso() ?>
        </div>
    </div>

    <!-- pega se é insert, delete ou update a partir do metodo get assim mandando para a página correspondente -->
    <form class="g-3" action="<?= baseUrl() ?>OrdemServico/<?= $this->getAcao() ?>" method="POST" id="form">

        <!--  verifica se o id está no banco de dados e retorna esse id -->
        <input type="hidden" name="id" id="id" value="<?= setValor('id') ?>">

        <?php if ($this->getAcao() == 'insert') : ?>
        <div class="row justify-content-center">
            <div class="col-6 mt-3">
                <label for="cliente_nome" class="form-label">Nome do Cliente:</label>
                <input type="text" id="cliente_nome"  class="form-control" name="cliente_nome" value="<?= isset($dadosMovimentacao['cliente_nome']) ? $dadosMovimentacao['cliente_nome'] : "" ?>" required>
            </div>

            <div class="col-3 mt-3">
                <label for="telefone_cliente" class="form-label">Telefone:</label>
                <input type="text" id="telefone_cliente"  class="form-control" name="telefone_cliente" value="<?= isset($dadosMovimentacao['telefone_cliente']) ? $dadosMovimentacao['telefone_cliente'] : "" ?>">
            </div>

            <div class="col-3 mt-3">
                <label for="modelo_dispositivo" class="form-label">Modelo do Dispositivo:</label>
                <input type="text" id="modelo_dispositivo"  class="form-control" name="modelo_dispositivo" value="<?= isset($dadosMovimentacao['modelo_dispositivo']) ? $dadosMovimentacao['modelo_dispositivo'] : ""?>">
            </div>

            <div class="col-8 mt-3">
                <label for="imei_dispositivo" class="form-label">IMEI:</label>
                <input type="text" id="imei_dispositivo"  class="form-control" name="imei_dispositivo" value="<?= isset($dadosMovimentacao['imei_dispositivo']) ? $dadosMovimentacao['imei_dispositivo'] : "" ?>">
            </div>

            <div class="col-4 mt-3">
                <label for="tipo_servico" class="form-label">Tipo de Serviço:</label>
                <select id="tipo_servico"  class="form-control" name="tipo_servico" required>
                    <option value="" <?= isset($dadosMovimentacao['tipo_servico']) && $dadosMovimentacao['tipo_servico'] == "" ? "SELECTED" : "" ?>>...</option>
                    <option value="Reparo" <?= isset($dadosMovimentacao['tipo_servico']) && $dadosMovimentacao['tipo_servico'] == "Reparo" ? "SELECTED" : "" ?>>Reparo</option>
                    <option value="Troca de Peça" <?= isset($dadosMovimentacao['tipo_servico']) && $dadosMovimentacao['tipo_servico'] == "Troca de Peça" ? "SELECTED" : "" ?>>Troca de Peça</option>
                    <option value="Atualização" <?= isset($dadosMovimentacao['tipo_servico']) && $dadosMovimentacao['tipo_servico'] == "Atualização" ? "SELECTED" : "" ?>>Atualização</option>
                    <option value="Outros" <?= isset($dadosMovimentacao['tipo_servico']) && $dadosMovimentacao['tipo_servico'] == "Outros" ? "SELECTED" : "" ?>>Outros</option>
                </select>
            </div>

            <div class="col-12 mt-3">
                <label for="descricao_servico" class="form-label">Descrição do Serviço:</label>
                <textarea id="descricao_servico"  class="form-control" name="descricao_servico" required><?= isset($dadosMovimentacao['descricao_servico']) ? $dadosMovimentacao['descricao_servico'] : "" ?></textarea>
            </div>

            <div class="col-12 mt-3">
                <label for="problema_reportado" class="form-label">Problema Reportado:</label>
                <textarea id="problema_reportado"  class="form-control" name="problema_reportado"><?= isset($dadosMovimentacao['problema_reportado']) ? $dadosMovimentacao['problema_reportado'] : "" ?></textarea>
            </div>

            <div class="col-12 mt-3">
                <label for="data_abertura" class="form-label">Data de Abertura:</label>
                <input type="date" id="data_abertura"  class="form-control" name="data_abertura" value="<?= isset($dadosMovimentacao['data_abertura']) ? $dadosMovimentacao['data_abertura'] : "" ?>" required>
            </div>

            <div class="col-12 mt-3">
                <label for="status" class="form-label">Status do Serviço:</label>
                <select id="status"  class="form-control" name="status" required>
                    <option value="Aberto" <?= isset($dadosMovimentacao['status']) && $dadosMovimentacao['status'] == "" ? "SELECTED" : "" ?>>Aberto</option>
                    <option value="Em Andamento" <?= isset($dadosMovimentacao['status']) && $dadosMovimentacao['status'] == "" ? "SELECTED" : "" ?>>Em Andamento</option>
                    <option value="Aguardando Peças" <?= isset($dadosMovimentacao['status']) && $dadosMovimentacao['status'] == "" ? "SELECTED" : "" ?>>Aguardando Peças</option>
                    <option value="Concluído" <?= isset($dadosMovimentacao['status']) && $dadosMovimentacao['status'] == "" ? "SELECTED" : "" ?>>Concluído</option>
                    <option value="Cancelado" <?= isset($dadosMovimentacao['status']) && $dadosMovimentacao['status'] == "" ? "SELECTED" : "" ?>>Cancelado</option>
                </select>
            </div>

            <div class="col-12 mt-3">
                <label for="observacoes" class="form-label">Observações:</label>
                <textarea id="observacoes"  class="form-control" name="observacoes"><?= isset($dadosMovimentacao['observacoes']) ? $dadosMovimentacao['observacoes'] : "" ?></textarea>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($this->getAcao() != 'insert') : ?>
        <div class="row justify-content-center">
            <div class="col-6 mt-3">
                <label for="cliente_nome" class="form-label">Nome do Cliente:</label>
                <input type="text" id="cliente_nome"  class="form-control" name="cliente_nome" value="<?= setValor('cliente_nome') ?>" required>
            </div>

            <div class="col-3 mt-3">
                <label for="telefone_cliente" class="form-label">Telefone:</label>
                <input type="text" id="telefone_cliente"  class="form-control" name="telefone_cliente" value="<?= setValor('telefone_cliente') ?>">
            </div>

            <div class="col-3 mt-3">
                <label for="modelo_dispositivo" class="form-label">Modelo do Dispositivo:</label>
                <input type="text" id="modelo_dispositivo"  class="form-control" name="modelo_dispositivo" value="<?= setValor('modelo_dispositivo') ?>">
            </div>

            <div class="col-8 mt-3">
                <label for="imei_dispositivo" class="form-label">IMEI:</label>
                <input type="text" id="imei_dispositivo"  class="form-control" name="imei_dispositivo" value="<?= setValor('imei_dispositivo') ?>">
            </div>

            <div class="col-4 mt-3">
                <label for="tipo_servico" class="form-label">Tipo de Serviço:</label>
                <select id="tipo_servico"  class="form-control" name="tipo_servico" required>
                    <option value="" <?= setValor('tipo_servico') == "" ? "SELECTED" : "" ?>>...</option>
                    <option value="Reparo" <?= setValor('tipo_servico') == "Reparo" ? "SELECTED" : "" ?>>Reparo</option>
                    <option value="Troca de Peça" <?= setValor('tipo_servico') == "Troca de Peça" ? "SELECTED" : "" ?>>Troca de Peça</option>
                    <option value="Atualização" <?= setValor('tipo_servico') == "Atualização" ? "SELECTED" : "" ?>>Atualização</option>
                    <option value="Outros" <?= setValor('tipo_servico') == "Outros" ? "SELECTED" : "" ?>>Outros</option>
                </select>
            </div>

            <div class="col-12 mt-3">
                <label for="descricao_servico" class="form-label">Descrição do Serviço:</label>
                <textarea id="descricao_servico"  class="form-control" name="descricao_servico" required><?= setValor('descricao_servico') ?></textarea>
            </div>

            <div class="col-12 mt-3">
                <label for="problema_reportado" class="form-label">Problema Reportado:</label>
                <textarea id="problema_reportado"  class="form-control" name="problema_reportado"><?= setValor('problema_reportado') ?></textarea>
            </div>

            <div class="col-12 mt-3">
                <label for="data_abertura" class="form-label">Data de Abertura:</label>
                <input type="date" id="data_abertura"  class="form-control" name="data_abertura" value="<?= setValor('data_abertura') ?>" required>
            </div>

            <div class="col-12 mt-3">
                <label for="status" class="form-label">Status do Serviço:</label>
                <select id="status"  class="form-control" name="status" required>
                    <option value="Aberto">Aberto</option>
                    <option value="Em Andamento">Em Andamento</option>
                    <option value="Aguardando Peças">Aguardando Peças</option>
                    <option value="Concluído">Concluído</option>
                    <option value="Cancelado">Cancelado</option>
                </select>
            </div>

            <div class="col-12 mt-3">
                <label for="status" class="form-label">Tipo de Serviço:</label>
                <select id="status"  class="form-control" name="status" required>
                    <option value="" <?= setValor('status') == "" ? "SELECTED" : "" ?>>...</option>
                    <option value="Aberto" <?= setValor('status') == "Aberto" ? "SELECTED" : "" ?>>Aberto</option>
                    <option value="Em Andamento" <?= setValor('status') == "Em Andamento" ? "SELECTED" : "" ?>>Em Andamento</option>
                    <option value="Aguardando Peças" <?= setValor('status') == "Aguardando Peças" ? "SELECTED" : "" ?>>Aguardando Peças</option>
                    <option value="Concluído" <?= setValor('status') == "Concluído" ? "SELECTED" : "" ?>>Concluído</option>
                    <option value="Cancelado" <?= setValor('status') == "Cancelado" ? "SELECTED" : "" ?>>Cancelado</option>
                </select>
            </div>

            <div class="col-12 mt-3">
                <label for="observacoes" class="form-label">Observações:</label>
                <textarea id="observacoes"  class="form-control" name="observacoes"><?= setValor('observacoes') ?></textarea>
            </div>
        </div>
        <?php endif; ?>
            
            <div class="col mt-4">
                <div class="col-auto text-end ml-2">
                <?php if ($this->getAcao() != "view" && $this->getAcao() != "delete"): ?>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="<?= ($this->getAcao() == 'insert') ? 'btnSalvar' : '' ?>" <?= ($this->getAcao() != 'insert') ? 'data-bs-toggle="modal" data-bs-target="#modalAdicionarProduto"' : '' ?>>
                        Adicionar Peça
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
                <?php if(isset($_SESSION['ordem_servico']) && isset($_SESSION['ordem_servico'][0]['produtos']) && $this->getAcao() == 'insert' ? $this->getAcao() : "") : "" ?>
                    <?php foreach ($_SESSION['ordem_servico'][0]['produtos'] as $produto) : ?>
                        <tr>
                            <td><?= $produto['id_peca'] ?></td>
                            <td><?= $produto['nome_peca'] ?></td>
                            <td><?= number_format($produto['valor'], 2, ",", ".") ?></td>
                            <td><?= $produto['quantidade'] ?></td>
                            <td><?= number_format(($produto['quantidade'] * $produto['valor']), 2, ",", ".") ?></td>
                            <td>
                                <?php if($this->getAcao() != 'delete' && $this->getAcao() != 'view') : ?>
                                    <a href="<?= baseUrl() ?>Produto/index/delete/<?= $this->getId() ?>/<?= $produto['id_peca'] ?>/<?= $produto['quantidade'] ?>/<?= setValor('tipo') ?>" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp;
                                <?php endif; ?>
                                    <a href="formProdutos.php?acao=view&id=<?= $produto['id_peca'] ?>&id_movimentacoes=<?= isset($idMovimentacaoAtual) ? $idMovimentacaoAtual : "" ?>" class="btn btn-outline-secondary btn-sm" title="Visualização">Visualizar</a>
                            </td>
                        </tr>

                        <input type="hidden" name="quantidade" id="quantidade" value="<?= $produto['quantidade'] ?>">
                        <input type="hidden" name="id_peca" id="id_peca" value="<?= $produto['id_peca'] ?>">
                        <input type="hidden" name="valor" id="valor" value="<?= $produto['valor'] ?>">

                        <?php
                            $total += $produto['quantidade'] * $produto['valor'];
                        ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            
                <?php if($this->getAcao() != 'insert') : ?>
                    <?php
                            foreach ($dados['aPeca'] as $row) {
                           
                        ?>
                        <tr>
                            <td><?= $row['id_peca'] ?></td>
                            <td><?= $row['nome'] ?></td>
                            <td><?= number_format($row['valor_peca'], 2, ",", ".")  ?> </td>
                            <td><?= $row['quantidade_peca_ordem'] ?></td>
                            <td><?= number_format(($row["quantidade_peca_ordem"] * $row["valor_peca"]), 2, ",", ".") ?></td>
                            <td>
                            <?php if($this->getAcao() != 'delete' && $this->getAcao() != 'view') : ?>
                                    <a href="<?= baseUrl() ?>Produto/index/delete_peca/<?= $this->getId() ?>/<?= $row['id_peca'] ?>/<?= $row['quantidade'] ?>/1" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp;
                                <?php endif; ?>
                                    <a href="formProdutos.php?acao=view&id=<?= $row['id_peca'] ?>&id_movimentacoes=<?= isset($idMovimentacaoAtual) ? $idMovimentacaoAtual : "" ?>" class="btn btn-outline-secondary btn-sm" title="Visualização">Visualizar</a>
                            </td>
                        </tr>

                        <input type="hidden" name="quantidade" id="quantidade" value="<?= $row['quantidade_peca_ordem'] ?>">
                        <input type="hidden" name="id_peca" id="id_peca" value="<?= $row['id_peca'] ?>">
                        <input type="hidden" name="valor" id="valor" value="<?= $row['valor_peca'] ?>">

                        <?php

                            $total = $total + ($row["quantidade_peca_ordem"] * $row["valor_peca"]);

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

            <?php if($this->getAcao() != 'insert' && $this->getAcao() != 'delete') : ?>
                <a target="parent" href="<?= baseUrl() . 'OrdemServico/requireimprimirOS/' . setValor('id') ?>" class="btn btn-primary btn-sm">Imprimir ordem de serviço</a>
            <?php endif; ?>

            </div>
        </div>
    </form>

    <!-- <button onclick="capturarValores()">Salvar na Sessão</button> -->
</main>

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

<script>

    $(function() {
        $('#search_peca').keyup(function() {
            var termo = $(this).val().trim();

            if (termo.length > 0) {
                $('#id_peca').hide();
                $('.carregando').show();

                $.getJSON('/OrdemServico/getPecaComboBox/' + termo, 
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
                    $('#id_peca').html(options).show();
                })
                .fail(function() {
                    console.error("Erro ao carregar produtos.");
                    $('#id_peca').html('<option value="" selected disabled>Erro ao carregar produtos</option>').show();
                })
                .always(function() {
                    $('.carregando').hide();
                });
            } else {
                $('#id_peca').html('<option value="" selected disabled>Escolha um produto</option>').show();
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
 
        // Chama a função capturarValores quando o link for clicado
        document.getElementById('btnSalvar').addEventListener('click', function(event) {
            event.preventDefault(); // Previne o comportamento padrão de redirecionamento do link
            capturarValores();
        });

        function capturarValores() {
            var cliente_nome = document.getElementById('cliente_nome').value;
            var telefone_cliente = document.getElementById('telefone_cliente').value;
            var modelo_dispositivo = document.getElementById('modelo_dispositivo').value;
            var imei_dispositivo = document.getElementById('imei_dispositivo').value;
            var tipo_servico = document.getElementById('tipo_servico').value;
            var descricao_servico = document.getElementById('descricao_servico').value;
            var problema_reportado = document.getElementById('problema_reportado').value;
            var data_abertura = document.getElementById('data_abertura').value;
            var status = document.getElementById('status').value;
            var peca = document.getElementById('id_peca').value;
            var observacoes = document.getElementById('observacoes').value;

            // Array para armazenar os produtos
            var produtos = [];

            // Iterar sobre os campos de produto e capturar seus valores
            var produtosCampos = document.querySelectorAll('.produto-campo');
            produtosCampos.forEach(function(campo) {
                var id_peca = campo.querySelector('.id_peca').value;
                var nome_produto = campo.querySelector('.nome_peca').value;
                var valor = campo.querySelector('.valor').value;
                var quantidade = campo.querySelector('.quantidade').value;

                produtos.push({
                    'id_peca': id_peca,
                    'nome_produto': nome_produto,
                    'valor': valor,
                    'quantidade': quantidade
                });
            });

            // Criação do objeto movimentacao
            var ordem_servico = {
                'cliente_nome': cliente_nome,
                'telefone_cliente': telefone_cliente,
                'modelo_dispositivo': modelo_dispositivo,
                'imei_dispositivo': imei_dispositivo,
                'tipo_servico': tipo_servico,
                'descricao_servico': descricao_servico,
                'problema_reportado': problema_reportado,
                'data_abertura': data_abertura,
                'status': status,
                'peca': peca,
                'observacoes': observacoes,
                'produtos': produtos 
            };

            
            console.log(ordem_servico);

            // Função para abrir o modal
            function abrirModal() {
                var modal = new bootstrap.Modal(document.getElementById('modalAdicionarProduto'));
                modal.show();
            }

            // Envia os dados para o PHP usando AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?= baseUrl() ?>/OrdemServico/form/insert/0', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    
                    abrirModal();
                } else {
                    console.log('Erro ao salvar informações');
                }
            };
            xhr.send(JSON.stringify(ordem_servico));
        }
    });

</script>

