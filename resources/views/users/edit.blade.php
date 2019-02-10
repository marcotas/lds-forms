@extends('layouts.app')

@section('content')
<div class="container">
    <users-form :resource="{{ json_encode($user) }}" :permissions="{{ json_encode($permissions) }}"></users-form>
</div>
@endsection
