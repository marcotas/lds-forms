<template>
    <div class="d-flex flex-column align-items-center">

        <ul class="pagination justify-content-center mb-1" v-if="meta &amp;&amp; links">
            <select class="form-control mr-2" :value="meta.per_page" @input="perPage($event.target.value)">
                <option :value="15">15 por página</option>
                <option :value="25">25 por página</option>
                <option :value="50">50 por página</option>
                <option :value="100">100 por página</option>
            </select>
            <li class="page-item cursor-pointer" :class="{ 'disabled': !links.prev }">
                <a class="page-link" @click.prevent="onClick(meta.current_page - 1)" :disabled="!links.prev"
                   tabindex="-1">
                    <i class="fa fa-angle-left"></i>
                </a>
            </li>

            <template v-if="show_first_page_links">
                <li class="page-item cursor-pointer">
                    <a class="page-link" @click.prevent="onClick(1)" tabindex="-1"> 1 </a>
                </li>

                <li class="page-item disabled" v-if="show_first_ellipsis">
                    <a class="page-link">...</a>
                </li>
            </template>

            <li class="page-item cursor-pointer" :class="{active: current(page)}" v-for="page of pages" :key="page">
                <a class="page-link" @click.prevent="onClick(page)" :disabled="current(page)" tabindex="-1">
                    {{ page }}
                </a>
            </li>

            <template v-if="show_last_page_links">
                <li class="page-item disabled" v-if="show_last_ellipsis">
                    <a class="page-link">...</a>
                </li>

                <li class="page-item cursor-pointer">
                    <a class="page-link" @click.prevent="onClick(meta.last_page)" tabindex="-1">
                        {{ meta.last_page }}
                    </a>
                </li>
            </template>

            <li class="page-item cursor-pointer" :class="{ 'disabled': !links.next }">
                <a class="page-link" @click.prevent="onClick(meta.current_page + 1)" :disabled="!links.next"
                   tabindex="-1">
                    <i class="fa fa-angle-right"></i>
                </a>
            </li>

        </ul>

        <small class="text-muted" v-if="meta">
            Mostrando {{ meta.from }} até {{ meta.to }} de {{ meta.total }} registro(s)
        </small>
    </div>
</template>

<script>
    export default {
        props: {
            meta: { required: true },
            links: { required: true },
            spacing: { default: 3 },
        },
        watch: {
            meta(val) {
                this.meta = val || {};
            },
            links(val) {
                this.links = val || {};
            },
        },
        computed: {
            last_page() {
                return (this.meta && this.meta.last_page) || 1;
            },
            current_page() {
                return (this.meta && this.meta.current_page) || 1;
            },
            max_pages() {
                return 2 * this.spacing + 1;
            },
            show_first_ellipsis() {
                return this.meta.current_page > this.spacing + 2;
            },
            show_last_ellipsis() {
                return this.meta.current_page + 1 < this.meta.last_page - this.spacing;
            },
            show_first_page_links() {
                return this.meta.current_page > this.spacing + 1 && !this.pages.includes(1);
            },
            show_last_page_links() {
                return this.meta.current_page < this.meta.last_page - this.spacing && !this.pages.includes(this.meta.last_page);
            },
            offset() {
                const page_end = this.current_page + this.spacing;
                return this.last_page < page_end ? this.last_page - page_end : 0;
            },
            pages() {
                let negatives = 0;
                const pageList = [];
                const page_start = this.current_page + (this.offset - this.spacing);

                for (let page = page_start; page <= this.last_page; page++) {
                    if (page <= 0) {
                        negatives++;
                        continue;
                    }

                    const maxPage = this.current_page + this.spacing;
                    if (page <= maxPage) {
                        pageList.push(page);
                        continue;
                    }

                    if (pageList.length < this.max_pages) {
                        pageList.push(page);
                        negatives--;
                    }
                }

                return pageList;
            },
        },
        methods: {
            current(page) {
                return page === this.meta.current_page;
            },
            onClick(page) {
                this.emit(page, this.meta.per_page);
            },
            perPage(perPage) {
                this.emit(this.meta.current_page, perPage);
            },
            emit(page, perPage) {
                this.$emit('page', { page, perPage });
            },
        },
    };
</script>

<style lang="scss" scoped>
    .page-item.active {
        a.page-link {
            z-index: initial;

            &:hover {
                color: white;
            }
        }
    }
</style>
