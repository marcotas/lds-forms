@extends('layouts.app')

@section('content')
<div class="container">
    <users-form :permissions="{{ json_encode($permissions) }}"></users-form>
</div>
@endsection
