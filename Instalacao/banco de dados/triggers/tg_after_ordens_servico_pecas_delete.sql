DELIMITER $$

CREATE TRIGGER after_ordens_servico_pecas_delete
AFTER DELETE ON ordens_servico_pecas
FOR EACH ROW
BEGIN
    DECLARE quantidade_anterior INT;
    
    -- Recupera a quantidade anterior do produto
    SELECT quantidade INTO quantidade_anterior FROM produto WHERE id = OLD.id_peca;
    
    -- Atualiza a quantidade do produto na tabela produto
    IF OLD.quantidade > 0 THEN
        UPDATE produto 
        SET quantidade = quantidade + OLD.quantidade 
        WHERE id = OLD.id_peca;
    END IF;
END$$

DELIMITER ;
