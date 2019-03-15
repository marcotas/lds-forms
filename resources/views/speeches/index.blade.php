@extends('layouts.app')

@section('content')
    <speeches :data="{{ $speeches }}"></speeches>
@endsection
