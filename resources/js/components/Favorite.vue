<template>
    <div>
        <button type="submit" :class="classes" @click="toggle">
            <div class="d-flex flex-column">
                <span class="material-icons" v-text="heartIcon">
                </span>
                <small v-text="count"></small>
            </div>

        </button>

    </div>
</template>

<script>
export default {

    props:['reply', 'icon'],

    data() {
        return {
            count: this.reply.favorites_count ? this.reply.favorites_count : 0,
            active: this.reply.isFavorited
        }
    },

    computed: {
        classes() {
            return ['btn', 'p-0', this.active ? 'btn-primary' : 'btn-light']
        },

        heartIcon() {
            return this.active ? 'favorite' : 'favorite_border'
        },

        endpoint() {
            return '/replies/' + this.reply.id + '/favorites';
        }


    },

    methods: {
        toggle() {
            this.active ? this.destroy() : this.create();
        },

        create() {
            axios.post(this.endpoint);
            this.active = true;
            this.count++;
        },

        destroy() {
            axios.delete(this.endpoint);
            this.active = false;
            this.count--;
        }
    }




}
</script>


<style>

</style>
