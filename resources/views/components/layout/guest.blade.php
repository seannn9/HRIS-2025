@extends('components.layout.root')

@section('content')
    <x-topbar />
    {{ $slot }}
@endsection