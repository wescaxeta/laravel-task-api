# laravel-task-api

![Tests](https://github.com/wescaxeta/laravel-task-api/actions/workflows/tests.yml/badge.svg)

API REST de gerenciamento de tarefas com autenticação via token (Laravel Sanctum).

## Funcionalidades

- Registro e login de usuários com token Sanctum
- CRUD completo de tarefas por usuário autenticado
- Autorização: cada usuário acessa apenas suas próprias tarefas
- Validação de requisições com Form Requests
- Resposta padronizada via API Resources
- Testes de feature com banco SQLite em memória

## Endpoints

| Método | Rota              | Autenticação | Descrição              |
|--------|-------------------|:------------:|------------------------|
| POST   | /api/register     | Não          | Registrar usuário      |
| POST   | /api/login        | Não          | Login (retorna token)  |
| POST   | /api/logout       | Sim          | Logout                 |
| GET    | /api/tasks        | Sim          | Listar tarefas         |
| POST   | /api/tasks        | Sim          | Criar tarefa           |
| GET    | /api/tasks/{id}   | Sim          | Ver tarefa             |
| PUT    | /api/tasks/{id}   | Sim          | Atualizar tarefa       |
| DELETE | /api/tasks/{id}   | Sim          | Remover tarefa         |

## Stack

- PHP 8.4
- Laravel 13
- Laravel Sanctum (autenticação por token)
- SQLite (testes) / PostgreSQL (produção)
- PHPUnit / Laravel HTTP Tests

## Como rodar

```bash
git clone https://github.com/wescaxeta/laravel-task-api
cd laravel-task-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## Testes

```bash
php artisan test
```

## Com Docker

```bash
docker-compose up
```

## Exemplo de uso

**Registrar:**
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Weslley","email":"wes@example.com","password":"password123","password_confirmation":"password123"}'
```

**Criar tarefa:**
```bash
curl -X POST http://localhost:8000/api/tasks \
  -H "Authorization: Bearer SEU_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"title":"Estudar Laravel","status":"pending","due_date":"2026-10-01"}'
```
