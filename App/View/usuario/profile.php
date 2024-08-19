<?php
    use App\Library\Formulario;
    use App\Library\Session;

    $nomeCargo = 'Nenhum cargo encontrado'; // Valor padrão


    foreach ($dados['aFuncionario'] as $indice => $funcionario) {
        $nomeCargo = 'Nenhum cargo encontrado'; // Valor padrão

        foreach ($dados['aCargo'] as $indice => $cargo) {
            if ($funcionario['cargo'] == $cargo['id']) {
                $nomeCargo = $cargo['nome']; // Atualiza o nome do cargo encontrado
                break; // Sai do loop assim que o cargo correspondente é encontrado
            }
        }
    }


?>

<div class="container">
    <div class="card-body" style="margin-top: 130px;">
        <div class="col-12">
            <div class="card author-box">
                <div class="card-body">
                    <div class="author-box-center">
                        <?php if ((Session::get('id_funcionario')) && (Session::get('usuarioImagem'))) : ?>
                            <img alt="image" src="<?= baseUrl() ?>uploads/funcionarios/<?= Session::get('usuarioImagem') ?>" width="200px" height="200px" class="rounded-circle">
                        <?php else : ?>
                            <img alt="image" class="rounded-circle" src="<?= baseUrl() . 'assets/img/users/person.svg' ?>" width="40px" height="40px">
                        <?php endif; ?>
                        
                        <div class="clearfix"></div>
                        <div class="author-box-name">
                            <a href="#"><?= $dados['aFuncionario'][0]['nome'] ?></a>
                        </div>
                        <div class="author-box-job">
                            <?= $nomeCargo ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Detalhes pessoais</h4>
                </div>
                <div class="card-body">
                    <div class="py-4">
                        <p class="clearfix">
                            <span class="float-left">
                                <i class="far fa-address-card"> CPF</i>
                            </span>
                            <span class="float-right text-muted">
                                <?= Formulario::formatarCPF($dados['aFuncionario'][0]['cpf']) ?>
                            </span>
                        </p>
                        <p class="clearfix">
                            <span class="float-left">
                                <i class="fas fa-phone-volume"> Telefone</i>
                            </span>
                            <span class="float-right text-muted">
                                <?= Formulario::formatarTelefone($dados['aFuncionario'][0]['telefone']) ?>
                            </span>
                        </p>
                        <p class="clearfix">
                            <span class="float-left">
                                <i class="far fa-envelope"> E-mail</i>
                            </span>
                            <span class="float-right text-muted">
                                <?= setValor('email') ?>
                            </span>
                        </p>
                        <p class="clearfix">
                            <span class="float-left">
                                <i class="far fa-money-bill-alt"> Salário</i>
                            </span>
                            <span class="float-right text-muted">
                                R$ <?= number_format($dados['aFuncionario'][0]['salario'], 2, ',', '.') ?>
                            </span> 			
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
