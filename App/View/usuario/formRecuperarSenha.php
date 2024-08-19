<?php

use App\Library\Formulario;
use App\Library\Session;

?>

<script type="text/javascript" src="<?= baseUrl(); ?>assets/js/usuario.js"></script>

<section>
    <div class="container">
        <div class="blog-banner">
            <div class="mt-5 mb-5 text-left">
                <h1 style="color: #384aeb;">Recuperação de Senha</h1>
            </div>
        </div>
    </div>
</section>

<div class="container" style="margin-top: 70px;">

    <div class="row">

        <div class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 div_login">                    

            <div class="card">

                <div class="card-body">

                    <form method="POST" id="recuperaSenhaform" class="form-horizontal" role="form" 
                        action="<?= baseUrl() ?>Login/atualizaRecuperaSenha">

                        <input type="hidden" name="id" id="id" value="<?= $dados['id'] ?>">
                        <input type="hidden" name="usuariorecuperasenha_id" id="usuariorecuperasenha_id" value="<?= $dados['usuariorecuperasenha_id'] ?>">
                        
                        <div style="margin-bottom: 25px" class="input-group">
                            <label class="ml-1">Olá <b><?= $dados['nome'] ?></b>! Para prosseguir com a recuperação da senha basta digitar a senha nos campos abaixo e clicar em atualizar.</label>                            
                        </div>

                        <div style="margin-bottom: 25px" class="control-group">
                            <span class="input-group-addon"><i class="fa fa-key"></i> Nova Senha</span>
                            <div class="controls">
                                <input id="NovaSenha" type="password" class="form-control" name="NovaSenha" required="required"
                                        onkeyup="checa_segur_senha( 'NovaSenha', 'msgSenhaNova', 'btEnviar' );">
                                <div id="msgSenhaNova" class="msgNivel_senha"></div>
                            </div>
                        </div>

                        <div style="margin-bottom: 25px" class="control-group">
                            <span class="input-group-addon"><i class="fa fa-key"></i> Confirme a nova senha</span>
                            <div class="controls">
                                <input id="NovaSenha2" type="password" class="form-control" name="NovaSenha2" placeholder="Nova senha" required="required"
                                        onkeyup="checa_segur_senha( 'NovaSenha2', 'msgSenhaNova2', 'btEnviar' );">
                                <div id="msgSenhaNova2" class="msgNivel_senha"></div>
                            </div>
                        </div>

                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-xs-2 controls">
                                <button class="btn btn-outline-primary btnCustomAzul" id="btEnviar" disabled>Atualizar</button>
                            </div>

                            <div class="col-xs-10 controls">
                                <?php 

                                    if (!empty(Session::get("msgError"))) {
                                        ?>
                                        <div class="alert alert-danger" role="alert">
                                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                            <?= Session::getDestroy("msgError") ?>
                                        </div>     
                                        <?php
                                    }

                                    if (!empty(Session::get("msgSuccess"))) {
                                        ?>                                    
                                        <div class="alert alert-success" role="alert">
                                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                            <?= Session::getDestroy("msgSuccess") ?>
                                        </div>      
                                        <?php
                                    }
                                ?>
                            </div>

                        </div>

                    </form>     

                </div>
            </div>

            <br>
            <br>

        </div>  

    </div>
    
</div>