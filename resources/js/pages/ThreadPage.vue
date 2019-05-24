<script>
    import RepliesComponent from '../components/RepliesComponent';
    
    export default {
        props: ['initialRepliesCount'],

        components: { RepliesComponent },

        data() {
            return {
                repliesCount: this.initialRepliesCount
            };
        }
    };
</script>