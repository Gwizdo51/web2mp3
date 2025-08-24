# yt2mp3_server

A small web project in Laravel + Vue to download songs from YouTube

# Process (Gemini)

### Feasibility and Technical Workflow
The architecture you've outlined is a classic full-stack web application model, where the front end (Vue.js) handles the user interface and interactions, and the back end (Laravel) manages the server-side logic and resource-intensive tasks.

### The Workflow
**Front-End User Interface (Vue.js)**:

- You would create a Vue component that contains a form. This form would have an input field for the YouTube URL, and elements like dropdowns or checkboxes for the options (e.g., file type like mp3, m4a, quality).

- When the user clicks the "DOWNLOAD" button, a JavaScript function is triggered. This function prevents the default form submission and sends an axios POST request to your Laravel back end, sending the URL and selected options in the request body.

**Back-End Processing (Laravel)**:

- Laravel would have an API route that receives this POST request. A controller method tied to this route would validate the incoming data (checking if the URL is valid, for instance).

- Once validated, the controller would execute a shell command using PHP's exec() or Symfony Process component to run yt-dlp. For example, a command might look like this: `yt-dlp -x --audio-format mp3 "https://www.youtube.com/watch?v=..."`.

- This is the most critical part, as it's a long-running process. The Laravel controller should not wait for yt-dlp to finish. Instead, it should start the process in the background and return an immediate response to the Vue.js front end. This is where your front-end will display the "Downloading & converting" page. You can use Laravel's queue system to handle this gracefully.

**Progress Notification and Loading State**:

- Upon receiving a successful response from the Laravel API that the job has been started, your Vue.js application would update its state. A simple state variable like isLoading can be set to true, which would trigger the UI to show the spinner and the "Downloading & converting" message.

- As you suspected, a real-time connection is the most reliable way to track progress. The front end needs a way to know when the background job is complete.

**Completion and Final Download**:

- Once the yt-dlp process finishes on the server, a final notification is sent to the front end.

- Upon receiving this notification, the Vue.js app updates its state again (isLoading to false, isComplete to true). This change will show the final UI with the manual download button and the "download another song" link.

- The front end will trigger an automatic download by creating a temporary link to the finished file on the server and using a programmatic click or a redirect to start the download.

### Follow-Up Questions
1. **How do I delete the song files from my server**?

This is an essential step for both security and server disk space management. There are two primary ways to approach this:

Immediately After Download: When the user's browser automatically starts the download, you can provide a route that first serves the file and then immediately deletes it from the server. This is the simplest method. You would use PHP's unlink() function or Laravel's Storage facade to delete the file right after it has been sent to the browser.

Scheduled Task: For files that might not be downloaded immediately, a more robust solution is to use Laravel's Task Scheduler. You can create a command that runs periodically (e.g., once an hour) and deletes any files in your temporary downloads directory that are older than a certain age (e.g., 30 minutes or 1 hour). This handles cases where a user might leave the page before the download completes.

2. **How can the backend notify the frontend that the download completed**?

Your intuition is correct: WebSockets are the ideal solution here. They provide a persistent, two-way communication channel between the client and the server, allowing the server to push real-time updates to the browser without the browser having to constantly ask for them.

Implementation: You would use a package like Laravel Echo with a WebSocket server like Pusher or Soketi.

The Process:

- When the user initiates a download, a unique job ID is created on the server.

- The Laravel back end dispatches the yt-dlp job to the queue and broadcasts an event on a specific channel for that job ID.

- Your Vue.js front end listens for messages on this same channel.

- When the queued job completes, it broadcasts another event (e.g., DownloadComplete).

- The front end receives this event, updates the UI, and triggers the download.

While you could use polling (where the front end repeatedly sends requests to the server to ask for the job status), this is inefficient and resource-intensive for both the client and the server. WebSockets are the modern, more efficient approach for real-time applications like this.

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
