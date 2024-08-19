DELIMITER $$

CREATE TRIGGER `after_insert_produto` 
AFTER INSERT ON `produto`
FOR EACH ROW 
BEGIN
    INSERT INTO `logs` (`tabela`, `acao`, `usuario`, `dados_novos`)
    VALUES ('produto', 'INSERT', @current_user, CONCAT('ID: ', NEW.id, ', Nome: ', NEW.nome, ', Descrição: ', NEW.descricao, ', Quantidade: ', NEW.quantidade, ', Fornecedor: ', NEW.fornecedor));
END $$

DELIMITER ;
