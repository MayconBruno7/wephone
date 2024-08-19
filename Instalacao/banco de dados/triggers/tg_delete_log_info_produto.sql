DELIMITER $$

CREATE TRIGGER `after_delete_produto` 
AFTER DELETE ON `produto`
FOR EACH ROW 
BEGIN
    INSERT INTO `logs` (`tabela`, `acao`, `usuario`, `dados_antigos`)
    VALUES ('produto', 'DELETE', @current_user, CONCAT('ID: ', OLD.id, ', Nome: ', OLD.nome, ', Descrição: ', OLD.descricao, ', Quantidade: ', OLD.quantidade, ', Fornecedor: ', OLD.fornecedor));
END $$

DELIMITER ;
