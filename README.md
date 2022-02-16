# Laravel To-do App

## Requisitos

[Requisitos Laravel](https://laravel.com/docs/7.x#server-requirements)

## Instalação

```
git clone git@github.com:BrunoEduardo1/laravel-vue-tasks.git

cd laravel-vue-tasks

# após criar o banco definir credenciais no arquivo(necessário para artisan migrate) e credenciais de email
cp .env.example .env

composer install

php artisan key:generate

php artisan jwt:secret

php artisan migrate

php artisan config:cache

php artisan serve

```

### Front-end

```
# modo de desenvolvimento
yarn

yarn watch

# ou
npm install

npm run watch

```

### Imagens

![Página de login](https://i.imgur.com/9mJD9YN.png)

![Dashboard](https://i.imgur.com/8nPpgS8.png)