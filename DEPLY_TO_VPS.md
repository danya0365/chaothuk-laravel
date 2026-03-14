# คู่มือการนำโปรเจค Laravel Sail ขึ้น VPS (Production)

เอกสารนี้สรุปขั้นตอนทั้งหมดที่ใช้ในการนำโปรเจค "Chaothuk" (Laravel Sail) ขึ้นสู่ VPS (Ubuntu) รวมถึงการตั้งค่า Domain, HTTPS และการแก้ปัญหาที่พบบ่อย (เช่น Error 500)

## 1. การเตรียมไฟล์สำหรับ Production (ฝั่ง Local)

ก่อนนำโปรเจคขึ้น VPS จำเป็นต้องเตรียมไฟล์ที่เหมาะสมสำหรับ Production:

1. **สร้างไฟล์ `docker-compose.prod.yml`:**
   ไฟล์นี้จะใช้แทน `docker-compose.yml` เริ่มต้นของ Sail โดยจะตัด Service ที่ไม่จำเป็นออก (เช่น Mailpit, Redis หากไม่ใช้) และไม่ผูก Volume ของ Source Code เข้ากับ Container (หรือผูกเฉพาะที่จำเป็น) เพื่อป้องกันการแก้ไขไฟล์บน Production โดยไม่ตั้งใจ รวมถึงเปลี่ยน Port ของ Laravel Container ไปเป็นพอร์ตอื่น (เช่น `8080`) เพื่อหลีกเลี่ยงการชนกับ Nginx
   ```yaml
   # ตัวอย่างใน docker-compose.prod.yml
   services:
       laravel.test:
           build:
               context: ./vendor/laravel/sail/runtimes/8.3
           image: sail-8.3/app
           ports:
               - "8080:80" # เปลี่ยน Port เพื่อไม่ให้ชน Nginx ที่รันอยู่บน Host
           # ... การตั้งค่าอื่นๆ
   ```

