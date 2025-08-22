# ⚡ LEMP + n8n + ERPNext Setup Guide

This guide walks through installing and configuring:

- 🐧 **LEMP Stack** (Linux, Nginx, MariaDB, PHP)
- 🤖 **n8n** (workflow automation, Zapier alternative)
- 🏢 **ERPNext** (open-source ERP system)

---

## 🔹 LEMP Overview

The **LEMP stack** is a web hosting stack alternative to LAMP, where Apache is replaced with **Nginx (pronounced Engine‑X)**:

- 🐧 **Linux** – operating system
- 🌍 **Nginx** – web server
- 🐬 **MariaDB** – database engine (MySQL compatible)
- 🐘 **PHP-FPM** – processes dynamic content

---

## 🌍 Nginx Setup

Install Nginx:

```

sudo apt update
sudo apt install nginx

```

Useful commands:

- `sudo systemctl status nginx` → Check Nginx status
- `sudo systemctl stop nginx` → Stop server
- `sudo ss -tulpn` → Show open ports

✅ Open your server IP in a browser → you should see the **Nginx welcome page** 🎉

---

## 🐬 MariaDB Setup

Install MariaDB:

```

sudo apt install mariadb-server
sudo mysql_secure_installation

```

🔑 Secure installation will ask you to:

- Set the root password
- Remove test databases
- Disallow remote root login (optional)

Check MariaDB:

```

sudo systemctl status mariadb

```

### ⚙️ Configure Remote Root Access (optional)

```

sudo mariadb -uroot -p

```

Inside MariaDB:

```

SELECT User, Host FROM mysql.user;
RENAME USER 'root'@'localhost' TO 'root'@'%';
FLUSH PRIVILEGES;

```

Update config:

```

sudo nano /etc/mysql/my.cnf

```

```

[mysqld]
bind-address = 0.0.0.0

```

Restart:

```

sudo systemctl restart mariadb

```

---

## 🐘 PHP Setup

Install PHP with MariaDB support:

```

sudo apt install php-fpm php-mysql

```

Create a project dir:

```

sudo mkdir /var/www/myapp
sudo chown -R $USER:$USER /var/www/myapp

```

### Add Virtual Host

```

sudo nano /etc/nginx/sites-available/myapp

```

```

server {
listen 80;
listen [::]:80;

    root /var/www/myapp;
    index index.php index.html index.htm;

    server_name my_app_domain 10.66.66.32;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    }
    }

```

Enable and test:

```

sudo ln -s /etc/nginx/sites-available/myapp /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx

```

### Test PHP

```

nano /var/www/myapp/index.php

```

```

<?php echo '<h1>Hello World 🎉</h1>'; ?>

```

Visit: `http://your_server/index.php`  
👉 You should see _Hello World 🎉_

---

## 💻 VS Code Remote Development

- Install extension: `Remote - SSH`
- Use it to connect directly to your server for live editing 🖥️

---

## 🤖 n8n Installation

n8n = **open-source Zapier alternative** 🤯

Install NodeJS via NVM:

```

curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.3/install.sh | bash
source ~/.bashrc
nvm install 22
nvm use 22
npm install n8n -g
n8n

```

Set environment vars:

```

export N8N_SECURE_COOKIE=false
export N8N_PORT=5678
export N8N_RUNNERS_ENABLED=true
export DB_SQLITE_POOL_SIZE=2
export N8N_EDITOR_BASE_URL="https://pm1-ct100-n8n.iecmu.com"

```

---

## 🛠 Supervisor Setup

Install:

```

sudo apt install supervisor

```

Create config:

```

sudo nano /etc/supervisor/conf.d/n8n.conf

```

```

[program:n8n]
directory=/home/admin
command=/home/admin/.nvm/versions/node/v22.18.0/bin/n8n start
autostart=true
autorestart=true
startsecs=10
user=admin
redirect_stderr=true
stdout_logfile=/var/log/n8n.log
environment=PATH=/home/admin/.nvm/versions/node/v22.18.0/bin:/usr/local/bin:/usr/bin:/bin, \
N8N_PORT=5678, \
N8N_SECURE_COOKIE=false, \
N8N_EDITOR_BASE_URL="https://pm1-ct100-n8n.iecmu.com", \
N8N_RUNNERS_ENABLED=true, \
DB_SQLITE_POOL_SIZE=2

```

Restart supervisor:

```

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl status

```

---

## 🏢 ERPNext Installation

ERPNext = full-featured open-source **ERP system** built on the Frappe framework.

### Dependencies

```

sudo apt install pkg-config
curl -LsSf https://astral.sh/uv/install.sh | sh
source ~/.profile
uv venv

```

Auto-enable venv:

```

echo "source \$HOME/.venv/bin/activate" >> ~/.profile
source ~/.profile

```

Install packages:

```

sudo apt install python3-venv python3-dev build-essential pkg-config \
redis-server xvfb libfontconfig wkhtmltopdf \
default-libmysqlclient-dev software-properties-common mariadb-client -y

```

### Configure MariaDB for UTF-8

```

sudo nano /etc/mysql/my.cnf

```

```

[mysqld]
character-set-client-handshake = FALSE
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci

[mysql]
default-character-set = utf8mb4

```

Restart:

```

sudo systemctl restart mariadb

```

### Install Frappe & ERPNext

```

npm install -g yarn
cd ~
bench init erp --frappe-branch version-15
cd erp
bench get-app erpnext --branch version-15
bench start

```

### Create Site

```

cd ~/erp
bench new-site local
bench --site local install-app erpnext

```

### Configure Services

```

bench set-nginx-port local 85
bench setup nginx --log_format ''
bench setup supervisor
bench setup socketio

sudo ln -s /home/admin/erp/config/nginx.conf /etc/nginx/sites-enabled/erpnext.conf
sudo systemctl restart nginx

sudo ln -s /home/admin/erp/config/supervisor.conf /etc/supervisor/conf.d/erpnext.conf
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl status

```
