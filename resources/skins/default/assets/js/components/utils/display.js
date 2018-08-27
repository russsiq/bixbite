export default class Display {
    constructor(block = 'block', none = 'none') {
        this.block = 'block'
        this.none = 'none'
    }
    
    toggle(event) {
        this.block = 'block' == this.block ? 'none' : 'block'
        this.none = 'none' == this.block ? 'block' : 'none'
        event.preventDefault()
    }
}