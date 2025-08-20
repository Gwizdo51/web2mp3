# yt2mp3_server

A small web project in Laravel + Vue to download songs from YouTube

# DUMP

### PostgreSQL

- Install and setup PostgreSQL :
    ```bash
    sudo apt update
    sudo apt install postgresql postgresql-contrib
    ```
- Log into PostgreSQL : `sudo -u postgres psql`
- Create a user + database :
    ```sql
    CREATE USER laravel_user WITH PASSWORD '123456789';
    CREATE DATABASE laravel OWNER laravel_user;
    GRANT ALL PRIVILEGES ON DATABASE laravel TO laravel_user;
    SET TIME ZONE 'UTC';
    ```
- Show all databases : `SELECT datname FROM pg_database;`
- Allow remote connections :
    - Edit `/etc/postgresql/16/main/postgresql.conf`
    - Replace `listen_addresses = 'localhost'` => `listen_addresses = '*'`
    - Edit `/etc/postgresql/16/main/pg_hba.conf`
    - Add at the end :
        ```
        host all all 0.0.0.0/0 md5
        host all all ::/0 md5
        ```
- Restart the server : `sudo systemctl restart postgresql`
- Install php driver : `sudo apt install php-pgsql`
- Restart the web server : `sudo systemctl restart apache2`
- Replace in .env :
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=laravel
    DB_USERNAME=laravel_user
    DB_PASSWORD=123456789
    ```
