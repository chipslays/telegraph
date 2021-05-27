# 📝 Telegra.ph

Interaction with [Telegra.ph](https://telegra.ph/).

## Installation
```bash
$ composer require chipslays/telegraph
```

## Usage

### Telegraph API

```php
use Chipslays\Telegraph\Client;

require __DIR__ . '/vendor/autoload.php';

$client = new Client;

/** create new account */
$account = $client->createAccount('chipslays');
$token = $account->getAccessToken(); // store this token in safe place for reuse

/** create new page */
$page = $client->createPage($token, 'New page', 'Hello world!');

print_r($page->toArray());
```

### Upload files

```php
use Chipslays\Telegraph\File;

require __DIR__ . '/vendor/autoload.php';

$links = File::upload('video.mp4');
$links = File::upload('nudes.jpg');
$links = File::upload(['video.mp4', 'nudes.jpg']);

// helper function
$links = upload_files('video.mp4');
$links = upload_files(['video.mp4', 'image.png']);

print_r($links);

// Array
// (
//     [0] => https://telegra.ph/file/xxxxxxxxxx.mp4
//     [1] => https://telegra.ph/file/xxxxxxxxxx.png
// )
```

## Examples

See examples [here](/examples).

## Methods

See all available methods [here](/src/Traits/Methods.php).

See all predefined elements [here](/src/Types/Elements/Element.php).

## Credits

- [Chipslays](https://github.com/chipslays)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.