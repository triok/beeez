<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'Testers') }}</title>
    <meta name="generator" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content=""/>
    <link href="/css/app.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    @stack('styles')
    <style>
        [v-cloak] { display: none; }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="/js/lang.js"></script>
    <link rel="apple-touch-icon" href="/bootstrap/img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/bootstrap/img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/bootstrap/img/apple-touch-icon-114x114.png">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu|Ubuntu+Condensed|Ubuntu+Mono|Noto+Sans|Fira+Sans|Comfortaa:300,400,700" rel="stylesheet">
</head>
<body>
    <div class="wrapper" id="app">
        <!-- Sidebar  -->
        <nav id="side" class="{{ (isset($_COOKIE['hide-sidebar']) ? 'active' : '') }}" style="min-height: 100vh;">
            <div class="side-header">
                <a class="navbar-brand" href="{{url('/')}}">
                    <h3>Lavoro</h3>
                </a>
            </div>
            <ul class="list-unstyled components">
                @if (Auth::guest())
                <li id="welcome" class="left-sidebar active">
                    <p>Добро пожаловать!</p>
                    <p>Зарегистрируйтесь или войдите в систему, что бы получить доступ ко всем функциям.</p>
                </li>
                <li class="left-sidebar">
                    <a href="{{url('/page/2')}}" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fa fa-hashtag fa-fw"></i>
                        <span>О площадке</span>
                    </a>
                </li>
                <li class="left-sidebar">
                    <a href="{{url('/page/5')}}" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fa fa-thumbs-o-up fa-fw"></i>
                        <span>Контакты</span>
                    </a>
                </li>                                
                @else
                <li id="welcome" class="left-sidebar active" style="text-align: center; padding: 20px 0px;">
                   <p>Добро пожаловать,</p>
                   <p>{{ Auth::user()->name }}!</p>
                </li>

<div id="accordion">
    <div class="card">
        <div class="card-header" id="headingTwo">
            <a href="#"><i class="fa fa-eye fa-fw"></i><span>@lang('layout.review')</span></a>
        </div>
    </div>
    <div class="card card-caret">
        <div class="card-header" id="headingOne">
            <a class="carets" href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              <i class="fa fa-dot-circle-o fa-fw" aria-hidden="true"></i><span>@lang('layout.jobs-manager')</span>
            </a>
        </div>
        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <ul class="list-unstyled" role="menu">
                    <li role="separator" class="divider"></li>                
                    <li><a href="{{ url('/') }}">- @lang('layout.findwork')</a></li>
                    <li role="separator" class="divider"></li>
                    <li>
                        <a href="{{route('my-applications')}}">- @lang('layout.applications')
                            <span class="badge">{{Auth::user()->applications()->where('status','!=','complete')->count()}}</span>
                        </a>
                    </li>
                    <li><a href="{{ route('projects.index') }}">- @lang('layout.projects')</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="/jobs/create">- @lang('layout.post-new')</a></li>                
                </ul>                       
            </div>
        </div>    
    </div>
    <div class="card">
        <div class="card-header" id="headingTwo">
            <a href="{{ route('vacancies.index') }}"><i class="fa fa-suitcase fa-fw"></i><span>@lang('layout.vacancies')</span></a>
        </div>
    </div>
    <div class="card card-caret">
        <div class="card-header" id="headingThree">
                <a class="carets" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                    <i class="fa fa-user-circle fa-fw" aria-hidden="true"></i><span>@lang('peoples.title')</span>
                </a>
        </div>
        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
            <div class="card-body">
                <ul class="list-unstyled" role="menu">
                    <li>
                        <a href="{{route('peoples.index')}}">- @lang('peoples.find')</a>                    
                    </li>      
                </ul>          
            </div>
        </div>    
    </div>
    <div class="card card-caret">
        <div class="card-header" id="headingFour">
                <a class="carets" href="#" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                    <i class="fa fa-group fa-fw" aria-hidden="true"></i><span>@lang('teams.title')</span>
                </a>
        </div>
        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
            <div class="card-body">
                <ul class="list-unstyled" role="menu">
                    <li role="separator" class="divider"></li>
                    <li>
                        <a href="{{route('teams.index')}}">- @lang('teams.find')</a>                    
                    </li>
                    <li>
                        <a href="{{route('teams.myteams')}}">- @lang('teams.title_myteams')</a>                    
                     </li>
                    @if(Auth::user()->allUserTeams()->count())
                    <li>
                        <a href="{{route('teams.projects')}}">- @lang('teams.projects')</a>
                    </li>
                    @endif
                    <li role="separator" class="divider"></li>                
                    <li>
                        <a href="{{route('teams.create')}}">- @lang('teams.create')</a>                    
                    </li>                                                
                </ul>         
            </div>
        </div>    
    </div>
    <div class="card card-caret">
        <div class="card-header" id="headingFive">
                <a class="carets" href="#" data-toggle="collapse" data-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                    <i class="fa fa-university fa-fw" aria-hidden="true"></i><span>@lang('organizations.title')</span>
                </a>
        </div>
        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
            <div class="card-body">
                <ul class="list-unstyled" role="menu">
                    <li role="separator" class="divider"></li>
                    <li>
                        <a href="{{route('organizations.index')}}">- @lang('organizations.all')</a>                    
                    </li>
                    <li>
                        <a href="{{route('organizations.my')}}">- @lang('organizations.title-my')</a>                    
                    </li>
                    <li role="separator" class="divider"></li>                
                    <li>
                        <a href="{{route('organizations.create')}}">- @lang('organizations.add')</a>                    
                    </li>                                                
                </ul>        
            </div>
        </div>    
    </div>
    <div class="card">
        <div class="card-header" id="headingSix">
            <a href="{{ route('my-bookmarks') }}"><i class="fa fa-bookmark fa-fw"></i><span>@lang('layout.bookmarks')</span>
                <span class="badge pull-right">{{count(Auth::user()->bookmarks)}}</span>
            </a>
        </div>
    </div>
    <div class="card card-caret">
        <div class="card-header" id="headingSeven">
                <a class="carets" href="#" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
                    <i class="fa fa-credit-card fa-fw"></i><span>@lang('layout.balance')</span>
                </a>
        </div>
        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
            <div class="card-body">
                <ul class="list-unstyled" role="menu">
                    <h6 class="dropdown-header">- @lang('balance.title'): 0 руб.</h6>
                    <li role="separator" class="divider"></li>
                    <li>
                        <a href="#">- @lang('balance.review')</a>                    
                    </li>
                    <li>
                        <a href="#">- @lang('balance.topup')</a>                    
                    </li>
                    <li role="separator" class="divider"></li>                
                    <li>
                        <a href="#">- @lang('balance.history')</a>                    
                    </li>                                                
                </ul>       
            </div>
        </div>    
    </div>
    <div class="card">
        <div class="card-header" id="headingEight">
                <a class="carets" href="#" data-toggle="collapse" data-target="#collapseEight" aria-expanded="true" aria-controls="collapseEight">
                    <i class="fa fa-comments-o fa-fw"></i><span>@lang('layout.society')</span>
                </a>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingNine">
                <a class="carets" href="#" data-toggle="collapse" data-target="#collapseNine" aria-expanded="true" aria-controls="collapseNine">
                    <i class="fa fa-check-square-o fa-fw"></i><span>@lang('layout.tasks')</span>
                </a>
        </div>
    </div>        
