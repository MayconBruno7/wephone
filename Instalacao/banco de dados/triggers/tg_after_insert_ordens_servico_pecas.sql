DELIMITER $$

CREATE TRIGGER tg_after_insert_ordens_servico_pecas
AFTER INSERT ON ordens_servico_pecas
FOR EACH ROW
BEGIN
    -- Atualiza a quantidade de peças em estoque
    UPDATE pecas
    SET quantidade = quantidade - NEW.quantidade
    WHERE id = NEW.id_peca;

    -- Verifica se a quantidade de peças em estoque é negativa
    IF (SELECT quantidade FROM pecas WHERE id = NEW.id_peca) < 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Erro: Estoque insuficiente para a peça.';
    END IF;
END$$

DELIMITER ;