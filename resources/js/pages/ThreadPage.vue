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
                editing: false
            };
        },

        methods: {
            toggleLock() {
                axios[this.locked ? 'delete' : 'post'](`/locked-threads/${this.thread.slug}`);
                this.locked = !this.locked;
            }
        }
    };
</script>