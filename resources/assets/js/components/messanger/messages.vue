<template>
    <div>
        <div v-if="thread" style="min-height: 50px;">
            <a :href="'/threads/' + thread.id + '/edit'"
               v-if="thread.thread_type == 'group' && thread.user_id == auth_user_id"
               class="btn btn-default btn-xs pull-right">
                <i class="fa fa-pencil"></i> Изменить
            </a>

            <form method="POST" :action="'/threads/' + thread.id" accept-charset="UTF-8" class="pull-right" style="display: inline-block; margin-right: 5px;">
                <input name="_method" type="hidden" value="DELETE">
                <input name="_token" type="hidden" value="sRKAeeRqf3qhQzhQ68UWq2ruhjc8fLOnQcPatjgR">
                <button type="submit" class="btn btn-xs btn-danger">
                    <i class="fa fa-trash"></i> Удалить чат
                </button>
            </form>

            <div v-if="thread.thread_type == 'group'">
                <h1>{{ thread.subject }}</h1>
                <p v-html="thread.description"> </p>
                <hr>
            </div>
        </div>

        <div id="messages">
            <div v-if="messages" v-for="message in messages" class="media">
                <message :message="message"></message>
            </div>

            <div v-if="!messages || messages.length == 0">
                {{ trans('messenges.partial.nothreads') }}
            </div>
        </div>

        <messageform :thread="thread"></messageform>
    </div>
</template>

<script>
    import message from './message.vue'
    import messageform from './messageform.vue'

    export default {
        data: function () {
            return {
                auth_user_id: 0,
                thread: null,
                messages: null
            }
        },

        mounted() {
            this.getMessages();

            var element = document.getElementById("messages");

            element.scrollTop = element.scrollHeight;
        },

        watch: {
            $route(to, from) {
                this.getMessages();
            }
        },

        methods: {
            getMessages() {
                this.auth_user_id = 0;
                this.thread = null;
                this.messages = null;

                if (this.$route.params.id === undefined) {
                    return;
                }

                var self = this;

                axios.get('/api/threads/' + this.$route.params.id + '/messages')
                    .then(function (response) {
                        self.messages = response.data.data;
                        self.thread = response.data.thread;
                        self.auth_user_id = response.data.auth_user_id;
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
