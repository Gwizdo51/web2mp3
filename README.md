# yt2mp3_server

A small web project in Laravel + Vue to download songs from YouTube

# TODO

- [ ] Add setup and run steps to README.md (for dev server + behind proxy)
- [x] Make the website responsive
- [x] Check for a faster/lighter DB (Redis ? SQLite ?)

## Requirements

Docker

## Setup

- Install Composer dependencies
- Install NPM dependencies
- Create docker_data folder tree
- Migrate database
- Generate new app + Reverb keys
- Add storage link
- `php artisan optimize`
- Adapt Docker files to your environment

# DUMP

### PostgreSQL

#### Setup

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
    DB_CONNECTION=postgres
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=laravel
    DB_USERNAME=laravel_user
    DB_PASSWORD=123456789
    ```

#### Commands

- Convert timestamp to date : `to_timestamp(j.created_at)`

### yt-dlp options

- output file path : `-o "/var/www/storage/app/public/%(title)s.%(ext)s"`
- extract audio : `-x`
- format selection : `-f bestaudio`
- audio format (`aac`, `alac`, `flac`, `m4a`, `mp3`, `opus`, `vorbis`, `wav`) : `--audio-format mp3`
- audio quality : `--audio-quality 0`
- disable caching : `--no-cache-dir`

Example : `yt-dlp -x -f bestaudio --audio-format mp3 --audio-quality 0 -o "/var/www/storage/app/public/%(title)s.%(ext)s" --no-cache-dir 'https://www.youtube.com/watch?v=wl95CZVD5m8'`

### Potential TailwindCSS component libraries

- https://flowbite.com/
- https://www.material-tailwind.com/
- https://preline.co/
- https://daisyui.com/

### MCD (mocodo)

```
DOWNLOADS: id download, youtube url, format, quality, file name
STATES: id state, label

DESCRIBE, 11 DOWNLOADS, 0N STATES
```

Possible states :
- Waiting
- Running
- Failed
- Succeeded

### SQLite3 CLI

- Exit : `.exit`
- Format output : `.mode line`
- See details about the current database : `.databases`
- See all tables : `.tables`
- See details about a table : `.schema <table_name>`
- Convert timestamp to datetime : `SELECT datetime(available_at, 'unixepoch') FROM jobs;`
- Dump the WAL into the database : `pragma wal_checkpoint(truncate);`
