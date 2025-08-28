# 🌍 Nginx Setup

## Install Nginx:

```
sudo apt update
sudo apt install nginx
```

Useful commands:

- `sudo systemctl status nginx` → Check Nginx status
- `sudo systemctl stop nginx` → Stop server
- `sudo ss -tulpn` → Show open ports

✅ Open your server IP in a browser → you should see the **Nginx welcome page** 🎉

## Change Nginx User to `admin`

By default, Nginx runs as the **`www-data`** user (on Ubuntu/Debian).  
If you want Nginx processes to run as `admin` instead, edit the **main config file**:

```
sudo nano /etc/nginx/nginx.conf
```

At the very top, update this line:

```
user admin;
```

Save the file, then test config and restart:

```
sudo nginx -t
sudo systemctl restart nginx
```

### 🔑 Why change the user?

- It allows Nginx worker processes to run under your `admin` account.
- Useful if you want file ownership in `/var/www/` to stay under `admin` without needing `www-data` group tweaks.
- ⚠ Be cautious: this gives more privileges to the `admin` user, so ensure permissions and security are configured properly.
