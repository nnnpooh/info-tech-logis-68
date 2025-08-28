# 🐬 MariaDB Setup

## Install MariaDB

```bash
sudo apt install mariadb-server
sudo mysql_secure_installation
```

🔑 Secure installation will ask you to:

- Set the root password
- Remove test databases
- Disallow remote root login (optional)

Check MariaDB:

```bash
sudo systemctl status mariadb
```

## ⚙️ Configure Remote Root Access

```bash
sudo mariadb -uroot -p
```

Inside MariaDB:

```sql
SELECT User, Host FROM mysql.user;
RENAME USER 'root'@'localhost' TO 'root'@'%';
FLUSH PRIVILEGES;
```

Update config:

```bash
sudo nano /etc/mysql/my.cnf
```

Paste the following into the file.

```
[mysqld]
bind-address = 0.0.0.0
```

Restart database service:

```bash
sudo systemctl restart mariadb
```

## Test Connectivity

You should be able to access database from your computer using `dbeaver`.
