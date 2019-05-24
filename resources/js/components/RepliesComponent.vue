<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply-component :data="reply" @deleted="remove(index)"></reply-component>
        </div>
        <paginator-component :dataSet="dataSet" @changed="fetch"></paginator-component>
        <new-reply-component @created="add"></new-reply-component>
    </div>
</template>

<script>
    import NewReplyComponent from './NewReplyComponent';
    import ReplyComponent from './ReplyComponent';
    import collection from '../mixins/collection';
    
    export default {
        components: { NewReplyComponent, ReplyComponent },

        mixins: [collection],
        
        data() {
            return { dataSet: false };
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch(page) {
                axios.get(this.url(page)).then(this.refresh);
            },

            url(page) {
                if (!page) {
                    let params = new URLSearchParams(window.location.search);
                    page = params.has('page') ? params.get('page') : 1;
                }
                return `${location.pathname}/replies?page=${page}`;
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;
            }
        }
    };
</script>