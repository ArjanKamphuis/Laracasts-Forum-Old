<template>
    <div :id="`reply-${id}`" class="card mb-3" :class="isBest ? 'border-success' : ''" v-cloak>
        <div class="card-header" :class="isBest ? 'bg-success' : ''">
            <div class="level">
                <div class="flex">
                    <a :href="`/profiles/${owner}`" v-text="owner"></a> said <span v-text="ago"></span>...
                </div>
                <div v-if="signedIn">
                    <favorite-component :reply="reply"></favorite-component>
                </div>
            </div>
        </div>
        <div class="card-body" :class="isBest ? 'text-success' : ''">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>
                    <button class="btn btn-sm btn-primary">Update</button>
                    <button class="btn btn-sm btn-link" @click="cancelReply" type="button">Cancel</button>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>

        <div class="card-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
            <div v-if="authorize('owns', reply)">
                <button class="btn btn-secondary btn-sm mr-2" @click="editReply">Edit</button>
                <button class="btn btn-danger btn-sm" @click="destroy">Delete</button>
            </div>
            <button class="btn btn-outline-secondary btn-sm ml-auto" @click="markBestReply" v-if="authorize('owns', reply.thread) && !isBest">Best Reply?</button>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import FavoriteComponent from './FavoriteComponent';
    
    export default {
        props: ['reply'],

        components: { FavoriteComponent },

        computed: {
            ago() {
                return moment.utc(this.reply.created_at).fromNow();
            }
        },

        data() {
            return {
                editing: false,
                id: this.reply.id,
                owner: this.reply.owner.name,
                body: this.reply.body,
                old_body_data: '',
                isBest: this.reply.isBest
            };
        },

        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id);
            });
        },

        methods: {
            editReply() {
                this.old_body_data = this.body;
                this.editing = true;
            },
            cancelReply() {
                this.body = this.old_body_data;
                this.old_body_data = '';
                this.editing = false;
            },
            update() {
                axios.patch(`/replies/${this.id}`, {
                        body: this.body
                    })
                    .then(() => {
                        this.editing = false;
                        flash('Updated');
                    }, error => flash(error.response.data, 'danger'));
            },
            destroy() {
                if (confirm('Are you sure you want to delete this reply?')) {
                    axios.delete(`/replies/${this.id}`);
                    this.$emit('deleted', this.id);
                    flash('Reply has been deleted!');
                }
            },
            markBestReply() {
                axios.post(`/replies/${this.id}/best`)
                    .then(() => {
                        window.events.$emit('best-reply-selected', this.id);
                        flash('Marked');
                    });
            }
        }
    };
</script>