<?php
    use App\Library\Formulario;
?>

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
                    Lista de Usuários
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbListausuario" class="table table-striped table-hover dataTable no-footer" style="width: 100%;" role="grid">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="tbListausuario" rowspan="1" colspan="1" style="width: 113.297px;" aria-sort="ascending" aria-label="ID: activate to sort column descending">ID</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListausuario" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Nome</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListausuario" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">E-mail</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListausuario" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Nível</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListausuario" rowspan="1" colspan="1" style="width: 79.5938px;" aria-label="Status do Usuario: activate to sort column ascending">Status</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListausuario" rowspan="1" colspan="1" style="width: 79.875px;" aria-label="Opções: activate to sort column ascending">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($aDados as $value): ?>
                                    <tr role="row" class="odd">
                                        <td class="sorting_1"><?= $value['id'] ?></td>
                                        <td><?= $value['nome'] ?></td>
                                        <td><?= $value['email'] ?></td>
                                        <td><?= ($value['nivel'] == 1 ? "Administrador" : "Usuário") ?></td>
                                        <td><?= Formulario::getStatusDescricao($value['statusRegistro']) ?></td>
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

        <?= Formulario::getDataTables("tbListausuario"); ?>
    </div>
</div>
