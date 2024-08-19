DELIMITER $$

CREATE TRIGGER `after_update_produto` 
AFTER UPDATE ON `produto`
FOR EACH ROW 
BEGIN
    INSERT INTO `logs` (`tabela`, `acao`, `usuario`, `dados_antigos`, `dados_novos`)
    VALUES ('produto', 'UPDATE', @current_user, CONCAT('ID: ', OLD.id, ', Nome: ', OLD.nome, ', Descrição: ', OLD.descricao, ', Quantidade: ', OLD.quantidade, ', Fornecedor: ', OLD.fornecedor),
            CONCAT('ID: ', NEW.id, ', Nome: ', NEW.nome, ', Descrição: ', NEW.descricao, ', Quantidade: ', NEW.quantidade, ', Fornecedor: ', NEW.fornecedor));
END $$

DELIMITER ;
