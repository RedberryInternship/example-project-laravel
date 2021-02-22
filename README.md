<div style="display:flex; align-items: center">
  <img src="readme/assets/logo.png" alt="drawing" width="100" style="margin-right: 20px" />
  <h1 style="position:relative; top: -6px" >E Space Mobile App</h1>
</div>

---
E Space Mobile App helps people to find e-car charger nearby and charge their electric cars easily.
E Space also gives opportunity to business with giving them **Business Module** that gives people opportunity to have their own chargers and to make use of **Business Module** service to manage those chargers: create tariffs, monitor them, have analytical dashboard and more...

#
### Prerequisites

* <img src="readme/assets/php.svg" width="35" style="position: relative; top: 4px" /> *PHP@7.2 and up*
* <img src="readme/assets/mysql.png" width="35" style="position: relative; top: 4px" /> *MYSQL@8 and up*
* <img src="readme/assets/npm.png" width="35" style="position: relative; top: 4px" /> *npm@6 and up*
* <img src="readme/assets/composer.png" width="35" style="position: relative; top: 6px" /> *composer@2 and up*

#
### Getting Started
1\. First of all you need to clone E Space repository from github:
```sh
git clone https://github.com/Chkhikvadze/espace-back.git
```

2\. Next step requires you to run *composer install* in order to install all the dependencies.
```sh
composer install
```

3\. after you have installed all the PHP dependencies, it's time to install all the JS dependencies:
```sh
npm install
```

and also:
```sh
npm run dev
```
in order to build your JS/SaaS resources.

4\. Now we need to set our env file. Go to the root of your project and execute this command.
```sh
cp .env.example .env
```
And now you should provide **.env** file all the necessary environment variables:

**MYSQL:**
>DB_CONNECTION=mysql
>DB_HOST=127.0.0.1
>DB_PORT=3306
>DB_DATABASE=*****
>DB_USERNAME=*****
>DB_PASSWORD=*****

**MAILGUN:**
>MAILGUN_DOMAIN=******
>MAILGUN_SECRET=******

**Georgian Card:**
>MERCHANT_ID=******
>PAGE_ID=******
>ACCOUNT_ID=******
>BACK_URL_S=******
>BACK_URL_F=******
>REFUND_API_PASS=******
>CCY=******

**Twilio:**
>TWILIO_SID=******
>TWILIO_TOKEN=******
>TWILIO_FROM=******

**Maradit:**
>MARADIT_HTTPS=true
>MARADIT_USERNAME=******
>MARADIT_PASSWORD=******

**Google Cloud Messaging:**
>FCM_SERVER_KEY=******
>FCM_SENDER_ID=******

after setting up **.env** file, execute:
```sh
php artisan config:cache
```
in order to cache environment variables.

4\. Now execute in the root of you project following:
```sh
  php artisan key:generate
```
Which generates auth key.

##### Now, you should be good to go!