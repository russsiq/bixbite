<template>
    <div class="container p-0">
        <table class="table table-sm" v-if="files.length > 0">
            <tr v-for="(file, key) in files" :key="file.key">
                <td width="10" class="text-right">
                    <i class="fa fa-spinner fa-pulse"   v-if="'uploading' == file.state"></i>
                    <i class="fa fa-ban text-danger"    v-else-if="'error' == file.state"></i>
                    <i class="fa fa-check text-success" v-else>
                        <input type="hidden" name="atachments[]" :value="file.id" v-if="file.id > 0" />
                    </i>
                </td>
                <td style="white-space: nowrap;">{{ file.name }}<!--br>{{ file.url }}--></td>
                <td v-html="file.message"></td>
                <td style="white-space: nowrap;" class="text-right">
                    <div class="btn-group ml-auto">
                        <span v-if="file.id > 0">
                            <button type="button" class="btn btn-link text-danger" @click="deleteFile(file.id)"><i class="fa fa-trash"></i></button>
                        </span>
                        <span v-else>
                            <button type="button" class="btn btn-link text-primary" @click="uploadFile(key)"><i class="fa fa-upload"></i></button>
                            <button type="button" class="btn btn-link text-warning" @click="removeFile(key)"><i class="fa fa-trash"></i></button>
                        </span>
                    </div>
                </td>
            </tr>
        </table>
        <input type="file" ref="files" :multiple="multiple" @change="handleFiles();" />
    </div>
</template>

<script>
    export default {
        props: {
            post_url: String,
            multiple: Boolean
        },
        data() {
            return {
                files: []
            }
        },
        watch: {
            files: {
                deep: true,
                handler(val) {
                    //console.log(val)
                },
            },
        },
        methods: {
            handleFiles() {
                this.files = [];
                this.files.splice(0)
                
                let uploadFiles = this.$refs.files.files;
                
                Array.from(uploadFiles, (file, index) => {
                    Object.assign(file, {
                        id: 0,
                        key: index,
                        url: null,
                        message: null,
                        state: false,
                    })
                    this.files.push(file);
                });
                
                this.submitFiles();
            },
            removeFile( key ){
                this.files.splice( key, 1 );
            },
            deleteFile( key ){
                alert('Not released. File id: ' + key);
            },
            reindexFiles() {
                 // Reindex files array.
                if (this.files.length) {
                    this.files = this.files.filter(function (item) {
                        return ! (item.id > 0);
                    }.bind(this));
                }
                
                // Check how many elements are left.
                if (! this.files.length) {
                    return confirm('Upload complete. Reload this page?') ? document.location.reload(true) : true ;
                }
            },
            async uploadFile(i) {
                
                let formData = new FormData();
                formData.append('file', this.files[i]);
                formData.append('mass_uploading', true);
                
                this.files.splice(i, 1, Object.assign(this.files[i], {
                    state: 'uploading',
                }))
                
                try {
                    const response = await axios({
                        method: 'post',
                        url: this.post_url,
                        data: formData,
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    
                    if (! response.data.file) {
                        throw new Error(response.data.message);
                    }
                    
                    this.files.splice(i, 1, Object.assign(this.files[i], {
                        id: response.data.file.id,
                        url: response.data.file.url,
                        state: 'uploaded',
                        message: response.data.message,
                    }))
                } catch (error) {
                    this.files.splice(i, 1, Object.assign(this.files[i], {
                        state: 'error',
                        message: error.message,
                    }))
                }
            },
            submitFiles() {
                // Check how many elements in ref="files".
                if (! this.$refs.files.files.length) {
                    return alert('Nothing to upload');
                }
                
                for( let i = 0; i < this.files.length; i++ ) {
                    if(! this.files[i].id) this.uploadFile(i);
                }
            }
        }
    }
</script>
