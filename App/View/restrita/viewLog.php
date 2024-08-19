<?php 

    use App\Library\Session;
    use App\Library\Formulario;

    $nomeImagem = "";
    $nomeUsuario = "";

?>


<div class="container" style="margin-top: 100px;">

    <div class="card">
        <div class="card-header">
            <h4>Informações do log</h4>
        </div>

        <div class="card-body">
            <?php 
                foreach ($aDados['aUsuario'] as $usuario) {         
                    if ($usuario['id'] == $dados['usuario']) {
                        foreach ($aDados['aFuncionario'] as $funcionario) {
                            if ($usuario['id_funcionario'] == $funcionario['id']) {
                                $nomeImagem = $funcionario['imagem'];
                                $nomeUsuario = $usuario['nome'];
                            }
                        }
                    }
                }

            ?>

            <div class="author-box-center text-center">
                <div class="mb-5">
                    <?php if ((Session::get('id_funcionario')) && (Session::get('usuarioImagem'))) : ?>
                        <img src="<?= baseUrl() ?>uploads/funcionarios/<?= $nomeImagem ?>" alt="Imagem do Funcionário" class="rounded-circle" width="70" height="70">
                    <?php else : ?>
                        <img alt="image" class="rounded-circle" src="<?= baseUrl() . 'assets/img/users/person.svg' ?>" width="40px" height="40px">
                    <?php endif; ?>
                    <div class="container mt-2">
                        <span class="ml-3"><?= $nomeUsuario ?></span>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="tabela" class="col-sm-3 col-form-label">Tabela: </label>
                <div class="col-sm-9">
                    <input type="text" id="tabela" class="form-control" value="<?= $dados['tabela'] ?>" disabled>
                </div>
            </div>

            <div class="form-group row">
                <label for="acao" class="col-sm-3 col-form-label">Ação: </label>
                <div class="col-sm-9">
                    <input type="text" id="acao" class="form-control" value="<?= $dados['acao'] ?>" disabled>
                </div>
            </div>

            <div class="form-group row">
                <label for="data" class="col-sm-3 col-form-label">Data: </label>
                <div class="col-sm-9">
                    <input type="text" id="data" class="form-control" value="<?= Formulario::formatarDataBrasileira($dados['data']) ?>" disabled>
                </div>
            </div>

            <div class="form-group mb-4 row">
                <label for="dados_antigos" class="col-sm-3 col-form-label">Dados Antigos: </label>
                <div class="col-sm-9">
                    <textarea id="dados_antigos" class="form-control" readonly><?= $dados['dados_antigos'] ?></textarea>
                </div>
            </div>

            <div class="form-group  row">
                <label for="dados_novos" class="col-sm-3 col-form-label">Dados novos: </label>
                <div class="col-sm-9">
                    <textarea id="dados_novos" class="form-control" readonly><?= $dados['dados_novos'] ?></textarea>
                </div>
            </div>
        </div>
 
    </div>