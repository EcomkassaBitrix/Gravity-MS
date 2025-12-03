# Настройка сервера

## Пример конфигурации Nginx.

```
server {

    gzip on;

    server_name ms.ecomkassa.ru;

    root /var/www/ms.ecomkassa.ru/public_html/public;

    error_log /var/log/nginx/ms.ecomkassa.ru.error.log;
    access_log /var/log/nginx/ms.ecomkassa.ru.access.log;

    index index.php index.html;

    location ~ vendor-endpoint.php {
      fastcgi_pass unix:/run/php/php8.4-fpm.sock;
      fastcgi_index index.php;
      fastcgi_param SCRIPT_FILENAME /var/www/ms.ecomkassa.ru/public_html/public/vendor-endpoint.php;
      fastcgi_param PATH_INFO        $fastcgi_path_info if_not_empty;
      include fastcgi_params;
    }

    location ~ fiscal {
      fastcgi_pass unix:/run/php/php8.4-fpm.sock;
      fastcgi_index index.php;
      fastcgi_param SCRIPT_FILENAME /var/www/ms.ecomkassa.ru/public_html/public/fiscal/index.php;
      fastcgi_param PATH_INFO        $fastcgi_path_info if_not_empty;
      include fastcgi_params;
    }

    location ~ \.php$  {
      fastcgi_pass unix:/run/php/php8.4-fpm.sock;
      fastcgi_index index.php;
      fastcgi_param SCRIPT_FILENAME /var/www/ms.ecomkassa.ru/public_html/public/$fastcgi_script_name;
      fastcgi_param PATH_INFO        $fastcgi_path_info if_not_empty;
      include fastcgi_params;
    }
    location / {

      proxy_set_header Host            $host;
      proxy_set_header X-Forwarded-For $remote_addr;
      proxy_set_header X-Forwarded-Proto https;
      proxy_http_version 1.1;

    }

    listen 443 ssl http2; # managed by Certbot
    listen [::]:443 ssl http2; # managed by Certbot
    ssl_certificate /etc/letsencrypt/live/ms.ecomkassa.ru/fullchain.pem; # managed by Certbot
    ssl_certificate_key /etc/letsencrypt/live/ms.ecomkassa.ru/privkey.pem; # managed by Certbot
    include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot
}


```

Обратите внимание на определение vendor-endpoint.php и fiscal. Эти адреса принимают запросы вида `./vendor-endpoint.php/path1/path2`, что не может быть обработано веб-сервером с конфигурацией по умолчанию.

## PHP

Установите PHP-FPM 8.4 с модулем php-curl

Например, `apt-get install php-curl`

## Composer

Выполните `composer install` для загрузки зависимостей из корневой директории.

## Конфигурация

Конфигурация задается в файле `.env`

Файл не должен храниться в репозитории.

### Параметры файла конфигурации:

Директория для хранения логов (не должна быть доступна по прямому URL, должна быть доступна для записи):

```
WEBHOOK_LOG=../logs/webhook.log
```

Директория для хранения файлов данных (не должна быть доступна по прямому URL, должна быть доступна для записи):

```
DATA_DIR=../data
```


Идентификатор решения (appId)  (получать в личном кабинете разработчика):

```
APP_ID=
```

Глобальный идентификатор (appUid) (получать в личном кабинете разработчика):

```
APP_UID=
```

Базовый адрес. Например: `https://ms.ecomkassa.ru`

```
APP_BASE_URL=
```

Секретный ключ (получать в личном кабинете разработчика):

```
SECRET_KEY=
```
