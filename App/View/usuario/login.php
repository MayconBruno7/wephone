<?php

use App\Library\Formulario;

?>

<section class="about section-margin" style="margin-top: 130px;">

    <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary">
              <div class="card-header">
                <h4>Login</h4>
              </div>
              <div class="col-12 mt-3">
                <?= Formulario::exibeMsgError() ?>
                </div>

                <div class="col-12 mt-3">
                    <?= Formulario::exibeMsgSucesso() ?>
                </div>
              <div class="card-body">
                <form method="POST" action="<?= baseUrl() ?>Login/signIn" class="needs-validation" novalidate="">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control" name="email" tabindex="1" required="" autofocus=""  value="<?= isset($_COOKIE['username']) ? $_COOKIE['username'] : '' ?>" autocomplete="current-username">
                    <div class="invalid-feedback">
                      Por favor preencha o email.
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="d-block">
                      <label for="senha" class="control-label">Senha</label>
                      <div class="float-right">
                        <a href="<?= baseUrl() ?>Login/solicitaRecuperacaoSenha" class="text-small">
                          Esqueceu sua senha?
                        </a>
                      </div>
                    </div>
                    <input id="senha" type="password" class="form-control" name="senha" tabindex="2" required="" value="<?= isset($_COOKIE['password']) ? $_COOKIE['password'] : '' ?>" autocomplete="current-password">
                    <div class="invalid-feedback">
                      Por favor preencha a senha.
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me" <?= isset($_COOKIE['username']) ? 'checked' : '' ?>>
                      <label class="custom-control-label" for="remember-me">Lembre de mim</label>
                    </div>
                  </div>
                  <div class="form-group">
                    <button type="submit" value="submit" class="btn btn-primary">Entrar</button>
                  </div>
                </form>
                <!-- <div class="text-center mt-4 mb-3">
                  <div class="text-job text-muted">Login With Social</div>
                </div>
                <div class="row sm-gutters">
                  <div class="col-6">
                    <a class="btn btn-block btn-social btn-facebook">
                      <span class="fab fa-facebook"></span> Facebook
                    </a>
                  </div>
                  <div class="col-6">
                    <a class="btn btn-block btn-social btn-twitter">
                      <span class="fab fa-twitter"></span> Twitter
                    </a>
                  </div>
                </div> -->
              </div>
            </div>
            <div class="mt-5 text-muted text-center">
              Não tem uma conta? <a href="<?= baseUrl() ?>Home/criarConta">Crie sua conta aqui</a>
            </div>
          </div>
        </div>
</section>