2. **เตรียมไฟล์ `.env.production` (Template):**
   สร้างไฟล์เพื่อใช้เป็นต้นแบบ Environment บน VPS โดยเน้นความปลอดภัย:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.com
   ASSET_URL=https://your-domain.com
   DB_PASSWORD=your_secure_password
   SESSION_DRIVER=database
   ```

## 2. การนำโปรเจคขึ้น VPS

1. **ส่งไฟล์ขึ้น VPS:** ใช้เครื่องมืออย่าง `rsync` หรือ `git clone` นำ Source Code ไปวางบน VPS (เช่น `~/chaothuk-laravel`)
2. **ตั้งค่า Environment:**
   - คัดลอก `.env.example` (หรือ `.env.production`) เป็น `.env`
   - แก้ไข `.env` ใส่รหัสผ่าน Database และตั้งค่าโดเมนให้ถูกต้อง

3. **ติดตั้ง Dependencies:**
   หากในโปรเจคยังไม่มี Folder `vendor` ให้รัน Composer ผ่าน Docker เพื่อติดตั้ง:
   ```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v $(pwd):/var/www/html \
       -w /var/www/html \
       laravelsail/php83-composer:latest \
       composer install --ignore-platform-reqs --no-interaction --no-plugins --no-scripts --prefer-dist --optimize-autoloader
   ```

## 3. เริ่มต้นระบบและตั้งค่า Database

1. **Start Docker Containers:**
   ```bash
   docker compose -f docker-compose.prod.yml up -d
   ```

2. **Generate Application Key:**
   ```bash
   docker compose -f docker-compose.prod.yml exec -T laravel.test php artisan key:generate --force
   ```

3. **Run Migrations:**
   ```bash
   docker compose -f docker-compose.prod.yml exec -T laravel.test php artisan migrate --force
   ```

4. **ตั้งค่าสิทธิ์ไฟล์ (File Permissions):**
   บ่อยครั้งที่เกิด Error 500 เพราะ Container ไม่มีสิทธิ์เขียนไฟล์ใน Storage หรือ Bootstrap Cache ให้รันคำสั่ง:
   ```bash
   sudo chown -R $USER:www-data storage bootstrap/cache vendor
   sudo chmod -R 775 storage bootstrap/cache vendor
   ```

## 4. การจัดการ Caching (สำคัญ: วิธีแก้ Error 500 "No application encryption key")

**ข้อควรระวังอย่างยิ่ง:** การสร้าง Cache ใน Docker (Laravel Sail) หากรันคำสั่งเชลล์โดยไม่ได้ระบุ User มันอาจจะรันด้วยสิทธิ์ `root` ซึ่งทำให้ **Laravel ไม่สามารถอ่านค่าจากไฟล์ `.env` ได้ถูกต้อง** ส่งผลให้ช่อง `APP_KEY` ในไฟล์ `bootstrap/cache/config.php` ว่างเปล่า เกิด Error 500 เสมอ

**ข้อควรระวังเรื่องไฟล์ `.env.production` ที่มาพร้อม Source Code:**
หากคุณมีไฟล์ต้นแบบเช่น `.env.production` แนบมากับโปรเจคด้วยบน VPS ระบบของ Laravel (ตั้งแต่เวอร์ชันหลังๆ) จะมีการตรวจสอบค่า `APP_ENV` หากใน `.env` ของคุณตั้งค่าเป็น `APP_ENV=production` Laravel จะพยายามไปโหลดไฟล์ที่ตรงกับชื่อ Environment นั่นก็คือ `.env.production` ขึ้นมาอ่าน **ทับไฟล์ `.env` ปกติทันที** 
หากในไฟล์ `.env.production` (ซึ่งมักเป็น Template) ยังไม่ได้ใส่ค่า `APP_KEY` เอาไว้ ก็จะทำให้เกิด `Error 500: No application encryption key has been specified` แม้ว่าในไฟล์ `.env` ปกติคุณจะใส่ Key ไปแล้วก็ตาม!
**วิธีแก้:** เมื่อก๊อปปี้ `.env` แล้ว ให้ **ลบไฟล์ Template อย่าง `.env.production` ทิ้งจากเซิร์ฟเวอร์** ไปเลย เพื่อไม่ให้ Laravel โหลดผิดไฟล์.

**การแก้ไขและคำสั่งที่ถูกต้องในการ Cache บน Production:**
ต้องรันคำสั่งโดยระบุ `--user sail` เสมอ เพื่อให้ Laravel จำลองสภาพแวดล้อมได้ถูกต้อง:

```bash
# ล้าง Cache เก่าที่มีปัญหา (รันเป็น root เพื่อให้ลบไฟล์ที่ root สร้างไว้ได้)
docker compose -f docker-compose.prod.yml exec -T --user root laravel.test php artisan optimize:clear

