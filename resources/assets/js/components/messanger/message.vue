<template>
    <div v-if="message">
        <a class="pull-left" href="#" style="padding-right: 20px;">
            <img :src="message.user.avatar"
                 class="img-thumbnail"
                 style="width: 60px; height: 60px;">
        </a>

        <div class="pull-right">
            <small>Posted {{ message.created_at_human }}</small>
        </div>

        <div class="media-body">
            <b>{{ message.user.name }}</b>

            <p style="padding-top: 7px;">{{ message.body }}</p>

            <div v-if="message.files" v-for="file in message.files">
                <a target="_blank" :href="'/storage/files/' + message.user.id + '/' + file.path" style="font-size: 11px;">
                    {{ file.title }}
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['message'],

        data: function () {
            return {}
        },

        mounted() {

        },

        methods: {
            getThreads() {
            }
        }
    }
</script>

<style>

</style>
