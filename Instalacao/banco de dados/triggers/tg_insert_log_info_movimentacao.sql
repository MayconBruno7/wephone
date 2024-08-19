DELIMITER //

CREATE TRIGGER tg_insert_log_info_movimentacao
AFTER INSERT ON `movimentacao`
FOR EACH ROW
BEGIN
    INSERT INTO `logs` (tabela, acao, data, usuario, dados_novos)
    VALUES (
        'movimentacao',
        'INSERT',
        CURRENT_TIMESTAMP,
        @current_user, -- Substitua com a lógica para obter o usuário atual
        CONCAT(
            '{"id":', NEW.id, ', ',
            '"id_setor":', NEW.id_setor, ', ',
            '"id_fornecedor":', NEW.id_fornecedor, ', ',
            '"statusRegistro":', NEW.statusRegistro, ', ',
            '"tipo":', NEW.tipo, ', ',
            '"motivo":"', NEW.motivo, '", ',
            '"data_pedido":"', NEW.data_pedido, '", ',
            '"data_chegada":"', NEW.data_chegada, '"}'
        )
    );
END//

DELIMITER ;
