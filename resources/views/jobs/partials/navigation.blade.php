@if(!isset($job))
    <div class="col-xs-6 col-sm-3 sidebar-offcanvas" role="navigation">
        <job-navigation :tabs="tabs" :selected="tab_selected" inline-template>
            <div id="">
                
                <h2>@lang('edit.create')</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eum veniam voluptas culpa repudiandae ullam assumenda architecto, doloremque nobis officia debitis beatae quae iste commodi consequatur, ipsum eligendi et quod fugit.</p>

                <button type="button"
                        v-if="tabs.length == 0"
                        @click.prevent="addTab()"
                        class="btn btn-primary btn-sm">

                    @lang('edit.separate')
                </button>

                <div v-if="tabs.length > 0" id="task-list" class="base-wrapper task-list nav">
                    <h3>@lang('edit.task-list')Список заданий:</h3>
                    <ol>
                        <div v-for="tab in tabs" class="media alert">
                        <li>
                            <h4 class="media-heading" role="presentation">
                                <a :class="'tab-' + tab.id + '-name'" data-toggle="tab" :href="'#task-' + tab.id" @click="activateTab(tab.id)">
                                    @{{ tab.name | truncate(20) }}
                                </a>
                                <span v-if="tab.id != 1" @click.prevent="deleteTab(tab.id)" class="label label-danger pull-right">x</span>
                            </h4>
                        </li>                  
                        </div>
                    </ol>
                <button type="button"
                        v-if="tabs.length > 0"
                        @click.prevent="addTab()"
                        class="btn btn-primary btn-sm"
                        id="task-add"
                        style="margin-top: 10px;">

                    Добавить
                </button>                      
                </div>


            </div>
        </job-navigation>
    </div>
@endif