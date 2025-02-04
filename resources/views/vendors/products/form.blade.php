
<div class="form-group row">
    <div class="col-6">
        <label for="">Name <span class="text-danger">*</span></label>
        <input type="text" required class="form-control" name="name" value="{{ old('name',$item->name) }}"
               placeholder="Enter Name">
    </div>
    <div class="col-6">
        <label for="">Brand</label>
        <input type="text" required class="form-control" name="brand" value="{{ old('brand',$item->brand) }}"
               placeholder="Enter Product Brand">
    </div>
</div>


<div class="form-group row">
    <div class="col-md-6">
        <label for="">Price <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="price" value="{{ old('price',$item->price) }}"
               placeholder="Enter price" required>
    </div>
    <div class="col-md-6">
        <label for=""> Quantity <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="stock_quantity" value="{{ old('stock_quantity',$item->stock_quantity) }}"
               placeholder="Enter quantity" required>
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
        <label for=""> Size</label>
        <input type="text" name="size" class="form-control" value="{{ old('size',$item->size) }}">
    </div>
</div>

<div class="form-group row">
    <div class="col-md-12">
        <label for="description">Description</label>
        <textarea name="description" id="description" class="form-control"
                  rows="4">{{ old('description', $item->description) ?? ''}}</textarea>
    </div>
</div>

