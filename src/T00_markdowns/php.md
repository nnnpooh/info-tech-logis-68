# After changin nginx user, you need to take care of this

- `sudo nano /etc/php/8.2/fpm/pool.d/www.conf`

```
listen.owner = admin
listen.group = admin
```

Perfect 👍 — here’s that section formatted properly for your **GitHub Page style guide**, with explanation and nice structure:

```markdown
### 🔐 After Changing Nginx User

If you change the **Nginx worker process user** to `admin` (instead of the default `www-data`),  
you must also update the PHP-FPM pool configuration so PHP sockets are owned by the same user.

Edit the PHP-FPM pool config:
```

sudo nano /etc/php/8.2/fpm/pool.d/www.conf

```

Find and update these lines:

```

listen.owner = admin
listen.group = admin

```

⚠️ Without this step:
- Nginx (running as `admin`) might not be able to connect to the PHP-FPM socket (still owned by `www-data`).
- This usually causes **502 Bad Gateway errors** when serving PHP pages.

Finally, restart PHP-FPM and Nginx:

```

sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx

```

✅ Now Nginx (`admin`) and PHP-FPM (`admin`) can communicate properly.
```
