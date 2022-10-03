<p align="center">
    <img src="https://i.ibb.co/HCwSsKx/purwantara-laravel-1.png" alt="purwantara-laravel">
</p>

[![CodeFactor](https://www.codefactor.io/repository/github/ezhasyafaat/purwantara-laravel/badge)](https://www.codefactor.io/repository/github/ezhasyafaat/purwantara-laravel)

# PURWANTARA LARAVEL

<h3 id="purwantara">✨ What is Purwantara?</h3> 
Purwantara is a digital payment service provider that helps businesses to accept digital payments with seamless and secure. Some of the payment services provided by Purwantara are Virtual Account, E Wallet, Credit Card and QRIS.

<h3 id="install">⚙️ How to install</h3>

```bash
composer require ezhasyafaat/purwantara-laravel
```

<h3 id="setup-configuration">🛠 Setup configuration</h3>

```bash
php artisan vendor:publish --provider="Ezhasyafaat\PurwantaraPayment\PurwantaraServiceProvider" --tag="config"
```

And you have to provide bearer token in .env file. You can get bearer token at purwantara.id
```shell
PURWANTARA_TOKEN="BEARER_TOKEN_FROM_PURWANTARA"
PURWANTARA_MODE="SANDBOX" #You can fill this with "SANDBOX" for sandbox mode or "PRODUCTION" for production mode
```

<h3 id="virtual-account">💈 Virtual account</h3>
Virtual accounts is a medium for receiving payments that customer pay.

- EXAMPLE CREATE VIRTUAL ACCOUNT
```php
use Illuminate\Support\Str;
use Ezhasyafaat\PurwantaraPayment\Purwantara;

public function create_va()
{
    $input = [
        'display_name'      => 'John Doe', // Merchant store name
        'channel_name'      => 'BRI', //Payment channel at purwantara.id
        'order_id_merchant' => Str::uuid(), //Unique id from merchant, customize by merchant
        'expected_amount'   => 10000, //Amount of virtual account
        'description'       => 'Testing create virtual account' //Description of virtual account
    ];
    
    $purwantara = new Purwantara;
    $response = $purwantara->create_virtual_account($input)
}
```

- EXAMPLE CANCEL VIRTUAL ACCOUNT
```php
use App\Models\Transaction;
use Ezhasyafaat\PurwantaraPayment\Purwantara;

public function cancel_va()
{
    $transaction    = Transaction::find(20);
    $input = [
        'purwantara_uuid' => $transaction->purwantara_uuid
    ];

    $purwantara = new Purwantara;
    $response = $purwantara->cancel_virtual_account($input);
}
```

- EXAMPLE INQUIRY VIRTUAL ACCOUNT
```php
use App\Models\Transaction;
use Ezhasyafaat\PurwantaraPayment\Purwantara;

public function inquiry_va()
{
    $transaction    = Transaction::find(20);
    $input = [
        'purwantara_uuid' => $transaction->purwantara_uuid
    ];

    $purwantara = new Purwantara;
    $response = $purwantara->inquiry_virtual_account($input);
}
```

<h3 id="virtual-account">🧸 QRIS</h3>
Create QRIS serves as a means of payment for client.

- EXAMPLE CREATE QRIS
```php
use Illuminate\Support\Str;
use Ezhasyafaat\PurwantaraPayment\Purwantara;

public function create_qris()
{
    $input = [
        'amount'                => 10000, //Amount of qris
        'customer_email'        => 'johndoe@apps.com', //Customer email
        'customer_first_name'   => 'John', //Customer first name
        'customer_last_name'    => 'Doe', //Customer last name
        'customer_phone'        => '0812345678910', //Customer phone number
        'description'           => 'Testing create qris', //Description of qris
        'channel_name'          => 'shopeepay', //Payment channel at purwantara.id
        'order_id_merchant'     => Str::uuid()
    ];

    $purwantara = new Purwantara;
    $response = $purwantara->create_qris($input);
}
```

- EXAMPLE INQUIRY QRIS
```php
use App\Models\Transaction;
use Ezhasyafaat\PurwantaraPayment\Purwantara;

public function inquiry_qris()
{
    $transaction    = Transaction::find(20);
    $input = [
        'purwantara_uuid' => $transaction->purwantara_uuid
    ];

    $purwantara = new Purwantara;
    $response = $purwantara->inquiry_qris($input);
}
```
