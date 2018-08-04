@extends('layouts.app')

@section('content')
<div class="container">
    <minutes-list :minutes="{{ $minutes }}"></minutes-list>
</div>
@endsection
