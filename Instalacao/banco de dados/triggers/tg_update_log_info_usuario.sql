DELIMITER //

CREATE TRIGGER tg_update_log_info_usuario
AFTER UPDATE ON `usuario`
FOR EACH ROW
BEGIN
    INSERT INTO `logs` (tabela, acao, data, usuario, dados_antigos, dados_novos)
    VALUES (
        'usuario',
        'UPDATE',
        CURRENT_TIMESTAMP,
        @current_user, -- Substitua com a lógica para obter o usuário atual
        CONCAT(
            '{"id":', OLD.id, ', ',
            '"nivel":"', OLD.nivel, '", ',
            '"statusRegistro":', OLD.statusRegistro, ', ',
            '"nome":"', OLD.nome, '", ',
            '"senha":"', OLD.senha, '", ',
            '"email":"', OLD.email, '", ',
            '"primeiroLogin":', OLD.primeiroLogin, ', ',
            '"id_funcionario":', OLD.id_funcionario, '}'
        ),
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
