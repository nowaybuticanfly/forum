<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>

        <new-reply @created="add"></new-reply>
    </div>
</template>


<script>
    import Reply from './Reply.vue';
    import NewReply from './NewReply.vue';
    export default {
        components: {Reply, NewReply},

        props: ['data'],


        data() {
            return {
                items: this.data
            }
        },
        methods: {
            add(reply) {
                this.items.push(reply);
                this.$emit('added');
            },
            remove(index) {
                this.items.splice(index, 1);
                this.$emit('removed');
                flash('Reply has been deleted');
            }
        }
    }
</script>
