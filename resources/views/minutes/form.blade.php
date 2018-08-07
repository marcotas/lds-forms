@extends('layouts.app')

@section('content')
    <div class="container">
        <minute-form :minute="{{ $minute }}"></minute-form>
    </div>
@endsection
