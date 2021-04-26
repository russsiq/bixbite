import Quill from 'quill';

import FragmentBlot from '../blots/FragmentBlot.js';

/**
 * Table handler.
 */
export default function(quill, {
    blot,
    parameters
}) {
    if (blot instanceof FragmentBlot) {
        blot.replaceWith(FragmentBlot.blotName, parameters);
    } else {
        const range = quill.getSelection(true);

        quill.insertEmbed(range.index, FragmentBlot.blotName, parameters, Quill.sources.USER);
        quill.setSelection(range.index + 1);
    }
}
