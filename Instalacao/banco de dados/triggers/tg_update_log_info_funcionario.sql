DELIMITER //

CREATE TRIGGER tg_update_log_info_funcionario
AFTER UPDATE ON `funcionario`
FOR EACH ROW
BEGIN
    INSERT INTO `logs` (tabela, acao, data, usuario, dados_antigos, dados_novos)
    VALUES (
        'funcionario',
        'UPDATE',
        CURRENT_TIMESTAMP,
        @current_user, -- Substitua com a lógica para obter o usuário atual
        CONCAT(
            '{"id":', OLD.id, ', ',
            '"nome":"', OLD.nome, '", ',
            '"cpf":"', OLD.cpf, '", ',
            '"telefone":"', OLD.telefone, '", ',
            '"setor":', OLD.setor, ', ',
            '"salario":', OLD.salario, ', ',
            '"statusRegistro":', OLD.statusRegistro, ', ',
            '"cargo":', OLD.cargo, ', ',
            '"imagem":"', OLD.imagem, '"}'
        ),
        CONCAT(
            '{"id":', NEW.id, ', ',
            '"nome":"', NEW.nome, '", ',
            '"cpf":"', NEW.cpf, '", ',
            '"telefone":"', NEW.telefone, '", ',
            '"setor":', NEW.setor, ', ',
            '"salario":', NEW.salario, ', ',
            '"statusRegistro":', NEW.statusRegistro, ', ',
            '"cargo":', NEW.cargo, ', ',
            '"imagem":"', NEW.imagem, '"}'
        )
    );
END//

DELIMITER ;
