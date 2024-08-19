DELIMITER //

CREATE TRIGGER tg_delete_log_info_movimentacao
AFTER DELETE ON `movimentacao`
FOR EACH ROW
BEGIN
    INSERT INTO `logs` (tabela, acao, data, usuario, dados_antigos)
    VALUES (
        'movimentacao',
        'DELETE',
        CURRENT_TIMESTAMP,
        @current_user, -- Substitua com a lógica para obter o usuário atual
        CONCAT(
            '{"id":', OLD.id, ', ',
            '"id_setor":', OLD.id_setor, ', ',
            '"id_fornecedor":', OLD.id_fornecedor, ', ',
            '"statusRegistro":', OLD.statusRegistro, ', ',
            '"tipo":', OLD.tipo, ', ',
            '"motivo":"', OLD.motivo, '", ',
            '"data_pedido":"', OLD.data_pedido, '", ',
            '"data_chegada":"', OLD.data_chegada, '"}'
        )
    );
END//

DELIMITER ;
