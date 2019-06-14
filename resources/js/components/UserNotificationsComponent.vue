<template>
    <li class="nav-item dropdown" v-if="notifications.length">
        <a id="notificationsDropdown" href="#" role="button" class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationsDropdown">
            <div v-for="notification in notifications" :key="notification.id">
                <a class="dropdown-item" :href="notification.data.link" v-text="notification.data.message" @click="markAsRead(notification)"></a>
            </div>
        </div>
    </li>
</template>

<script>
    export default {
        data() {
            return { notifications: false };
        },

        created() {
            axios.get(`/profiles/${window.App.user.name}/notifications/`)
                .then(response => this.notifications = response.data);
        },

        methods: {
            markAsRead(notification) {
                axios.delete(`/profiles/${window.App.user.name}/notifications/${notification.id}`);
            }
        }
    };
</script>