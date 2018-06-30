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
                        <span v-if="! (file.id > 0)">
                            <button type="button" class="btn btn-link text-primary" v-on:click="uploadFile(key)"><i class="fa fa-upload"></i></button>
                            <button type="button" class="btn btn-link text-warning" v-on:click="removeFile(key)"><i class="fa fa-trash"></i></button>
                        </span>
                        <span v-if="file.id > 0">
                            <!--button type="button" class="btn btn-link text-primary" v-on:click="downloadFile(key)"><i class="fa fa-download"></i></button-->
                            <button type="button" class="btn btn-link text-danger" v-on:click="deleteFile(file.id)"><i class="fa fa-trash"></i></button>
                        </span>
                    </div>
                </td>
            </tr>
        </table>
        <input type="file" ref="files" :multiple="multiple" v-on:change="handleFiles();" />
        <!--button type="button" class="btn btn-outline-primary" v-on:click="submitFiles()"><i class="fa fa-upload"></i></button-->
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
        methods: {
            handleFiles() {
                this.files = [];
                let uploadedFiles = this.$refs.files.files;
                for(var i = 0; i < uploadedFiles.length; i++)
                    this.files.push(uploadedFiles[i]);
                this.submitFiles();
            },
            removeFile( key ){
                this.files.splice( key, 1 );
            },
            deleteFile( key ){
                alert('Not released. File id: ' + key);
            },
            reindexFiles() {
                 // Reindex files array
                if (this.files.length) {
                    this.files = this.files.filter(function (item) {
                        return ! (item.id > 0);
                    }.bind(this));
                }
                
                // Check how many elements are left
                if (! this.files.length) {
                    return confirm('Upload complete. Reload this page?') ? document.location.reload(true) : true ;
                }
            },
            async uploadFile(i) {
                
                let formData = new FormData();
                formData.append('file', this.files[i]);
                formData.append('mass_uploading', true);
                
                this.files[i].state = 'uploading';
                Vue.set(this.files, i, this.files[i]);
                
                try {
                    const response = await axios({
                        method: 'post',
                        url: this.post_url,
                        data: formData,
                        headers: {'Content-Type': 'multipart/form-data'}
                    });
                    
                    if (! response.data.file) {
                        throw new Error(response.data.message);
                    }
                    
                    var attributes = JSON.parse(response.data.file);
                    this.files[i].id = attributes.id;
                    this.files[i].url = attributes.url;
                    this.files[i].message = response.data.message;
                    this.files[i].state = 'uploaded';
                } catch (error) {
                    console.log(error);
                    this.files[i].message = error.message
                    this.files[i].state = 'error';
                }
                
                Vue.set(this.files, i, this.files[i]);
            },
            submitFiles() {
                // Check how many elements in ref="files"
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
