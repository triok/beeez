<template>
    <div class="col-sm-9" id="main">
        <div class="row current-jobs base-wrapper">
            <ol class="breadcrumb" v-if="category">

                <li v-if="category && category.parent">
                    <a :href="'/jobs/category/'+ category.parent.id">{{ category.parent.nameRu }}</a>
                </li>
                <li v-if="category">
                    <a :href="'/jobs/category/'+ category.id">{{ category.nameRu }}</a>
                </li>
            </ol>
            <div v-show="show_title">
                <h3 v-if="!category">{{ trans('home.title') }}</h3>
            </div>
            <div name="table" class="col-xs-12">
                <div name="head" class="col-xs-12">
                    <div name="name" class="col-xs-5">{{ trans('home.task') }}</div>
                    <div name="timefor" class="col-xs-2">{{ trans('home.timefor') }}</div>
                    <div name="created" class="col-xs-2">{{ trans('home.created') }}</div>
                    <div name="price" class="col-xs-2">{{ trans('home.price') }}</div>
                    <div name="status" class="col-xs-1">{{ trans('home.status') }}</div>
                </div>
                <div name="body" class="col-xs-12">
                    <div v-for="job in jobs" name="line" class="col-xs-12">
                        <div name="job-name" class="col-xs-5">
                            <a v-if="job.status == 'open'" :href="'/jobs/' + job.id" :id="job.id">{{ job.name }}</a>
                            <a v-else class="disabled" :href="'/jobs/' + job.id" :id="job.id">{{ job.name }}</a>                        
                            <span v-html="job.comment"> </span>
                            <div>
                                <p>{{ trans('home.before') }} {{ job.end_date }}</p>
                                <p>Размещено: <a :href="'/peoples/' + job.client_id">{{ job.client }}</a> (<span class="text-success">{{ job.client_rating_positive }}</span>/<span class="text-danger">{{ job.client_rating_negative }}</span>)</p>
                                <span class="badge" v-for="skill in job.skills">{{ skill.name }}</span>
                            </div>                            
                        </div>
                        <div name="job-timefor" class="col-xs-2">
                            <span class="hidden">{{ job.time_for_work.id }}</span> {{ job.time_for_work.value }}
                        </div>
                        <div name="job-created" class="col-xs-2">
                            <span class="date-short">{{ customFormatter(job.created_at) }}</span>
                        </div>
                        <div name="job-price" class="col-xs-2">{{ job.price }}</div>
                        <div name="job-status" class="col-xs-1">
                            <div v-if="job.auth_check">
                                <span class="searching" v-if="job.allow_apply">
                                    <i class="fa fa-search"></i> Поиск исполнителя
                                </span>

                                <span class="disabled" v-if="(job.status == 'in progress' || job.status == 'in review')">
                                    <i class="fa fa-handshake-o"></i> {{ trans('home.in_progress') }}
                                </span>

                                <span class="disabled" v-if="job.status == 'complete'">
                                    <i class="fa fa-check"></i> {{ trans('home.completed') }}
                                </span>

                                <span class="enddate" v-if="(job.status == 'open' && job.ended) || job.status == 'closed'">
                                    <i class="fa fa-clock-o"></i> {{ trans('home.enddate') }}
                                </span>                           
                            </div>
                            <span v-if="!job.auth_check">
                                not authorized
                            </span>                            
                        </div>
                    </div>
                </div>
            </div>                     
            <table class="table table-responsive" id="jobs-table" v-show="show_table">
                <thead>
                <tr>
                    <th scope="col">{{ trans('home.task') }}</th>
                    <th scope="col">{{ trans('home.timefor') }}</th>
                    <th scope="col">{{ trans('home.created') }}</th>
                    <th scope="col">{{ trans('home.price') }}</th>
                    <th scope="col">{{ trans('home.status') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="job in jobs">
                    <td scope="row" class="job-name">

                        <a v-if="job.status == 'open'" :href="'/jobs/' + job.id" :id="job.id">{{ job.name }}</a>
                        <a v-else class="disabled" :href="'/jobs/' + job.id" :id="job.id">{{ job.name }}</a>                        
                        <span v-html="job.comment"> </span>
                        <div>
                            <p>{{ trans('home.before') }} {{ job.end_date }}</p>
                            <p>Размещено: <a :href="'/peoples/' + job.client_id">{{ job.client }}</a> (<span class="text-success">{{ job.client_rating_positive }}</span>/<span class="text-danger">{{ job.client_rating_negative }}</span>)</p>
                            <span class="badge" v-for="skill in job.skills">{{ skill.name }}</span>
                        </div>
                    </td>
                    <td class="center">
                        <span class="hidden">{{ job.time_for_work.id }}</span> {{ job.time_for_work.value }}
                    </td>
                    <td class="center">
                        <span class="date-short">{{ customFormatter(job.created_at) }}</span>
                    </td>
                    <td class="center">
                        {{ job.price }}
                    </td>
                    <td class="center">
                        <div v-if="job.auth_check">
                            <span class="searching" v-if="job.allow_apply">
                                <i class="fa fa-search"></i> Поиск исполнителя
                            </span>

                            <span class="disabled" v-if="(job.status == 'in progress' || job.status == 'in review')">
                                <i class="fa fa-handshake-o"></i> {{ trans('home.in_progress') }}
                            </span>

                            <span class="disabled" v-if="job.status == 'complete'">
                                <i class="fa fa-check"></i> {{ trans('home.completed') }}
                            </span>

                            <span class="enddate" v-if="(job.status == 'open' && job.ended) || job.status == 'closed'">
                                <i class="fa fa-clock-o"></i> {{ trans('home.enddate') }}
                            </span>                           
                        </div>

                        <span v-if="!job.auth_check">

                            not authorized
                           

                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>

    export default {
        data: function () {
            return {
                show_table: false,
                show_title: false,
                table: null,
                category: null,
                jobs: [],
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        },

        mounted() {
            this.getCategory();
            this.getJobs();
        },

        watch: {
            $route(to, from) {
                this.getCategory();
                this.getJobs();
            }
        },

        methods: {
            getJobs() {
                var self = this;

                self.show_table = false;

                if (self.table) {
                    $('#jobs-table').DataTable().destroy();
                }

                axios.get(this.getUrl())
                    .then(response => (this.jobs = response.data.data))
                    .then(function () {
                        if (!self.table) {
                            self.table = $('#jobs-table').DataTable({
                                bFilter: false,
                                bInfo: false,
                                "order": [[ 2, "desc" ]],
                                "pageLength": 20,
                                "lengthChange": false,
                            });
                        } else {
                            self.table = $('#jobs-table').DataTable({
                                bFilter: false,
                                bInfo: false,
                                "order": [[ 2, "desc" ]],
                                "pageLength": 20,
                                "lengthChange": false,
                            });
                        }

                        self.show_table = true;
                    });
            },

            getCategory() {
                this.category = null;

                if (this.$route.params.id !== undefined) {
                    this.show_title = false;

                    axios.get('/api/categories/' + this.$route.params.id)
                        .then(response => (this.category = response.data.data))
                        .then(() => (this.show_title = true));
                } else {
                    this.show_title = true;
                }
            },

            getUrl() {
                var skill = 'skill=';

                if (this.$route.query.skill !== undefined) {
                    skill += this.$route.query.skill;
                }

                if (this.$route.params.id === undefined) {
                    return '/api/jobs?' + skill;
                }

                return '/api/jobs?category_id=' + this.$route.params.id + '&' + skill;
            },

            customFormatter(date) {
                return moment(date).format('ll');
            },
        }
    }
</script>

<style>
    .dataTables_wrapper {
        padding: 0 20px;
    }

    table.dataTable thead th {
        border-bottom: 1px solid #ddd;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid #ddd;
    }

    table.dataTable tbody tr {
        height:100px;
        transition: 0.5s;
    }

    table.dataTable tbody tr .job-name {
        position:relative;
    }

    table.dataTable tbody tr .center {
        text-align:center;
        vertical-align:middle;
    }    

    table.dataTable tbody tr .job-name>a{
        font-size:17px;
        font-weight:bold;
    }

    table.dataTable tbody tr .job-name div {
        position:absolute;
        bottom:0;
    } 

    table.dataTable tbody tr .job-name div p{
        margin:0;
        font-size: 13px;
        color: #8e8e8e;        
    } 

    table.dataTable tbody tr .searching {
        color:#48b0a5;
    }    

    table.dataTable tbody tr .disabled {
        color:#8e8e8e;
    } 

    table.dataTable tbody tr .enddate {
        color:#de4848;
    }     
</style>
