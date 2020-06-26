<template>
<div class="form-group tagsinput has-float-label">
    <label class="control-label">Теги</label>
    <template v-for="(tag, index) in tags">
        <span class="tag my-1 btn btn-sm btn-outline-dark">
            <span class="tag-text">{{ tag.title }}</span>
            <button type="button" class="tag-remove" @click="detach(tag, index)"><i class="fa fa-times"></i></button>
        </span>
    </template>

    <div>
        <input type="text" list="suggested-tags" v-model="tag" maxlength="255" autocomplete="off" placeholder="Добавить тег" class="tag-input form-control" @keyup.enter="attach" />
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
                return 'number' === typeof taggable.id
                    && 'string' === typeof taggable.type;
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
        handleChange(event) {
            // this.$emit(
            //     'input',
            //     Array.from(event.target.options)
            //     .filter(option => option.selected)
            //     .map(option => Number(option.value))
            // )
        },

        attach() {
            if (this.tag) {
                const title = this.tag.trim();

                const exists = this.suggestedTags.some(tag => title === tag.title);

                if (!exists) {
                    Tag.$create({
                            data: {
                                title,
                                taggable_id: this.taggable.id,
                                taggable_type: this.taggable.type,

                            }
                        })
                        // .then((response) => {
                        //     this.suggestedTags = response.entities.tags
                        // });
                }

                this.tags.push({
                    title: this.tag
                });

                this.tag = '';

                this.suggestedTags = [];

                // this.$emit('input', this.tags);
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
.tagsinput {
    display: flex;
    width: auto;
    min-height: auto;
    height: auto;
    flex-wrap: wrap;
    background: #fff;
    color: #556270;
    padding: 5px 5px 0;
    border: 1px solid #e6e6e6;
}

.tagsinput .tag {
    position: relative;
    display: block;
    max-width: 100%;
    word-wrap: break-word;
    padding-right: 30px;
    border-radius: 2px;
    margin: 5px;
}

.tagsinput .tag-remove {
    position: absolute;
    background: 0 0;
    display: block;
    width: 30px;
    height: 30px;
    top: 0;
    right: 0;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    color: var(--danger);
    line-height: 30px;
    padding: 0;
    border: 0;
    font-weight: 300;
    font-size: 0.9rem;
    font-weight: 300;
}

.tagsinput div {
    flex-grow: 1;
}

.tagsinput div .tag-input {
    background: none;
    border: none;
}
</style>
