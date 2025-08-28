# 🐬 MariaDB Setup

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

## ⚙️ Configure Remote Root Access (optional)

```
sudo mariadb -uroot -p
```

Inside MariaDB:

```sql
SELECT User, Host FROM mysql.user;
RENAME USER 'root'@'localhost' TO 'root'@'%';
FLUSH PRIVILEGES;
```

Update config:

```
sudo nano /etc/mysql/my.cnf
```

Paste the following into the file.

```
[mysqld]
bind-address = 0.0.0.0
```

Restart database service:

```
sudo systemctl restart mariadb
```
