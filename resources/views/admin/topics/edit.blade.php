@extends('layouts.app')

@section('content')
<div class="container">
    <topic-form :topic="{{ json_encode($topic) }}"></topic-form>
</div>
@endsection
