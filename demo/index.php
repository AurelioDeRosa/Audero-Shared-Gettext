<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Audero Shared Gettext Demo by Aurelio De Rosa</title>
        <style>
            body
            {
                max-width: 500px;
                margin: 2em auto;
                font-size: 20px;
            }
        </style>
    </head>
    <body>
        <?php
            // Update include path
            set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../src');
            require_once 'Audero\SharedGettext\SharedGettext.php';

            $translationsPath = 'languages';
            $language = 'it_IT';
            $domain = 'audero';

            putenv('LC_ALL=' . $language);
            setlocale(LC_ALL, $language);

            try {
                $sharedGettext = new Audero\SharedGettext\SharedGettext($translationsPath, $language, $domain);
                $newDomain = $sharedGettext->updateTranslation();

                // Sets the path for the current domain
                bindtextdomain($newDomain, $translationsPath);
                // Specifies the character encoding
                bind_textdomain_codeset($newDomain, 'UTF-8');

                // Choose domain
                textdomain($newDomain);
            } catch(\Exception $ex) {
                echo $ex->getMessage();
                exit;
            }
        ?>
        <h1>Audero Shared Gettext</h1>
        <p>
            <?php echo _('SALUTATION_PARAGRAPH'); ?>
        </p>
        <p>
            <?php echo _('DEMO_PARAGRAPH'); ?>
        </p>

        <small class="author">
            <?php echo _('Created by'); ?> <a href="http://www.audero.it">Aurelio De Rosa</a>
            (<a href="https://twitter.com/AurelioDeRosa">@AurelioDeRosa</a>)
        </small>
    </body>
</html>