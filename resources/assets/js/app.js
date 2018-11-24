require('./bootstrap');
require('./bootstrap-notify');
require('./bootstrap-jquery');

import VueRouter from 'vue-router';

Vue.use(VueRouter);

Vue.component('job-navigation', require('./components/job/navigation'));
Vue.component('job-tabs', require('./components/job/tabs'));

var filter = function(text, length){
    return text.length > length ? text.slice(0, length) + '...' : text;
};

Vue.filter('truncate', filter);

const Jobs = require('./components/Jobs.vue');
const Messanger = require('./components/messanger');

const routes = [
    {path: '/', component: Jobs},
    {path: '/jobs/category/:id', component: Jobs},
    {path: '/messages', component: Messanger},
    {path: '/messages/:id', component: Messanger}
];

const router = new VueRouter({
    mode: 'history',
    routes
});

const app = new Vue({
    el: '#app',

    data: function () {
        return {
            last_tab_num: 0,
            tab_selected: 1,
            tabs: [],
        }
    },

    methods: {
        addTab() {
            if (this.last_tab_num == 0) {
                this.last_tab_num++;

                this.tabs.push({id: this.last_tab_num, name: 'Задание ' + this.last_tab_num});
            }

            this.last_tab_num++;

            this.tabs.push({id: this.last_tab_num, name: 'Задание ' + this.last_tab_num});

            this.addSubTask(this.last_tab_num);
        },

        activateTab(id) {
            this.tab_selected = id;
        },

        deleteTab(id) {
            var self = this;

            this.tabs.forEach(function (tab, i) {
                if (tab.id === id) {
                    self.tabs.splice(i, 1);

                    $('#task-' + id).remove();
                }
            });
        },

        updateTabName(id, name) {
            // console.log(id, name);
        },

        addSubTask(task_id) {
            var load = $('<div>');

            load.load('/job/subtask?task_id=' + task_id + '&project_id=' + parseInt(this.$route.query.project_id), function (result) {
                $('.tab-content .tab-pane:last').after(result);

                $('.editor1').summernote();
                $('.editor2').summernote();

                $('.timepicker-actions').datepicker({
                    timepicker: true,
                    startDate: new Date(),
                    minHours: 9,
                    maxHours: 24,
                    onSelect: function (fd, d, picker) {
                        if (!d) return;

                        picker.update({
                            minHours: 0,
                            maxHours: 24
                        })
                    }
                });

                $('.input-category-name').on('click', function () {
                    $('#modal-task-id').val($(this).attr('name'));

                    $('#modal-categories').modal({ backdrop: 'static', keyboard: false, 'show': true });
                });

                tinymce.init({
                    selector: '#sub-instruction-' + task_id,
                    height: 400,
                    theme: 'modern',
                    plugins: [
                        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                        'searchreplace wordcount visualblocks visualchars code fullscreen',
                        'insertdatetime media nonbreaking save table contextmenu directionality',
                        'emoticons template paste textcolor colorpicker textpattern imagetools',
                        'autoresize'
                    ],
                    toolbar1: 'insertfile undo redo | styleselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media',
                    image_advtab: true,
                    content_css: [
                        '/plugins/tinymce/codepen.min.css'
                    ]
                });

                $('.selectpicker').selectpicker('refresh');
            });
        }
    },

    router
});
