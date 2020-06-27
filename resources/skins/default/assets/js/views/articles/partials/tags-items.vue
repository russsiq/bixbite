<template>
<div class="tags-group">
    <template v-for="(tag, index) in tags">
        <span class="tag form-control">
            <span class="tag-text">{{ tag.title }}</span>
            <button type="button" class="tag-remove" @click="detach(tag, index)"><i class="fa fa-times"></i></button>
        </span>
    </template>

    <div class="tags-group-input">
        <input type="text" list="suggested-tags" v-model="tag" maxlength="255" autocomplete="off" placeholder="Добавить тег" class="form-control" @keyup.enter="attach" />
    </div>

    <datalist id="suggested-tags">
        <template v-for="(tag, index) in suggestedTags">
            <option :key="tag.id" :value="tag.title" />
        </template>
    </datalist>
</div>
</template>

<script type="text/ecmascript-6">
import debounce from 'lodash/debounce';

import Tag from '@/store/models/tag';

export default {
    props: {
        value: {
            type: Array,
            default: [],
        },

        taggable: {
            type: Object,
            required: true,
            validator(taggable) {
                return 'number' === typeof taggable.id &&
                    'string' === typeof taggable.type;
            }
        }
    },

    data() {
        return {
            tag: '',
            tags: [],
            suggestedTags: [],
        }
    },

    watch: {
        // эта функция запускается при любом изменении вопроса
        tag: function(newTag, oldTag) {
            this.debouncedGetSuggestions();
        }
    },

    computed: {

    },

    mounted() {
        this.tags = this.$props.value;

        this.debouncedGetSuggestions = debounce(this.getSuggestions, 888);
    },

    methods: {
        async attach() {
            if (this.tag) {
                const title = this.tag.trim();

                const finded = this.suggestedTags.find(tag => title === tag.title); // find

                if (finded) {
                    this.tags.push(finded);
                } else {
                    const result = await Tag.$create({
                        data: {
                            title,
                            taggable_id: this.taggable.id,
                            taggable_type: this.taggable.type,

                        }
                    })

                    this.tags.push(result);
                }

                this.tag = '';

                this.suggestedTags = [];

                this.$emit('input', this.tags);
            }
        },

        detach(tag, index) {
            this.tags.splice(index, 1);

            this.$emit('input', this.tags);

            // const result = confirm(`Вы точно хотите открепить этот тег [${tag.id}]?`);
            //
            // result && Tag.$delete({
            //     params: {
            //         id: tag.id
            //     }
            // });
        },

        getSuggestions() {
            const title = this.tag.trim();

            if (this.suggestedTags && this.suggestedTags.some(tag => title === tag.title)) {
                return false;
            }

            this.fetchSuggestions(title);
        },

        fetchSuggestions(title) {
            if (title.length >= 3) {
                return Tag.$fetch({
                        title
                    })
                    .then((response) => {
                        this.suggestedTags = response.entities.tags || []
                    });
            }

            this.suggestedTags = [];
        }
    }
}
</script>

<style lang="scss" scoped>
.tags-group {
    display: flex;
    flex-wrap: wrap;
}

.tags-group .tag {
    position: relative;
    display: block;
    max-width: 100%;
    width: auto;
    word-wrap: break-word;
    margin: 5px;
    color: #222;
    background-color: #fff;
    border-color: #222;
    padding-right: 37px;
}

.tags-group .tag:hover {
    color: #fff;
    background-color: #222;
    border-color: #222;
}

.tags-group .tag-remove {
    position: absolute;
    background: 0 0;
    display: block;
    width: 37px;
    height: 37px;
    line-height: 37px;
    top: 0;
    right: 0;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    outline: none;
    color: var(--danger);
    padding: 0;
    border: 0;
    font-weight: 300;
    font-size: 0.9rem;
}

.tags-group-input {
    flex-grow: 1;
    margin: 5px;
}
</style>
