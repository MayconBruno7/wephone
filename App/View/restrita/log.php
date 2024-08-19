<?php
    use App\Library\Formulario;
    use App\Library\Session;


    $nomeImagem  = "";
    $nomeUsuario = "";
?>

<div id="app" style="margin-top: 100px;">
    <div class="container">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Logs do sistema</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="table-1_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-striped" id="tableLog">
                                        <thead>
                                            <tr role="row">
                                                <th class="text-center" style="width: 61.6562px;">#</th>
                                                <th class="sorting" tabindex="0" aria-controls="table-1" rowspan="1" colspan="1" aria-label="Task Name: activate to sort column ascending" style="width: 256.125px;">Tabela</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Progress" style="width: 136.281px;">Ação</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Members" style="width: 338.281px;">Data</th>
                                                <th class="sorting" tabindex="0" aria-controls="table-1" rowspan="1" colspan="1" aria-label="Due Date: activate to sort column ascending" style="width: 162.531px;">Usuário</th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Progress" style="width: 136.281px;">Detalhes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($aDados['aLog'] as $value) : ?>   
                                                <?php
                                                    foreach ($aDados['aUsuario'] as $usuario) {         
                                                        if ($usuario['id'] == $value['usuario']) {
                                                            $nomeUsuario = $usuario['nome'];

                                                            foreach ($aDados['aFuncionario'] as $funcionario) {
                                                                if ($usuario['id_funcionario'] == $funcionario['id']) {
                                                                    $nomeImagem = $funcionario['imagem'];
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                                <tr role="row" class="odd">
                                                    <td class="sorting_1"><?= $value['id'] ?></td>
                                                    <td><?= $value['tabela'] ?></td>
                                                    <td class="align-middle"><?= $value['acao'] ?></td>
                                                    <td><?= Formulario::formatarDataBrasileira($value['data']) ?></td>
                                                    <td>
                                                        <div class="container">
                                                        <?php if ((Session::get('id_funcionario')) && (Session::get('usuarioImagem'))) : ?>
                                                            <img alt="image" class="rounded-circle" src="<?= baseUrl() . 'uploads/funcionarios/' . $nomeImagem ?>" width="40px" height="40px">
                                                        <?php else : ?>
                                                            <img alt="image" class="rounded-circle" src="<?= baseUrl() . 'assets/img/users/person.svg' ?>" width="40px" height="40px">
                                                        <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td><a href="<?= baseUrl() ?>/Log/viewLog/view/<?= $value['id'] ?>" class="btn btn-primary">Detalhes</a></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>

<?= Formulario::getDataTables("tableLog"); ?>


