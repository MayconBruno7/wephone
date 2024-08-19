DELIMITER //

CREATE TRIGGER tg_delete_log_info_fornecedor
AFTER DELETE ON `fornecedor`
FOR EACH ROW
BEGIN
    INSERT INTO `logs` (tabela, acao, data, usuario, dados_antigos)
    VALUES (
        'fornecedor',
        'DELETE',
        CURRENT_TIMESTAMP,
        @current_user, -- Substitua com a lógica para obter o usuário atual
        CONCAT(
            '{"id":', OLD.id, ', ',
            '"nome":"', OLD.nome, '", ',
            '"cnpj":"', OLD.cnpj, '", ',
            '"endereco":"', OLD.endereco, '", ',
            '"cidade":', OLD.cidade, ', ',
            '"estado":', OLD.estado, ', ',
            '"bairro":"', OLD.bairro, '", ',
            '"numero":"', OLD.numero, '", ',
            '"telefone":"', OLD.telefone, '", ',
            '"statusRegistro":', OLD.statusRegistro, '}'
        )
    );
END//

DELIMITER ;
