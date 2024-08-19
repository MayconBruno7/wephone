DELIMITER $$

CREATE TRIGGER `tg_update_log_info_setor` 
AFTER UPDATE ON `setor`
FOR EACH ROW 
BEGIN
    INSERT INTO `logs` (`tabela`, `acao`, `usuario`, `dados_novos`)
    VALUES ('setor', 'UPDATE', @current_user, CONCAT('ID: ', NEW.id, ', Nome: ', NEW.nome, ', Responsável: ', NEW.responsavel, ', Status do Registro: ', NEW.statusRegistro));
END $$

DELIMITER ;
