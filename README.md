# 📝 Telegra.ph

Interaction with Telegra.ph.

## Installation
```bash
$ composer require chipslays/telegraph
```

## Methods

### Upload Image

Upload image to Telegra.ph:

```
Telegraph::upload(string|array $images) : array
```

Upload single image:

```php
use Chipslays\Telegraph\Telegraph;

require __DIR__ . '/vendor/autoload.php';

$response = Telegraph::upload('potato.png');

print_r($response);

Array
(
    [0] => https://telegra.ph/file/5a4277524d8e6da5c186c.png
)
```

Upload multiple images:

```php
use Chipslays\Telegraph\Telegraph;

require __DIR__ . '/vendor/autoload.php';

$response = Telegraph::upload([
    'potato.png', 
    'chips.jpg', 
    'dickpic.png',
]);

print_r($response);

Array
(
    [0] => https://telegra.ph/file/5a4277524d8e6da5c186c.png
    [1] => https://telegra.ph/file/dc123c67d2b244727c1f7.png
    [2] => https://telegra.ph/file/8ae2555d4dc97883f423d.png
)
```

Short alias `upload_image` function.

```php
$response = upload_image('potato.png');
$response = upload_image(['potato.png', 'chips.jpg']);
```
