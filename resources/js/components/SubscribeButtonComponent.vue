<template>
    <button :class="classes" @click="subscribe" v-text="buttonText"></button>
</template>

<script>
    export default {
        props: ['active'],

        data() {
            return {
                isActive: this.active
            }
        },

        computed: {
            classes() {
                return ['btn', this.isActive ? 'btn-warning' : 'btn-success'];
            },
            buttonText() {
                return this.isActive ? 'Unsubscribe': 'Subscribe';
            }
        },

        methods: {
            subscribe() {
                axios[this.isActive ? 'delete' : 'post'](`${location.pathname}/subscriptions`)
                    .then(() => {
                        this.isActive = !this.isActive
                    });
            }
        }
    };
</script>