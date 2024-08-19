DELIMITER $$

CREATE TRIGGER after_ordens_servico_pecas_insert_update
AFTER INSERT ON ordens_servico_pecas
FOR EACH ROW
BEGIN
    DECLARE quantidade_anterior INT;
    
    -- Recupera a quantidade anterior do produto
    SELECT quantidade INTO quantidade_anterior FROM produto WHERE id = NEW.id_peca;
    
    -- Atualiza a quantidade do produto na tabela produto
    IF NEW.quantidade > 0 THEN
        UPDATE produto 
        SET quantidade = quantidade - NEW.quantidade 
        WHERE id = NEW.id_peca;
    END IF;
END$$

DELIMITER ;
