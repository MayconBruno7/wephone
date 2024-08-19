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
                    Lista de fornecedores
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbListasetor" class="table table-striped table-hover dataTable no-footer" style="width: 100%;" role="grid">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 113.297px;" aria-sort="ascending" aria-label="ID: activate to sort column descending">ID</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Nome</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">CNPJ</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Telefone</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 79.5938px;" aria-label="Status do Usuario: activate to sort column ascending">Status do produto</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListasetor" rowspan="1" colspan="1" style="width: 79.875px;" aria-label="Opções: activate to sort column ascending">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($aDados as $value): ?>
                                    <tr role="row" class="odd">
                                        <td class="sorting_1"><?= $value['id'] ?></td>
                                        <td><?= $value['nome'] ?></td>
                                        <td><?= Formulario::formatarCNPJInput($value['cnpj']) ?></td>
                                        <td><?= Formulario::formatarTelefone($value['telefone']) ?></td>
                                        <td><?= getStatus($value['statusRegistro']) ?></td>
                                        <td>
                                            <?= Formulario::botao("view", $value['id']) ?>
                                            <?= Formulario::botao("update", $value['id']) ?>
                                            <?= Formulario::botao("delete", $value['id']) ?>
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
