<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Basic Command Line

you can use following command line to reduce dev time

### start on normally

`./vendor/bin/sail up`

### start in background

`./vendor/bin/sail up -d`

Once the application's Docker containers have been started, you can access the application in your web browser at: http://localhost.

[Full Laravel Sail](https://laravel.com/docs/9.x/sail)

### stop sail

`./vendor/bin/sail stop`

### Execute NPM

Install dependency
`sail yarn`

Add new package
`sail yarn add {packageName}`

Build Js/css
`sail yarn dev`

### DB Seeder

`sail php artisan migrate:fresh --seed`

### Database Management

[PhpMyAdmin](http://localhost:8081/)

### Generate CRUD

[awais-vteams/laravel-crud-generator](https://github.com/awais-vteams/laravel-crud-generator)

Add migration file to create `table` first
`sail php artisan make:migration create_banks_table`

Then generate CRUD
`sail php artisan make:crud banks`

Add route.php
`Route::resource('banks', 'BankController');`

### ทุกครั้งที่มีการเพิ่ม Lib ด้วย NodeJS

ต้องทำการติดตั้งและ Generate css และ js ไปที่ public folder ด้วยคำสั่ง

`yarn && yarn dev`

### Generate Enum

สร้าง enum UserType

`php artisan make:enum UserType`

### Generate Event & Listener

`sail php artisan make:event EmployeeOperationLogCreated`
`sail php artisan make:listener CalculateUserWorkingTimeNotification --event=EmployeeOperationLogCreated`

### CronJob Schedule

add new command
`sail php artisan make:command DaillyReportCron --command=dailyReport:cron`

open `app/Console/Commands/DaillyReportCron.php`

search for `function handle()`

add below code

```
use Illuminate\Support\Facades\Log;
...

function handle() {
    Log::info("Cron is working fine!");
}
```

open `app/Console/Kernel.php`

add below code in `function schedule(Schedule $schedule)`

`$schedule->command(DaillyReportCron::class)->daily();`

test to force run schedule

`sail php artisan schedule:run`

\*\*\*only for local server to force cronjob running
`sail php artisan schedule:work`

### Set up your server to run crontab every second

At last you can manage this command on scheduling task, you have to add a single entry to your server’s crontab file:
`* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`

## Executing Commands

When using Laravel Sail, your application is executing within a Docker container and is isolated from your local computer. However, Sail provides a convenient way to run various commands against your application such as arbitrary PHP commands, Artisan commands, Composer commands, and Node / NPM commands.

When reading the Laravel documentation, you will often see references to Composer, Artisan, and Node / NPM commands that do not reference Sail. Those examples assume that these tools are installed on your local computer. If you are using Sail for your local Laravel development environment, you should execute those commands using Sail:

### Running Artisan commands locally...

`php artisan queue:work`

### Running Artisan commands within Laravel Sail...

`./vendor/bin/sail artisan queue:work`

### Show PHP version on your sail

`./vendor/bin/sail php --version`

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Cubet Techno Labs](https://cubettech.com)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[Many](https://www.many.co.uk)**
-   **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
-   **[DevSquad](https://devsquad.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[OP.GG](https://op.gg)**
-   **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
-   **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
