<template>
    <div :id="`reply-${id}`" class="card mb-3" :class="isBest ? 'border-success' : ''" v-cloak>
        <div class="card-header" :class="isBest ? 'bg-success' : ''">
            <div class="level">
                <div class="flex">
                    <a :href="`/profiles/${owner}`" v-text="owner"></a> said <span v-text="ago"></span>...
                </div>
                <div v-if="signedIn">
                    <favorite-component :reply="data"></favorite-component>
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

        <div class="card-footer level">
            <div v-if="canUpdate">
                <button class="btn btn-secondary btn-sm mr-2" @click="editReply">Edit</button>
                <button class="btn btn-danger btn-sm" @click="destroy">Delete</button>
            </div>
            <button class="btn btn-outline-secondary btn-sm ml-auto" @click="markBestReply" v-show="!isBest">Best Reply?</button>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import FavoriteComponent from './FavoriteComponent';
    
    export default {
        props: ['data'],

        components: { FavoriteComponent },

        computed: {
            ago() {
                return moment.utc(this.data.created_at).fromNow();
            },
            signedIn() {
                return window.App.signedIn;
            },
            canUpdate() {
                return this.authorize(user => this.data.user_id == user.id);
            }
        },

        data() {
            return {
                editing: false,
                id: this.data.id,
                owner: this.data.owner.name,
                body: this.data.body,
                old_body_data: '',
                isBest: false
            };
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
                this.isBest = true;
            }
        }
    };
</script>