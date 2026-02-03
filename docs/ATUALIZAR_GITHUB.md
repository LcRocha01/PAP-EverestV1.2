# Atualizar o projeto no GitHub

## O que significa "Already up to date"
Quando executas `git pull` e aparece **"Already up to date"**, significa que o **teu branch local** já está sincronizado com o **branch remoto** correspondente (por exemplo, `main`).

## Possíveis causas de não veres alterações
1. **As alterações estão noutro branch**
   - Se o trabalho foi feito num branch diferente (ex.: `work`), fazer `git pull` no `main` não vai buscar essas mudanças.

2. **As alterações ainda não foram enviadas para o GitHub**
   - Se o commit ainda está só localmente, o GitHub não vai mostrar nada novo.

## Como confirmar o branch atual
No terminal, usa:
```bash
git branch --show-current
```
Se estiveres em `main`, mas as alterações estiverem noutro branch, muda para o branch correto:
```bash
git checkout work
git pull
```

## Quando precisas de fazer push
Se tu (ou o agente) fizeste commits localmente, precisas de enviar para o GitHub:
```bash
git push
```

> Nota: para `git push` funcionar no GitHub, tens de estar autenticado (token ou SSH).