# สร้าง Cache ใหม่ (ต้องใช้ --user sail)
docker compose -f docker-compose.prod.yml exec -T --user sail laravel.test php artisan config:cache
docker compose -f docker-compose.prod.yml exec -T --user sail laravel.test php artisan route:cache
docker compose -f docker-compose.prod.yml exec -T --user sail laravel.test php artisan view:cache
```

## 5. การตั้งค่า Nginx (Reverse Proxy) และ HTTPS (SSL)

เนื่องจาก Laravel Container รันอยู่บนพอร์ต `8080` (ตั้งค่าใน docker-compose.prod.yml) เราจะใช้ Nginx ที่ติดตั้งบน VPS (เครื่อง Host) เป็นตัวรับ Traffic พอร์ต 80 และ 443 แล้วส่งต่อไปยัง Container

1. **ติดตั้ง Nginx และ Certbot:**
   ```bash
   sudo apt update
   sudo apt install nginx certbot python3-certbot-nginx -y
   ```

2. **สร้าง Nginx Configuration (Reverse Proxy):**
   สมมติโดเมนคือ `chaothuk.dev.await.life`
   สร้างไฟล์ `/etc/nginx/sites-available/chaothuk`:
   ```nginx
   server {
       listen 80;
       listen [::]:80;
       server_name chaothuk.dev.await.life;

       location / {
           proxy_pass http://localhost:8080; # ชี้ไปยัง Port ของ Docker
           proxy_set_header Host $host;
           proxy_set_header X-Real-IP $remote_addr;
           proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
           proxy_set_header X-Forwarded-Proto $scheme;
       }
   }
   ```

3. **เปิดใช้งาน Site และ Restart Nginx:**
   ```bash
   sudo ln -s /etc/nginx/sites-available/chaothuk /etc/nginx/sites-enabled/
   sudo rm -f /etc/nginx/sites-enabled/default # ลบค่าเริ่มต้นทิ้งเพื่อไม่ให้ชนกัน
   sudo nginx -t # ตรวจสอบไวยากรณ์
   sudo systemctl restart nginx
   ```

4. **ขอใบรับรอง SSL ด้วย Certbot:**
   รันคำสั่งเพื่อให้ Certbot จัดการตั้งค่า HTTPS ใน Nginx ให้โดยอัตโนมัติ:
   ```bash
   sudo certbot --nginx -d chaothuk.dev.await.life
   ```

5. **อัปเดต `.env` ใน Laravel ให้รองรับ HTTPS เต็มรูปแบบ:**
   แก้ค่าในไฟล์ `.env`:
   ```env
   APP_URL=https://chaothuk.dev.await.life
   ASSET_URL=https://chaothuk.dev.await.life
   ```
   จากนั้นรันคำสั่ง Cache Config อีกครั้ง (อย่าลืม `--user sail`):
   ```bash
   docker compose -f docker-compose.prod.yml exec -T --user sail laravel.test php artisan config:cache
   docker compose -f docker-compose.prod.yml exec -T --user sail laravel.test php artisan view:cache
   ```

 เสร็จสิ้นกระบวนการนำขึ้น VPS และตั้งค่าระบบสำหรับสภาพแวดล้อม Production อย่างปลอดภัยครับ!

## 6. การตั้งค่า CI/CD ด้วย GitHub Actions (สำหรับ Branch `develop`)

เพื่อความสะดวกในการพัฒนา ผมได้สร้างไฟล์ Workflow ของ GitHub Actions ไว้ที่ `.github/workflows/deploy-dev.yml` เรียบร้อยแล้ว ระบบจะทำการ Deploy โค้ดไปยัง VPS อัตโนมัติทุกครั้งที่คุณ Push หรือ Merge โค้ดลง Branch `develop`

**สิ่งที่ต้องเตรียมบนบัญชี GitHub ของคุณ:**
เพื่อให้ GitHub Actions ทำงานได้สำเร็จ คุณต้องเข้าไปตั้งค่า Secrets ใน Repository ของคุณ (ไปที่เมนู `Settings` > `Secrets and variables` > `Actions` > กด `New repository secret`) แล้วเพิ่มตัวแปร 3 ตัวดังนี้:

1. **`VPS_HOST`**: ใส่ค่า IP ของเซิร์ฟเวอร์ (เช่น `95.216.221.18`)
2. **`VPS_USERNAME`**: ใส่ค่า Username (เช่น `maros`)
3. **`VPS_PASSWORD`**: ใส่รหัสผ่านของเซิร์ฟเวอร์

**โครงสร้างการทำงานของ CI/CD (Deploy Dev):**
1. คัดลอกเปิดโค้ดใหม่ทั้งหมดไปวางทับบน VPS (ผ่านโปรโตคอล SCP อย่างปลอดภัย)
2. รันคำสั่งอัปเดตไลบรารี `composer install` 
3. รัน `php artisan migrate --force` อัตโนมัติ หากมีการแก้ไข Database
4. ล้างแคช (`optimize:clear`) โดยไม่แคชโค้ด เพื่อให้เหมาะกับ Environment ของระบบทดสอบ
5. Build Frontend Assets (`npm run build`) อัตโนมัติ
