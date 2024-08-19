DELIMITER $$

CREATE TRIGGER `tg_Update_BloqueiaMovimentacaoFinalSemana` 
BEFORE UPDATE ON `movimentacao` 
FOR EACH ROW 
BEGIN
   DECLARE dia_semana INT;
   SET dia_semana = DAYOFWEEK(CURDATE());

   IF dia_semana IN (1, 7) THEN
      SIGNAL SQLSTATE '45000' 
      SET MESSAGE_TEXT = 'Operações não são permitidas no final de semana';
   END IF;
END$$

DELIMITER ;
