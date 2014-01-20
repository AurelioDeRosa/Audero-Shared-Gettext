# Audero Shared Gettext #
[Audero Shared Gettext](https://github.com/AurelioDeRosa/Audero-Shared-Gettext) is a PHP library that allows you to
bypass the problem of the translations, loaded via the gettext function, that are cached by Apache. In fact, once a
translation is loaded Apache caches it, so unless you can restart the engine any update to a translation file won't
be seen. This is particularly annoying if you work on a shared hosting where you don't have administrator permissions.

## Requirements ##
This library requires PHP version 5.3 or higher.

## Download ##
### Download via [Composer](http://getcomposer.org/) ###
You can obtain "Audero Shared Gettext" via [Composer](http://getcomposer.org/) adding the following lines to your
`composer.json`:

    "require": {
        "audero/audero-shared-gettext": "1.0.*"
    }

And then run the `install` command to resolve and download the dependencies:

    php composer.phar install

Composer will install the library to your project's `vendor/audero` directory.

### Download via [Git](http://git-scm.com/) ###
If you haven't or don't want to use [Composer](http://getcomposer.org/), you can download the library from its
[repository](https://github.com/AurelioDeRosa/Audero-Shared-Gettext) via [Git](http://git-scm.com/) running the
following command:

    git clone https://github.com/AurelioDeRosa/Audero-Shared-Gettext.git

## Usage ##
[Audero Shared Gettext](https://github.com/AurelioDeRosa/Audero-Shared-Gettext) is very easy to use. However, since
the library uses namespaces and follows the [PSR standards](https://github.com/php-fig/fig-standards), you've to use an
autoloader to dynamically load the classes needed. After that, you have to create an `SharedGettext` instance and
call the method you need.

### Installed via [Composer](http://getcomposer.org/) ###
If you installed the library using [Composer](http://getcomposer.org/), you can rely on the its autoloader. So,
after included the latter, you can use one of the previously cited methods as shown in the following example.

#### Bypass the cache problem (main usage) ####

    <?php
        // Include the Composer autoloader
        require_once 'vendor/autoload.php';

        $translationsPath = 'languages';
        $language = 'it_IT';
        $domain = 'audero';

        putenv('LC_ALL=' . $language);
        setlocale(LC_ALL, $language);

        try {
           $sharedGettext = new Audero\SharedGettext\SharedGettext($translationsPath, $language, $domain);
           // Create the mirror copy of the translation and return the new domain
           $newDomain = $sharedGettext->updateTranslation();

           // Sets the path for the current domain
           bindtextdomain($newDomain, $translationsPath);
           // Specifies the character encoding
           bind_textdomain_codeset($newDomain, 'UTF-8');

           // Choose domain
           textdomain($newDomain);
        } catch(\Exception $ex) {
           echo $ex->getMessage();
        }
    ?>

### Installed via [Git](http://git-scm.com/) ###
If you obtained the code via [Git](http://git-scm.com/), you have to load the library by your own. Before using it,
you've to add the path to the library to the PHP include path as shown in the following example.

#### Delete old translations ####
    <?php
        // Update include path
        set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../src');
        require_once 'Audero\SharedGettext\SharedGettext.php';

        $translationsPath = 'languages';
        $language = 'it_IT';
        $domain = 'audero';

        try {
           $sharedGettext = new Audero\SharedGettext\SharedGettext($translationsPath, $language, $domain);
           // Delete old translations for the current language
           $sharedGettext->deleteOldTranslations();
        } catch(\Exception $ex) {
           echo $ex->getMessage();
        }
    ?>

## License ##
[Audero Shared Gettext](https://github.com/AurelioDeRosa/Audero-Shared-Gettext) is licensed under the
[CC BY-NC 4.0](http://creativecommons.org/licenses/by-nc/4.0/) ("Creative Commons Attribution NonCommercial 4.0")

## Authors ##
[Aurelio De Rosa](http://www.audero.it) (Twitter: [@AurelioDeRosa](https://twitter.com/AurelioDeRosa))