@php
    $title = 'User Detailes';
@endphp

@extends('layouts.app')

@section('header_title', 'User Information')

@section('content')
    <div class="card mb-5">
        <div class="card-body d-flex align-items-start gap-1">
            <div class="view_img_container border border-1 p-1">
                <img src="{{ asset('assets/img/sample.jpg') }}" alt="User Image">
            </div>
            <div class="view_content border border-1 w-100 p-2">
                <p class="fs-5">First Name: <span class="fw-bold">John</span></p>
                <p class="fs-5">Last Name: <span class="fw-bold">Doe</span></p>
                <p class="fs-5">Middle Name: <span class="fw-bold">Manases</span></p>
            </div>
        </div>
    </div>
    <div class="card my-2">
        <div class="card-header d-flex align-items-center">
            <i class="fa-solid fa-graduation-cap fs-1 me-2"></i>
            <h2 class="m-0">Class Enrolled</h2>
        </div>
    </div>
    <div class="class_grid_container mb-5">
        <div class="card">
            <img src="{{ asset('assets/img/leiaai_logo.png') }}" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title fw-bold">ATPL Class Batch - 2025</h5>
                <p class="card-text">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quidem, corporis!</p>
            </div>
        </div>
        <div class="card">
            <img src="{{ asset('assets/img/leiaai_logo.png') }}" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title fw-bold">CPL Class Batch - 2025</h5>
                <p class="card-text">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quidem, corporis!</p>
            </div>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-header d-flex align-items-center">
            <i class="fa-solid fa-bookmark fs-1 me-2"></i>
            <h2 class="m-0">Assessment Progress</h2>
        </div>
    </div>
    <table id="view_user_progress" class="display w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Suffix</th>
                <th>Role</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#view_user_progress').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('user.Home') }}',
                dom: 'Blfrtip',
                buttons: ['copy', 'csv', 'print', 'colvis'],
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'fname'
                    },
                    {
                        data: 'lname'
                    },
                    {
                        data: 'ext_name'
                    },
                    {
                        data: 'role'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endsection
