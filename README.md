php-crowdin-api
===============

A simple PHP Crowdin API client http://crowdin.net/page/api.

Crowdin is a translation and localization management platform : http://crowdin.net/

To translate Akeneo PIM, our workflow is the following :
* push our new english translations from our Github repository to Crowdin
* build and download others languages translations from Crowdin to push them to our Github repository

The architecture is inspired from https://github.com/KnpLabs/php-github-api/ a really complementary library to achieve our translation workflow.

There is an official Ruby Client here : https://github.com/crowdin/crowdin-api

Features
--------

PSR-2 conventions and coding standard

Wrap following API methods :
* Update File, Upload fresh version of your localization file.
* Export Translations, Build fresh package with the latest translations.
* Download Translations, Download last exported translation package (one language or all languages as one zip file).
* Supported Languages, Get supported languages list with Crowdin codes mapped to locale name and standardized codes.
* Translation Status, Track overall translation and proofreading progress of each target language.
* Project Info, Shows project details and meta information.

Requirements
------------

* PHP >= 5.3.3
* Guzzle https://github.com/guzzle/guzzle
* PHP Spec https://github.com/phpspec/phpspec

How to use ?
------------

Add the following lines in your project composer.json :
```yaml
{
    "require": {
        "akeneo/crowdin-api": "*"
    },
    "minimum-stability": "dev"
}
```

Then,
```php
require 'vendor/autoload.php';

use Crowdin\Client;

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
$source = '/tmp/update-crowdin';
$file   = 'src/Pim/Bundle/CatalogBundle/Resources/translations/messages.en.yml';
$api->addFile($file, $source.'/'.$file);
$result = $api->execute();
echo $result

```

Licence
-------

The MIT License (MIT)

Contribution
------------

Feel free to fork and propose PR to complete missing API methods, any contributions are welcomed !

Not implemented
---------------

All options and parameters for previous methods are not implemented.

Following API methods :
* Add File, Add new file to Crowdin project that should be translated.
* Delete File, Remove file from Crowdin project.
* Create Directory, Create a new directory in Crowdin project.
* Remove Directory, Remove directory with nested files from Crowdin project.
* Change Directory, Rename or change directory attributes.
* Upload Translations, Upload translations made in a third party software or previously made translations.
* Create Project, Create a new Crowdin Project.
* Edit Project, Edit Crowdin project details.
* Delete Project, Delete Crowdin project with all files, translations and other meta-data.
* Download TM, Download Translation Memory created by Crowdin for your project.
* Upload TM, Upload Translation Memory (will be merged with TM in Crowdin).
* Download Glossary, Download Glossaries created by users for your project.
* Upload Glossary, Upload your glossary to Crowdin project (imported terms will be merged with already existing)
* Account Projects, List account projects with details (including API keys)
