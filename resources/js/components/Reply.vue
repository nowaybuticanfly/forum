

<script>
    import Favorite from "./Favorite.vue";

    export default {
        props:['reply'],

        components: {Favorite},

        data() {
            return {
                body: this.reply.body,
                editing: false
            };
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.reply.id, {
                    body: this.body,
                });

                this.editing = false;
                flash('updated');
            },
            destroy() {
             axios.delete('/replies/' + this.reply.id);

             $(this.$el).fadeOut(300, () => {
                 flash('Reply has been deleted');
             });
            }
        }

    }

</script>
