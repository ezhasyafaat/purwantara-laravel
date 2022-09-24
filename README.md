# PURWANTARA LARAVEL

<h3 id="purwantara">✨ WHAT IS PURWANTARA?</h3> 
Purwantara is a digital payment service provider that helps businesses to accept digital payments with seamless and secure. Some of the payment services provided by Purwantara are Virtual Account, E Wallet, Credit Card and QRIS.

<h3 id="virtual-account">💳 Virtual account<h3>
Virtual accounts is a medium for receiving payments that customer pay.

- How to create Virtual Account?
```php
use Ezhasyafaat\PurwantaraLaravel\Purwantara;
use Illuminate\Support\Str;

public function create_va()
{
    $input = [
        'display_name'      => 'John Doe', // Merchant store name
        'channel_name'      => 'BRI', //Payment channel at purwantara.id
        'order_id_merchant' => Str::uuid(), //Unique id from merchant, customize by merchant
        'expected_amount'   => 10000, //Amount of virtual account
        'description'       => 'Testing create virtual account' //Description of virtual account
    ];

    $response = Purwantara::create_virtual_account($input);
}
```