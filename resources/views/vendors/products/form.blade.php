<div class="form-group row">
    <div class="col-6">
        <label for="">Name <span class="text-danger">*</span></label>
        <input type="text" required class="form-control" name="name" value="{{ old('name',$item->name) }}"
               placeholder="Enter Name">
    </div>
    <div class="col-6">
        <label for="">Brand <span class="text-danger">*</span></label>
        <input type="text" required class="form-control" name="brand" value="{{ old('brand',$item->brand) }}"
               placeholder="Enter Product Brand">
    </div>
</div>


<div class="form-group row">
    <div class="col-md-6">
        <label for=""> Image</label>
        <input type="file" name="image" class="form-control" value="{{ old('dob',$item->image) }}">
        @if($item->getImage())
            <div class="mt-2">
                <img src="{{ $item->getImage() }}" alt="image" width="300px" height="300px">
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <label for="">Price <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="price" value="{{ old('price',$item->price) }}"
               placeholder="Enter price" required>
    </div>
</div>


<div class="card card-custom  my-8">
    <div class="card-header">
        <h3 class="card-title">
            Sizes
        </h3>
        <div class="card-toolbar">
            <div class="example-tools justify-content-center">
                <button id="add_size" type="button" class="btn btn-primary btn-sm float-right">
                    Add Size
                </button>
            </div>
        </div>
    </div>

    <!--begin::Form-->
    <form>
        <div class="card-body">
            <table class="table table-xl mb-0 thead-border-top-0">
                <thead>
                <tr>
                    <th class="w-30 text-center">Size
                    <th class="w-20 text-center">Quantity</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="list" id="data">
                @if($method === "create")
                    <tr>
                        <td>
                            <input name="size[]" type="text"
                                   class="sizes form-control"
                                   required>
                        </td>
                        <td>
                            <input name="quantity[]" type="text"
                                   class="quantities form-control "
                                   autocomplete="off" required>
                        </td>
                    </tr>
                @endif
                @forelse($item->product_sizes ?? [] as $variation)
                    <tr>
                        <td>
                            <input name="size[]" type="text" value="{{ $variation['name'] }}"
                                   class="sizes form-control"
                                   required>
                        </td>
                        <td>
                            <input name="quantity[]" type="text" value="{{ $variation['value'] }}"
                                   class="quantities form-control "
                                   autocomplete="off" required>
                        </td>
                        @if(!$loop->first)
                            <td>
                                <a onclick="removeRow(this)">
                                <span class="symbol symbol-35 symbol-light-danger">
                                    <span class="symbol-label font-size-h5 font-weight-bold"
                                          style="cursor: pointer"><i class="fas fa-times-circle text-danger fa-lg"
                                                                     style="margin-top: 10%"></i></span>
                                </span>
                                </a>
                            </td>
                        @endif
                    </tr>
                @empty

                @endforelse
                </tbody>
            </table>
        </div>
    </form>
</div>


<div class="card card-custom  my-8">
    <div class="card-header">
        <h3 class="card-title">
            Product Specifications
        </h3>
        <div class="card-toolbar">
            <div class="example-tools justify-content-center">
                <button id="add_new_row" type="button" class="btn btn-primary btn-sm float-right">
                    Add Specification
                </button>
            </div>
        </div>
    </div>

    <!--begin::Form-->
    <form>
        <div class="card-body">
            <table class="table table-xl mb-0 thead-border-top-0">
                <thead>
                <tr>
                    <th class="w-30 text-center">Name
                    <th class="w-20 text-center">Value</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="list" id="items">
                @forelse($item->specifications ?? [] as $variation)
                    <tr>
                        <td>
                            <input name="specification_name[]" type="text" value="{{ $variation['name'] }}"
                                   class="specficationName form-control"
                                   required>
                        </td>
                        <td>
                            <input name="specification_value[]" type="text" value="{{ $variation['value'] }}"
                                   class="specficationValue form-control "
                                   autocomplete="off" required>
                        </td>
                        <td>
                            <a onclick="removeRow(this)">
                                <span class="symbol symbol-35 symbol-light-danger">
                                    <span class="symbol-label font-size-h5 font-weight-bold"
                                          style="cursor: pointer"><i class="fas fa-times-circle text-danger fa-lg"
                                                                     style="margin-top: 10%"></i></span>
                                </span>
                            </a>
                        </td>


                    </tr>
                @empty

                @endforelse
                </tbody>
            </table>
        </div>
    </form>
</div>

<div class="form-group row">
    <div class="col-md-12">
        <label for="description">Description</label>
        <textarea name="description" id="description" class="form-control"
                  rows="4">{{ old('description', $item->description) ?? ''}}</textarea>
    </div>
</div>

@push('scripts')
    <script>
        $("#add_new_row").click(function () {
            addProductRow();
        });

        $("#add_size").click(function () {
            addSizeRow();
        });

        function addProductRow() {
            var productItems = $('#items');

            productItems.append(`
            <tr class="details">
                 <td>
                    <input name="specification_name[]" type="text" class="specficationName form-control priceListener" required>
                 </td>
                <td>
                    <input name="specification_value[]" type="text" class="specficationValue form-control price_input priceListener" autocomplete="off" required>
                </td>
                <td>
                    <a onclick="removeRow(this)">
                        <span class="symbol symbol-35 symbol-light-danger">
                            <span class="symbol-label font-size-h5 font-weight-bold" style="cursor: pointer"><i class="fas fa-times-circle text-danger fa-lg" style="margin-top: 10%"></i></span>
                        </span>
                    </a>
                </td>


            </tr>
        `);
        }

        function addSizeRow() {
            var sizeItems = $('#data');

            sizeItems.append(`
            <tr class="details">
                 <td>
                    <input name="size[]" type="text" class="sizes form-control priceListener" required>
                 </td>
                <td>
                    <input name="quantity[]" type="text" class="quantities form-control price_input priceListener" autocomplete="off" required>
                </td>
                <td>
                    <a onclick="removeRow(this)">
                        <span class="symbol symbol-35 symbol-light-danger">
                            <span class="symbol-label font-size-h5 font-weight-bold" style="cursor: pointer"><i class="fas fa-times-circle text-danger fa-lg" style="margin-top: 10%"></i></span>
                        </span>
                    </a>
                </td>


            </tr>
        `);
        }

        function removeRow(elem) {
            $(elem).closest('tr').remove();
        }

    </script>
@endpush

