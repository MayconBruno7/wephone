DELIMITER //

CREATE TRIGGER tg_update_log_info_movimentacao_item
AFTER UPDATE ON `movimentacao_item`
FOR EACH ROW
BEGIN
    INSERT INTO `logs` (tabela, acao, data, usuario, dados_antigos, dados_novos)
    VALUES (
        'movimentacao_item',
        'UPDATE',
        CURRENT_TIMESTAMP,
        @current_user, -- Substitua com a lógica para obter o usuário atual
        CONCAT(
            '{"id":', OLD.id, ', ',
            '"id_movimentacoes":', OLD.id_movimentacoes, ', ',
            '"id_produtos":', OLD.id_produtos, ', ',
            '"quantidade":', OLD.quantidade, ', ',
            '"valor":', OLD.valor, '}'
        ),
        CONCAT(
            '{"id":', NEW.id, ', ',
            '"id_movimentacoes":', NEW.id_movimentacoes, ', ',
            '"id_produtos":', NEW.id_produtos, ', ',
            '"quantidade":', NEW.quantidade, ', ',
            '"valor":', NEW.valor, '}'
        )
    );
END//

DELIMITER ;
