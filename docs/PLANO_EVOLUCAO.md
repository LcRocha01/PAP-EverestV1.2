# Plano de Evolução do Projeto (PAP Logística)

## Objetivos principais
- Garantir um sistema **útil, funcional e visualmente consistente** para empresas de logística.
- Manter linguagem **PT-PT** correta em toda a interface.
- Priorizar **segurança**, **manutenção** e **reutilização de código**.

## Fase 1 — Base sólida (agora)
1. **Arquitetura consistente**
   - Reutilizar layout (sidebar, topbar e cartões) em todas as páginas do painel.
   - Centralizar estilos no mesmo ficheiro CSS.
2. **CRUDs essenciais**
   - Clientes (entidades), produtos, pedidos.
3. **Segurança mínima obrigatória**
   - `prepare()` em todas as queries com input.
   - Validação de dados no backend.

## Fase 2 — Funcionalidades-chave
1. **Margem padrão**
   - Aplicar margem no momento do pedido.
2. **Relatórios**
   - Exportação em PDF/CSV.
   - Métricas de desempenho (por cliente e por período).
3. **Notificações**
   - Mensagens básicas para pedidos e aprovações.

## Fase 3 — UX/UI e consistência
1. **Layout responsivo**
   - Garantir boa leitura em desktop e tablet.
2. **Linguagem clara**
   - Frases curtas e diretas, sem jargão técnico.
3. **Coerência visual**
   - Botões, cores e tipografia unificados.

## Fase 4 — Polimento final
1. **Documentação**
   - Explicar estrutura do projeto e decisões técnicas.
2. **Apresentação**
   - Preparar um fluxo simples para a demonstração final.
