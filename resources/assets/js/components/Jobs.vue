<template>
    <div class="col-sm-9" id="main">

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

        <div class="row current-jobs">
            <table class="table table-striped" id="jobs-table" v-show="show_table">
                <thead>
                <tr>
                    <th scope="col">{{ trans('home.task') }}</th>
                    <th scope="col">{{ trans('home.timefor') }}</th>
                    <th scope="col">{{ trans('home.before') }}</th>
                    <th scope="col">{{ trans('home.price') }}</th>
                    <th scope="col">{{ trans('home.work') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="job in jobs">
                    <td scope="row" class="job-name">
                        <a :href="'/jobs/' + job.id" :id="job.id">{{ job.name }}</a>
                        <span v-html="job.comment"> </span>
                    </td>
                    <td>
                        {{ job.time_for_work }} {{ trans('home.hours') }}
                    </td>
                    <td>
                        {{ job.end_date }}
                    </td>
                    <td>
                        {{ job.price }}
                    </td>
                    <td>
                        <div v-if="job.auth_check">
                            <button :disabled="job.status == 'in review'"
                                    class="btn btn-success btn-sm btn-review"
                                    v-if="job.applications_count && job.application">

                                <i class="fa fa-handshake-o"></i> {{ trans('home.complete') }}
                            </button>

                            <button disabled
                                    class="btn btn-warning btn-sm"
                                    v-if="job.applications_count && !job.application">

                                <i class="fa fa-history"></i> {{ trans('home.in_progress') }}
                            </button>


                            <form :action="'/jobs/'+job.id+'/apply'" method="post" v-if="job.allow_apply">
                                <input type="hidden" name="_token" :value="csrf">
                                <button class="btn btn-default btn-sm" type="submit">
                                    <i class="fa fa-briefcase"></i> {{ trans('home.apply') }}
                                </button>
                            </form>

                            <button disabled
                                    class="btn btn-default btn-sm apply-job-btn"
                                    v-if="(job.status == 'in progress' || job.status == 'in review') && !job.application">


                                <i class="fa fa-handshake-o"></i> {{ trans('home.in_progress') }}

                            </button>


                            <button disabled
                                    class="btn btn-default btn-sm apply-job-btn"
                                    v-if="job.status == 'complete' && !job.application">


                                <i class="fa fa-handshake-o"></i> {{ trans('home.complete') }}

                            </button>

                            <button disabled
                                    class="btn btn-default btn-sm apply-job-btn"
                                    v-if="(job.status == 'open' && job.ended) || job.status == 'closed'">


                                <i class="fa fa-handshake-o"></i> {{ trans('home.enddate') }}

                            </button>
                        </div>

                        <button class="btn btn-default btn-sm apply-job-btn"
                                v-if="!job.auth_check">


                            <i class="fa fa-handshake-o"></i> {{ trans('home.apply') }}

                        </button>
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
                                "lengthChange": false
                            });
                        } else {
                            self.table = $('#jobs-table').DataTable({
                                bFilter: false,
                                bInfo: false,
                                "lengthChange": false
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
                if (this.$route.params.id === undefined) {
                    return '/api/jobs';
                }

                return '/api/jobs?category_id=' + this.$route.params.id;
            }
        }
    }
</script>

<style>
    table.dataTable thead th {
        border-bottom: 1px solid #ddd;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid #ddd;
    }
</style>
