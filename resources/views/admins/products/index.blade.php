@extends('templates.index')

@section('title', 'Products')

@section('content_header')
    <h1>Products</h1>
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
                <th>Vendor</th>
                <th>Brand</th>
                <th>Price(Nrs)</th>
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
                ajax: "{{ route('admin.products.index') }}",
                columns: [
                    {data: 'id', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'vendor_id', name: 'vendor_id'},
                    {data: 'brand', name: 'brand'},
                    {data: 'price', name: 'price'},
                    {data: 'action', name: 'action'},
                ],
            });
        });
        setTimeout(function(){
            $('.alert').hide();
        },3000);
    </script>



@endpush
