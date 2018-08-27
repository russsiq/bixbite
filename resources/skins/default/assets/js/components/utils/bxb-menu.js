export default class BxbMenu {
    constructor(
        top = '0px',
        left = '0px',
        show = 'none'
    ) {
        this.top = top
        this.left = left
        this.show = show
    }
    
    set(ref, event) {
        let top = event.y
        let left = event.x
        /*let largestHeight = window.innerHeight - ref.offsetHeight - 25;
        let largestWidth = window.innerWidth - ref.offsetWidth - 25;

        if (top > largestHeight) top = largestHeight;
        if (left > largestWidth) left = largestWidth;*/
        
        this.top = top + 'px';
        this.left = left + 'px';
    }
    
    open(ref, event) {
        this.show = 'block'
        ref.focus()
        this.set(ref, event)
    }
    
    close() {
        this.show = 'none';
    }
    
    toggle(event) {
        if (event) event.preventDefault()
        this.block = 'block' == this.block ? 'none' : 'block'
        this.none = 'none' == this.block ? 'block' : 'none'
    }
}