@extends('layouts.app')

@section('content')
    <speeches-list :data="{{ json_encode($speeches) }}"></speeches-list>
@endsection
