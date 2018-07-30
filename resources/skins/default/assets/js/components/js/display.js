export default class Display {
    constructor(block = 'block', none = 'none') {
        this.block = 'block'
        this.none = 'none'
    }
    
    toggle(event) {
        if (event) event.preventDefault()
        this.block = 'block' == this.block ? 'none' : 'block'
        this.none = 'none' == this.block ? 'block' : 'none'
    }
}