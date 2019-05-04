@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        {{ __('Conta não verificada') }}
                    </h4>

                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Um link de verificação foi enviado para seu e-mail cadastrado.') }}
                        </div>
                    @endif

                    {{ __('Por favor, para verificar sua conta confirme seu e-mail cadastrado.') }}
                    {{ __('Se você não recebeu um e-mail') }}, <a href="{{ route('verification.resend') }}">{{ __('reenvie clicando aqui') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
