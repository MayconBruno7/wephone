<?php

    // Sanitiza e obtém os dados do formulário
    $emailRemetente = 'teste@gmail.com';
    $nomeRemetente = 'Sistema de controle de estoque';
    $assunto = 'Alerta de estoque';
    $mensagem = 'mensagem';

    // Construir a mensagem
    $message = "Os seguintes produtos estão com o estoque abaixo do limite de alerta:<br><br>";
    $temProdutoAbaixoDoLimite = false;

    foreach ($dados['aProduto'] as $produto) {
        if ($produto['quantidade'] < 3) {
            $message .= "Nome: " . $produto['nome'] . "<br>";
            $message .= "Quantidade: " . $produto['quantidade'] . "<br>";

            foreach ($dados['aFornecedor'] as $fornecedor) {
                if($fornecedor['id'] == $produto['fornecedor']) {
                    $fornecedorNome = $fornecedor['nome'];
                    break;
                }
            }

            $message .= "Fornecedor: " . $fornecedorNome . "<br><br>";
            $temProdutoAbaixoDoLimite = true;
        } 
    }

    // Definir uma mensagem de alerta caso não haja produtos abaixo do limite
    if ($temProdutoAbaixoDoLimite) {
        
?>

<!-- Inserir os dados e a mensagem em campos hidden -->
<input type="hidden" name="mensagem" id="messageContent" value="<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="email" id="emailRemetente" value="<?php echo htmlspecialchars($emailRemetente, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="nome" id="nomeRemetente" value="<?php echo htmlspecialchars($nomeRemetente, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="assunto" id="assunto" value="<?php echo htmlspecialchars($assunto, ENT_QUOTES, 'UTF-8'); ?>">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ler os valores dos campos hidden
        var message = document.getElementById('messageContent').value;
        var emailRemetente = document.getElementById('emailRemetente').value;
        var nomeRemetente = document.getElementById('nomeRemetente').value;
        var assunto = document.getElementById('assunto').value;
        var mensagem = document.getElementById('messageContent').value;

        // Configurar a URL da página de destino
        var targetUrl = '<?= baseUrl() ?>FaleConosco/enviaNotificacaoEstoque';

        // Enviar os dados usando Fetch API
        fetch(targetUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'message': message,
                'email': emailRemetente,
                'nome': nomeRemetente,
                'assunto': assunto,
                'mensagem': mensagem
            })
        })
        .then(response => response.text())
        .then(result => {
            console.log('Resposta da outra página:', result);
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    });
</script>

<?php  
    }
?>