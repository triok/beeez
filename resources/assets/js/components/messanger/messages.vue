<template>
    <div>
        <div v-if="updated">
            <div class="nothreads" v-if="thread.id == undefined">
                {{ trans('messages.partial.nothreads') }}
            </div>

            <div v-if="thread.id != undefined" style="min-height: 50px;">
                <a :href="'/threads/' + thread.id + '/edit'"
                   v-if="thread.thread_type == 'group' && thread.user_id == auth_user_id"
                   class="btn btn-default btn-xs pull-right">
                    <i class="fa fa-pencil"></i> Изменить
                </a>
                <div class="chat-delete">
                <form method="POST" :action="'/threads/' + thread.id" accept-charset="UTF-8" class="" >
                    <input name="_method" type="hidden" value="DELETE">
                    <input name="_token" type="hidden" :value="csrf">
                    <button type="submit" class="btn btn-xs btn-danger">
                        <i class="fa fa-trash"></i> Удалить чат
                    </button>
                </form>
                </div>
                <div v-if="thread.thread_type == 'group'">
                    <h1>{{ thread.subject }}</h1>
                    <p v-html="thread.description"> </p>
                    <hr>
                </div>
                <div class="message-box">
                <div v-for="message in messages" class="media">
                    <message :message="message"></message>
                </div>
                </div>
                <messageform :thread="thread"></messageform>
            </div>
        </div>
    </div>
</template>

<script>
    import message from './message.vue'
    import messageform from './messageform.vue'

    export default {
        data: function () {
            return {
                updated: false,
                auth_user_id: 0,
                thread: [],
                messages: [],
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        },

        mounted() {
            this.getMessages();
        },

        watch: {
            $route(to, from) {
                this.getMessages();
            }
        },

        methods: {
            getMessages() {
                this.auth_user_id = 0;
                this.thread = [];
                this.messages = [];
                this.updated = false;

                if (this.$route.params.id === undefined) {
                    this.updated = true;
                    
                    return;
                }

                var self = this;

                axios.get('/api/threads/' + this.$route.params.id + '/messages')
                    .then(function (response) {
                        self.messages = (response.data.data != undefined ? response.data.data : []);
                        self.thread = (response.data.thread != undefined ? response.data.thread : []);
                        self.auth_user_id = (response.data.auth_user_id != undefined ? response.data.auth_user_id : 0);
                        self.updated = true;
                    });
            }
        },

        components: {
            message,
            messageform
        }
    }
</script>

<style>
    #messages {
        max-height: 500px;
        overflow-y: scroll;
        padding-right: 10px;
    }
</style>
