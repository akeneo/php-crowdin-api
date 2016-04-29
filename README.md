php-crowdin-api
===============

A simple PHP Crowdin API client http://crowdin.net/page/api.

Crowdin is a translation and localization management platform : http://crowdin.net/

FYI, an official and more complete Ruby Client exists here : https://github.com/crowdin/crowdin-api

[![Build Status](https://travis-ci.org/akeneo/php-crowdin-api.png)](https://travis-ci.org/akeneo/php-crowdin-api) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/akeneo/php-crowdin-api/badges/quality-score.png?s=6f2062a3c333671eb8112a79d3c5f6118f0ad496)](https://scrutinizer-ci.com/g/akeneo/php-crowdin-api/)

Features
--------

PSR-2 conventions and coding standard

Wrap following API methods :
* Add a file, delete a file
* Add a directory, delete a directory, change a directory
* Update File, Upload translations, Upload fresh version of your localization file
* Export Translations, Build fresh package with the latest translations.
* Download Translations, Download last exported translation package (one language or all languages as one zip file).
* Supported Languages, Get supported languages list with Crowdin codes mapped to locale name and standardized codes.
* Translation Status, Track overall translation and proofreading progress of each target language.
* Project Info, Shows project details and meta information.

Missing API methods https://github.com/akeneo/php-crowdin-api/issues?q=is%3Aopen+is%3Aissue+label%3Aapi-method

Requirements
------------

* PHP >= 5.5
* GuzzleHttp https://github.com/guzzle/guzzle

Optional, for dev purpose:

* PHP Spec https://github.com/phpspec/phpspec
* PHP-CS-Fixer https://github.com/FriendsOfPHP/PHP-CS-Fixer

How to use ?
------------

Add the following lines in your project composer.json :
```yaml
{
    "require": {
        "akeneo/crowdin-api": "1.0.0-alpha3"
    },
    "minimum-stability": "alpha"
}
```

Then, to instantiate the client and use available API methods :
```php
<?php
require 'vendor/autoload.php';

use Akeneo\Crowdin\Client;

$project = 'akeneo';
$key     = 'my-api-key';
$client  = new Client($project, $key);

// download last build package from Crowdin
$api = $client->api('download');
$api->setCopyDestination('/tmp/download-crowdin');
$api->setPackage('fr.zip');
$result = $api->execute();

// update a Crowdin file from local filesystem
$api = $client->api('update-file');
$localPath = '/home/nico/git/pim/src/Pim/Bundle/CatalogBundle/Resources/translations/messages.en.yml';
$crowdinPath = 'PimCommunity/CatalogBundle/Resources/translations/messages.en.yml';
$api->addTranslation($localPath, $crowdinPath);
$result = $api->execute();
```

You can take a look at following use cases to see more real life samples.

Use cases
---------

The Akeneo core team uses this library in Nelson, a command based translation workflow between Crowdin and Github (cf https://github.com/akeneo/nelson).

The Sylius core team uses this library in SyliusBot to manage community translations https://github.com/SyliusBot/SyliusBot.

If you use this library don't hesitate to open a PR to explain your use case here :)

Licence
-------

The MIT License (MIT)

Contribution
------------

Feel free to fork and propose PR to complete missing API methods https://github.com/akeneo/php-crowdin-api/issues?q=is%3Aopen+is%3Aissue+label%3Aapi-method

Any contributions are welcomed!
