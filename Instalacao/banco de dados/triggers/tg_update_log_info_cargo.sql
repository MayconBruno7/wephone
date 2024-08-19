DELIMITER //

CREATE TRIGGER tg_update_log_info_cargo
AFTER UPDATE ON `cargo`
FOR EACH ROW
BEGIN
    INSERT INTO `logs` (tabela, acao, data, usuario, dados_antigos, dados_novos)
    VALUES (
        'cargo',
        'UPDATE',
        CURRENT_TIMESTAMP,
        @current_user, -- Se você estiver usando uma variável de sessão ou contexto para o usuário
        CONCAT('{"id":', OLD.id, ', "nome":"', OLD.nome, '", "statusRegistro":', OLD.statusRegistro, '}'),
        CONCAT('{"id":', NEW.id, ', "nome":"', NEW.nome, '", "statusRegistro":', NEW.statusRegistro, '}')
    );
END//

DELIMITER ;
