<template>
    <button :class="classes" @click="toggle">
        <i class="fas fa-heart"></i>
        <span v-text="count"></span>
    </button>
</template>

<script>
    export default {
        props: ['reply'],

        data() {
            return {
                count: this.reply.favoritesCount,
                active: this.reply.isFavorited
            };
        },

        computed: {
            classes() {
                return ['btn', this.active ? 'btn-primary' : 'btn-secondary'];
            },

            endpoint() {
                return `/replies/${this.reply.id}/favorites`
            }
        },

        methods: {
            toggle() {
                return this.active ? this.destroy() : this.create();
            },

            create() {
                axios.post(this.endpoint);
                this.count++;
                this.active = true;
            },

            destroy() {
                axios.delete(this.endpoint);
                this.count--;
                this.active = false;
            }
        }
    }
</script>