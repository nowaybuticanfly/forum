<template>
    <div>
        <div class="level mb-2">
            <img :src="this.avatar" alt="avatar" width="50" height="50" class="mr-1">
            <h1 v-text="this.user.name"></h1>
        </div>



        <form method="POST"  enctype="multipart/form-data" v-if="canUpdate">
            <image-upload name="avatar" @loaded="onLoad"></image-upload>
        </form>

    </div>

</template>


<script>
    import ImageUpload from './ImageUpload.vue';

    export default {

        components: {ImageUpload},

        props:['user'],

        data() {
            return {
                avatar: this.user.avatar_path
            }
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id)
            }
        },

        methods: {
            onLoad(avatar) {
                this.avatar = avatar.src;
                this.persist(avatar.file);
            },

            persist(file) {
                let data = new FormData();

                data.append('avatar', file);

                axios.post(`/api/users/${this.user.name}/avatar`, data)
                    .then(() => {
                        flash('Avatar Uploaded!');
                    });
            }
        }

    }
</script>
