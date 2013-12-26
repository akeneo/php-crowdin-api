php-crowdin-api
===============

A simple PHP Crowdin API client

Crowdin is a translation and localization management platform that handles both documents and software projects.

As we use it to translate Akeneo PIM, our workflow is the following :
* push new english translations from Github repository to Crowdin
* retrieve others languages translations from Crowdin to push them to Github

NB 1, there is an existing Ruby Client here : https://github.com/crowdin/crowdin-api

NB 2, this bundle takes inspiration from https://github.com/KnpLabs/php-github-api

Features
--------

PSR-0 conventions and coding standard

Wrap following API methods http://crowdin.net/page/api :
* Update File, Upload fresh version of your localization file. Often used to reach continuous localization.
* Export Translations, Build fresh package with the latest translations.
* Download Translations, Download last exported translation package (one target language or all languages as one zip file).
* Supported Languages, Get supported languages list with Crowdin codes mapped to locale name and standardized codes.
* Translation Status, Track overall translation and proofreading progress of each target language.
* Project Info, Shows project details and meta information (last translations date, currently uploaded files, target languages etc..).

Not implemented
---------------

All options and parameters for previous methods are not implemented

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

Requirements
------------

* PHP >= 5.3
* Guzzle https://github.com/guzzle/guzzle
* PHP Spec https://github.com/phpspec/phpspec

Contribution
------------
Feel free to fork and propose PR to complete missing API methods.

