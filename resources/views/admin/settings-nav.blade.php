<div class="col-md-3 bg-gray admin-nav">
    <button class="btn btn-info btn-xs admin-nav-btn dropdown-toggle hidden-lg hidden-sm hidden-md"
            data-toggle="dropdown"><i class="fa fa-gear"></i> Settings menu
    </button>
    <ul class="nav nav-pills nav-stacked margin-bottom-10 hidden-xs">

        <li class="{{Request()->segment(2)=='settings'?'active':''}}">
            <a href="/admin/settings">
                <i class="fa fa-cogs"></i> Settings </a>
        </li>
        <li class="{{Request()->segment(1)=='users'?'active':''}}">
            <a href="/users">
                <i class="fa fa-group"></i> Users </a>
        </li>
        <li class="{{Request()->segment(1)=='roles'?'active':''}}">
            <a href="/roles">
                <i class="fa fa-key"></i> Roles/Permissions </a>
        </li>
        <li class="{{Request()->segment(2)=='moderation'?'active':''}}">
            <a href="{{route('organizations.moderation')}}">
                <i class="fa fa-university"></i> Moderation </a>
        </li> 
        <li class="{{Request()->segment(2)=='skills'?'active':''}}">
            <a href="{{ route('skills.index') }}">
                <i class="fa fa-graduation-cap"></i> Skills </a>
        </li>
        <li class="{{Request()->segment(2)=='categories'?'active':''}}">
            <a href="{{ route('categories.index') }}">
                <i class="fa fa-th-list"></i> Categories </a>
        </li>
        <li class="{{Request()->segment(2)=='jobs'?'active':''}}">
            <a href="{{ route('jobs-admin') }}">
                <i class="fa fa-dashboard"></i> Jobs </a>
        </li>                                         
        <li class="{{Request()->segment(2)=='pages'?'active':''}}">
            <a href="/admin/pages">
                <i class="fa fa-file-powerpoint-o"></i> Pages </a>
        </li>        
        <li class="{{Request()->segment(2)=='logs'?'active':''}}">
            <a href="/admin/logs">
                <i class="fa fa-bug"></i> Logs </a>
        </li>
        <li class="{{Request()->segment(2)=='debug'?'active':''}}">
            <a href="/admin/debug">
                <i class="fa fa-bug"></i> Debug Log </a>
        </li>
    </ul>

</div>