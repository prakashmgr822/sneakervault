@extends('templates.index')

@section('title', 'Vendors')

@section('content_header')
    <h1>Vendors</h1>
@stop

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
@endpush

@section('index_content')
    <div class="table-responsive">
        <table class="table" id="data-table">
            <thead>
            <tr class="text-left text-capitalize">
                <th>#id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Sales(Nrs)</th>
                <th>action</th>
            </tr>
            </thead>

        </table>
    </div>
@stop

@push('scripts')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

    <script>
        $(function () {
            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('vendors.index') }}",
                columns: [
                    {data: 'id', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'total_sales', name: 'total_sales'},
                    {data: 'action', name: 'action'},
                ],
            });
        });
        setTimeout(function(){
            $('.alert').hide();
        },3000);
    </script>



@endpush
