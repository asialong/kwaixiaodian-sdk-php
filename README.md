# jinritemai-sdk

## Install

```
composer require asialong/jinritemai-sdk -vvv
```

# Usage

```php
<?php

$dispatch = new \Asialong\KwaixiaodianSdk\Doudian([
    'client_id' => 'your-app-key',
    'client_secret' => 'your-secret',
    'service_id' => 12345,
    'is_self_used' => true, //是否自用
    'debug' => true,
    'member_type' => 'MERCHANT',
    'redirect_uri' => 'http://www.xxx.com/callback',
    'log' => [
        'name' => 'doudian',
        'file' => __DIR__ . '/doudian.log',
        'level' => 'debug',
        'permission' => 0777,
    ],
]);



```