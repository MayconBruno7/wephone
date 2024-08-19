DELIMITER //

CREATE TRIGGER tg_insert_log_info_fornecedor
AFTER INSERT ON `fornecedor`
FOR EACH ROW
BEGIN
    INSERT INTO `logs` (tabela, acao, data, usuario, dados_novos)
    VALUES (
        'fornecedor',
        'INSERT',
        CURRENT_TIMESTAMP,
        @current_user, -- Substitua com a lógica para obter o usuário atual
        CONCAT(
            '{"id":', NEW.id, ', ',
            '"nome":"', NEW.nome, '", ',
            '"cnpj":"', NEW.cnpj, '", ',
            '"endereco":"', NEW.endereco, '", ',
            '"cidade":', NEW.cidade, ', ',
            '"estado":', NEW.estado, ', ',
            '"bairro":"', NEW.bairro, '", ',
            '"numero":"', NEW.numero, '", ',
            '"telefone":"', NEW.telefone, '", ',
            '"statusRegistro":', NEW.statusRegistro, '}'
        )
    );
END//

DELIMITER ;
