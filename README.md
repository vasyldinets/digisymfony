**DigiSymfony**

To start:

1. Clone repository
2. Prepare your destination computer (php7.2, MySQL 5.7)
3. When copied, go to your destination folder and run **composer install**
4. In .env change access data to DB
5. Run   **php bin/console doctrine:schema:update --force** (or **php bin/console doctrine:database:create** before it if DB not exist)
6. Run **mkdir var/jwt**
7. Run **openssl genrsa -out var/jwt/private.pem -aes256 4096** (Setup and confirm password, for default use 123456. If you use another pass - change it in %project%\config\packages\lexik_jwt_authentication.yaml)
8. Run **openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem** (Need password for private.pem)
9. If need run **php bin/console doctrine:fixtures:load** for fake data
10. Make sure your webserver is serving pages from project/public folder.

API:

1. Register on site
2. Create client or Token with POST on endpoint **%root%/credential/token** with Basic Auth and user credentials
3. In response you get Bearer Token
4. In all request add in headers "Accept: application/json" and "Authorization: Bearer %YOUR_TOKEN%"
5. All methods and endpoint in  Controller@methods in annotation route (types on Model Classes in annotation too)

Web:

1. After Login  you take access to Transaction page

Cron:

1. Change DB access in %project%/public/cron/calcsum.php
2. File with command in root of project ./crontab.txt

Log:
1. All API log write to the %project%/var/log/api.log

Cache:
1. Cache set on all GET methods 