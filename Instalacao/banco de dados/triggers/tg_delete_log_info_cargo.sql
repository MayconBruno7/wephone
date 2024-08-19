DELIMITER //

CREATE TRIGGER tg_delete_log_info_cargo
AFTER DELETE ON `cargo`
FOR EACH ROW
BEGIN
    INSERT INTO `logs` (tabela, acao, data, usuario, dados_antigos)
    VALUES (
        'cargo',
        'DELETE',
        CURRENT_TIMESTAMP,
        @current_user, -- Se você estiver usando uma variável de sessão ou contexto para o usuário
        CONCAT('{"id":', OLD.id, ', "nome":"', OLD.nome, '", "statusRegistro":', OLD.statusRegistro, '}')
    );
END//

DELIMITER ;
