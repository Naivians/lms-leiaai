@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
             title: @json($title),
            text: 'Please check the following errors:',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonText: 'OK'
        });
    </script>
@endif
