DELIMITER $$

CREATE TRIGGER `tg_Insert_BloqueiaMovimentacaoFinalSemana` 
BEFORE INSERT ON `movimentacao` 
FOR EACH ROW 
BEGIN
   DECLARE dia_semana INT;
   SET dia_semana = DAYOFWEEK(CURDATE());

   IF dia_semana = 1 OR dia_semana = 7 THEN
      SIGNAL SQLSTATE '45000' 
      SET MESSAGE_TEXT = 'Operações não são permitidas no final de semana';
   END IF;
END$$

DELIMITER ;
