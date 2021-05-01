import Quill from 'quill';

import Attachment from '@/store/models/attachment';

/**
 * Image upload handler.
 */
const figureHandler = async function(quill, attachable) {

    if (!attachable.id || !attachable.type) {
        Notification.warning({
            message: __('Before you can upload attachments, you must save the article.')
        });

        return false;
    }

    const input = document.createElement('input');

    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.classList.add('ql-image');
    input.click();

    input.addEventListener('change', async () => {
        try {
            // Save current cursor position.
            const range = quill.getSelection(true);

            if (!input.files || !input.files.length) {
                throw new Error(__('No files selected.'));
            }

            const formData = new FormData();
            formData.append('file', input.files[0]);
            formData.append('attachable_id', attachable.id);
            formData.append('attachable_type', attachable.type);

            const image = await Attachment.$create({
                data: formData
            });

            // Вставляем загруженное изображение.
            quill.insertEmbed(range.index, 'figure-image', {
                id: image.id,
                url: image.url,
                caption: image.title,
            }, Quill.sources.USER);

            // Перемещаем курсор ниже изображения.
            // Еще бы обавить крутилку экрана до курсора.
            quill.setSelection(range.index + 1);

            input.value = '';
            input.remove && input.remove();
        } catch (error) {
            !error.response && console.log(error);
        }
    });
};

export default figureHandler;
