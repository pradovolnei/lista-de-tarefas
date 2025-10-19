# 📝 To-Do List — Laravel API + Blade/Alpine.js Frontend

Aplicação de **Lista de Tarefas (To-Do List)** desenvolvida com **Laravel** como backend RESTful API e **Blade/Alpine.js** para o frontend.  
O projeto foi estruturado seguindo boas práticas de organização e padronização do Laravel, com foco em simplicidade e eficiência.

---

## 🚀 Tecnologias Utilizadas

- **PHP** >= 8.1  
- **Laravel** (API e Blade Views)  
- **Laravel Breeze** (autenticação e estrutura de layout)  
- **MySQL / MariaDB** (banco de dados)  
- **Node.js** e **NPM** (build de assets com Vite)  
- **Tailwind CSS**  
- **Alpine.js**

---

## ⚙️ Instalação e Configuração

### 1. Clonar o Repositório

```bash
git clone https://github.com/seu-usuario/nome-do-repositorio.git
cd nome-do-repositorio
```

### 2. Instalar Dependências do PHP

```bash
composer install
```

### 3. Configurar o Ambiente

Crie o arquivo `.env` a partir do modelo e gere a chave da aplicação:

```bash
cp .env.example .env
php artisan key:generate
```

Em seguida, configure as credenciais do banco de dados no arquivo `.env`:
```
DB_DATABASE=laravel_todo
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Executar as Migrações

```bash
php artisan migrate
```

Isso criará as tabelas necessárias, incluindo as de **tarefas** (`tasks`) e **autenticação** (Laravel Breeze).

---

## 💻 Frontend — Laravel Breeze + Vite

### 1. Instalar Dependências do Node.js

```bash
npm install
```

### 2. Compilar os Assets

```bash
npm run dev
# ou use:
npm run watch
```

O Vite cuidará da compilação dos arquivos CSS e JS, incluindo o **Tailwind** e **Alpine.js**.

---

## 🌐 Rotas da Aplicação

### 🔹 API (routes/api.php)

| Método | Endpoint                  | Descrição |
|--------:|---------------------------|------------|
| `GET`   | `/api/tasks`              | Lista todas as tarefas |
| `POST`  | `/api/tasks`              | Cria uma nova tarefa |
| `PUT`   | `/api/tasks/{task}`       | Atualiza o título e descrição |
| `PATCH` | `/api/tasks/{task}/toggle`| Alterna o status (pendente/concluída) |
| `DELETE`| `/api/tasks/{task}`       | Remove uma tarefa |

### 🔹 Frontend (routes/web.php)

| Método | Endpoint | Descrição |
|--------:|-----------|------------|
| `GET`   | `/`       | Exibe a interface principal da To-Do List (`resources/views/tasks/index.blade.php`) |

---

## ▶️ Executando o Projeto

Inicie o servidor de desenvolvimento do Laravel:

```bash
php artisan serve
```

Acesse no navegador:  
👉 **http://127.0.0.1:8000**

---

## 🧩 Observações Importantes

Durante a instalação inicial, o Laravel Breeze pode causar o erro:

```
Attempt to read property "name" on null
```

Isso ocorre ao acessar a rota principal sem estar logado.  
Este repositório já inclui as correções necessárias no arquivo:

```
resources/views/layouts/navigation.blade.php
```

Essas correções garantem que:
- A página inicial (`/`) seja acessível mesmo sem login.  
- O nome do usuário só seja exibido se houver autenticação (`@auth`).  
- Após login, se for redirecionado para `/dashboard`, basta retornar à rota raiz (`/`).

---

## 🧠 Conceitos Aplicados

- Arquitetura **MVC** do Laravel  
- Boas práticas **RESTful API**  
- Reatividade com **Alpine.js**  
- Layout dinâmico com **Blade Components**  
- Estilização com **Tailwind CSS**

---

## 📄 Licença

Este projeto é distribuído sob a licença **MIT**.  
Sinta-se livre para usar, modificar e distribuir conforme necessário.

---

**Desenvolvido com ❤️ e Laravel.**
