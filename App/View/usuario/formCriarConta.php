<?php

use App\Library\Formulario;

?>

<script type="text/javascript" src="<?= baseUrl(); ?>assets/js/usuario.js"></script>

<main class="container">

    <div style="margin-top: 100px; margin-bottom: 50px;">
        <?= Formulario::titulo("Criar Conta", false, false) ?>
    </div>

    <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
        <div class="card card-primary">
            <div class="card-header">
            <h4>Cadastrar-se</h4>
            </div>
            <div class="card-body">
            <form method="POST" action="<?= baseUrl() ?>Login/novaContaVisitante">
                <div class="row">
                    <div class="form-group col-12">
                        <label for="nome">Nome</label>
                        <input id="nome" type="text" class="form-control" name="nome" autofocus="" placeholder="Informe o nome" required>
                    </div>
            
                </div>
                <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control" name="email" placeholder="Informe a senha" required>
                <div class="invalid-feedback">
                </div>
                </div>
                <div class="row">
                <div class="form-group col-6">
                    <label for="senha" class="d-block">Senha</label>
                    <input id="senha" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="senha"
                    value="<?= setValor('senha') ?>" 
                        required placeholder="Informe uma senha"
                        onkeyup="checa_segur_senha('senha', 'msgSenha', 'btGravar');">
                    <div id="msgSenha" class="msgNivel_senha"></div>
                    <div id="pwindicator" class="pwindicator">
                    <div class="bar"></div>
                    <div class="label"></div>
                    </div>
                </div>
                <div class="form-group col-6">
                    <label for="confSenha" class="d-block">Confirmação de senha</label>
                    <input id="confSenha" type="password" class="form-control" name="confSenha" value="<?= setValor('senha') ?>" 
                        required placeholder="Confirme a senha"
                        onkeyup="checa_segur_senha('confSenha', 'msgConfSenha', 'btGravar');">
                    <div id="msgConfSenha" class="msgNivel_senha"></div>
                </div>
                </div>
                <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    Registrar
                </button>
                </div>
                <input type="hidden" name="id" value="<?= setValor('id') ?>">
                <input type="hidden" name="statusRegistro" id="statusRegistro" value="1">
                <input type="hidden" name="nivel" id="nivel" value="11">
            </form>
            </div>
            <div class="mb-4 text-muted text-center">
            Já tem conta? <a href="<?= baseUrl() ?>Login/signIn">Login</a>
            </div>
        </div>
        </div>

</main>