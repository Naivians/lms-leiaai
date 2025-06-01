@php
    $title = 'Dashboard';
@endphp

@extends('layouts.app')
@section('header_title', 'Dashboard')
{{-- @section('header_description', 'Your Learning Management System') --}}
@section('content')
    @include('partials.messages', ['title' => "Permission Denied"])
@endsection
