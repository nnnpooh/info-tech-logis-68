# 🐘 PHP Setup

## Install PHP with MariaDB support

```bash
sudo apt install php-fpm php-mysql
```

## Make sure nginx can connect to PHP-FPM

If you change the **Nginx worker process user** to `admin` (instead of the default `www-data`), you must also update the PHP-FPM pool configuration so PHP sockets are owned by the same user.

Edit the PHP-FPM pool config:

```bash
sudo nano /etc/php/8.2/fpm/pool.d/www.conf
```

Find and update these lines:

```bash
listen.owner = admin
listen.group = admin
```

Restart PHP-FPM and Nginx:

```bash
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

Without this step:

- Nginx (running as `admin`) might not be able to connect to the PHP-FPM socket (still owned by `www-data`).
- This usually causes **502 Bad Gateway errors** when serving PHP pages.
