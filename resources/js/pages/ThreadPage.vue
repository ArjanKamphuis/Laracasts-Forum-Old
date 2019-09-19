<script>
    import RepliesComponent from '../components/RepliesComponent';
    import SubscribeButtonComponent from '../components/SubscribeButtonComponent';
    
    export default {
        props: ['dataRepliesCount', 'dataLocked'],

        components: { RepliesComponent, SubscribeButtonComponent },

        data() {
            return {
                repliesCount: this.dataRepliesCount,
                locked: this.dataLocked
            };
        }
    };
</script>