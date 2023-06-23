
# XMTechnical Task
## Support Solutions



## Installation
1. clone the project from git 
2. `composer install`
3. Rename or copy `.env.example` file to `.env`
      add /replace in .env file
      `MAIL_MAILER=smtp
         MAIL_HOST=server.my-conect.com
         MAIL_PORT=465
         MAIL_USERNAME=people@my-conect.com
         MAIL_PASSWORD=sniloc8813
         MAIL_ENCRYPTION=ssl
         MAIL_FROM_ADDRESS="people@my-conect.com"`
   and 
   
   `X_RAPIDAPI_KEY=36d92f7874mshcffc999e2012edap1a0d6fjsn6ffcf8c2b1d2
      X_RAPIDAPI_HOST=yh-finance.p.rapidapi.com
      X_RAPIDAPI_ENDPOINT=https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data`
      for email testing (this is dummy test email server "it works")
4. `php artisan key:generate`
5. Set your database and mail credentials in your `.env` file
6. `npm install`
7. `npm run dev`
8. `php artisan storage:link`
9. `composer require consoletvs/charts`
10. `composer require barryvdh/laravel-dompdf`
11. add  "files": [
    "app/Http/Helpers/funtions.php"
    ]
12. under the autoload object in composer.json file.
13. `php artisan optimize`
14. `composer dump-autoload`
15. `php artisan serve` or use Laravel Valet or Laravel Homestead
16. Visit `localhost:8000` in your browser
