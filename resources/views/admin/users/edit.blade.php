@extends('layouts.app')

@section('content')
<div class="container">
    <user-form :user="{{ json_encode($user) }}"></user-form>
</div>
@endsection
