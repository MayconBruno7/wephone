<?php
    use App\Library\Formulario;
?>

<div class="row mt-5">
    <div class="col-12 mt-5">
        <h2 class="text-center">Fale Conosco</h2>
    </div>
</div>

<main class="container mt-5 d-flex justify-content-center align-items-center">

    <div class="row">
        <div class="col-12">
            <?= Formulario::exibeMsgError() ?>
        </div>

        <div class="col-12 mt-3">
            <?= Formulario::exibeMsgSucesso() ?>
        </div>
    </div>

    <form class="g-3" action="<?= baseUrl() ?>FaleConosco/enviarEmail" method="POST">

        <div class="row">

            <div class="col-12 mt-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" name="nome" id="nome"
                       placeholder="Seu nome" required autofocus>
            </div>

            <div class="col-9 mt-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="text" class="form-control" name="email" id="email" 
                       placeholder="Seu e-mail" required>
            </div>

            <div class="col-3 mt-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control" name="telefone" id="telefone" 
                       placeholder="Seu telefone" required>
            </div>

            <div class="col-12 mt-3">
                <label for="assunto" class="form-label">Assunto</label>
                <input type="text" class="form-control" name="assunto" id="assunto" 
                       placeholder="Assunto a ser tratado" required>
            </div>

            <div class="col-12 mt-3">
                <label for="mensagem" class="form-label">Mensagem</label>
                <textarea class="form-control" rows="10" name="mensagem" id="mensagem" 
                          placeholder="Descreva mais sobre o assunto que deseja tratar conosoco."></textarea>
            </div>

            <div class="col-auto mt-5 mb-3">
                <button type="submit" class="btn btn-primary btn-sm">Enviar</button>
            </div>
        </div>

    </form>
</main>

<script src="<?= baseUrl() ?>assets/ckeditor5/ckeditor.js"></script>

<script>
    ClassicEditor
        .create(document.querySelector('#mensagem'))
        .catch( error => {
            console.error(error);
        });
</script>
