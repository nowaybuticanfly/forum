<template>
    <li class="nav-item dropdown mr-1" v-if="notifications.length">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="material-icons">
                notifications_active
            </span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item"
               v-for="notification in notifications"
               :href="notification.data.link"
               v-text="notification.data.message"
               @click="markAsRead(notification)"></a>
         </div>
    </li>

</template>


<script>
    export default {

        data() {
            return { notifications: false }
        },

        created() {
            axios.get('/profiles/' + window.App.user.name + "/notifications")
                .then(response => this.notifications = response.data);
        },
        methods: {
            markAsRead(notification) {
                axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id);
            }
        }

    }
</script>
