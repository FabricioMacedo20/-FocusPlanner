# FocusPlanner
FocusPlanner é um sistema Laravel para organizar tarefas diárias, hábitos, metas, cursos, leituras e relatórios de produtividade.

Instruções de uso;

1. Requisitos
- PHP 8.2 ou superior
- Composer
- Node.js e npm
- Banco de dados configurado em `.env`

2. Instalação
1. Copie o arquivo de ambiente:
`cp .env.example .env`
2. Instale dependências PHP:
`composer install`
3. Gere a chave da aplicação:
`php artisan key:generate`
4. Configure as credenciais do banco de dados em `.env`.
5. Execute as migrações com dados de teste:
`php artisan migrate --seed`
6. Instale dependências de front-end:
`npm install`
7. Compile os assets:
`npm run build`

3. Executar o aplicativo
- Para rodar em modo de desenvolvimento:
`php artisan serve`
- Acesse o app pelo navegador em: `http://127.0.0.1:8000`

4. Autenticação e Testes
O aplicativo usa autenticação Laravel Breeze. Para testar:

- Acesse `/login` para entrar com uma conta
- O comando `php artisan migrate --seed` cria automaticamente usuários de teste

## Usuários para Demonstração e Testes

Após executar `php artisan migrate --seed`, o seguinte usuário estará disponíveL:

| Nome | E-mail | Senha |
|------|--------|-------|
| Admin | admin@focusplanner.com | admin123 |

## Status do Projeto
**FocusPlanner - MVP Funcional**

- Projeto desenvolvido para aprendizado e prática com Laravel;
- Estrutura preparada para evolução contínua;
- Arquitetura organizada e pronta para expansão;
- Melhorias futuras incluem testes automatizados, otimizações adicionais e recursos de integração de apps.

5. Idioma
Todas as mensagens de autenticação, validações e interface estão em português, incluindo:
- Mensagens de erro de login
- Validações de formulários
- Labels e botões
- Mensagens de sucesso e confirmação

6. Páginas principais
- `/dashboard`
  - Visão geral das tarefas e progresso diário.
- `/planner`
  - Planejamento de atividades e tarefas detalhadas.
- `/relatorio`
  - Relatórios de produtividade semanal, mensal e anual.

7. Funcionalidades
- Tarefas diárias:
Criar, completar e excluir tarefas.

- Hábitos:
Registrar hábitos diários e marcar como concluídos.

- Metas:
Criar metas com progresso numérico e alternar status.

- Cursos:
Controlar progresso de cursos e visualizar detalhes.

- Leituras:
Registrar leituras e marcar páginas ou itens como concluídos.

8. Rotas importantes
- `GET /dashboard`
- `GET /planner`
- `POST /task/store`
- `GET /task/complete/{id}`
- `GET /task/delete/{id}`
- `GET /relatorio`
- `POST /relatorio/days`
- Rotas de recursos protegidas para `habits`, `goals`, `courses` e `readings`.

9. Comandos úteis
- Rodar testes:
`composer test`
- Limpar cache de configuração:
`php artisan config:clear`
- Executar build Vite:
`npm run build`
 
