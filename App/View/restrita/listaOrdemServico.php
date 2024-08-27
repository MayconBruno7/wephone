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
            <?= Formulario::titulo("", true, false); ?>

            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    Lista de Ordens de serviço
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbListaProduto" class="table table-striped table-hover dataTable no-footer" style="width: 100%;" role="grid">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="tbListaProduto" rowspan="1" colspan="1" style="width: 113.297px;" aria-sort="ascending" aria-label="ID: activate to sort column descending">ID</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListaProduto" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Cliente</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListaProduto" rowspan="1" colspan="1" style="width: 175.656px;" aria-label="Usuario: activate to sort column ascending">Modelo dispositivo</th>
                                    <?php if (!$this->getAcao()) : ?>
                                        <th class="sorting" tabindex="0" aria-controls="tbListaProduto" rowspan="1" colspan="1" style="width: 79.5938px;" aria-label="Status do Usuario: activate to sort column ascending">Data</th>

                                    <?php endif; ?>
                                    <th class="sorting" tabindex="0" aria-controls="tbListaProduto" rowspan="1" colspan="1" style="width: 79.5938px;" aria-label="Status do Usuario: activate to sort column ascending">Tipo serviço</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListaProduto" rowspan="1" colspan="1" style="width: 79.5938px;" aria-label="Status do Usuario: activate to sort column ascending">Descrição serviço</th>
                                    <th class="sorting" tabindex="0" aria-controls="tbListaProduto" rowspan="1" colspan="1" style="width: 79.875px;" aria-label="Opções: activate to sort column ascending">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($aDados as $value): ?>
                                    <tr role="row" class="odd">
                                        <td class="sorting_1"><?= $value['ordem_id'] ?></td>
                                        <td><?= $value['cliente_nome'] ?></td>
                                        <td><?= $value['modelo_dispositivo'] ?></td>
                                        <td><?= $value['data_abertura'] ?></td>
                                        <td><?= $value['tipo_servico'] ?></td>
                                        <td><?= $value['descricao_servico'] ?></td>
                                        <td>

                                        <?php if (!$this->getAcao()) : ?> 
                                            <?= Formulario::botao("view", $value['ordem_id']) ?>
                                            <?= Formulario::botao("update", $value['ordem_id']) ?>
                                            <?= Formulario::botao("delete", $value['ordem_id']) ?>
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


