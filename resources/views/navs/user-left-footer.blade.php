<a href="{{ route('settings.account') }}" v-tooltip="'Configurações'" class="site-menubar-footer-button">
    <i class="fa fa-tools"></i>
</a>

<a href="{{ route('settings.team') }}" v-tooltip="'Ala'" class="site-menubar-footer-button">
    <i class="fa fa-users"></i>
</a>

<a href="{{ route('logout') }}" v-tooltip="'{{ __('Sair') }}'" class="site-menubar-footer-button"
    @click.prevent="$refs.logoutForm.submit()">
    <i class="fa fa-power-off"></i>
</a>

<form ref="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
