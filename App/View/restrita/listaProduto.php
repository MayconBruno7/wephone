<?php
    use App\Library\Formulario;
?>

<div class="loader"></div>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <!-- Navbar, Sidebar e Conteúdo aqui -->
        <main class="container mt-5">
            <div class="row">
                <div class="col-12 mt-3">
                    <?= Formulario::exibeMsgError() ?>
                </div>
                <div class="col-12 mt-3">
                    <?= Formulario::exibeMsgSucesso() ?>
                </div>
            </div>
            <div class="container mb-3">
            <?= Formulario::titulo($this->getAcao() == 'delete' ? $aDados[0]['nome'] : "", true, false); ?>

            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    Lista de Produtos
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbListaProduto" class="table table-striped table-hover dataTable no-footer" style="width: 100%;" role="grid">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="tbListaProduto" rowspan="1" colspan="1" style="width: 113.297px;" aria-sort="ascending" aria-label="ID: activate to sort column descending">ID</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListaProduto" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Nome Produto</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListaProduto" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Quantidade</th>
                                    <?php if (!$this->getAcao()) : ?>
                                        <th class="sorting" tabindex="0" aria-controls="tbListaProduto" rowspan="1" colspan="1" style="width: 79.5938px;" aria-label="Status do Usuario: activate to sort column ascending">Valor</th>

                                    <?php endif; ?>
                                    <th class="sorting" tabindex="0" aria-controls="tbListaProduto" rowspan="1" colspan="1" style="width: 79.5938px;" aria-label="Status do Usuario: activate to sort column ascending">Estado do produto</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListaProduto" rowspan="1" colspan="1" style="width: 79.5938px;" aria-label="Status do Usuario: activate to sort column ascending">Status do produto</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListaProduto" rowspan="1" colspan="1" style="width: 79.875px;" aria-label="Opções: activate to sort column ascending">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($aDados as $value): ?>
                                    <tr role="row" class="odd">
                                        <td class="sorting_1"><?= $value['id'] ?></td>
                                        <td><?= $value['nome'] ?></td>
                                        <td><?= !empty($value['quantidade']) ? $value['quantidade'] : 'Não encontrado' ?></td>
                                        <?php if (!$this->getAcao()) : ?>
                                            <td>
                                                <?= !empty($value['valor']) ? number_format($value['valor'], 2, ",", ".") : "Não encontrado" ?>
                                            </td>
                                        <?php endif; ?>
                                        <td><?= Formulario::getCondicao($value['condicao']) ?></td>
                                        <td><?= Formulario::getStatusDescricao($value['statusRegistro']) ?></td>
                                        <td>
                                        <?php if ($this->getAcao() == 'delete') : ?>
                                            <form class="g-3" action="<?= baseUrl() ?>OrdemServico/deleteProdutoOrdemServico/<?= $this->getAcao() ?>" method="post">
                                                <p>Quantidade atual: <?= $this->getOutrosParametros(5) ?></p>
                                                <label for="quantidadeRemover" class="form-label">Quantidade</label>
                                                <input type="number" name="quantidadeRemover" id="quantidadeRemover" class="form-control" required></input>
                                                <input type="hidden" name="id_produto" value="<?= $this->getOutrosParametros(4) ?>">
                                                <input type="hidden" name="id_movimentacao" value="<?= $this->getId() ?>">
                                                <input type="hidden" name="tipo" value="<?= $this->getOutrosParametros(6) ?>">
                                                <button type="submit" class="btn btn-primary btn-sm mt-2">Remover</button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if ($this->getAcao() == 'delete_peca') : ?>
                                            <form class="g-3" action="<?= baseUrl() ?>OrdemServico/deleteProdutoOrdemServico/delete" method="post">
                                                <p>Quantidade atual: <?= $this->getOutrosParametros(5) ?></p>
                                                <label for="quantidadeRemover" class="form-label">Quantidade</label>
                                                <input type="number" name="quantidadeRemover" id="quantidadeRemover" class="form-control" required></input>
                                                <input type="hidden" name="id_produto" value="<?= $this->getOutrosParametros(4) ?>">
                                                <input type="hidden" name="id_movimentacao" value="<?= $this->getId() ?>">
                                                <input type="hidden" name="tipo" value="<?= $this->getOutrosParametros(6) ?>">
                                                <button type="submit" class="btn btn-primary btn-sm mt-2">Remover</button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if (!$this->getAcao()) : ?>
                                            <?= Formulario::botao("view", $value['id']) ?>
                                            <?= Formulario::botao("update", $value['id']) ?>
                                            <?= Formulario::botao("delete", $value['id']) ?>
                                        <?php endif; ?>
                                    </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <?= Formulario::getDataTables("tbListaProduto"); ?>
    </div>
</div>
