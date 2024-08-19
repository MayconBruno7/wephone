DELIMITER //

CREATE TRIGGER tg_delete_log_info_funcionario
AFTER DELETE ON `funcionario`
FOR EACH ROW
BEGIN
    INSERT INTO `logs` (tabela, acao, data, usuario, dados_antigos)
    VALUES (
        'funcionario',
        'DELETE',
        CURRENT_TIMESTAMP,
        @current_user, -- Substitua com a lógica para obter o usuário atual
        CONCAT(
            '{"id":', OLD.id, ', ',
            '"nome":"', OLD.nome, '", ',
            '"cpf":"', OLD.cpf, '", ',
            '"telefone":"', OLD.telefone, '", ',
            '"setor":', OLD.setor, ', ',
            '"salario":', OLD.salario, ', ',
            '"statusRegistro":', OLD.statusRegistro, ', ',
            '"cargo":', OLD.cargo, ', ',
            '"imagem":"', OLD.imagem, '"}'
        )
    );
END//

DELIMITER ;
