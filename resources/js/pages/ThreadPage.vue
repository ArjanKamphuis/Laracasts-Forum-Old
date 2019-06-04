<script>
    import RepliesComponent from '../components/RepliesComponent';
    import SubscribeButtonComponent from '../components/SubscribeButtonComponent';
    
    export default {
        props: ['initialRepliesCount'],

        components: { RepliesComponent, SubscribeButtonComponent },

        data() {
            return {
                repliesCount: this.initialRepliesCount
            };
        }
    };
</script>