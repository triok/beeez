<template>
    <section id="tasks">
        <header class="header">
            <h1>Задачи</h1>

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
                    <input class="form-control timepicker-actions"
                           autocomplete="off"
                           placeholder="Дата"
                           v-model="newTaskDate">
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

        <section class="main" v-show="tasks.length" v-cloak>
            <div class="row row-title">
                <div class="col-md-1"></div>
                <div class="col-md-4">Название</div>
                <div class="col-md-2">Дата</div>
                <div class="col-md-4">Коментарий</div>
                <div class="col-md-1"></div>
            </div>

            <div class="row task"
                 v-for="task in activeTasks"
                 :key="task.id"
                 :class="{ completed: task.completed }">

                <div class="col-md-1">
                    <input class="form-control toggle"
                           type="checkbox"
                           v-model="task.completed">
                </div>

                <div class="col-md-4">{{ task.title }}</div>

                <div class="col-md-2">{{ task.endDate }}</div>

                <div class="col-md-4">
                    <span v-if="task != editedTask">{{ task.comment}}</span>

                    <input class="form-control"
                           autocomplete="off"
                           @keyup="editTask(task)"
                           v-if="task == editedTask"
                           v-model="task.comment">
                </div>

                <div class="col-md-1" style="padding-left: 0;">
                    <button type="button"
                            class="btn btn-xs btn-primary"
                            @click="saveTask(task)"
                            v-if="task == editedTask">

                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                    </button>

                    <button type="button"
                            class="btn btn-xs btn-primary"
                            @click="editTask(task)"
                            v-if="!editedTask">

                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </section>

        <footer class="footer" v-show="tasks.length" v-cloak>
            <h2>Выполнено</h2>

            <div class="row row-title">
                <div class="col-md-1"></div>
                <div class="col-md-4">Название</div>
                <div class="col-md-2">Дата</div>
                <div class="col-md-4">Коментарий</div>
                <div class="col-md-1"></div>
            </div>

            <div class="row task"
                 v-for="task in completedTasks"
                 :key="task.id"
                 :class="{ completed: task.completed, editing: task == editedTask }">

                <div class="col-md-1">
                    <input class="form-control toggle"
                           type="checkbox"
                           v-model="task.completed">
                </div>

                <div class="col-md-4">
                    <span @dblclick="editTask(task)">{{ task.title }}</span>
                </div>

                <div class="col-md-5" v-show="false">
                    <input class="edit" type="text"
                           v-model="task.title"
                           v-task-focus="task == editedTask"
                           @blur="doneEdit(task)"
                           @keyup.enter="doneEdit(task)"
                           @keyup.esc="cancelEdit(task)">
                </div>

                <div class="col-md-2">
                    <span @dblclick="editTask(task)">{{ task.endDate }}</span>
                </div>

                <div class="col-md-4">
                    {{ task.comment }}
                </div>

                <div class="col-md-1" style="padding-left: 0;">
                    <button type="button" class="btn btn-xs btn-danger" @click="removeTask(task)">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </footer>
    </section>
</template>

<script>
    var STORAGE_KEY = 'tasks-vuejs-2.0';

    var taskStorage = {
        fetch: function () {
            var tasks = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');

            tasks.forEach(function (task, index) {
                task.id = index
            })

            taskStorage.uid = tasks.length

            return tasks
        },
        save: function (tasks) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(tasks))
        }
    }

    export default {
        data: function () {
            return {
                tasks: taskStorage.fetch(),

                showForm: false,
                newTask: '',
                newTaskDate: '',

                editedTask: null,
                taskComment: '',
            }
        },

        watch: {
            tasks: {
                handler: function (tasks) {
                    taskStorage.save(tasks)
                },
                deep: true
            }
        },

        computed: {
            activeTasks: function () {
                return this.tasks.filter(function (task) {
                    return !task.completed
                })
            },
            completedTasks: function () {
                return this.tasks.filter(function (task) {
                    return task.completed
                })
            },
        },

        methods: {
            addTask: function () {
                var title = this.newTask && this.newTask.trim();
                var endDate = this.newTaskDate;

                if (!title) return;

                this.tasks.push({
                    id: taskStorage.uid++,
                    title: title,
                    endDate: endDate,
                    comment: '',
                    completed: false
                })

                this.newTask = '';
                this.newTaskDate = '';

                this.showForm = false;
            },

            removeTask: function (task) {
                this.tasks.splice(this.tasks.indexOf(task), 1)
            },

            editTask: function (task) {
                this.editedTask = task;
            },

            saveTask: function (task) {
                if (!this.editedTask) return;

                task.comment = task.comment.trim();

                this.editedTask = null;
            }
        },

        directives: {
            'task-focus': function (el, binding) {
                if (binding.value) {
                    el.focus()
                }
            }
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
</style>
