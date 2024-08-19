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
                <?= Formulario::titulo(!empty($dados[0]['nome_produto']) ? $dados[0]['nome_produto'] : "Nenhum histórico", true, false); ?>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    Lista de fornecedores
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbListasetor" class="table table-striped table-hover dataTable no-footer" style="width: 100%;" role="grid">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 113.297px;" aria-sort="ascending" aria-label="ID: activate to sort column descending">Pedido</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Fornecedor</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Tipo</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Data do pedido</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Data de chegada</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Quantidade</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Total</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 79.875px;" aria-label="Opções: activate to sort column ascending">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if ($dados) {
                                        foreach ($dados as $row) {
                                            ?>
                                                <tr>
                                                    <td> <?= isset($row['id_mov']) ? $row['id_mov'] : 'Nenhuma movimentação encontrada' ?> </td>
                                                    <td> <?= isset($row['nome_fornecedor']) ? $row['nome_fornecedor'] : 'Nenhum fornecedor encontrado' ?> </td>
                                                    <td> <?= isset($row['tipo']) ? Formulario::getTipoMovimentacao($row['tipo']) : 'Nenhum tipo de movimentação' ?></td>
                                                    <td> <?= isset($row['data_pedido']) ? date('d/m/Y', strtotime($row['data_pedido'])) : 'Nenhuma data de pedido encontrada' ?> </td>
                                                    <td> <?= isset($row['data_chegada']) ? date('d/m/Y', strtotime($row['data_chegada'])) : 'Nenhuma data de chegada encontrada' ?> </td>
                                                    <td> <?= isset($row['Quantidade']) ? $row['Quantidade'] : 'Nenhuma '?> </td>
                                                    <td> <?= isset($row['Valor']) ? number_format($row['Valor'], 2, ",", "."): 'Nenhuma '?> </td>
                                                    <td>
                                                        <a href="<?= baseUrl() ?>Movimentacao/Form/view/<?= $row['id_mov'] ?>" class="btn btn-outline-secondary btn-sm styleButton" title="Visualizar">Visualizar</a>&nbsp;
                                                        <a href="<?= baseUrl() ?>Movimentacao/Form/update/<?= $row['id_mov'] ?>" class="btn btn-outline-primary btn-sm styleButton" title="Alteração">Alterar</a>&nbsp;
                                                        <a href="<?= baseUrl() ?>Movimentacao/Form/<?= $row['id_mov'] ?>" class="btn btn-outline-danger btn-sm styleButton" title="Exclusão">Excluir</a>&nbsp;
                                                    </td>
                                                </tr>
                                            <?php
                                        }
                                    } 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

<?= Formulario::getDataTables("listaProdutos"); ?>



