<!DOCTYPE html>
<html lang="Zh_cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#52768e">
    <title>@yield('title') Dashboard {{ $site_title or '' }}</title>
    <link href="{{ mix('css/admin.css') }}" rel="stylesheet">
    @yield('css')
    <script>
        window.XblogConfig = <?php echo json_encode([
            'csrfToken' => csrf_token(),
            'github_username' => isset($github_username) ? $github_username : '',
        ]);?>
    </script>
    <?php
    $has_sidebar_image = isset($admin_sidebar_bg_image) && $admin_sidebar_bg_image;
    ?>
    @if($has_sidebar_image)
        <style>
            .sidebar-wrapper {
                background: url({{ $admin_sidebar_bg_image }}) no-repeat center center;
                background-size: cover;
            }
        </style>
    @endif
</head>
<body>
<div class="main">
    <div class="sidebar-wrapper bg-placeholder" id="sidebar-wrapper">
        <div class="p-3" style="{{ $has_sidebar_image ?'background-color: rgba(16,16,16,0.5);height: 100%;':'' }}">
            <div class="sidebar-header">
                <button class="sidebar-toggler" type="button" data-toggle="collapse" data-target="#sidebar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="sidebar-toggler-icon"></span>
                </button>
                <a href="{{ route('admin.index') }}" class="admin-brand">Admin</a>
            </div>
            <div class="collapse sidebar" id="sidebar">
                <?php
                $menus = [
                    [
                        'name' => 'Dashboard',
                        'icon' => 'tachometer',
                        'route' => 'admin.index'
                    ],
                    [
                        'name' => 'Writing',
                        'icon' => 'pencil',
                        'route' => 'post.create'
                    ],
                    [
                        'is_parent' => true,
                        'name' => 'Resources',
                        'icon' => 'list-alt',
                        'children' => [
                            [
                                'name' => 'Posts',
                                'icon' => 'book',
                                'route' => 'admin.posts'
                            ],
                            [
                                'name' => 'Pages',
                                'icon' => 'file-text',
                                'route' => 'admin.pages'
                            ],
                            [
                                'name' => 'Comments',
                                'icon' => 'comments',
                                'route' => 'admin.comments'
                            ],
                            [
                                'name' => 'Tags',
                                'icon' => 'tags',
                                'route' => 'admin.tags'
                            ],
                            [
                                'name' => 'Categories',
                                'icon' => 'folder',
                                'route' => 'admin.categories'
                            ],
                            [
                                'name' => 'Users',
                                'icon' => 'users',
                                'route' => 'admin.users'
                            ],
                            [
                                'name' => 'IP',
                                'icon' => 'internet-explorer',
                                'route' => 'admin.ips'
                            ],
                            [
                                'name' => 'Images',
                                'icon' => 'image',
                                'route' => 'admin.images'
                            ],
                            [
                                'name' => 'Files',
                                'icon' => 'file-archive-o',
                                'route' => 'admin.files'
                            ]
                        ]
                    ],
                    [
                        'name' => 'Settings',
                        'icon' => 'cog',
                        'route' => 'admin.settings'
                    ],
                    [
                        'name' => 'Failed jobs',
                        'icon' => 'heartbeat',
                        'route' => 'admin.failed-jobs'
                    ],
                ]
                ?>
                <div class="nav-wrapper">
                    <nav class="nav flex-column nav-pills">
                        @foreach( $menus as $menu)
                            @if(isset($menu['is_parent']) && $menu['is_parent'])
                                <?php
                                foreach ($menu['children'] as $children_menu) {
                                    if (route($children_menu['route']) == request()->url()) {
                                        $show = true;
                                        break;
                                    } else
                                        $show = false;
                                }
                                ?>
                                <a class="nav-link{{ $show ? ' active':' collapsed' }}" role="tab" data-toggle="collapse" href="#{{ $menu['name'] }}" aria-expanded="false">
                                    <i class="fa fa-{{ $menu['icon'] }} fa-fw mr-3"></i>
                                    {{ $menu['name'] }}
                                </a>
                                <div class="collapse {{ $show ? ' show':'' }}" id="{{ $menu['name'] }}">
                                    <div class="nav-wrapper">
                                        <nav class="nav nav-pills flex-column">
                                            @foreach( $menu['children'] as $children_menu)
                                                <?php $link = route($children_menu['route']);?>
                                                <a class="ml-3 my-1 nav-link {{ $link == request()->url() ? ' active':'' }}" role="tab" href="{{ $link }}">
                                                    <i class="fa fa-{{ $children_menu['icon'] }} fa-fw mr-3"></i>
                                                    {{ $children_menu['name'] }}
                                                </a>
                                            @endforeach
                                        </nav>
                                    </div>
                                </div>
                            @else
                                <?php $link = route($menu['route']);?>
                                <a class="nav-link {{ $link == request()->url() ? ' active':'' }}" role="tab" href="{{ $link }}">
                                    <i class="fa fa-{{ $menu['icon'] }} fa-fw mr-3"></i>
                                    {{ $menu['name'] }}
                                </a>
                            @endif
                        @endforeach
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="content-wrapper">
        <div class="pt-3 pr-3 pl-3">
            <div class="content-header">
                <div class="content-header-title">
                    <h6 class="content-header-title-des">Dashboards</h6>
                    <h2 class="content-header-title-det mt-0">@yield('title')</h2>
                </div>
                <div class="content-header-action">
                    @yield('action')
                </div>
            </div>
            <hr class="divider mt-3">
        </div>
        <div class="p-3">
            @include('partials.msg')
            @yield('content')
        </div>
    </div>
</div>

<script src="{{ mix('js/app.js') }}"></script>
@yield('script')
</body>
</html>
