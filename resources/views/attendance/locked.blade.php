@extends('attendance.root')

@section('subContent')
    <h2>Attendance Already Recorded</h2>
    <p>{{ $message }}</p>
    <a href="{{ route('attendance.index') }}">Back</a>
@endsection