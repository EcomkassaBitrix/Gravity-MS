# Параметры установки МойСклад

# Регистрация приложения в интерфейсе разработчика МойСклад

## Название

```
Екомкасса Чеки
```

## Псевдоним

```
checks
```

## Ссылка на внешний сайт (с инструкцией)

```
https://ms.ecomkassa.ru/doc/
```

## Дескриптор

```
<ServerApplication xmlns="https://apps-api.moysklad.ru/xml/ns/appstore/app/v2"
                   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                   xsi:schemaLocation="https://apps-api.moysklad.ru/xml/ns/appstore/app/v2 https://apps-api.moysklad.ru/xml/ns/appstore/app/v2/application-v2.xsd">
    <iframe>
        <sourceUrl>https://ms.ecomkassa.ru/iframe.php</sourceUrl>
        <expand>true</expand>
    </iframe>
    <vendorApi>
        <endpointBase>https://ms.ecomkassa.ru/vendor-endpoint.php</endpointBase>
    </vendorApi>
    <fiscalApi>
        <endpointBase>https://ms.ecomkassa.ru/fiscal/</endpointBase>
        <operationTypes>
            <retailDemand/>
            <retailSalesReturn/>
        </operationTypes>
        <paymentTypes>
            <cash/>
            <card/>
            <cashCard/>
        </paymentTypes>
    </fiscalApi>
    <access>
        <resource>https://api.moysklad.ru/api/remap/1.2</resource>
        <scope>admin</scope>
    </access>
</ServerApplication>
```

## Пример конфигурации Nginx.

Так же следует настроить PHP-FPM 8.4 (curl)

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