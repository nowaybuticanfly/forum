<template>
    <div class="mt-5">
        <div v-if="signedIn">
            <div class="form-group">
                <textarea name="body"
                          id="body"
                          class="form-control"
                          placeholder="Have something to say?"
                          rows="5"
                          required
                          v-model="body"
                ></textarea>
            </div>


            <button type="submit"
                    class="btn btn-outline-secondary"
                    @click="addReply">Post</button>
        </div>

        <p class="text-center" v-else>
            Please <a href="/login">sign in</a> to participate in this
            discussion.
        </p>
    </div>
</template>

<script>
import Tribute from "tributejs";

export default {

    components: {
        Tribute
    },

    data() {
        return {
            body: '',
            endpoint: location.pathname + '/replies',
        };
    },
    mounted() {
        let tribute = new Tribute({
            noMatchTemplate: function () {
                return '<span style:"visibility: hidden;"></span>';
            },
            // column to search against in the object (accepts function or string)
            lookup: 'value',
            // column that contains the content to insert by default
            fillAttr: 'value',
            menuShowMinLength: 2,
            values: function(query, cb) {
                axios.get('/api/users', {params: {name: query}} )
                    .then(function(response){
                        console.log(response);
                        cb(response.data);
                    });
            },
        });
        tribute.attach(document.querySelectorAll("#body"));
    },
    computed: {
        signedIn() {
            return window.App.signedIn;
        }
    },
    methods: {
        addReply() {
            axios.post(this.endpoint, { body: this.body })
                .catch(error => {
                    flash(error.response.data, 'danger')
                })
                .then(({data}) => {
                    this.body = '';
                    flash('Your reply has been posted.');
                    this.$emit('created', data);
                });
        }
    }
}
</script>
