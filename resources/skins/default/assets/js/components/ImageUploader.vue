<template>
    <label class="image-uploader">
        <div v-if="'uploaded' == state" class="image-uploader-success">
            <img :src="src" alt="image" />
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
            
            url: {String, required: false},
            post_url: {String, required: true},
            fetch_url: {String, required: false},
        },
        data(){
            return {
                src: null,
                state: null,
                image_id: 0
            }
        },

        mounted() {
            // Executed after the next DOM update cycle.
            this.$nextTick(() => {
                if (this.url && this.url.length > 0) {
                    this.state = 'uploaded';
                    this.src = this.url
                } else if (this.fetch_url && this.fetch_url.length > 0) {
                    this.fetchImage()
                }
            })
        },
        
        methods: {
            async fetchImage() {
                try {
                    const response = await axios(this.fetch_url)
                    
                    if (! response.data.file) {
                        throw new SyntaxError(response.data.message);
                    }
                    
                    // WHY not JSON.parse????
                    let attributes = response.data.file;
                    
                    this.image_id = response.data.file.id;
                    this.src = response.data.file.url;
                    this.state = 'uploaded';
                    this.$refs.image.value = '';
                } catch (error) {
                    this.image_id = 0;
                    this.state = 'error';
                    console.log(error);
                    Notification.error({message: error.message});
                }
            },
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
                    
                    let attributes = JSON.parse(response.data.file);
                    
                    this.image_id = attributes.id;
                    this.src = attributes.url;
                    this.state = 'uploaded';
                    this.$refs.image.value = '';
                } catch (error) {
                    this.image_id = 0;
                    this.state = 'error';
                    console.log(error);
                    Notification.error({message: error.message});
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
        display: inline-block;
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
