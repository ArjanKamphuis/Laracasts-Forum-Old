<script>
    import RepliesComponent from '../components/RepliesComponent';
    import SubscribeButtonComponent from '../components/SubscribeButtonComponent';
    
    export default {
        props: ['thread'],

        components: { RepliesComponent, SubscribeButtonComponent },

        data() {
            return {
                repliesCount: this.thread.replies_count,
                locked: this.thread.locked,
                title: this.thread.title,
                body: this.thread.body,
                form: {},
                editing: false
            };
        },

        created() {
            this.resetForm();
        },

        methods: {
            toggleLock() {
                let uri = `/locked-threads/${this.thread.slug}`;
                axios[this.locked ? 'delete' : 'post'](uri);
                this.locked = !this.locked;
            },
            update() {
                let uri = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;
                axios.patch(uri, this.form).then(() => {
                    this.editing = false;
                    this.title = this.form.title;
                    this.body = this.form.body;
                    flash('Your thread has been updated.');
                }, error => flash(error.response.data, 'danger'));
            },
            resetForm() {
                this.form = {
                    title: this.thread.title,
                    body: this.thread.body
                };
                this.editing = false;
            }
        }
    };
</script>