<template>
    <button :class="classes" @click="subscribe" v-text="buttonText"></button>
</template>

<script>
    export default {
        props: ['active'],

        computed: {
            classes() {
                return ['btn', this.active ? 'btn-warning' : 'btn-success'];
            },
            buttonText() {
                return this.active ? 'Unsubscribe': 'Subscribe';
            }
        },

        methods: {
            subscribe() {
                axios[this.active ? 'delete' : 'post'](`${location.pathname}/subscriptions`);
                this.active = !this.active;
            }
        }
    };
</script>