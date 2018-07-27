<template>
    <div class="container p-0">
        <table class="table table-sm" v-if="files.length > 0">
            <tr v-for="(file, key) in files" :key="file.key">
                <td width="10" class="text-right">
                    <i class="fa fa-spinner fa-pulse"   v-if="'uploading' == file.state"></i>
                    <i class="fa fa-ban text-danger"    v-else-if="'error' == file.state"></i>
                    <i class="fa fa-check text-success" v-else>
                        <input type="hidden" name="files[]" :value="file.id" v-if="file.id > 0" />
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
    </div>
</template>

<script>
    export default {
        props: {
            attachment_id:      {Number,required: true},
            attachment_type:    {String,required: true},
            upload_url:         {String,required: true},
            update_url:         {String,required: true},
            delete_url:         {String,required: true},
            multiple:           Boolean
        },
        data() {
            return {
                files: [
                    //
                ]
            }
        },
        methods: {
            //
        }
    }
</script>