</div>

          
                @endif
            </ul>
        </nav>
        <!-- Page Content  -->
        <div id="content" class="" style="min-height: 500px; padding-top: 0px">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <ul class="nav navbar-nav">
                        <li class="button-collapse">
                            <button type="button" id="sidebarCollapse" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i>
                            </button>                             
                        </li>
<!--                         <li>
                            <a href="{{ url('setlocale/en') }}">En</a>                 
                        </li>
                        <li>
                            <a href="{{ url('setlocale/ru') }}"> Ru</a>                          
                        </li>   -->                  
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
<!--                         <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ currency()->getUserCurrency() }}<span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                @foreach(currency()->getActiveCurrencies() as $currency)
                                <li>
                                    <a href="?currency={{ $currency['code'] }}" class="btn btn-link btn-block">
                                    {{ $currency['code'] }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li> -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">@lang('layout.login-title')</a></li>
                            <li><a href="{{ route('register') }}">@lang('layout.register-title')</a></li>
                        @else
                            <li class="dropdown">
                                @role('admin')
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-expanded="false">
                                    <i class="fa fa-dot-circle-o" aria-hidden="true"></i> @lang('layout.jobs-manager')<span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ url('/') }}"><i class="fa fa-search"></i> @lang('layout.findwork')</a></li>

                                    <li>
                                        <a href="{{route('my-applications')}}"><i class="fa fa-briefcase"></i> @lang('layout.applications')
                                            <span class="badge">{{Auth::user()->applications()->where('status','!=','complete')->count()}}</span>
                                        </a>
                                    </li>
                                    <li><a href="{{ route('projects.index') }}"><i class="fa fa-sitemap"></i> @lang('layout.projects')</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="/jobs/create"><i class="fa fa-plus-circle"></i> @lang('layout.post-new')</a></li>

                                    @permission('read-jobs-manager')
                                    <li role="separator" class="divider"></li>
                                    <li>
                                        <a href="{{ route('jobs-admin') }}"><i class="fa fa-dashboard"></i> @lang('layout.current-tasks')</a>
                                    </li>
                                    @endpermission
                                    @permission('read-job-categories')
                                    <li><a href="{{ route('categories.index') }}"><i class="fa fa-th-list"></i>
                                            Categories</a></li>
                                    @endpermission
                                    @permission('read-job-skills')
                                    <li>
                                        <a href="{{ route('skills.index') }}"><i class="fa fa-th-list"></i> Skills</a>
                                    </li>
                                    @endpermission
                                    @permission('read-job-applications')
                                    <li role="separator" class="divider"></li>
                                    <li><a href="{{ route('applications-admin') }}"><i class="fa fa-briefcase"></i>
                                            Job Applications</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li role="separator" class="divider"></li>
                                    @endpermission

                                    
                                </ul>
                            </li>
                            <li><a href="{{route('peoples.index')}}"><i class="fa fa-user-circle"></i> @lang('peoples.title')</a></li>
                            <li><a href="{{route('teams.index')}}"><i class="fa fa-group"></i> @lang('teams.title')</a></li>  
                            @endrole

                            @permission('read-payouts')
                            <li><a href="/payouts"><i class="fa fa-money"></i> Payouts</a></li>
                            @endpermission

                            @permission('read-pages')
                            <li><a href="admin/pages"><i class="fa fa-file-powerpoint-o"></i> Pages</a></li>
                            @endpermission

                            <li>
                                <a href="{{route('messages')}}" title="@lang('messages.title')">
                                    <i class="fa fa-envelope"></i>
                                    @include('messenger.unread-count')
                                </a>
                            </li>

                            <li>
                                <a href="{{route('notifications.index')}}" title="Уведомления">
                                    <i class="fa fa-bell"></i>
                                    @include('notifications.unread-count')
                                </a>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-expanded="false">
                                    <i class="fa fa-user-circle"></i><span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="/account"><i class="fa fa-user"></i> @lang('layout.profile')</a></li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                                 document.getElementById('logout-form').submit();">
                                            <i class="fa fa-lock"></i> @Lang('layout.logout')
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                              style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>                                

                                    @role('admin')
                                    @permission('read-users')
                                    <li><a href="{{ route('users') }}"><i class="fa fa-group"></i> Users</a></li>
                                    @endpermission
                                    <li><a href="/admin/settings"><i class="fa fa-wrench"></i> Settings</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="/roles"><i class="fa fa-key"></i> Roles</a></li>
                                    @permission('read-logs')
                                    <li><a href="/admin/logs"><i class="fa fa-history"></i> System logs</a></li>
                                    <li><a href="/admin/debug"><i class="fa fa-bug"></i> Debug logs</a></li>
                                    @endpermission
                                    @endrole                                
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </nav>
            <div v-cloak>
                <div class="row">

                <div style="min-height: 30vw">@yield('content')</div>

                </div>                
            </div>

            @include('notifications.modal')

            <footer>
                <div class="container">
                    <hr>
                    <ul class="list-inline footer-list">
                        <li>@lang('layout.sign')</li>
                        @foreach ($pages as $page)
                        <li><a href="/page/{{ $page->id }}">{{ $page->title }}</a></li>
                        @endforeach
                    </ul>
                    <hr>
                    <ul class="list-inline footer-social">
                        <li><a href="#" rel="nofollow"><img src="/img/linkedin.svg"></a></li>
                        <li><a href="#" rel="nofollow"><img src="/img/twitter.svg"></a></li>
                        <li><a href="#" rel="nofollow"><img src="/img/whatsup.svg"></a></li>
                    </ul>
                </div>
            </footer>
        </div>
    </div>
    <button id="back2Top" class="btn btn-default btn-sm" title="Back to top"><i class="fa fa-chevron-up"></i></button>
    <script src="/js/app.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/plugins/listjs/listjs.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script src="/js/menucollapse.js"></script>
    <script src="/plugins/moment.min.js" type="text/javascript"></script>    
    <script>
        moment.locale('{{ config('app.locale') }}');

        $(".date-short").html(function(index, value) {
            if(value !== '') {
                return moment(value, "YYYY-MM-DD mm:ss").format("ll");
            }

            return '';
        });

        $(".date-full").html(function(index, value) {
            if(value !== '') {
                return moment(value, "YYYY-MM-DD mm:ss").format("lll");
            }

            return '';
        });

        $(".date-ago").html(function(index, value) {
            if(value !== '') {
                return moment(value, "YYYY-MM-DD mm:ss").startOf('hour').fromNow();
            }

            return '';
        });

    </script>

    @if(Auth::check())
        <script src="/js/main.js" type="text/javascript"></script>
    @else
    <script type="text/javascript">
        $('document').ready(function () {
            $('.share-job-btn,.bookmark-job,.apply-job-btn,.box .job-desc, .box .panel-heading h4')
                .click(function () {
                    return notice('Please login to enable feature! <a href="/login">click to login</a>', 'error');
                });
        });
    </script>
    @endif
    @role('admin')
    <script src="/js/admin.js"></script>
    @endrole

    @if(config('app.env')=='production')
        <script>

            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
            ga('create', '', '');
            ga('send', 'pageview');
        </script>
    @endif


    @include('partials.flash')

    @stack('scripts')
    @stack('modals')
</body>
</html>