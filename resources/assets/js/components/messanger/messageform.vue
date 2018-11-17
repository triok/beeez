<template>
    <div v-if="thread">
        <hr>

        <form :action="'/messages/' + thread.id" method="post" id="send-message">
            <input type="hidden" name="_method" value="put">
            <input type="hidden" name="_token" :value="this.csrf">

            <h2>{{ trans('messages.add') }}</h2>

            <div class="form-group" style="position: relative">
                <textarea v-model="message" name="message" id="message" required rows="3" class="form-control" @focus="closeEmojiWindow();"></textarea>

                <div style="position: absolute;right: 0;bottom: 0;">
                    <a tabindex="0"
                       id="smile"
                       class="btn btn-link btn-lg"
                       role="button"
                       data-toggle="popover"
                       data-trigger="focus"
                       style="outline: none;"
                       title="Emoji"
                       data-content="">
                        <i class="fa fa-smile-o"></i>
                    </a>
                </div>
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

        <div id="smile-template" class="hide">
            <div class="popover" role="tooltip" style="width: 460px;max-width: 460px;padding: 10px;" ref="template">
                <div class="text-right" style="border-bottom: 1px solid #ccc;">
                    <button type="button" class="btn btn-link btn-sm" @click="closeEmojiWindow();">
                        <i class="fa fa-close"></i>
                    </button>
                </div>

                <button v-for="emoji in emojis" type="button"
                        @click="addSmile(emoji)"
                        class="btn btn-link"
                        style="width: 50px; height: 50px; text-decoration: none; outline: none;">
                    {{ emoji }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['thread'],

        data: function () {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                message: '',
                emojis: [
                    "\u{1F382}",
                    "\u{1F3C3}",
                    "\u{1F3E0}",
                    "\u{1F3E2}",
                    "\u{1F4A9}",
                    "\u{1F4BB}",
                    "\u{1F4B0}",
                    "\u{1F4F7}",
                    "\u{1F44D}",
                    "\u{1F44E}",
                    "\u{1F46A}",
                    "\u{1F48B}",
                    "\u{1F52B}",
                    "\u{1F600}",
                    "\u{1F601}",
                    "\u{1F602}",
                    "\u{1F603}",
                    "\u{1F604}",
                    "\u{1F605}",
                    "\u{1F606}",
                    "\u{1F609}",
                    "\u{1F60A}",
                    "\u{1F60B}",
                    "\u{1F60C}",
                    "\u{1F60E}",
                    "\u{1F60D}",
                    "\u{1F618}",
                    "\u{1F617}",
                    "\u{1F619}",
                    "\u{1F61A}",
                    "\u{1F61C}",
                    "\u{1F61D}",
                    "\u{1F61E}",
                    "\u{1F620}",
                    "\u{1F621}",
                    "\u{1F622}",
                    "\u{1F623}",
                    "\u{1F624}",
                    "\u{1F625}",
                    "\u{1F628}",
                    "\u{1F629}",
                    "\u{1F62A}",
                    "\u{1F62B}",
                    "\u{1F62D}",
                    "\u{1F630}",
                    "\u{1F631}",
                    "\u{1F632}",
                    "\u{1F633}",
                    "\u{1F635}",
                    "\u{1F637}",
                    "\u{1F638}",
                    "\u{1F639}",
                    "\u{1F63A}",
                    "\u{1F645}",
                    "\u{1F646}",
                    "\u{1F647}",
                    "\u{1F648}",
                    "\u{1F649}",
                    "\u{1F64A}",
                    "\u{1F64B}",
                    "\u{1F64C}",
                    "\u{1F64D}",
                    "\u{1F64F}",
                    "\u{1F61F}",
                    "\u{1F622}",
                    "\u{1F631}",
                    "\u{1F64C}",
                    "\u{1F64F}",
                    "\u{263A}",
                    "\u{2705}",
                    "\u{2753}"
                ]
            }
        },

        mounted() {
            $('#smile').popover({
                trigger: 'click',
                placement: 'left',
                template: this.$refs.template
            });
        },

        methods: {
            addSmile(smile) {
                this.message = this.message + ' ' + smile + ' ';
            },

            closeEmojiWindow() {
                $('#smile').popover('hide');
            }
        }
    }
</script>

<style>

</style>
