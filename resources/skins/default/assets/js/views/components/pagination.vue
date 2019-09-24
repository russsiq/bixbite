<template>
<ul v-if="pages.length > 1" class="pagination" role="navigation">
    <template v-for="page in pages">
        <li v-if="'...' === page" class="page-item disabled">
            <span class="page-link">...</span>
        </li>
        <li v-else class="page-item" :class="activeClass(page)">
            <a href="#" class="page-link" @click.prevent="changePage(page)">{{ page }}</a>
        </li>
    </template>
</ul>
</template>

<script type="text/ecmascript-6">
import {
    mapGetters
} from 'vuex';

export default {
    computed: {
        ...mapGetters({
            currentPage: 'paginator/current_page',
            pageRange: 'paginator/page_range',
            perPage: 'paginator/per_page',
            total: 'paginator/total',
        }),

        totalPages() {
            return Math.ceil(this.total / this.perPage);
        },

        rangeStart() {
            return Math.max(this.currentPage - this.pageRange, 1);
        },

        rangeEnd() {
            return Math.min(this.currentPage + this.pageRange, this.totalPages);
        },

        pages() {
            const pages = [];

            // If the first page is not included in the page range.
            if (this.rangeStart > 1) {
                // Let's add the first page.
                pages.push(1);

                // Let's display three dots instead of the second page.
                if (this.rangeStart > 2) {
                    pages.push('...');
                }
            }

            // Let's form an array of pages included in the displayed range.
            for (let i = this.rangeStart; i <= this.rangeEnd; i++) {
                pages.push(i);
            }

            // If the last page is not included in the page range.
            if (this.rangeEnd < this.totalPages) {
                // Instead of the penultimate page, we will display three dots.
                if (this.totalPages - this.rangeEnd > 1) {
                    pages.push('...');
                }

                // Let's add the last page.
                pages.push(this.totalPages);
            }

            return pages;
        },

        activeClass() {
            return (page) => ({
                'active': page === this.currentPage,
            });
        },
    },

    methods: {
        changePage(page) {
            if (page !== this.currentPage) {
                this.$emit('paginate', page);
            }
        }
    },
}
</script>

<style lang="scss" scoped>
.pagination .page-item.disabled .page-link {
    background: transparent;
    color: #222;
}
</style>
