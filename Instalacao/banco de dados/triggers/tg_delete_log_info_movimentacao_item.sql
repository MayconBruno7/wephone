DELIMITER //

CREATE TRIGGER tg_delete_log_info_movimentacao_item
AFTER DELETE ON `movimentacao_item`
FOR EACH ROW
BEGIN
    INSERT INTO `logs` (tabela, acao, data, usuario, dados_antigos)
    VALUES (
        'movimentacao_item',
        'DELETE',
        CURRENT_TIMESTAMP,
        @current_user, -- Substitua com a lógica para obter o usuário atual
        CONCAT(
            '{"id":', OLD.id, ', ',
            '"id_movimentacoes":', OLD.id_movimentacoes, ', ',
            '"id_produtos":', OLD.id_produtos, ', ',
            '"quantidade":', OLD.quantidade, ', ',
            '"valor":', OLD.valor, '}'
        )
    );
END//

DELIMITER ;
