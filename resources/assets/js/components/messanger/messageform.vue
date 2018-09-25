<template>
    <div v-if="thread">
        <hr>

        <form :action="'/messages/' + thread.id" method="post" id="send-message">
            <input type="hidden" name="_method" value="put">
            <input type="hidden" name="_token" :value="this.csrf">

            <h2>{{ trans('messages.add') }}</h2>

            <div class="form-group">
                <textarea name="message" id="message" required rows="3" class="form-control"></textarea>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-responsive" id="table-files">
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ trans('messages.send') }}</button>

                <div style="display: inline-block;padding-left: 10px;">
                    <label for="file-upload" style="font-size: 12px;color: #47afa5;cursor: pointer;">
                        Прикрепить файл
                    </label>
                    <input id="file-upload" type="file" style="display: none;" onchange="uploadMessageFile(event)">
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['thread'],

        data: function () {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }
    }
</script>

<style>

</style>
