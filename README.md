
# 🚀 Sistema de Gerenciamento de Tarefas - To-Do List


Este é um sistema web para gerenciamento de tarefas (To-Do List), com autenticação de usuários, controle de permissões e interface responsiva utilizando **Laravel 10**, **Bootstrap 5** e **jQuery**.

## 🛠️ Tecnologias Utilizadas
- [Laravel 10](https://laravel.com)
- [Laravel Breeze (Autenticação)](https://laravel.com/docs/10.x/starter-kits#breeze)
- [AdminLTE 3](https://adminlte.io/)
- [Bootstrap 5](https://getbootstrap.com/)
- [jQuery 3](https://jquery.com/)
- [Datatables](https://datatables.net/)

## 🎯 Funcionalidades
- Autenticação de usuários (Login, Registro, Recuperação de Senha)
- Cadastro, listagem, edição e exclusão de usuários (com status ativo/inativo)
- Cadastro, listagem, filtro e gerenciamento de tarefas
- Atribuição de usuários a tarefas (muitos para muitos)
- Marcar tarefa como concluída
- Filtrar tarefas por título, status e pessoas
- Interface responsiva com AdminLTE
- Proteção por permissões com Policies
- CRUD com validação via Form Requests
- Mensagens de feedback para ações

*(Clique na imagem para ampliar o diagrama ER)*
![DER-esfera-informatica](https://github.com/user-attachments/assets/31d5dddc-946b-4cf8-b96d-1280e647524c)
  
## 🚀 Como Rodar o Projeto

### 1) Clone o repositório
```bash
git clone https://github.com/abreujean/esfera-informatica
cd seu-repositorio
````
### 2) Instale as dependências PHP e NPM

```bash
composer install
npm install && npm run dev
```

### 3) Configure o arquivo `.env`

```bash
cp .env.example .env
php artisan key:generate
```

Edite `.env` e configure seu banco de dados e e-mail (Mailtrap recomendado para testes).

### 4) Rode as migrations e seeders

```bash
php artisan migrate --seed
```

### 5) Rode o servidor local

```bash
php artisan serve
```

Acesse: `http://localhost:8000`



## 🗂️ Estrutura Principal

```
app/
  Models/
    Task.php
    User.php
    Profile.php
  Http/
    Controllers/
      RouteController.php
      TaskController.php
      UserController.php
    Middleware/
resources/
  views/
    layouts/
    components/
    profile/
    dashboard.blade.php
routes/
  web.php
```

## 📦 Pacotes Extras Utilizados

* `adminlte` (template AdminLTE)
* `datatables` (datatables)

## 💡 Usuário Admin Padrão (Seeder)

* **E-mail:** admin@esfera.com
* **Senha:** SenhaSegura123@

## 📝 Licença

Este projeto está sob a licença MIT.
