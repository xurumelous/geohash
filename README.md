# Geohash
[![Build Status](https://travis-ci.org/Eleme/geohash.png?branch=master)](https://travis-ci.org/Eleme/geohash)

php geohash encoder/decoder

### Install
Install Geohash with Composer.

```json
require: {
    "geohash/geohash": "1.0"
}
```

### Usage
Encode a coordinate:

```php
<?php
require "vendor/autoload.php";

use Geohash\Geohash;

echo Geohash::encode(31.283131, 121.500831); // wtw3uyfjqw61
```

Decode a Geohash:

```php
<?php
require "vendor/autoload.php";

use Geohash\Geohash;

list($lat, $lng) = Geohash::decode('wtw3uyfjqw61');
echo $lat, ', ', $lng; // 31.283131, 121.500831
```

Calculate adjacent geohash:

```php
<?php
require "vendor/autoload.php";

use Geohash\Geohash;

echo Geohash::calculateAdjacent('r3gx0w', Geohash::DIRECTION_RIGHT); // r3gx0y
```

Get neighboring geohashes:

```php
<?php
require "vendor/autoload.php";

use Geohash\Geohash;

echo Geohash::getNeighbors('r3gx0');
// array(
//     'r3gx2', // Top
//     'r3gx3', // Top Right
//     'r3gx1', // Right
//     'r3gwc', // Bottom Right
//     'r3gwb', // Bottom
//     'r3gqz', // Bottom Left
//     'r3grp', // Left
//     'r3grr', // Top Left
// );
```


