<template>
    <div v-if="signedIn">
        <div class="form-group">
            <textarea id="body" class="form-control" v-model="body" placeholder="Have something to say?" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary" @click="addReply">Post</button>
    </div>
    <p v-else class="text-center">Please <a href="/login">sign in</a> to participate in this discussion</p>
</template>

<script>
    import 'jquery.caret';
    import 'at.js';
    
    export default {
        data() {
            return {
                body: ''
            };
        },

        mounted() {
            $('#body').atwho({
                at: "@",
                delay: 750,
                callbacks: {
                    remoteFilter: (query, callback) => {
                        $.getJSON('/api/users', { name: query }, (usernames) => {
                            callback(usernames);
                        });
                    }
                }
            });
        },

        methods: {
            addReply() {
                axios.post(`${location.pathname}/replies`, { body: this.body })
                    .then(({data}) => {
                        this.body = '';
                        this.$emit('created', data);
                        flash('Your reply has been posted.');
                    }, error => flash(error.response.data, 'danger'));
            }
        }
    };
</script>