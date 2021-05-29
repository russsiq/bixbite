<template>
<div class="tags-group">
    <div v-for="(tag, index) in tags" class="tag form-control" :key="tag.id">
        <div class="tag-text">{{ tag.title }}</div>
        <button type="button" class="tag-remove" @click="detach(tag, index)">
            <i class="fa fa-times"></i>
        </button>
    </div>

    <div class="tags-group-input">
        <input
            type="text"
            list="suggested-tags"
            v-model="newTag"
            maxlength="255"
            autocomplete="off"
            placeholder="Добавить тег"
            class="form-control"
            @keyup.enter="attach" />
    </div>

    <datalist id="suggested-tags">
        <option v-for="tag in suggestedTags" :key="tag.id" :value="tag.title" />
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
            newTag: '',
            tags: [],
            suggestedTags: [],
        }
    },

    computed: {
        title() {
            const title = this.newTag.trim()

            return title.length >= 3 ? title : '';
        },
    },

    watch: {
        newTag(newTag, oldTag) {
            this.debouncedGetSuggestions();
        },

        'value': {
            immediate: true,
            handler(val, oldVal) {
                this.tags = val;
            }
        },
    },

    mounted() {
        this.debouncedGetSuggestions = debounce(this.getSuggestions, 888);
    },

    methods: {
        async attach() {
            if (! this.title) {
                return false;
            }

            const finded = this.suggestedTags.find(tag => this.title === tag.title);

            if (finded) {
                this.tags.push(finded);
            } else {
                const result = await Tag.$create({
                    title: this.title,
                    taggable_id: this.taggable.id,
                    taggable_type: this.taggable.type,
                });

                this.tags.push(result);
            }

            this.newTag = '';

            this.suggestedTags = [];

            this.$emit('update:tags', this.tags);
        },

        detach(tag, index) {
            const id = tag.id;

            this.$emit(
                'update:tags',
                this.tags.filter((tag) => id !== tag.id)
            );
        },

        getSuggestions() {
            if (this.suggestedTags && this.suggestedTags.some(tag => this.title === tag.title)) {
                return false;
            }

            this.suggestedTags = [];

            this.fetchSuggestions();
        },

        fetchSuggestions() {
            this.title && Tag.$fetch({
                    params: {
                        title: this.title,
                    }
                })
                .then((collection) => {
                    this.suggestedTags = collection || []
                });
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
