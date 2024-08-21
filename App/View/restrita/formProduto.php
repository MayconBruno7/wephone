<?php

    use App\Library\Formulario;

    // var_dump($dados);
?>

<div class="container">
    
    <div class="container" style="margin-top: 100px;">
        <?= Formulario::titulo('Produto', false, false) ?>
    </div>

    <?php

        if ($this->getAcao() != 'insert') {
            ?>
            <div class="row">
                <div class="col-12 d-flex justify-content-start">
                    <a href="<?= baseUrl() ?>HistoricoProdutoMovimentacao/index/<?= $this->getAcao() ?>/<?= $this->getId() ?>" class="btn btn-outline-primary btn-sm mt-3 mb-3 m-0 styleButton" title="Visualizar">Visualizar Histórico de Movimentações</a>
                </div>
            </div>
        <?php
        }
    ?>

    <form method="POST" action="<?= baseUrl() ?>Produto/<?= $this->getAcao() ?>">

        <div class="row">

            <div class="col-10">
                <label for="nome" class="form-label">Nome</label>
                <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do item" required autofocus value="<?= setValor('nome') ?>" <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
            </div>

            <div class="col-2">
                <label for="tipo_produto" class="form-label">Tipo de produto</label>
                <select class="form-control" name="tipo_produto" id="tipo_produto" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value=""  <?= setValor('tipo_produto') == ""  ? "SELECTED": "" ?>>...</option>
                    <option value="1" <?= setValor('tipo_produto') == "1" ? "SELECTED": "" ?>>Produto</option>
                    <option value="2" <?= setValor('tipo_produto') == "2" ? "SELECTED": "" ?>>Peça</option>
                </select>
            </div>

            <div class="col-4 mt-3">
                <label for="statusRegistro" class="form-label">Status</label>
                <select class="form-control" name="statusRegistro" id="statusRegistro" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value=""  <?= setValor('statusRegistro') == ""  ? "SELECTED": "" ?>>...</option>
                    <option value="1" <?= setValor('statusRegistro') == "1" ? "SELECTED": "" ?>>Ativo</option>
                    <option value="2" <?= setValor('statusRegistro') == "2" ? "SELECTED": "" ?>>Inativo</option>
                </select>
            </div>

            <div class="col-4 mt-3">
                <label for="condicao" class="form-label">Estado do item</label>
                <select name="condicao" id="condicao" class="form-control" required <?= $this->getAcao() == 'delete' || $this->getAcao() == 'view' ? 'disabled' : '' ?>><?= setValor('condicao') ?>>
                    <!--  verifica se o statusItem está no banco de dados e retorna esse status -->
                    <option value=""  <?= setValor('condicao') == "" ? "selected" : ""  ?>>...</option>
                    <option value="1" <?= setValor('condicao') == 1  ? "selected" : ""  ?>>Novo</option>
                    <option value="2" <?= setValor('condicao') == 2  ? "selected" : ""  ?>>Usado</option>
                </select>
            </div>

            <div class="col-4 mt-3">
                <label for="quantidade" class="form-label">Quantidade</label>
                <!--  verifica se a quantidade está no banco de dados e retorna essa quantidade -->
                <input type="number" class="form-control" name="qtd_item" id="quantidade" min="1" max="100" value="<?= setValor('quantidade') ?>" disabled >
                <input type="hidden" name="quantidade" id="hidden" value="<?= setValor('quantidade') ?>" >
            </div>

            <div class="col-12 mt-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" name="descricao" id="descricao" placeholder="Descrição do item" <?= $this->getAcao() == 'delete' || $this->getAcao() == 'view' ? 'disabled' : '' ?>><?= setValor('descricao') ?></textarea>
            </div>

            <!-- se a ação for view não aparece a hora formatada no formprodutos -->
            <?php  if ($this->getAcao() == 'view' || $this->getAcao() == 'delete' || $this->getAcao() == 'update') { ?>
            <div class="col-6 mt-3">
                <label for="dataMod" class="form-label">Data da ultima modificação</label>
                <input type="text" class="form-control" name="dataMod" id="dataMod" value="<?= setValor('dataMod') ?>" disabled>

                <input type="hidden" class="form-control" name="dataMod" id="dataMod" value="<?= setValor('dataMod') ?>">
            </div>
            <?php 
            } 
            ?>

            <?php if ($this->getAcao() != 'insert' && $this->getAcao() != 'delete' && $this->getAcao() != 'view') : ?>
            <div class="col-6 mt-3">
                <label for="historico" class="form-label">Histórico de Alterações</label>
                <input type="date" class="form-control" name="historico" id="search_historico" placeholder="Data do histórico" autofocus value="" max="<?= date('Y-m-d') ?>" <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                <select id="id_produto" class="form-control" style="display:none;">
                    <option value="" selected disabled>Escolha a data</option>
                </select>
            </div>
            <?php endif; ?>

            <input type="hidden" name="id" id="id" value="<?= setValor('id') ?>">

            <div class="form-group col-12 mt-5">
                <?= Formulario::botao('voltar') ?>
                <?php if ($this->getAcao() != "view"): ?>
                    <button type="submit" value="submit" id="btGravar" class="btn btn-primary btn-sm">Gravar</button>
                <?php endif; ?>
            </div>
            
        </div>

    </form>

