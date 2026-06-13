# **Sistema de Controle de Chamados Internos**

Sistema criado como parte do teste da empresa **Codificar**.

---

## **Sobre o Projeto**

Este projeto é um sistema para organização de demandas administrativas desenvolvido em Laravel. As funcionalidades consolidadas incluem as seguintes entregas técnicas.

* Autenticação de usuários e proteção de rotas integradas
* Painel pessoal de Dashboard com estatísticas individuais e atalhos rápidos
* Quadro Kanban para organização visual com limites de WIP (Work In Progress) e contadores visuais
* Tela dedicada de métricas ágeis contendo Lead Time, Cycle Time, Throughput e Distribuição de Fluxo
* Criação de chamados com classificação de prioridade e identificação de setor
* Distribuição automática de tarefas para o atendente com menor volume de trabalho acumulado
* Atualização de status e edição de informações dos pedidos em andamento
* Exibição de datas de abertura e última modificação diretamente nos cartões
* Escalonamento automático de prioridade por tempo de atraso com sinalização visual de alerta
* Exclusão de registros protegida por tela de confirmação flutuante
* Design padronizado em tema escuro para redução de cansaço visual
* Navegação estruturada no cabeçalho para acesso direto ao quadro e aos indicadores

## **Requisitos**

Garanta a instalação dos seguintes componentes no seu ambiente local.

* **PHP** >= 8.2 ([Download PHP](https://www.php.net/))
* **Composer** ([Download Composer](https://getcomposer.org/))
* **Node.js** ([Download Node.js](https://nodejs.org/pt))
* **SQLite** (padrão da aplicação)

---

## **Instalação**

Siga os passos abaixo para configurar e executar o projeto localmente.

---

### **Passo 1: Clonar o Repositório**

No terminal, execute:

```bash
git clone [https://github.com/matbeavis/sistema-chamados.git](https://github.com/matbeavis/sistema-chamados.git)
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
### **Passo 9: Iniciar o Agendador de Tarefas (Cron)**

O sistema de escalonamento automático de prioridades por atraso exige a execução contínua do verificador de rotinas. Abra um terceiro terminal na pasta do projeto e mantenha o comando abaixo operando de forma ininterrupta.

```bash
php artisan schedule:work
```

A aplicação estará disponível em [http://127.0.0.1:8000](http://127.0.0.1:8000).

## **Suporte**

Se você encontrar problemas entre em contato pelo [GitHub Issues](https://github.com/matbeavis/sistema-chamados/issues).