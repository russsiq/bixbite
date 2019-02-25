import getPluralIndex from 'zend-get-plural-index.js'

export default class Translator {
    constructor(url, locale) {
        const instance = this.constructor.instance

        if (instance) {
            return instance
        }

        this._url = url
        this._locale = locale || 'en'
        this._queueCount = 0
        this._translations = {}
        this._loaded = {
            files: []
        }

        // Load the common language file.
        this.loadFromJsonPath()

        this.constructor.instance = this
    }

    /**
     * Get the translation for the given key.
     *
     * @param  args
     * @return {mixed}
     */
    get(key, replace, locale) {
        replace = replace || []
        locale = locale || this._locale

        if (this._queueCount > 0) {
            setTimeout(() => {
                return this.get(key, replace, locale)
            }, 2000);
        }

        return this._translations[key] || key
    }

    /**
     * Determine if a translation exists.
     *
     * @param  {String}  key
     * @param  {String}  locale
     * @return {boolean}
     */
    has(key, locale) {
        locale = locale || this._locale

        if ('en' === locale || 'en_GB' === locale) {
            return true
        }

        return this.get(key) != key
    }

    /**
     * Get the translation for a given key.
     *
     * @param  {String}  key
     * @param  {Array}   replace
     * @param  {String}  locale
     * @return void
     */
    trans(key, replace, locale) {
        return this.get(key, replace, locale)
    }

    /**
     * Load the specified language group.
     *
     * @param  {String}  module
     * @return void
     */
    async loadFromJsonPath(module) {
        let fileName = module ? `${module}/${this._locale}.json` : `${this._locale}.json`

        if (!this._loaded.files.includes(fileName)) {

            this._queueCount++
            this._loaded.files.push(fileName)

            await axios
                .get(this._url + fileName)
                .then((response) => Object.assign(this._translations, response.data))
                .catch((error) => console.log(error))
                .then(() => this._queueCount--)
        }
    }

    /**
     * Get the index to use for pluralization.
     * The plural rules are derived from code of the Zend Framework.
     *
     * @param  {String}  locale
     * @param  {Number}  number
     * @return {Number}
     */
    getPluralIndex(locale, number) {
        return getPluralIndex(locale, number)
    }
}
