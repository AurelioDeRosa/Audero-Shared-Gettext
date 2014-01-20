<?php
namespace Audero\SharedGettext;

/**
 * Audero Shared Gettext allows you to bypass the problem of the translations, loaded via the gettext function, that
 * are cached by Apache. In fact, once a translation is loaded Apache caches it, so unless you can restart the engine
 * any update to a translation file won't be seen. This is particularly annoying if you work on a shared hosting where
 * you don't have administrator permissions.
 *
 * PHP version 5.3
 *
 * WARRANTY: The software is provided "as is", without warranty of any kind,
 * express or implied, including but not limited to the warranties of
 * merchantability, fitness for a particular purpose and noninfringement.
 * In no event shall the authors or copyright holders be liable for any claim,
 * damages or other liability, whether in an action of contract, tort or otherwise,
 * arising from, out of or in connection with the software or the use or
 * other dealings in the software.
 *
 * @author  Aurelio De Rosa <aurelioderosa@gmail.com>
 * @license http://creativecommons.org/licenses/by-nc/4.0/ CC BY-NC 4.0
 *
 */
class SharedGettext
{
    /**
     * The extension of the translation's binary file
     */
    const FILE_EXTENSION = '.mo';

    /**
     * The path where the translations are stored
     *
     * @var string
     */
    private $translationsPath;

    /**
     * The language into which to translate
     *
     * @var string
     */
    private $language;

    /**
     * The name of the translation file (referred as domain in gettext)
     *
     * @var string
     */
    private $domain;

    /**
     * The default constructor
     *
     * @param string $languagesPath The path where the translations are stored
     * @param string $language      The language into which to translate
     * @param string $domain        The name of the translation file (referred as domain in gettext)
     */
    function __construct($languagesPath, $language, $domain)
    {
        $this->translationsPath = $languagesPath;
        $this->language = $language;
        $this->domain = $domain;
    }

    /**
     * Retrieves the path where the translations are stored
     *
     * @return string
     */
    public function getTranslationsPath()
    {
        return $this->translationsPath;
    }

    /**
     * Sets the path where the translations are stored
     *
     * @param string $languagesPath The path where the translations are stored
     *
     * @return SharedGettext
     */
    public function setTranslationsPath($languagesPath)
    {
        $this->translationsPath = $languagesPath;

        return $this;
    }

    /**
     * Retrieves the language into which to translate
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Sets the language into which to translate
     *
     * @param string $language The language into which to translate
     *
     * @return SharedGettext
     *
     * @throws \InvalidArgumentException
     */
    public function setLanguage($language)
    {
        if (!self::isLanguageCodeValid($language)) {
            throw new \InvalidArgumentException("The given language don't comply with the ISO 3166 standard");
        }
        $this->language = $language;

        return $this;
    }

    /**
     * Retrieves the name of the translation file (referred as domain in gettext)
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Sets the domain of the name of the translation file (referred as domain in gettext)
     *
     * @param string $domain The name of the translation file (referred as domain in gettext)
     *
     * @return SharedGettext
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Returns the path to the translation file of the chosen language
     *
     * @return string
     */
    public function getTranslationPath()
    {
        return $this->translationsPath . DIRECTORY_SEPARATOR .
               $this->language . DIRECTORY_SEPARATOR .
               'LC_MESSAGES' . DIRECTORY_SEPARATOR .
               $this->domain . self::FILE_EXTENSION;
    }

    /**
     * Check if the given language code complies with the ISO 3166 standard
     *
     * @param string $language The language code to test
     *
     * @return bool
     */
    public static function isLanguageCodeValid($language)
    {
        return preg_match('/^[a-z]{2}(_[A-Z]{2})?$/', $language) > 0;
    }

    /**
     * Check if the translation's file for the chosen language exists
     *
     * @return bool
     */
    public function translationExists()
    {
        return file_exists($this->getTranslationPath());
    }

    /**
     * Create a mirror copy of the translation file
     *
     * @return string The name of the created translation file (referred as domain in gettext)
     *
     * @throws \Exception If the translation's file cannot be found
     */
    public function updateTranslation()
    {
        if (!self::translationExists()) {
            throw new \Exception('The translation file cannot be found in the given path.');
        }
        $originalTranslationPath = $this->getTranslationPath();
        $lastAccess = filemtime($originalTranslationPath);
        $newTranslationPath = str_replace(self::FILE_EXTENSION, $lastAccess . self::FILE_EXTENSION, $originalTranslationPath);

        if(!file_exists($newTranslationPath)) {
            copy($originalTranslationPath, $newTranslationPath);
        }

        return $this->domain . $lastAccess;
    }

    /**
     * Remove all the mirror copies but the last from the folder of the chosen translation
     *
     * @return SharedGettext
     */
    public function deleteOldTranslations()
    {
        $translations = glob(str_replace(self::FILE_EXTENSION, '[0-9]*' . self::FILE_EXTENSION, $this->getTranslationPath()));
        // Sort translations based on their name (which in turn sort them by the date of creation)
        sort($translations);
        // Remove the most up-to-date translation from the array because it won't be deleted
        array_pop($translations);
        foreach($translations as $translation) {
            unlink($translation);
        }

        return $this;
    }
}