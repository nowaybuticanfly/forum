<script>
    export default {
        props:['reply'],
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
            }
        }

    }

</script>
