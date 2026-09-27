# UNIFAN Avaliações API

TDE da disciplina de Programação Backend 

## Sobre a linguagem

O enunciado original pedia Java ou Python. Fizemos em **PHP com Laravel**, com autorização do professor, mantendo os mesmos princípios da disciplina, orientação a objetos, padrão MVC, as práticas de API REST e a logica pra microsserviços

## O que é o projeto

Uma API para gestão de avaliações acadêmicas com cursos, disciplinas, questões, avaliações e templates de PDF, com dois tipos de usuário, o admin e o autor

## Stack

PHP 8.4, Laravel 13, JWT (`tymon/jwt-auth`) para autenticação, SQLite como banco.

## Como rodar

\`\`\`bash
git clone <url-deste-repositorio>
cd tde-backend

composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret

touch database/database.sqlite
php artisan migrate --seed

php artisan serve
\`\`\`

A API sobe em \`http://127.0.0.1:8000\`.

**Usuários de exemplo (do seeder):**

| Email | Senha | Tipo |
|---|---|---|
| admin@unifan.com | 12345678 | admin |
| autor@unifan.com | 12345678 | autor |

## Endpoints do TDE1

| Método | Rota | O quê | Quem pode |
|---|---|---|---|
| POST | \`/api/login\` | Login, retorna token | Qualquer um |
| GET | \`/api/users\` | Lista usuários | Admin |
| POST | \`/api/users\` | Cria usuário | Admin |
| PUT | \`/api/users/{id}\` | Atualiza usuário | O próprio usuário |
| DELETE | \`/api/users/{id}\` | Remove usuário | Admin |

## Onde está cada coisa

- **Script SQL:** \`database_export.sql\` (estrutura completa + dados de exemplo)
- **Login/JWT:** \`app/Modules/Auth/Controllers/AuthController.php\`
- **CRUD de usuário:** \`app/Modules/User/\` (Controller, Service, Requests)
- **Migrations:** \`database/migrations/\`

O banco já tem completo de todas as entidades do enunciado, usuários, cursos, disciplinas, questões, avaliações, templates.