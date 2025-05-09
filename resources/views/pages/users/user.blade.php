@php
    $title = 'User';
@endphp

@extends('layouts.app')

@section('header_title', 'User Management')

@section('content')
    <table id="myTable" class="display w-100">
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
    $(document).ready(function () {
        $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("user.Home") }}',
            dom: 'Blfrtip',
            buttons: ['copy', 'csv', 'print', 'colvis'],
            columns: [
                { data: 'id' },
                { data: 'fname' },
                { data: 'lname' },
                { data: 'ext_name' },
                { data: 'role' },
                { data: 'email' },
                { data: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endsection
