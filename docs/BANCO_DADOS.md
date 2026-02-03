# Orientações para a Base de Dados

## Regras essenciais para nota máxima
1. **Integridade referencial**
   - Todas as chaves estrangeiras devem ter o comportamento correto (`ON DELETE CASCADE` quando fizer sentido).
2. **Proteção de dados**
   - Evitar SQL injection com `prepare()` no backend.
3. **Normalização**
   - Manter entidades bem separadas (ex.: pedidos, itens, produtos, utilizadores).

## Ajustes recomendados
### Cascades (exemplo)
Use `ON DELETE CASCADE` para evitar registos órfãos:
- `pedidos.entidade_id` → `entidades.id`
- `pedido_itens.pedido_id` → `pedidos.id`
- `pedido_itens.produto_id` → `produtos.id` (quando for coerente apagar itens com o produto)

### Índices úteis
- `entidades.id_logistica`
- `pedidos.entidade_id`
- `pedido_itens.pedido_id`

## Script de referência
Existe um script de ajustes em `PAP/database/ajustes.sql` com as alterações recomendadas.
