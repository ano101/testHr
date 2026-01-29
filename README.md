# Test HR Project

Приложение на Laravel 12 с Inertia.js (Vue 3) и Tailwind CSS.

## Требования

- PHP 8.5+
- Composer
- Node.js & NPM
- Docker (для Laravel Sail)

## Установка и запуск проекта

### 1. Клонировать репозиторий

```bash
git clone https://github.com/ano101/testHr.git
cd testHr
```

### 2. Запустить Docker контейнеры

```bash
docker compose up -d
```

### 3. Войти в контейнер приложения

```bash
docker compose exec laravel.test bash
```

Все дальнейшие команды выполняются внутри контейнера.

### 4. Установить зависимости Composer

```bash
composer install
```

### 5. Настроить файл окружения

Скопируйте `.env.example` в `.env` и настройте параметры:

```bash
cp .env.example .env
```

Сгенерируйте ключ приложения:

```bash
php artisan key:generate
```

### 6. Установить JavaScript зависимости

```bash
npm install
```

### 7. Выполнить миграции

```bash
php artisan migrate
```

### 8. Настроить Meilisearch и загрузить данные

```bash
php artisan meilisearch:setup
```

Или запустите обычные сидеры:

```bash
php artisan db:seed
```

### 9. Запустить сборку фронтенда

Для разработки:

```bash
npm run dev
```

Или для продакшена:

```bash
npm run build
```

### 10. Открыть приложение

Откройте браузер по адресу: `http://localhost`

## Полезные команды

### Остановить контейнеры

```bash
docker compose stop
```

### Запустить тесты

```bash
php artisan test
```

### Форматирование кода (Pint)

```bash
./vendor/bin/pint
```

## Стек технологий

- Laravel 12
- Inertia.js v2
- Vue 3
- Tailwind CSS v4
- Laravel Sanctum
- Meilisearch
- PHPUnit
