@guest
    <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
    </li>
    <li class="nav-item">
        @if (Route::has('subscribe'))
            <a class="nav-link" href="{{ route('subscribe') }}">{{ __('Inscrever-me') }}</a>
        @endif
    </li>
@else
    <li class="nav-item dropdown top-menu">
        <a id="navbarDropdown" class="nav-link d-flex align-items-center py-2 dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="mr-2">
                <avatar :src="$user.photo_url" username="{{ Auth::user()->name }}" color="white" :size="35"></avatar>
            </div>
            <span class="d-none d-md-inline">{{ Auth::user()->name }} <span class="caret"></span></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            @if (session('admin:impersonator'))
                <h6 class="dropdown-header">{{__('Personificação')}}</h6>

                <!-- Stop Impersonating -->
                <a class="dropdown-item" href="{{ route('users.stop-impersonating') }}">
                    <i class="fa fa-fw text-left fa-btn fa-user-secret"></i> {{__('Voltar À Minha Conta')}}
                </a>

                <div class="dropdown-divider"></div>
            @endif

            @auth
                <template v-if="$teams.length">
                    <h6 class="dropdown-header">Alas</h6>

                    <a class="dropdown-item" v-for="team of $teams" :key="team.id" :href="'/teams/' + team.id + '/switch'">
                        <span v-if="$team && team.id === $team.id">
                            <i class="fa fa-fw mr-2 text-left fa-btn fa-check text-success"></i> <b>@{{ team.name }}</b>
                        </span>

                        <span v-else>
                            <img :src="team.photo_url" class="spark-profile-photo-xs mr-2"><i class="fa fa-btn"></i> @{{ team.name }}
                        </span>
                    </a>

                    <div class="dropdown-divider"></div>
                </template>
            @endauth

            <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                <i class="fas fa-fw fa-sign-out-alt mr-2"></i>
                {{ __('Sair') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </li>
@endguest
