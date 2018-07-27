<template>
    <label class="image-uploader">
        <div v-if="'uploaded' == state" class="image-uploader-success">
            <img :src="url" alt="image" />
            <input type="hidden" :name="input_name" :value="image_id" v-if="image_id > 0" />
        </div>
        <div v-else class="image-uploader-variant d-flex justify-content-center align-items-center">
            <div v-if="'uploading' == state" class="text-primary"><i class="fa fa-4x fa-spinner fa-spin"></i></div>
            <div v-else-if="'error' == state" class="text-danger"><i class="fa fa-4x fa-exclamation-triangle"></i></div>
            <div v-else class="image-uploader-wait"><i class="fa fa-4x fa-file-image-o"></i></div>
        </div>
        
        <input type="file" name="file" accept="image/*" class="image-uploader-file" ref="image" @change="uploadImage">
    </label>
</template>

<script>
    export default {
        props: {
            input_name: String,
            focusable: String,
            post_url: String,
            url: {String, required: false},
            state: {String, required: false}
        },
        data(){
            return {
                //url: '',
                //state: null,
                image_id: 0
            }
        },
        methods: {
            async uploadImage(){
                try {
                    let files = this.$refs.image.files;
                    if (! files.length) {
                        // this.url = null;
                        this.state = null;
                        
                        return;
                    }
                    
                    if (! files[0].type.match('image.*')) {
                        this.state = 'error';
                        
                        return;
                    }
                    
                    let formData = new FormData();
                    formData.append('file', files[0]);
                    this.state = 'uploading';
                    
                    const response = await axios({
                        method: 'post',
                        url: this.post_url,
                        data: formData,
                        headers: {'Content-Type': 'multipart/form-data'}
                    });
                    
                    if (! response.data.file) {
                        throw new SyntaxError(response.data.message);
                    }
                    
                    let focusable = document.getElementsByName(this.focusable);
                    if (focusable.length) {
                        focusable[0].focus();
                    }
                    
                    let attributes = JSON.parse(response.data.file);
                    this.image_id = attributes.id;
                    this.url = attributes.url;
                    this.state = 'uploaded';
                    
                    this.$refs.image.value = '';
                    
                    console.log('file.id: ' + attributes.id);
                } catch (error) {
                    this.image_id = 0;
                    this.state = 'error';
                    console.log(error);
                    alert(error.message);
                }
            },
            deleteImage() {
                // 
            }
        }
    }
</script>

<style scoped>
    label.image-uploader {
        cursor: pointer;
        display: block;
    }
    .image-uploader-variant {
        min-height: 178px;
    }
    .image-uploader-wait {
        color: #e1e1e1;
    }
    .image-uploader-success {
        color: #43ac6a;
        overflow: hidden;
        box-shadow: 0 8px 12px 0 rgba(0, 0, 0, .25);
        padding: .25rem;
        border: 1px solid rgba(0, 0, 0, .125);
    }
    input.image-uploader-file {
        display: none;
        position: absolute;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
    }
</style>
