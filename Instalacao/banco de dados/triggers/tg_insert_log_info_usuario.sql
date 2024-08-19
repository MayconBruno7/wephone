DELIMITER //

CREATE TRIGGER tg_insert_log_info_usuario
AFTER INSERT ON `usuario`
FOR EACH ROW
BEGIN
    INSERT INTO `logs` (tabela, acao, data, usuario, dados_novos)
    VALUES (
        'usuario',
        'INSERT',
        CURRENT_TIMESTAMP,
        @current_user, -- Substitua com a lógica para obter o usuário atual
        CONCAT(
            '{"id":', NEW.id, ', ',
            '"nivel":"', NEW.nivel, '", ',
            '"statusRegistro":', NEW.statusRegistro, ', ',
            '"nome":"', NEW.nome, '", ',
            '"senha":"', NEW.senha, '", ',
            '"email":"', NEW.email, '", ',
            '"primeiroLogin":', NEW.primeiroLogin, ', ',
            '"id_funcionario":', NEW.id_funcionario, '}'
        )
    );
END//

DELIMITER ;
