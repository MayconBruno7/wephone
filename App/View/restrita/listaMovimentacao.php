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
            <?= Formulario::titulo('', true, false); ?>

            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    Lista de Movimentações
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbListasetor" class="table table-striped table-hover dataTable no-footer" style="width: 100%;" role="grid">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 113.297px;" aria-sort="ascending" aria-label="ID: activate to sort column descending">ID</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Fornecedor</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Tipo</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Data do pedido</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Data do pedido</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 79.875px;" aria-label="Opções: activate to sort column ascending">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($aDados as $row): ?>
                                    <tr role="row" class="odd">
                                        <td class="sorting_1"><?= $row['id_movimentacao'] ?></td>
                                        <td><?= !empty($row['nome_fornecedor']) ? $row['nome_fornecedor'] : "Venda na loja" ?></td>
                                        <td><?= Formulario::getTipo($row['tipo_movimentacao']) ?></td>
                                        <td><?= Formulario::formatarDataBrasileira($row['data_pedido']) ?></td>
                                        <td><?= $row['data_chegada'] != '0000-00-00' ? Formulario::formatarDataBrasileira($row['data_chegada']) : 'Nenhuma data encontrada' ?></td>
                                        <td>
                                            <?= Formulario::botao("view", $row['id_movimentacao']) ?>
                                            <?= Formulario::botao("update", $row['id_movimentacao']) ?>
                                            <?= Formulario::botao("delete", $row['id_movimentacao']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <?= Formulario::getDataTables("tbListasetor"); ?>
    </div>
</div>
