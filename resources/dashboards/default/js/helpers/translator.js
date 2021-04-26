import axios from 'axios'
import getPluralIndex from 'zend-get-plural-index.js'

export default class Translator {
    /**
     * Create a new Translator instance.
     *
     * @param  {string}  url
     * @param  {string}  locale
     * @return {void}
     */
    constructor({url, locale}) {
        const instance = this.constructor.instance

        if (instance) {
            return instance
        }

        // Prepare.
        this._url = url
        this._queueCount = 0
        this._loadedFiles = []
        this._translations = []

        // In the beginning, we determine the reserve locale.
        this._setFallback('en')

        // Set current locale.
        this.setLocale(locale)

        this.addLocale(locale)

        // If the current locale is not English,
        // then load the common language file.
        if (this.locale() != this._getFallback()) {
            this.loadFromJsonPath()
        }

        this.constructor.instance = this
    }

    /**
     * Get the translation for the given key.
     *
     * @param  {string}  key
     * @param  {object}  replace
     * @param  {string}  locale
     * @return {mixed}
     */
    get(key, replace, locale) {
        // Temporarily, until there is a substring replacement handler.
        if (this._getFallback() == locale) {
            return key
        }

        if (this._queueCount > 0) {
            setTimeout(() => {
                return this.get(key, replace, locale)
            }, 1000);
        }

        replace = replace || []
        locale = this.checkLocale(locale)

        return this._translations[locale][key] || key
    }

    /**
     * Determine if a translation exists.
     *
     * @param  {string}  key
     * @param  {string}  locale
     * @return {boolean}
     */
    has(key, locale) {
        if (['en', 'en_GB'].indexOf(locale) > -1) {
            return true
        }

        return key != this.get(key, locale)
    }

    /**
     * Get the translation for a given key.
     *
     * @param  {string}  key
     * @param  {array}   replace
     * @param  {string}  locale
     * @return {string}
     */
    trans(key, replace, locale) {
        return this.get(key, replace, locale)
    }

    /**
     * Get a translation according to an integer value.
     *
     * @param  {string}  key
     * @param  {number}  number
     * @param  {array}   replace
     * @param  {string}  locale
     * @return {string}
     */
    choice(key, number, replace, locale) {
        // Return choice logic.
    }

    /**
     * Get the index to use for pluralization.
     *
     * @param  {string}  locale
     * @param  {number}  number
     * @return {number}
     */
    getPluralIndex(locale, number) {
        return getPluralIndex(locale, number)
    }

    /**
     * Get the default locale being used.
     *
     * @return {string}
     */
    locale() {
        return this.getLocale()
    }

    /**
     * Get the default locale being used.
     *
     * @return {string}
     */
    getLocale() {
        return this._locale
    }

    /**
     * Set the default locale.
     *
     * @param  {string}  locale
     * @return {void}
     */
    setLocale(locale) {
        if ('string' != typeof locale) {
            locale = this._getFallback()
            console.error('Could not set a locale to Translator.')
        }

        this._locale = locale
    }

    /**
     * Add locale if non exists.
     *
     * @param  {string}  locale
     * @return {void}
     */
    addLocale(locale) {
        if ('string' != typeof locale) {
            console.error('Could not add a locale to Translator.')
        } else if (!this._translations.includes(locale)) {
            this._translations.push(locale)
            this._translations[locale] = {}
        }

        // this._translations.forEach(function(locale, index, array) {
        //     console.log(locale, index);
        // });
    }

    /**
     * Check a given locale and return actual locale.
     *
     * @param  {string}  locale
     * @return {string}
     */
    checkLocale(locale) {
        if ('string' != typeof locale) {
            locale = this.locale() || this._getFallback()
        }

        return locale
    }

    /**
     * Load the specified language group.
     *
     * @param  {string}  module
     * @param  {string}  locale
     * @return {void}
     */
    async loadFromJsonPath(module, locale) {
        locale = this.checkLocale(locale)
        let fileName = this._fileName(module, locale)

        try {
            if (this._isLoaded(fileName)) {
                if ('production' == process.env.NODE_ENV) {
                    return 1;
                } else {
                    throw new Error(`File ${fileName} is already loaded.`)
                }
            }

            // Add locale if non exists.
            this.addLocale(locale)

            this._queueCount++
            this._loadedFiles.push(fileName)

            await axios.get(fileName)
                .then((response) => {
                    Object.assign(this._translations[locale], response.data)
                })
                .catch((error) => {
                    console.log(error)
                })
                .then(() => {
                    this._queueCount--
                })
        } catch (error) {
            console.log(`${error.name}: ${error.message}`)
        }
    }

    /**
     * Get a file name.
     *
     * @param  {string}  module
     * @param  {string}  locale
     * @return {string}
     */
    _fileName(module, locale) {
        return this._url + (module ? `${module}/${locale}.json` : `${locale}.json`)
    }

    /**
     * Determine if the given file has been loaded.
     *
     * @param  {string}  file
     * @return {bool}
     */
    _isLoaded(fileName) {
        return this._loadedFiles.includes(fileName)
    }

    /**
     * Get the fallback locale being used.
     *
     * @return {string}
     */
    _getFallback() {
        return this._fallback
    }

    /**
     * Set the fallback locale being used.
     *
     * @param  {string}  fallback
     * @return {void}
     */
    _setFallback(fallback) {
        this._fallback = fallback
    }
}
