{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-title">Rutas</li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('customer') }}"><i class="nav-icon la la-users "></i> Clientes</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('credit') }}"><i class="nav-icon  la la-money"></i> Creditos</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('product') }}"><i class="nav-icon la la-cube "></i> Productos</a></li>

<!--  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('payment-history') }}"><i class="nav-icon la la-question"></i> Payment histories</a></li> -->

<li class="nav-item nav-dropdown"><a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-lg la-briefcase "></i> Administración</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('vehicle') }}"><i class="nav-icon la la-car "></i> Vehiculos</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('route') }}"><i class="nav-icon la la-road  "></i> Rutas </a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('credit-status') }}"><i class="nav-icon la la-check-circle"></i> Estados Crediticios</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('payment-type') }}"><i class="nav-icon la la-credit-card "></i> Tipos de Pagos</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('billing-schedule') }}"><i class="nav-icon la la-calendar-o"></i> Recaudaciones</a></li>

        <li class="nav-item nav-dropdown"><a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-lg la la-map "></i> Direcciones</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('province') }}"><i class="nav-icon la la-dot-circle-o "></i> Provincias</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('canton') }}"><i class="nav-icon la la-dot-circle-o "></i> Cantones</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('distrit') }}"><i class="nav-icon la la-dot-circle-o "></i> Distritos</a></li>
            </ul>
        </li>

    </ul>
</li>


<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> Users</a></li>


