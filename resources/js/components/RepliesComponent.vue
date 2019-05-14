<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply-component :data="reply" @deleted="remove(index)"></reply-component>
        </div>
        <new-reply-component :endpoint="endpoint" @created="add"></new-reply-component>
    </div>
</template>

<script>
    import NewReplyComponent from './NewReplyComponent';
    import ReplyComponent from './ReplyComponent';
    
    export default {
        props: ['data'],

        components: { NewReplyComponent, ReplyComponent },
        
        data() {
            return {
                endpoint: `${location.pathname}/replies`,
                items: this.data
            };
        },

        methods: {
            add(reply) {
                this.items.push(reply);
                this.$emit('added');
            },

            remove(index) {
                this.items.splice(index, 1);
                this.$emit('removed');
                flash('Reply has been deleted!')
            }
        }
    };
</script>