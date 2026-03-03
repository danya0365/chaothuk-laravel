# Installation

install docker-desktop on your mac

## Alias sail in your bash_profile

`alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'`

the you can run
`./vendor/bin/sail up -d`

or
`sail up -d`

## start sail

### start on normally

`./vendor/bin/sail up`

### start in background

`./vendor/bin/sail up -d`

Once the application's Docker containers have been started, you can access the application in your web browser at: http://localhost.

[Full Laravel Sail](https://laravel.com/docs/9.x/sail)

### stop sail

`./vendor/bin/sail stop`

### Manage DB with PGAdmin

open http://localhost:5050
then create new server connection
host: host.docker.internal
port: 5432
user: sail
pass: password

### Execute NPM

Install dependency
`sail yarn`

Add new package
`sail yarn add {packageName}`

Build Js/css
`sail yarn dev`

### Public Storage folder

exceute following cmd when create public folder on first deployment on local or production
`sail artisan storage:link`

### DB Migrate & Seeder

Run db migration
`sail php artisan migrate`

Refresh Database (delete all data) and seed reference data
`sail php artisan migrate:fresh --seed`

---

#### 🌱 Seeder Map

| Seeder | ประเภท | สิ่งที่ทำ | ENV |
|---|---|---|---|
| `DatabaseSeeder` | Master data | Geography, Provinces, WorkTypes, Categories, Roles | ทุก env |
| `DemoSeeder` | Demo accounts | 4 users + sample works/recruits | dev, staging |
| `MockSeeder` | Volume data | 50 users, 100 works, 450 posts, 500 notifications | dev เท่านั้น |
| `ProductionSeeder` | Admin accounts | Super admin จาก `.env` | prod เท่านั้น |

> **Note:** `MockSeeder` เรียก `DemoSeeder` อัตโนมัติ — ไม่ต้องรัน Demo แยก

---

#### 🔑 Demo Accounts (`DemoSeeder`)

Password ทุก account คือ **`password`**

| Email | ชื่อ | บทบาท |
|---|---|---|
| `worker1@chaothuk.test` | สมชาย ขับรถดี | ผู้รับงาน |
| `worker2@chaothuk.test` | มานะ ทำงานดี | ผู้รับงาน |
| `employer1@chaothuk.test` | บริษัท ขนส่งไทย | ผู้จ้าง |
| `employer2@chaothuk.test` | ห้างหุ้นส่วน โลจิสติกส์ดี | ผู้จ้าง |

---

#### 🚀 Commands per Environment

**[DEV] — ข้อมูลครบสำหรับเทส**
```bash
sail artisan migrate:fresh --seed
sail artisan db:seed --class=MockSeeder   # ← รัน DemoSeeder ภายในอัตโนมัติ
```

**[STAGING] — demo accounts + reference data เท่านั้น**
```bash
sail artisan migrate:fresh --seed
sail artisan db:seed --class=DemoSeeder
```

**[PRODUCTION] — master data + admin เท่านั้น**
```bash
# .env ต้องมี:
# ADMIN_EMAIL=admin@chaothuk.app
# ADMIN_PASSWORD=your-secure-password

sail artisan migrate --force
sail artisan db:seed --force                          # StarterSeeder (master data)
sail artisan db:seed --class=ProductionSeeder --force # admin user
```

**[ลำดับ seeder ถ้ารันแยก]**
```bash
1. migrate:fresh --seed     # DatabaseSeeder ก่อนเสมอ
2. DemoSeeder               # demo accounts (ถ้าต้องการ)
3. MockSeeder               # volume data (dev only)
```

#### ⚙️ Factories

`WorkFactory` · `RecruitFactory` · `PostFactory` · `WorkBookingFactory` · `RecruitBookingFactory` · `UserFactory` · `NotificationFactory`


### Database Management

[PGAdmin](http://localhost:5050)

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

show all schedule list
`sail php artisan schedule:list`

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

# Semantic Commit Messages

See how a minor change to your commit message style can make you a better programmer.

Format: `<type>(<scope>): <subject>`

`<scope>` is optional

## Example

```
feat: add hat wobble
^--^  ^------------^
|     |
|     +-> Summary in present tense.
|
+-------> Type: chore, docs, feat, fix, refactor, style, or test.
```

More Examples:

-   `feat`: (new feature for the user, not a new feature for build script)
-   `fix`: (bug fix for the user, not a fix to a build script)
-   `docs`: (changes to the documentation)
-   `style`: (formatting, missing semi colons, etc; no production code change)
-   `refactor`: (refactoring production code, eg. renaming a variable)
-   `test`: (adding missing tests, refactoring tests; no production code change)
-   `chore`: (updating grunt tasks etc; no production code change)

References:

-   https://www.conventionalcommits.org/
-   https://seesparkbox.com/foundry/semantic_commit_messages
-   http://karma-runner.github.io/1.0/dev/git-commit-msg.html

### Install tailwind with sass

[Install Tailwind CSS & SASS with Laravel Mix (2022)](https://ralphjsmit.com/tailwind-sass-laravel)
[Tailwind Documentation](https://tailwindcss.com/docs/installation)
