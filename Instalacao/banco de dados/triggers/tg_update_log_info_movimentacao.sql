DELIMITER //

CREATE TRIGGER tg_update_log_info_movimentacao
AFTER UPDATE ON `movimentacao`
FOR EACH ROW
BEGIN
    INSERT INTO `logs` (tabela, acao, data, usuario, dados_antigos, dados_novos)
    VALUES (
        'movimentacao',
        'UPDATE',
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
        ),
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
