# Инструкция по установке и настройке проекта с интеграцией Google Sheets

Этот проект позволяет интегрировать Google Sheets в приложение Laravel для чтения и хранения данных в таблицах Google Sheets.
Следуйте этим шагам, чтобы настроить проект и подключение к Google Sheets API.

## Требования

- PHP >= 8.0
- Composer
- Laravel >= 9.x
- MySQL или другая поддерживаемая база данных
- Доступ к Google Cloud Console и Google Sheets
- Учетная запись Google для настройки API

## 1. Клонирование репозитория

Склонируйте репозиторий и перейдите в папку проекта:

```bash
git clone https://github.com/Afzalshoh02/google_sheets_api.git
cd google_sheets_api
```

## 2. Установка зависимостей

Установите зависимости PHP через Composer:

```bash
composer install
```

## 3. Настройка переменных окружения

Скопируйте файл `.env.example` в `.env` и настройте параметры подключения к базе данных:

```bash
cp .env.example .env
```

Откройте файл `.env` и укажите настройки базы данных, например:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

## 4. Выполнение миграций

Создайте таблицы в базе данных, выполнив миграции:

```bash
php artisan migrate
```

## 5. Настройка Google Sheets API

Для интеграции с Google Sheets необходимо настроить Google Cloud Console и получить JSON-ключ Service Account.

### 5.1. Создание проекта в Google Cloud Console
1. Перейдите в [Google Cloud Console](https://console.cloud.google.com/).
2. Нажмите **Select a project** (в верхнем левом углу) → **New Project**.
3. Укажите имя проекта (например, `Google Sheets Integration`) и нажмите **Create**.
4. Выберите созданный проект в верхнем меню.

### 5.2. Включение Google Sheets API
1. В Google Cloud Console перейдите в **APIs & Services** → **Library**.
2. Найдите **Google Sheets API** через поиск и нажмите **Enable**.

### 5.3. Создание Service Account и JSON-ключа
1. Перейдите в **APIs & Services** → **Credentials**.
2. Нажмите **Create Credentials** → **Service Account**.
3. Заполните поля:
   - **Service account name**: Например, `sheets-api-service`.
   - **Service account ID**: Оставьте автоматически сгенерированный или задайте свой.
4. Нажмите **Create and Continue**, пропустите необязательные шаги (роли и доступ) и нажмите **Done**.
5. Найдите созданный Service Account в списке, нажмите на него → вкладка **Keys** → **Add Key** → **Create new key** → выберите **JSON**.
6. Скачайте JSON-файл (например, `your-project-id-1234567890.json`) и сохраните его в безопасном месте.

### 5.4. Размещение JSON-ключа
1. Переместите скачанный JSON-файл в папку `storage/app/` вашего проекта и переименуйте его в `google-credentials.json`:
   ```bash
   mv /path/to/your-project-id-1234567890.json storage/app/google-credentials.json
   ```
2. Убедитесь, что файл имеет правильные права доступа:
   ```bash
   chmod 640 storage/app/google-credentials.json
   chown www-data:www-data storage/app/google-credentials.json
   ```
   Замените `www-data` на пользователя вашего веб-сервера, если он отличается.

### 5.5. Предоставление доступа к таблице
1. Откройте JSON-файл `storage/app/google-credentials.json` и найдите поле `client_email` (например, `sheets-api-service@your-project-id.iam.gserviceaccount.com`).
2. Откройте таблицу Google Sheets, с которой вы хотите работать.
3. Нажмите **Share** (Поделиться) в правом верхнем углу.
4. Введите `client_email` из JSON-файла и установите права:
   - **Viewer** — для чтения данных.
   - **Editor** — для чтения и записи.
5. Нажмите **Share**. Убедитесь, что таблица не ограничена корпоративными политиками (например, доступом только для пользователей домена).

### 5.6. Настройка `spreadsheetId`
1. Получите ID таблицы из URL Google Sheets, например:
   ```
   https://docs.google.com/spreadsheets/d/1aBcDeFgHiJkLmNoPqRsTuVwXy/edit
   ```
   ID: `1aBcDeFgHiJkLmNoPqRsTuVwXy`.

## 6. Настройка планировщика (Cron)

Для автоматического выполнения команды `google:fetch` настройте Laravel Scheduler:

1. Настройте crontab для запуска планировщика Laravel каждую минуту:
   ```bash
   crontab -e
   ```
   Добавьте строку:
   ```bash
   * * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
   ```
   Замените `/path/to/your/project` на путь к вашему проекту (например, `/var/www/google_sheet_async`).

## 7. Запуск проекта

Запустите локальный сервер для тестирования:

```bash
php artisan serve
```

## 8. Тестирование интеграции

Протестируйте подключение к Google Sheets:

```bash
php artisan google:fetch
```

Эта команда должна вывести данные из таблицы Google Sheets (диапазон `A2:B` на листе `Sheet1`).

### Возможные ошибки и их устранение
- **404: Requested entity was not found**:
  - Проверьте `spreadsheetId` в базе данных:
    ```bash
    php artisan tinker
    >>> \App\Models\GoogleSheet::first()->spreadsheet_id
    ```
  - Убедитесь, что таблица существует и ID корректен.
  - Проверьте, что лист (например, `Sheet1`) существует в таблице.
- **403: The caller does not have permission**:
  - Убедитесь, что `client_email` из `storage/app/google-credentials.json` добавлен в доступ к таблице с правами «Viewer» или «Editor».
  - Проверьте, не ограничена ли таблица корпоративными политиками.
- **File not found**:
  - Убедитесь, что `storage/app/google-credentials.json` существует и доступен:
    ```bash
    ls -l storage/app/google-credentials.json
    ```
  - Проверьте права доступа и владельца файла.

## 9. Дополнительные ресурсы
- [Google Sheets API Documentation](https://developers.google.com/sheets/api)
- [Google Cloud Service Accounts](https://cloud.google.com/iam/docs/service-accounts)
- [Laravel Documentation](https://laravel.com/docs)

