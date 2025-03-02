@extends('templates.index')

@section('title', 'Orders')

@section('content_header')
    <h1>Orders</h1>
@stop

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
@endpush

@section('index_content')
    <div class="table-responsive">
        <table class="table" id="data-table">
            <thead>
            <tr class="text-left text-capitalize">
                <th>#</th>
                <th>Order ID</th>
                <th>User</th>
                <th>Products</th>
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
                ajax: "{{ route('orders.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'id', name: 'id'},
                    {data: 'user', name: 'user'},
                    {data: 'products', name: 'products'},],
            });
        });

        setTimeout(function(){
            $('.alert').hide();
        }, 3000);
    </script>



@endpush
