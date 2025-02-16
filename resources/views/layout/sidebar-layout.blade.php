@php
    function isActive($routes)
    {
        return in_array(Route::currentRouteName(), (array)$routes) ? 'active' : '';
    }

    $menuItems = [
        ['route' => 'dashboard', 'icon' => 'fas fa-fire', 'text' => 'Dashboard'],
        [
            'text' => 'Absen',
            'icon' => 'fas fa-fingerprint',
            'submenu' => [
                ['route' => 'absen', 'text' => 'Absen'],
                ['route' => 'data-absen', 'text' => 'Data Absen'],
            ]
        ],
        ['route' => 'detail-user', 'icon' => 'fas fa-user', 'text' => 'Profile'],
    ];

    //admin
    if (Auth::user()->jabatan_id === 1) {
        $menuItems[] = [
            'text' => 'Setting',
            'icon' => 'fas fa-cog',
            'submenu' => [
                ['route' => 'shift.index', 'text' => 'Shift'],
                ['route' => 'users.index', 'text' => 'Data Users'],
                ['route' => 'jabatan.index', 'text' => 'Jabatan'],
            ]
        ];
    }
@endphp

<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/img/infracom.jpg') }}" alt="Logo" style="max-height: 50px;">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/img/infracom.jpg') }}" alt="Logo" style="max-height: 30px;">
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">List Menu</li>
            @foreach($menuItems as $item)
                @if(isset($item['submenu']))
                    <li class="dropdown {{ isActive(collect($item['submenu'])->pluck('route')->toArray()) }}">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                            <i class="{{ $item['icon'] }}"></i> <span>{{ $item['text'] }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            @foreach($item['submenu'] as $subItem)
                                <li class="{{ isActive($subItem['route']) }}">
                                    <a class="nav-link" href="{{ route($subItem['route']) }}">{{ $subItem['text'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li class="{{ isActive($item['route']) }}">
                        <a class="nav-link" href="{{ is_array($item['route']) ? route($item['route'][0], $item['route'][1]) : route($item['route']) }}">
                            <i class="{{ $item['icon'] }}"></i> <span>{{ $item['text'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </aside>
</div>