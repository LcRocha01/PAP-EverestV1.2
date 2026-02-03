-- Ajustes recomendados para integridade referencial (MySQL)

ALTER TABLE pedidos
    DROP FOREIGN KEY pedidos_ibfk_1,
    ADD CONSTRAINT pedidos_ibfk_1
        FOREIGN KEY (entidade_id)
        REFERENCES entidades (id)
        ON DELETE CASCADE;

ALTER TABLE pedido_itens
    DROP FOREIGN KEY pedido_itens_ibfk_1,
    ADD CONSTRAINT pedido_itens_ibfk_1
        FOREIGN KEY (pedido_id)
        REFERENCES pedidos (id)
        ON DELETE CASCADE;

ALTER TABLE pedido_itens
    DROP FOREIGN KEY pedido_itens_ibfk_2,
    ADD CONSTRAINT pedido_itens_ibfk_2
        FOREIGN KEY (produto_id)
        REFERENCES produtos (id)
        ON DELETE RESTRICT;
