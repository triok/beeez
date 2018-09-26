<template>
    <div class="nav">
        <div class="media alert"
             v-if="threads && threads.length > 0"
             v-for="thread in threads"
             :class="thread.unread_count ? 'alert-info' : ''">

            <router-link :to="'/messages/' + thread.id" class="pull-left">
                <img :src="thread.avatar" class="img-thumbnail">
            </router-link>

            <h4 class="media-heading">
                <router-link :to="'/messages/' + thread.id">
                    {{ thread.subject }}
                </router-link>

                <span v-if="thread.unread_count" class="label label-danger">{{ thread.unread_count }}</span>
            </h4>
        </div>

        <p v-if="threads && threads.length == 0">{{ trans('messages.nothreads') }}</p>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                threads: null
            }
        },

        mounted() {
            this.getThreads();
        },

        watch: {
            $route(to, from) {
                if (this.$route.params.id !== undefined) {
                    var thread_id = this.$route.params.id;
                    var self = this;

                    this.threads.forEach(function(element) {
                        if(element.id == thread_id){
                            element.unread_count = 0;
                        }
                    });
                }
            }
        },

        methods: {
            getThreads() {
                this.threads = null;

                axios.get('/api/threads')
                    .then(response => (this.threads = (response.data.data != undefined ? response.data.data : [])));
            }
        }
    }
</script>

<style>
    #chat .nav .media-heading .label-danger {
        float: right;
        margin-right: 12px;
        padding: 3px 7px 1px 7px;
    }

    #chat .nav .media-heading {
        padding-top: 10px;
    }

    #chat .nav .media {
        margin: 3px 0;
        padding: 3px 0 3px 3px;
    }

    #chat .nav img {
        display: block;
        width: 40px;
        height: 40px;
        margin: 0 auto;
    }
</style>
