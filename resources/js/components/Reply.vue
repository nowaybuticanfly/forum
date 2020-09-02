<template>
    <div class="card mt-3" :id="'reply-'+this.data.id">
        <h6 class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+data.owner.name"
                    v-text="data.owner.name">
                    </a>
                    said <span v-text="ago"></span>

                </h5>
                <div v-if="signedIn">
                    <favorite :reply="this.data"></favorite>
                </div>
            </div>
        </h6>

        <div class="card-body">
            <div v-if="editing">
                <form @submit="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>

                    <button class="btn btn-primary btn-sm" @click="submit">Update</button>
                    <button class="btn btn-link btn-sm" @click="editing=false" type="button">Cancel</button>
                </form>
            </div>
            <div v-else v-text="body">
            </div>
        </div>


        <div class="card-footer level" v-if="canUpdate">
            <button class="btn btn-primary btn-sm mr-3" @click="editing = true">Edit</button>
            <button class="btn btn-danger btn-sm" @click="destroy">Delete</button>
        </div>
    </div>
</template>
<script>
    import Favorite from "./Favorite.vue";
    import moment from  'moment';
    export default {
        props:['data'],

        components: {Favorite},

        computed: {
            signedIn() {
                return window.App.signedIn
            },
            canUpdate() {
                return this.authorize(user => this.data.user_id == user.id)
            },
            ago() {
                return moment(this.data.created_at).fromNow();
            }
        },

        data() {
            return {
                body: this.data.body,
                editing: false
            };
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.body,
                })
                .catch(error => {
                   flash(error.response.data, 'danger')
                });
                this.editing = false;
                flash('updated');

            },
            destroy() {
             axios.delete('/replies/' + this.data.id);
             this.$emit('deleted', this.index);
            }
        }

    }

</script>
