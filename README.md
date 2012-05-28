# myna.php — PHP client library for Myna

**myna.php** is a PHP client for the [Myna](http://mynaweb.com) split testing service.

## Usage

```php
require_once 'myna.php';

$response = myna_suggest('your-experiment-id') or print(myna_problem());
$token = $response['token'];
$variant = $response['variant'];

// ...use variant to choose what to display and put token somewhere so that it can be used when the user performs a positive action.

// When a positive action occurs...

require_once 'myna.php';
myna_reward('your-experiment-id', $token) or print(myna_problem());
```

## License

This code is public domain. In countries that do not recognize public domain declarations, this code is licensed under the terms of [CC0](http://creativecommons.org/publicdomain/zero/1.0/).

