DELIMITER $$

CREATE TRIGGER `tg_delete_log_info_setor` 
AFTER DELETE ON `setor`
FOR EACH ROW 
BEGIN
    INSERT INTO `logs` (`tabela`, `acao`, `usuario`, `dados_novos`)
    VALUES ('setor', 'DELETE', @current_user, CONCAT('ID: ', OLD.id, ', Nome: ', OLD.nome, ', Responsável: ', OLD.responsavel, ', Status do Registro: ', OLD.statusRegistro));
END $$

DELIMITER ;
