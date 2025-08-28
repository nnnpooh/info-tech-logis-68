# Add Web Service

## Create Virtual Host

Create a project directory for the app in /var/www

```bash
sudo mkdir /var/www/myapp
```

Change ownership of the directory so the current user can manage files inside it

```bash
sudo chown -R $USER:$USER /var/www/myapp
```

Open the Nginx configuration file

```bash
sudo nano /etc/nginx/sites-available/myapp
```

Add the following

```nginx
server
{
	listen 80;
	listen [::]:80;

	root /var/www/myapp;
	index index.php index.html index.htm;

	server_name server_local_url;

	location /
	{
		try_files $uri $uri/ =404;
	}

	location ~ \.php$
	{
		include snippets/fastcgi-php.conf;
		fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
	}
}

```

Creates a symbolic link (symlink) from the /etc/nginx/sites-available/myapp configuration file to the /etc/nginx/sites-enabled directory. This "enables" the site by making Nginx reference that config, as only files linked in sites-enabled are loaded by the server.

```bash
sudo ln -s /etc/nginx/sites-available/myapp /etc/nginx/sites-enabled
```

Test and reload

```
sudo nginx -t
sudo systemctl restart nginx
```

## 🐘 Test PHP

```
nano /var/www/myapp/info.php
```

Insert into the PHP file:

```php
<?php
phpinfo();
```

Visit `http://your_server_url/info.php`

## Hello World

```
nano /var/www/myapp/index.php
```

Insert into the PHP file:

```php
<!DOCTYPE html>
<html>
<head>
  <title>PHP Test</title>
</head>
<body>
  <?php echo '<p>Hello World</p>'; ?>
</body>
</html>
```

Visit: `http://your_server_url`  
👉 You should see _Hello World 🎉_

## 💻 VS Code Remote Development

- Install extension: `Remote - SSH`
- Use it to connect directly to your server for live editing.
