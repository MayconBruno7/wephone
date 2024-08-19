DELIMITER $$

CREATE TRIGGER after_ordens_servico_pecas_update
AFTER UPDATE ON ordens_servico_pecas
FOR EACH ROW
BEGIN
    DECLARE quantidade_anterior INT;

    -- Recupera a quantidade atual do produto em estoque
    SELECT quantidade INTO quantidade_anterior FROM produto WHERE id = NEW.id_peca;

    -- Se a nova quantidade for maior que a antiga, subtrai a diferença do estoque
    IF NEW.quantidade > OLD.quantidade THEN
        UPDATE produto 
        SET quantidade = quantidade - (NEW.quantidade - OLD.quantidade)
        WHERE id = NEW.id_peca;
    -- Se a nova quantidade for menor que a antiga, adiciona a diferença ao estoque
    ELSEIF NEW.quantidade < OLD.quantidade THEN
        UPDATE produto 
        SET quantidade = quantidade + (OLD.quantidade - NEW.quantidade)
        WHERE id = NEW.id_peca;
    END IF;
END$$

DELIMITER ;
