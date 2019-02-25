import Quill from 'quill';

/**
 * Image upload handler.
 */
const figureHandler = async function(quill, {
    upload_url,
    attachment_id,
    attachment_type
}) {

    if (!attachment_id || !attachment_type) {
        Notification.warning({
            message: langProvider.trans('Before you can upload files, you must save the article.')
        })

        return false
    }

    const input = document.createElement('input')

    input.setAttribute('type', 'file')
    input.setAttribute('accept', 'image/*')
    input.classList.add('ql-image')
    input.click()

    input.addEventListener('change', async () => {
        // Save current cursor position.
        let range = quill.getSelection(true)

        try {
            if (!input.files || !input.files.length) {
                throw new Error(langProvider.trans('No files selected.'))
            }

            const formData = new FormData()
            formData.append('file', input.files[0])
            formData.append('attachment_id', attachment_id)
            formData.append('attachment_type', attachment_type)
            
            const response = await axios.post(upload_url, formData);

            if (!response.data.file) {
                throw new Error(response.data.message);
            }

            // Insert uploaded image.
            quill.insertEmbed(range.index, 'figure-image', {
                url: response.data.file.url,
                caption: response.data.file.title,
            }, Quill.sources.USER)

            // Move the cursor below the image.
            quill.setSelection(range.index + 1, Quill.sources.SILENT)

            input.value = '';
        } catch (error) {

            console.log(error)

            Notification.error({
                message: error.message
            })
        }
    })
}

export default figureHandler
