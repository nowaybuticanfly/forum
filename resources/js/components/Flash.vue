<template>
    <div class="alert alert-success alert-flash" role="alert" v-show="show">
        <strong>Success!</strong> {{body}}
    </div>
</template>

<script>
window.events = new Vue();
window.flash = function (message) {
    window.events.$emit( 'flash', {message} );
};
export default {
    props: ['message'],

    data () {
        return {body: this.message, show: false}
    },

    created () {
        if (this.message) {
            this.flash();
        }
        window.events.$on( 'flash', data => this.flash( data ) );
    },

    methods: {
        flash (data) {
            if (data) {
                this.body = data.message;
            }
            this.show = true;
            this.hide();
        }, hide () {
            setTimeout( () => {
                this.show = false;
            }, 3000 );
        }
    }
};
</script>


<style>
.alert-flash {
    position: fixed;
    right: 25px;
    bottom: 25px;
}
</style>
