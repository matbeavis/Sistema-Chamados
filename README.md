# **Sistema de Controle de Chamados Internos**

Sistema criado como parte do teste da empresa **Codificar**.

---

## **Sobre o Projeto**

Este projeto é um sistema para organização de demandas (chamados) desenvolvido em Laravel. Ele inclui funcionalidades como:

- Autenticação de usuários e proteção de rotas integradas
- Quadro Kanban para organização visual das demandas administrativas
- Criação de chamados com classificação de prioridade e identificação de setor
- Distribuição automática de tarefas para o atendente com menor volume de trabalho acumulado
- Atualização de status e edição de informações dos pedidos em andamento
- Exclusão de registros protegida por tela de confirmação flutuante
- Design padronizado em tema escuro para redução de cansaço visual
- Navegação estruturada no cabeçalho para acesso direto ao quadro geral

## **Requisitos**

Certifique-se de ter os seguintes itens instalados no seu sistema:

- **PHP** >= 8.0 ([Download PHP](https://www.php.net/))
- **Composer** ([Download Composer](https://getcomposer.org/))
- **Node.js** ([Download Node.js](https://nodejs.org/pt))
- **MySQL** (ou outro banco de dados compatível)

---

## **Instalação**

Siga os passos abaixo para configurar e executar o projeto localmente.

---

### **Passo 1: Clonar o Repositório**

No terminal, execute:

```bash
git clone https://github.com/matbeavis/sistema-chamados.git
cd sistema-chamados
```

---

### **Passo 2: Instalar Dependências do PHP**

No diretório do projeto, instale as dependências do Laravel:

```bash
composer install
```

> ⚠️ **Nota**: Se ocorrer um erro relacionado ao `zip`, ative a extensão `zip` no arquivo de configuração do PHP (`php.ini`).

---

### **Passo 3: Instalar Dependências do Node.js**

Instale as dependências do frontend:

```bash
npm install
```

---

### **Passo 4: Configurar o Arquivo `.env`**

Crie uma cópia do arquivo `.env.example` e ajuste as variáveis conforme necessário (banco de dados, chave da aplicação, etc.):

```bash
copy .env.example .env
```

---

### **Passo 5: Gerar a Chave da Aplicação**

Gere a chave única da aplicação:

```bash
php artisan key:generate
```

---

### **Passo 6: Executar Migrações**

Crie as tabelas no banco de dados e preencha dados iniciais:

```bash
php artisan migrate --seed
```

---

### **Passo 7: Compilar os Assets Frontend**

Compile os arquivos CSS/JavaScript para desenvolvimento:

```bash
npm run dev
```

Para produção, use:

```bash
npm run build
```

---

### **Passo 8: Iniciar o Servidor**

No terminal, execute:

```bash
php artisan serve
```

A aplicação estará disponível em [http://127.0.0.1:8000](http://127.0.0.1:8000).

## **Suporte**

Se você encontrar problemas entre em contato pelo [GitHub Issues](https://github.com/matbeavis/sistema-chamados/issues).