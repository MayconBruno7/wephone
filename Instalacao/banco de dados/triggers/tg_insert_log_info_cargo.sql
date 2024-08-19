DELIMITER //

CREATE TRIGGER tg_insert_log_info_cargo
AFTER INSERT ON `cargo`
FOR EACH ROW
BEGIN
    INSERT INTO `logs` (tabela, acao, data, usuario, dados_novos)
    VALUES (
        'cargo',
        'INSERT',
        CURRENT_TIMESTAMP,
        @current_user, -- Se você estiver usando uma variável de sessão ou contexto para o usuário
        CONCAT('{"id":', NEW.id, ', "nome":"', NEW.nome, '", "statusRegistro":', NEW.statusRegistro, '}')
    );
END//

DELIMITER ;
