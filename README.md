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
### Tech Stack

* <img src="readme/assets/laravel.png" height="18" style="position: relative; top: 4px" /> [Laravel](https://laravel.com/) - back-end framework
* <img src="readme/assets/nova.png"  height="17" style="position: relative; top: 4px" /> [Laravel Nova](https://nova.laravel.com/) - flexible Admin Panel as espace "Super Admin"
* <img src="readme/assets/mix.png" height="18" style="position: relative; top: 4px" /> [Laravel Mix](https://laravel-mix.com/) - is a webpack wrapper which makes an ease for a developer to start working on JS files and compile them with such simplicity...
* <img src="readme/assets/jwt.png" height="18" style="position: relative; top: 4px" /> [JWT Auth](https://jwt-auth.readthedocs.io/en/develop/) - Authentication system for mobile users
* <img src="readme/assets/spatie.png" height="19" style="position: relative; top: 4px" /> [Spatie Translatable](https://github.com/spatie/laravel-translatable) - package for translation

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

#
**MYSQL:**
>DB_CONNECTION=mysql

>DB_HOST=127.0.0.1

>DB_PORT=3306

>DB_DATABASE=*****

>DB_USERNAME=*****

>DB_PASSWORD=*****

#
**MAILGUN:**
>MAILGUN_DOMAIN=******

>MAILGUN_SECRET=******

#
**Georgian Card:**
>MERCHANT_ID=******

>PAGE_ID=******

>ACCOUNT_ID=******

>BACK_URL_S=******

>BACK_URL_F=******

>REFUND_API_PASS=******

>CCY=******

#
**Twilio:**
>TWILIO_SID=******

>TWILIO_TOKEN=******

>TWILIO_FROM=******

#
**Maradit:**
>MARADIT_HTTPS=true

>MARADIT_USERNAME=******

>MARADIT_PASSWORD=******

#
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


#
### Migration
if you've completed getting started section, then migrating database if fairly simple process, just execute:
```sh
php artisan migrate
```

#
### Running Unit tests
Running unit tests also is very simple process, just type in following command:

```sh
composer test
```

### Development

You can run Laravel's built-in development server by executing:

```sh
  php artisan serve
```

when working on JS you may run:

```sh
  npm run dev
```
it builds your js files into executable scripts.
If you want to watch files during development, execute instead:

```sh
  npm run watch
```
it will watch JS files and on change it'll rebuild them, so you don't have to manually build them.


### Deployment

Once you pull changes to production server from github, there are several steps you have to keep in mind.

if you have made database update, you should execute:

```sh
  php artisan migrate
```

if you have updated php dependencies, the execute following:

```sh
  composer install
```

if you have updated JS dependencies, then execute following:

```sh
  npm install
```

to updated build JS, execute following:
```sh
  npm run build
```

Then everything should be OK :pray: