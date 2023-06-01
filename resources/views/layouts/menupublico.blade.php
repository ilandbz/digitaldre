@php
    $modulos = App\Models\Modulo::where('id', 6)
    ->with([
        'getMenusActivos' => function($query) {
            $query->with('getSubmenusActivos');
        }
    ])
    ->get();
@endphp

<nav class="mt-3 mb-5">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        {{-- <li class="nav-header">MENU DE NAVEGACIÓN</li> --}}
        <li class="nav-item {{ (request()->is('home')) ? 'menu-open' : '' }}">
            <a href="{{route('home')}}" class="nav-link {{ (request()->is('home')) ? 'active' : '' }}">
                <i class="nav-icon fas fa-home" style="color: rgb(0, 93, 146)"></i>
                <p>Inicio</p>
            </a>
        </li>
        <li class="nav-item {{ (request()->is('buscar')) ? 'menu-open' : '' }}">
            <a href="{{route('buscar')}}" class="nav-link {{ (request()->is('buscar')) ? 'active' : '' }}">
                <i class="nav-icon fas fa-search"  style="color: rgb(255, 166, 0)"></i>
                <p>Buscar</p>
            </a>
        </li>

        @foreach ($modulos as $modulo)
            <li class="nav-header">
                <div class="separador"></div>
                {{$modulo->x_nombre}}
            </li>

            @foreach ($modulo->getMenusActivos as $menu)
                <li class="nav-item {{ (request()->is($menu->x_url.'/*')) ? 'menu-open' : '' }}">
                    @if ($menu->l_submenus == 'S')
                        <a href="#" class="nav-link {{ (request()->is($menu->x_url.'/*')) ? 'active' : '' }}">
                            <i class="nav-icon {{$menu->x_favicon}}" style="color: rgb(141, 141, 141)"></i>
                            <p>
                                {{$menu->x_nombre}}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ($menu->getSubmenusActivos as $submenu)
                                <li class="nav-item">
                                    <a href="{{route($submenu->x_route)}}" class="nav-link {{ (request()->is($submenu->x_url)) ? 'active' : '' }}">
                                        <i class="fas fa-circle nav-icon" style="color: rgb(255, 166, 0)"></i>
                                        <p>{{$submenu->x_nombre}}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <a href="{{route($menu->x_route)}}" class="nav-link {{ (request()->is($menu->x_url)) ? 'active' : '' }}">
                            <i class="nav-icon {{$menu->x_favicon}}" style="color: rgb(148, 0, 141)"></i>
                            <p>
                                {{$menu->x_nombre}}
                            </p>
                        </a>
                    @endif
                </li>
            @endforeach
        @endforeach
    </ul>
</nav>