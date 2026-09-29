@include(request()->is('admin', 'admin/*') && auth()->user()?->is_admin ? 'admin.errors.403' : 'pages.forbidden')
