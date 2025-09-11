## 🤖 n8n Installation

Install NodeJS via Node Version Manager (NVM):

```bash
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.3/install.sh | bash
source ~/.bashrc
nvm install 22
nvm use 22
npm install n8n -g
```

Set environment vars:

```bash
export N8N_SECURE_COOKIE=false
export N8N_PORT=5678
export N8N_RUNNERS_ENABLED=true
export DB_SQLITE_POOL_SIZE=2
export N8N_EDITOR_BASE_URL="https://pmX-ctXXX-n8n.iecmu.com"

```

Start program

```
n8n
```

You can visit the site

- Locally (e.g. `http://pmX-ctXXX.local:5678`)
- Publicy (e.g. `https://pmX-ctXXX-n8n.iecmu.com`)

> Running n8n directly from the terminal will keep it active only while the terminal is open, so the process stops if the window is closed. To keep n8n running continuously as a background service, a process manager like Supervisor will be used.

## 🛠 Supervisor Setup

Install:

```bash
sudo apt install supervisor
```

Create config:

```bash
sudo nano /etc/supervisor/conf.d/n8n.conf
```

```bash
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
N8N_EDITOR_BASE_URL="https://pmX-ctXXX-n8n.iecmu.com", \
N8N_RUNNERS_ENABLED=true, \
DB_SQLITE_POOL_SIZE=2
```

Restart supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl status
```
