# kwaixiaodian-sdk

## Install

```
composer require asialong/kwaixiaodian-sdk -vvv
```

# Usage

```php
<?php

$dispatch = new \Asialong\KwaixiaodianSdk\Kwaixiaodian([
    'client_id' => 'your-app-key',
    'client_secret' => 'your-secret',
    'service_id' => 12345,
    'is_self_used' => true, //是否自用
    'debug' => true,
    'member_type' => 'MERCHANT',
    'redirect_uri' => 'http://www.xxx.com/callback',
    'log' => [
        'name' => 'kwaixiaodian',
        'file' => __DIR__ . '/kwaixiaodian.log',
        'level' => 'debug',
        'permission' => 0777,
    ],
]);



```