@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $team = team()->load('users.roles');
    @endphp
    <settings-team :team="{{ $team }}"></settings-team>
</div>
@endsection
