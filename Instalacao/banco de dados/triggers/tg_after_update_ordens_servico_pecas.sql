DELIMITER $$

CREATE TRIGGER tg_after_update_ordens_servico_pecas
AFTER UPDATE ON ordens_servico_pecas
FOR EACH ROW
BEGIN
    -- Reverte a quantidade anterior de peças em estoque
    UPDATE pecas
    SET quantidade = quantidade + OLD.quantidade
    WHERE id = OLD.id_peca;

    -- Subtrai a nova quantidade de peças
    UPDATE pecas
    SET quantidade = quantidade - NEW.quantidade
    WHERE id = NEW.id_peca;

    -- Verifica se a quantidade de peças em estoque é negativa
    IF (SELECT quantidade FROM pecas WHERE id = NEW.id_peca) < 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Erro: Estoque insuficiente para a peça.';
    END IF;
END$$

DELIMITER ;
