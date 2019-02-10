<li class="site-menu-header">Principal</li>

@can('manage.agenda')
    <li class="site-menu-item {{ active('agenda') }}">
        <a href="{{ route('agenda') }}">
            <i class="fa fa-fw icon-left fa-calendar-alt"></i>
            Agenda
        </a>
    </li>
@endcan

@can('manage.services')
    <li class="site-menu-item {{ active('services.index') }}">
        <a href="{{ route('services.index') }}">
            <i class="fa fa-fw icon-left fa-cut"></i>
            Serviços
        </a>
    </li>
@endcan

@can('manage.clients')
    <li class="site-menu-item {{ active('clients.index') }}">
        <a href="{{ route('clients.index') }}">
            <i class="fa fa-fw icon-left fa-user-tag"></i>
            Clientes
        </a>
    </li>
@endcan

<li class="site-menu-header">Configurações</li>

<li class="site-menu-item {{ active('settings.account') }}">
    <a href="{{ route('settings.account') }}">
        <i class="fa fa-fw icon-left fa-user-circle"></i>
        {{ __('Conta') }}
    </a>
</li>

@can('manage.team.settings')
    <li class="site-menu-item {{ active('settings.team') }}">
        <a href="{{ route('settings.team') }}">
            <i class="fas fa-fw icon-left fa-users"></i>
            {{ __('Equipe') }}
        </a>
    </li>
@endcan

@role('admin')
    <li class="site-menu-header">Administração</li>

    <li class="site-menu-item {{ active('users.index') }}">
        <a href="{{ route('users.index') }}">
            <i class="fas fa-fw icon-left fa-users"></i>
            {{ __('Usuários') }}
        </a>
    </li>
@endrole
