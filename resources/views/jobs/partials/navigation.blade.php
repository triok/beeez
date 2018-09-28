@if(!isset($job))
    <div class="col-xs-6 col-sm-3 sidebar-offcanvas" role="navigation">
        <job-navigation :tabs="tabs" :selected="tab_selected" inline-template>
            <div id="sidebar">
                <h2>@lang('edit.create')</h2>

                <button type="button"
                        v-if="tabs.length == 0"
                        @click.prevent="addTab()"
                        class="btn btn-primary btn-sm">

                    @lang('edit.separate')
                </button>

                <div v-if="tabs.length > 0" id="task-list" class="nav">
                    <div v-for="tab in tabs" class="media alert" style="margin: 0;">
                        <h4 class="media-heading" role="presentation">
                            <a :class="'tab-' + tab.id + '-name'" data-toggle="tab" :href="'#task-' + tab.id" @click="activateTab(tab.id)">
                                @{{ tab.name | truncate(20) }}
                            </a>

                            <span v-if="tab.id != 1" @click.prevent="deleteTab(tab.id)" class="label label-danger pull-right">x</span>
                        </h4>
                    </div>
                </div>

                <button type="button"
                        v-if="tabs.length > 0"
                        @click.prevent="addTab()"
                        class="btn btn-success btn-sm"
                        id="task-add"
                        style="margin-top: 10px;">

                    <i class="fa fa-plus"></i> Добавить
                </button>
            </div>
        </job-navigation>
    </div>
@endif