## Instalaltion Required
- clone this project in your local directory
- run command in your terminal code editor "composer install", for install vendor 
- run command "composer require realrashid/sweet-alert" to install sweet alert package to your project's dependencies. check "https://realrashid.github.io/sweet-alert/install"  for detail installation sweet alert in laravel
- run command "composer require maatwebsite/excel" to install laravel excel package to your project's dependencies. check "https://docs.laravel-excel.com/3.1/getting-started/installation.html" for detail installation laravel excel
- dont forget to install xampp for the local server and database serve (mysql), and make database with name "sippm".
- run command "php artisan storage:link"
- run command "php artisan migrate --seed"
- finally, you can run the project with command "php artisan serve"

## Setup FIle .ENV (If this project was uploaded)
- APP_ENV=production
- APP_DEBUH=false 
- APP_URL, dan DB Connections menyesuaikan.

## Setup PHP MYSQL
- change file php.ini 
    - upload_max_filesize = 50M (maksimal ukuran file yang diupload)
    - post_max_size = 50M (maksimal ukuran file yang diupload)


