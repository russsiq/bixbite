import Quill from 'quill';
let BlockEmbed = Quill.import('blots/block/embed');

class FigureBlot extends BlockEmbed {
    static create(value) {
        let node = super.create();

        node.setAttribute('contenteditable', false);
        // node.dataset.layout = value.layout;

        let img = document.createElement('img');
        img.classList.add('single_article_image__img');
        img.setAttribute('alt', value.caption);
        img.setAttribute('src', value.url);

        let picture = document.createElement('picture');
        picture.classList.add('single_article_image__inner');

        picture.appendChild(img);
        node.appendChild(picture);

        if (value.caption) {
            let caption = document.createElement('figcaption');
            caption.classList.add('single_article_image__caption');
            caption.innerHTML = value.caption;
            node.appendChild(caption);
        }

        return node;
    }

    /**
     * Set non-format related attributes with static values.
     * @param  {Object} node [description]
     * @return {Object}      [description]
     */
    static value(node) {
        let img = node.querySelector('img');

        return {
            // layout: node.dataset.layout,
            caption: img.getAttribute('alt'),
            url: img.getAttribute('src')
        };
    }

    // /**
    //  * We still need to report unregistered embed formats
    //  *
    //  * @param  {[type]} node [description]
    //  * @return {Object}      [description]
    //  */
    // static formats(node) {
    //     let format = {};
    //
    //     if (node.hasAttribute('height')) {
    //         format.height = node.getAttribute('height');
    //     }
    //
    //     if (node.hasAttribute('width')) {
    //         format.width = node.getAttribute('width');
    //     }
    //
    //     return format;
    // }

    // /**
    //  * Handle unregistered embed formats.
    //  * @param  {[type]} name  [description]
    //  * @param  {[type]} value [description]
    //  * @return {[type]}       [description]
    //  */
    // format(name, value) {
    //     if (name === 'height' || name === 'width') {
    //         if (value) {
    //             this.domNode.setAttribute(name, value);
    //         } else {
    //             this.domNode.removeAttribute(name, value);
    //         }
    //     } else {
    //         super.format(name, value);
    //     }
    // }
}

FigureBlot.blotName = 'figure-image';
FigureBlot.tagName = 'figure';
FigureBlot.className = 'single_article__image';

export default FigureBlot;


// <figure class="single_article__image">
// <picture class="single_article_image__inner">
// <source media="(max-width: 240px)" srcset="http://localhost/bixbite/uploads/image/default/thumb/tm-c_1549704008.jpeg" type="image/jpeg">
// <source media="(max-width: 576px)" srcset="http://localhost/bixbite/uploads/image/default/small/tm-c_1549704008.jpeg" type="image/jpeg">
// <source media="(max-width: 992px)" srcset="http://localhost/bixbite/uploads/image/default/medium/tm-c_1549704008.jpeg" type="image/jpeg">
// <img src="http://localhost/bixbite/uploads/image/default/tm-c_1549704008.jpeg" alt="Тренированная спортсменка" class="single_article_image__img">
// </picture>
// <figcaption class="single_article_image__caption">Тренированная спортсменка</figcaption>
// </figure>