</div>


<script src="<?= baseUrl() ?>assets/ckeditor5/ckeditor.js"></script>

<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create(document.querySelector('#descricao'), {})
            .then(editor => { // Definindo o editor CKEditor aqui
                document.getElementById('search_historico').addEventListener('change', function() {
                    var option = this.options[this.selectedIndex];
                    
                    // Definindo os outros valores conforme necessário
                    document.getElementById('nome').value = option.getAttribute('data-nome');
                    document.getElementById('quantidade').value = option.getAttribute('data-quantidade');
                    document.getElementById('statusRegistro').value = option.getAttribute('data-status');
                    document.getElementById('condicao').value = option.getAttribute('data-statusitem');
                    editor.setData(option.getAttribute('data-descricao')); 
                    console.log(option);
                });
            })
            .catch(error => {
                console.error(error);
            });
    });

    // busca historico a partir da data escolhida
    $(function() {
        $('#search_historico').change(function() {
            var termo = $(this).val().trim();

            if (termo.length > 0) {
                $('.carregando').show();

                $.getJSON('/HistoricoProduto/getHistoricoProduto/' + termo, function(data) {
                    console.log(data);
                    var options = '<option value="" selected disabled>Escolha a data</option>';
                    if (data.length > 0) {
                        for (var i = 0; i < data.length; i++) {
                            options += '<option value="' + data[i].id + '" data-nome="' + data[i].nome_produtos + '" data-descricao="' + data[i].descricao_anterior + '" data-quantidade="' + data[i].quantidade_anterior + '" data-status="' + data[i].status_anterior + '" data-statusitem="' + data[i].statusItem_anterior + '">' + (data[i].dataMod != '0000-00-00 00:00:00' ? data[i].dataMod : 'Primeira alteração') + '</option>';
                        }
                    } else {
                        options = '<option value="" selected disabled>Nenhum histórico encontrado</option>';
                    }
                    $('#id_produto').html(options).show();

                    // Atualizar o formulário com os dados do primeiro item retornado
                    if (data.length > 0) {
                        var firstItem = data[0];
                        $('#nome').val(firstItem.nome_produtos);
                        $('#quantidade').val(firstItem.quantidade_anterior);
                        $('#statusRegistro').val(firstItem.status_anterior);
                        $('#condicao').val(firstItem.statusItem_anterior);
                        $('#descricao').val(firstItem.descricao_anterior);
                    }
                })
                .fail(function() {
                    console.error("Erro ao carregar histórico.");
                    $('#id_produto').html('<option value="" selected disabled>Erro ao carregar históricos</option>').show();
                })
                .always(function() {
                    $('.carregando').hide();
                });
            } else {
                $('#id_produto').html('<option value="" selected disabled>Escolha a data</option>').show();
            }
        });

        $('#id_produto').change(function() {
            var selectedOption = $(this).find(':selected');

            // Atualizar o formulário com os dados do item selecionado
            $('#nome').val(selectedOption.data('nome'));
            $('#quantidade').val(selectedOption.data('quantidade'));
            $('#statusRegistro').val(selectedOption.data('status'));
            $('#condicao').val(selectedOption.data('statusitem'));
            $('#descricao').val(selectedOption.data('descricao'));
        });
    });

</script>