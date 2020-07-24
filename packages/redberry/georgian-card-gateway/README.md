# Georgian Card Gateway

Georgian Card Gateway is easy to use package. it takes a lot of work off your back.

with this package you can set up:
- regular payments ( requiring input of user's credit card information on each transaction)
- recurrent payments (basically with this method you can save user's credit card into merchant and then make payments without asking user credit card info every time. )

## Installation

Installation is fairly simple:

```sh
$ composer require redberry/georgian-card-gateway
```

## Set Up
-------
### 1. env
you have to enter necessary credentials for your merchant in the enviroment variables file.
```env
MERCHANT_ID=---YOUR-MERCHANT-ID---
PAGE_ID=---YOUR-PAGE-ID---
ACCOUNT_ID=---YOUR-ACCOUNT-ID---
CCY=---YOUR-CURRENCY-CODE---
REFUND_API_PASS=---YOUR-REFUND-API-PASSWORD---
BACK_URL_S=https://your-website.ge/payment/succeed
BACK_URL_F=https://your-website.ge/payment/failed
```
###### MERCHANT_ID
represents your merchant's identificator, which will be given to you from BOG.
###### PAGE_ID
represents the identificator of the payments page, which can be customized and styled with your preferences.
###### ACCOUNT_ID
On merchant there can be meny pos terminals. And on each terminal there will be account, on which the payments will be directed. Most likely you will need only one terminal. But in this doc we will also see how can we manage payments on multiple terminals.
###### CCY
Represents in which currency transaction should happen. you will most probably use 981 which represents Lari. [see more](https://en.wikipedia.org/wiki/ISO_4217)

###### REFUND_API_PASS
Represents password for making refunds of your transactions.

###### BACK_URL_S
The url which will be visited after successfull transaction. for example: https://your-web-site.ge/payments/success

###### BACK_URL_F
The url which will be visited after failed transaction. for example: https://your-web-site.ge/payments/success

### 2. vendor publish
```sh
php artisan vendor:publish --provider="Redberry\GeorgianCardGateway\Support\ServiceProvider"
```

### 3. bind georgian card handler
For you to be able to make transaction records in database, make reccurent transaction or do something on failed/success ending, it's nessessary to create class for Georgian card and bind it into container.

So... let's create class, which will have path: "app/Library/GeorgianCard.php" and will implement GeorgianCardHandler interface:
```php
<?php

namespace App\Library;

use Redberry\GeorgianCardGateway\Contracts\GeorgianCardHandler;
use Illuminate\Http\Request;

class GeorgianCard implements GeorgianCardHandler
{
  /**
   * Get primary transaction id
   * for recurrent transactions.
   * 
   * @param   Request $request
   * @return  string|null
   */
  public function getPrimaryTransactionId( Request $request )
  {
    // Return primary transaction id
  }

  /**
   * Determine if it should save card or pay
   * and proceed accordingly.
   * 
   * @param   Request  $request
   * 
   * @return  void
   */
  public function update( Request $request )
  {
    // Do things...
  }

  /**
   * Success method will be executed if
   * transaction is to end successfully.
   * 
   * @return mixed
   */
  public function success()
  {
    dump( 'Success' );
  }

  /**
   * Failed method will be executed if
   * transaction is to end with failure.
   * 
   * @return mixed
   */
  public function failure()
  {
    dump( 'Failure' );
  }
}
```

now bind it into our app service provider:
```php
namespace App\Providers;

use App\Library\GeorgianCard;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this -> app -> bind( 'redberry.georgian-card.handler', GeorgianCard :: class );
    }
```

## Now you can make simple regular transaction
---
```php
use Redberry\GeorgianCardGateway\Transaction;

$transaction = new Transaction;

$transaction 
      -> setOrderId   ( $orderId    )
      -> setAmount    ( $amount     ) // 100 = 1 lari
      -> setUserId    ( $userId     ) // optional
      -> setUserCardId( $userCardId ) // optional
      -> set( 'rame'  , 'rume' ) 
      -> execute();
```
all this fields that we set when making new instance of Transaction, will be available for us to see with bunch of other BOG info, when our GeorgianCard -> update() method will be executed. and in that moment you can save db records and so on...

## Making refund of the transaction
---
```php
use Redberry\GeorgianCardGateway\Refund;

$refund =  new Refund;

$refund
      -> setTrxId ( $trxId  )
      -> setRRN   ( $RRN    )
      -> setAmount( $amount )
      -> execute();
```

##### TrxId and RRN
TrxId and RRNs are transaction ids, which will be given to you in the GeorgianCard -> update method, you have to save it in DB and then use it to make refund of that transaction.