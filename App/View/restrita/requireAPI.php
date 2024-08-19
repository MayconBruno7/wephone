<?php

    // Verifica se o parâmetro 'cnpj' foi enviado na requisição GET
    if(isset($this->getGet()['cnpj'])) {

        $cnpj = preg_replace("/[^0-9]/", "", $this->getGet()['cnpj']);
        // URL da API que você deseja acessar
        $url = "https://www.receitaws.com.br/v1/cnpj/{$cnpj}";

        // Configuração do contexto SSL para ignorar a verificação do certificado
        $options = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];
        $context = stream_context_create($options);

        // Faz a solicitação HTTP para a API com o contexto SSL configurado
        $response = file_get_contents($url, false, $context);

        // Verifica se a resposta da API não está vazia
        if($response !== false) {
            // Decodifica a resposta JSON em um array associativo
            $data = json_decode($response, true);

            // Verifica se a decodificação do JSON foi bem-sucedida
            if($data !== null) {
                // Retorna os dados da API como resposta para o frontend
                header('Content-Type: application/json');
                echo json_encode($data);
            } else {
                // Se a decodificação do JSON falhar, retorna um erro
                echo json_encode(['error' => 'Erro ao decodificar a resposta da API.']);
            }
        } else {
            // Se a solicitação HTTP falhar, retorna um erro
            echo json_encode(['error' => 'Erro ao consultar a API.']);
        }
    } else {
        // Se o parâmetro 'cnpj' não foi enviado na requisição GET, retorna um erro
        echo json_encode(['error' => 'Parâmetro CNPJ não fornecido na requisição.']);
    }

?>
