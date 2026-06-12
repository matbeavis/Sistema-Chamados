# Sistema de Controle de Chamados Internos

Plataforma desenvolvida para centralizar, organizar e distribuir os pedidos de suporte da área administrativa de forma equilibrada.

## Tecnologias Utilizadas
* Laravel (Backend)
* Vue.js com Composition API (Frontend)
* Inertia.js (Comunicação)
* Tailwind CSS (Estilização)
* SQLite (Banco de Dados)

## Justificativas Arquiteturais
A integração do Vue.js pelo pacote Inertia.js elimina a necessidade de construir e manter uma API REST separada. O servidor entrega os dados diretamente para as páginas visuais e reduz a complexidade do código. A escolha da interface no formato Kanban atende à necessidade de acompanhar os chamados com total clareza. As colunas visuais traduzem o status de cada pedido e aplicam os conceitos de metodologias ágeis de forma orgânica no dia a dia do setor. A regra de distribuição automática foi processada diretamente na inserção do banco de dados para garantir que a equipe mantenha um fluxo de trabalho sempre equilibrado.

## Instruções de Instalação e Execução

O ambiente local exige as instalações prévias do PHP, do Composer e do Node.js.

1. Realize o clone do repositório para o seu ambiente local.
2. Abra o terminal na pasta raiz do projeto.
3. Execute `composer install` para baixar as dependências do servidor.
4. Execute `npm install` para baixar as dependências da interface.
5. Crie uma cópia do arquivo `.env.example` com o nome `.env`.
6. Execute `php artisan key:generate` para estabelecer a chave de segurança.
7. Altere a configuração de banco de dados no arquivo `.env` para `DB_CONNECTION=sqlite` e remova as outras linhas de DB.
8. Crie um arquivo em branco chamado `database.sqlite` dentro da pasta `database`.
9. Execute `php artisan migrate:fresh --seed` para construir as tabelas e popular os três usuários de suporte iniciais.
10. Inicie o servidor do backend executando `php artisan serve`.
11. Abra um segundo terminal e execute `npm run dev` para compilar a interface.
12. Acesse `http://localhost:8000` no navegador.
13. Utilize as credenciais `suporte1@empresa.com` com a senha `senha123` para acessar o sistema.