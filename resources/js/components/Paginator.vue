<template>
<div class="mt-2" v-if="shouldPaginate">
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item" v-show="prevUrl">
                <a class="page-link" href="#" tabindex="-1" rel="prev" @click.prevent="page--">&laquo;Prev</a>
            </li>
            <li class="page-item" v-show="nextUrl">
                <a class="page-link" href="#" rel="next" @click.prevent="page++">Next&raquo;</a>
            </li>
        </ul>
    </nav>
</div>
</template>


<script>
    export default {

        props:['dataSet'],

        watch: {
            dataSet() {
                this.page = this.dataSet.current_page;
                this.prevUrl = this.dataSet.prev_page_url;
                this.nextUrl = this.dataSet.next_page_url;
            },

            page() {
                this.broadcast().updateUrl();
            }
        },

        data() {
            return {
                page: 1,
                prevUrl: false,
                nextUrl: false,
            }
        },

        computed: {
            shouldPaginate() {
                return !!(this.prevUrl || this.nextUrl);
            }
        },

        methods: {
            broadcast() {
                return this.$emit('changed', this.page);
            },

            updateUrl() {
                history.pushState(null, null, '?page=' + this.page);
            }

        }
    }


</script>
