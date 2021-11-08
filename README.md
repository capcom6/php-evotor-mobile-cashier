# evotor-mobile-cashier

Evotor's MobileCashier REST API Client.

#### Installation

Recommended method: [Composer](http://getcomposer.org):

```
$ composer require softc/evotor-mobile-cashier
```

#### Usage

Initialize and create receipt:

```php
<?php

require 'vendor/autoload.php';

$client = new SoftC\Evotor\MobileCashier\Client(<UserId>);

$positions = [];    // instances of SoftC\Evotor\MobileCashier\Data\Position
$receipt = new SoftC\Evotor\MobileCashier\Data\Receipt('4e34ac31-ae28-4f52-be00-e6af9383343a', positions);

$response = $client->create($receipt);
var_dump($response);

```

#### License

See LICENSE file.