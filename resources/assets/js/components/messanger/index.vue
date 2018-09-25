<template>
    <div class="container-fluid" id="chat">
        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" role="navigation">
            <div id="sidebar">
                <h2>{{ trans('messages.thread_title') }}</h2>

                <a href="/threads/create" class="btn btn-xs btn-success">
                    {{ trans('messages.create_thread') }}
                </a>

                <threads></threads>
            </div>
        </div>

        <div class="col-xs-6 col-sm-9" id="main">
            <messages></messages>
        </div>
    </div>
</template>

<script>
    import messages from './messages.vue';
    import threads from './threads.vue';

    export default {
        components: {
            threads,
            messages
        }
    }
</script>

<style>
    #chat h2 {
        margin-top: 0;
    }
</style>
