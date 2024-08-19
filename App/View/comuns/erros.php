<div class="alert alert-danger" role="alert">
    <?php 
        if (isset($dados['msgError'])) {
            echo $dados['msgError'];
        } else {
            echo "Página não encontrada...";
        }
    ?>
</div>