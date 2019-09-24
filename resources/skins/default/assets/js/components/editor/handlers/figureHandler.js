import Quill from 'quill';

import File from '@/store/models/file';

/**
 * Image upload handler.
 */
const figureHandler = async function(quill, attachment) {

    if (!attachment.id || !attachment.type) {
        Notification.warning({
            message: __('Before you can upload files, you must save the article.')
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
            formData.append('attachment_id', attachment.id);
            formData.append('attachment_type', attachment.type);

            const image = await File.$create({
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
