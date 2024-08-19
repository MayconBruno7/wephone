DELIMITER $$

CREATE TRIGGER `adiciona_historico_atualiza_quantidade_produto_insert`
AFTER INSERT ON `movimentacao_item`
FOR EACH ROW
BEGIN
    DECLARE nova_quantidade INT;
    DECLARE tipo_movimentacao INT;
    DECLARE quantidade_anterior INT;

    -- Obter a quantidade atual do produto
    SET quantidade_anterior = (SELECT `quantidade` FROM `produto` WHERE `id` = NEW.`id_produtos`);
    
    -- Obter o tipo de movimentação
    SET tipo_movimentacao = (SELECT `tipo` FROM `movimentacao` WHERE `id` = NEW.`id_movimentacoes`);

    -- Calcula a nova quantidade do produto com base na movimentação
    IF tipo_movimentacao = 1 THEN
        -- Entrada
        SET nova_quantidade = quantidade_anterior + NEW.`quantidade`;
    ELSE 
        -- Saída
        SET nova_quantidade = quantidade_anterior - NEW.`quantidade`;
    END IF;

    -- Insere o novo registro na tabela historico_produto
    INSERT INTO `historico_produto` (
        `id_produtos`,
        `fornecedor_id`,
        `nome_produtos`,
        `descricao_anterior`,
        `quantidade_anterior`,
        `status_anterior`,
        `statusItem_anterior`,
        `dataMod`
    ) VALUES (
        NEW.`id_produtos`,
        (SELECT `fornecedor` FROM `produto` WHERE `id` = NEW.`id_produtos`),
        (SELECT `nome` FROM `produto` WHERE `id` = NEW.`id_produtos`),
        (SELECT `descricao` FROM `produto` WHERE `id` = NEW.`id_produtos`),
        quantidade_anterior,
        (SELECT `statusRegistro` FROM `produto` WHERE `id` = NEW.`id_produtos`),
        (SELECT `condicao` FROM `produto` WHERE `id` = NEW.`id_produtos`),
        NOW()
    );

    -- Atualiza a quantidade do produto na tabela produto
    UPDATE `produto`
    SET `quantidade` = nova_quantidade
    WHERE `id` = NEW.`id_produtos`;
END$$

DELIMITER ;
