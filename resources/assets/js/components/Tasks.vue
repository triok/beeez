<template>
    <section id="tasks">
        <header class="header">
            <h1>{{ trans('tasks.title') }}</h1>
            <p>{{ trans('tasks.titledesc') }}</p>

            <button type="button"
                    class="btn btn-success"
                    v-if="!showForm"
                    @click="showForm = true">
                Создать задачу
            </button>

            <div class="row" v-if="showForm">
                <div class="col-md-4">
                    <input class="form-control"
                           autofocus autocomplete="off"
                           placeholder="Название"
                           v-model="newTask">
                </div>

                <div class="col-md-3">
                    <datepicker
                            input-class="form-control"
                            autocomplete="off"
                            placeholder="Дата"
                            v-model="newTaskDate"
                            :format="customFormatter"
                            :disabledDates="{to: fromDate()}"></datepicker>
                </div>

                <div class="col-md-1">
                    <button type="button"
                            class="btn btn-success"
                            @click="addTask">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </header>

        <section class="main" v-show="tasks && tasks.length" v-cloak>
            <h2>{{ trans('tasks.current') }}</h2>
            <table class="table table-hover table-condensed table-fixed">
                <thead>
                    <th style="width:5%;"></th>
                    <th style="width:50%;">{{ trans('tasks.name') }}</th>
                    <th style="width:10%;">{{ trans('tasks.date') }}</th>
                    <th style="width:30%;">{{ trans('tasks.comment') }}</th>
                    <th style="width:5%;"></th>
                </thead>
                <tbody>
                    <tr v-for="task in activeTasks" :key="task.id">
                        <td><input class="form-control" type="checkbox" @click="completeTask(task)"></td>
                        <td>
                            <span v-if="task != editedTask">{{ task.name }}</span>
                            <input class="form-control"
                                   autocomplete="off"
                                   v-if="task == editedTask"
                                   v-model="taskName" >
                        </td>
                        <td class="date">
                            <span v-if="task != editedTask">{{ task.do_date }}</span>
                            <datepicker
                                input-class="form-control"
                                autocomplete="off"
                                placeholder="Дата"
                                v-if="task == editedTask"
                                v-model="taskDate"
                                :format="customFormatter"
                                :disabledDates="{to: fromDate()}"></datepicker>
                        </td>
                        <td>
                            <span v-if="task != editedTask">{{ task.comment}}</span>
                            <input class="form-control"
                                   autocomplete="off"
                                   v-if="task == editedTask"
                                   v-model="taskComment" >
                        </td>
                        <td>
                            <button type="button"
                                    class="btn btn-xs btn-primary"
                                    @click="saveComment(task)"
                                    v-if="task == editedTask">

                                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            </button>

                            <button type="button"
                                    class="btn btn-xs btn-primary"
                                    @click="editTask(task)"
                                    v-if="!editedTask">

                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <footer class="footer" v-show="tasks && completedTasks.length" v-cloak>
            <h2>{{ trans('tasks.completed') }}</h2>

            <table v-show="tasks && completedTasks.length" class="table table-responsive table-hover">
                <thead>
                    <th style="width:5%;"></th>
                    <th style="width:50%;">{{ trans('tasks.name') }}</th>
                    <th style="width:10%;">{{ trans('tasks.date') }}</th>
                    <th style="width:30%;">{{ trans('tasks.comment') }}</th>
                    <th style="width:5%;"></th>
                </thead>
                <tbody>
                    <tr v-for="task in completedTasks" :key="task.id">
                        <td>
                            <input class="form-control"
                                type="checkbox"
                                checked
                                @click="uncompleteTask(task)">
                        </td>
                        <td><s>{{ task.name }}</s></td>
                        <td class="date">{{ task.do_date }}</td>
                        <td>{{ task.comment }}</td>
                        <td>
                            <button type="button" class="btn btn-xs btn-danger" @click="removeTask(task)">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>            
        </footer>
    </section>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';

    export default {
        data: function () {
            return {
                route: '/api/tasks',

                tasks: [],

                showForm: false,
                newTask: '',
                newTaskDate: '',

                editedTask: null,
                taskComment: '',
                taskName: '',
                taskDate: '',

            }
        },

        mounted() {
            this.getTasks();
        },

        computed: {
            activeTasks: function () {
                if(this.tasks) {
                    return this.tasks.filter(function (task) {
                        return !task.completed
                    })
                }
            },
            completedTasks: function () {
                if(this.tasks) {
                    return this.tasks.filter(function (task) {
                        return task.completed
                    })
                }
            },
        },

        methods: {
            getTasks() {
                axios.get(this.route)
                    .then(response => (this.tasks = response.data.data));
            },

            addTask: function () {
                var name = this.newTask && this.newTask.trim();
                var do_date = this.newTaskDate;

                if (!name) return;

                axios.post(this.route, {
                    name: name,
                    do_date: do_date
                }).then(response => (this.tasks = response.data.data));

                this.newTask = '';
                this.newTaskDate = '';

                this.showForm = false;
            },

            saveTask(task) {
                this.editedTask = null;

                axios.patch(this.taskUrl(task), task)
                    .then(response => (this.tasks = response.data.data));
            },

            completeTask(task) {
                task.completed = true;

                this.saveTask(task);
            },

            uncompleteTask(task) {
                task.completed = false;

                this.saveTask(task);
            },

            saveComment(task) {
                task.comment = this.taskComment;
                task.name = this.taskName;
                task.do_date = moment(this.taskDate).format('YYYY-MM-DD');
                this.taskComment = null;

                this.saveTask(task);
            },

            editTask(task) {
                this.editedTask = task;
                this.taskName = task.name;
                this.taskComment = task.comment;
                var d = moment(task.do_date,'DD-MM-YYYY').toDate();
                var g = moment(d).format('YYYY-MM-DD');



                this.taskDate = g;

            },

            removeTask(task) {
                axios.delete(this.taskUrl(task))
                    .then(response => (this.tasks = response.data.data));
            },

            taskUrl(task) {
                return this.route + '/' + task.id
            },

            customFormatter(date) {
                return moment(date).format('DD.MM.YYYY');
            },


            fromDate() {
                var d = new Date();

                d.setDate(d.getDate()-1);

                return d;
            }
        },

        components: {
            Datepicker
        }
    }
</script>

<style>
    [v-cloak] {
        display: none;
    }

    #tasks input[type="checkbox"] {
        position: inherit;
        right: 0;
        width: 20px;
        height: 20px;
    }

    #tasks header {
        margin-bottom: 20px;
    }

    #tasks .row {
        margin-bottom: 2px;
    }

    #tasks .row-title {
        margin-bottom: 4px;
        font-weight: bold;
    }
    #tasks table thead th{
        text-align:center;
    }
    #tasks table td.date {
        text-align:center;
    }
</style>
