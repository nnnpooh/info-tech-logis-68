# 🏢 ERPNext Installation

## Setup

### Making sure your repository list is updated

```bash
sudo apt clean && sudo apt update
```

### Installing Additional Packages

```bash
sudo apt install python3-venv python3-dev build-essential pkg-config \
redis-server xvfb libfontconfig  \
default-libmysqlclient-dev software-properties-common mariadb-client -y
```

### Fix `wkhtmltopdf` package

```bash
sudo apt install xfonts-75dpi fontconfig libxext6 libxrender1 xfonts-base &&
wget https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6.1-3/wkhtmltox_0.12.6.1-3.bookworm_amd64.deb &&
sudo dpkg -i wkhtmltox_0.12.6.1-3.bookworm_amd64.deb &&
sudo cp /usr/local/bin/wkhtmlto* /usr/bin/ &&
sudo chmod a+x /usr/bin/wk* &&
sudo rm wk* &&
sudo apt --fix-broken install -y
echo "Expect wkhtmltopdf with patched qt version ==> $(wkhtmltopdf --version)"
```

### Installing Python

```bash
sudo apt install pkg-config
curl -LsSf https://astral.sh/uv/install.sh | sh
source ~/.profile
uv venv
uv pip install frappe-bench
```

Auto-enable venv:

```bash
echo "source \$HOME/.venv/bin/activate" >> ~/.profile
source ~/.profile
```

### Configure MariaDB for UTF-8

```bash
sudo nano /etc/mysql/my.cnf
```

Copy this to the file

```bash
[mysqld]
character-set-client-handshake = FALSE
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci

[mysql]
default-character-set = utf8mb4
```

Restart:

```bash
sudo systemctl restart mariadb
```

## Install Frappe & ERPNext

```bash
npm install -g yarn
cd ~
bench init erp --frappe-branch version-15
cd erp
bench get-app erpnext --branch version-15
bench start
```

### Create Site

```bash
cd ~/erp
bench new-site local
bench --site local install-app erpnext
```

### Configure Services

```bash
bench set-nginx-port local 85
bench setup nginx --log_format ''
bench setup supervisor
bench setup socketio
```

### Adding site to `nginx`

```bash
sudo ln -s /home/admin/erp/config/nginx.conf /etc/nginx/sites-enabled/erpnext.conf
```

Restart

```
sudo systemctl restart nginx
```

### Configuring `supervisor`

```
sudo ln -s /home/admin/erp/config/supervisor.conf /etc/supervisor/conf.d/erpnext.conf
```

Restart

```
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl status
```
