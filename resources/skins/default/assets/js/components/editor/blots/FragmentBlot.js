import Quill from 'quill';

const BlockEmbed = Quill.import('blots/block/embed');

/**
 * [FragmentBlot description]
 *
 * @extends BlockEmbed
 */
class FragmentBlot extends BlockEmbed {
    static create(value) {
        const node = super.create();

        node.setAttribute('contenteditable', false);

        node.innerHTML = value.content;

        return node;
    }

    static value(node) {
        return {
            content: node.innerHTML
        };
    }
}

FragmentBlot.blotName = 'html-fragment';
FragmentBlot.tagName = 'div';
FragmentBlot.className = 'ql-fragment';

export default FragmentBlot;
