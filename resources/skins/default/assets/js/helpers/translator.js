export default class Translator {
    constructor(url, locale) {
        let instance = this.constructor.instance

        if (instance) {
            return instance
        }

        this._url = url
        this._locale = locale
        this._state = false
        this._translations = {}
        this._loaded = {
            files: []
        }

        // Load the common language file.
        this.loadFromJsonPath()

        this.constructor.instance = this
    }

    async loadFromJsonPath(module) {
        let fileName = module ? `${module}/${this._locale}.json` : `${this._locale}.json`

        if (this._loaded.files.includes(fileName)) {
            return
        }

        this._state = true
        this._loaded.files.push(fileName)

        await axios
            .get(this._url + fileName)
            .then((response) => {
                Object.assign(this._translations, response.data)
                this._state = false
            })
    }

    trans(key) {
        while (this._state == false) {
            return this._translations[key] || key
        }
    }
}
