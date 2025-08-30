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
redis-server xvfb libfontconfig  \
default-libmysqlclient-dev software-properties-common mariadb-client -y

```

```
sudo apt install xfonts-75dpi fontconfig libxext6 libxrender1 xfonts-base &&
wget https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6.1-3/wkhtmltox_0.12.6.1-3.bookworm_amd64.deb &&
sudo dpkg -i wkhtmltox_0.12.6.1-3.bookworm_amd64.deb &&
sudo cp /usr/local/bin/wkhtmlto* /usr/bin/ &&
sudo chmod a+x /usr/bin/wk* &&
sudo rm wk* &&
sudo apt --fix-broken install -y
echo "Expect wkhtmltopdf with patched qt version ==> $(wkhtmltopdf --version)"
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
