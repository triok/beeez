<job-tabs :tabs="tabs" :selected="tab_selected" inline-template>
    <ul class="nav nav-tabs job-tabs" id="task-list-nav" v-if="tabs.length > 0">
        <li v-for="tab in tabs" role="presentation" :class="selected == tab.id ? 'active' : ''">
            <a :class="'tab-' + tab.id + '-name'" data-toggle="tab" :href="'#task-' + tab.id" @click="activateTab(tab.id)">
                @{{ tab.name | truncate(20) }}
            </a>
        </li>
    </ul>
</job-tabs>