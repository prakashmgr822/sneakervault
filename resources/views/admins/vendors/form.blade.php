<div class="form-group row">
    <div class="col-6">
        <label for="">Name <span class="text-danger">*</span></label>
        <input type="text" required class="form-control" name="name" value="{{ old('name',$item->name) }}"
               placeholder="Enter Name">
    </div>
    <div class="col-6">
        <label for="">Phone <span class="text-danger">*</span></label>
        <input type="tel" required class="form-control" name="phone" value="{{ old('phone',$item->phone) }}"
               placeholder="Enter Vendor's Phone Number">
    </div>
</div>

<div class="form-group row">
    <div class="col-6">
        <label for="">Email <span class="text-danger">*</span></label>
        <input type="text" required class="form-control" name="email" value="{{ old('email',$item->email) }}"
               placeholder="Enter Vendor's Email Address">
    </div>
    <div class="col-6">
        {{--        <label for="">Password <span class="text-danger">*</span></label>--}}
        {{--        <div style="position: relative">--}}
        {{--            <input type="password" name="password" class="form-control pr-5" placeholder="Enter Password"--}}
        {{--                   autocomplete="current-password" @if(isset($password))value="{{ old('password',$password) }}"--}}
        {{--                   @endif required id="password">--}}
        {{--            <span class="far fa-eye" id="togglePassword"--}}
        {{--                  style="position: absolute; top: 13px; right: 13px; cursor: pointer;"></span>--}}
        {{--        </div>--}}
        <label for="">Password @if($routeName == "Create") * @endif</label>
        <div style="position: relative">
            <input type="password" name="password" class="form-control pr-5" placeholder="Enter Password"
                   autocomplete="current-password" @if(isset($password))
                   @endif @if($routeName == "Create") required @endif id="password">
            <span class="far fa-eye" id="togglePassword"
                  style="position: absolute; top: 13px; right: 13px; cursor: pointer;"></span>

            @if($routeName == "Edit")
                <span class="text-muted">Leave Blank To Remain Unchanged</span>
            @endif
        </div>
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
        jQuery(document).ready(function () {
            $('#togglePassword').click(function (e) {
                const type = $('#password').attr('type') === 'password' ? 'text' : 'password';
                $('#password').attr('type', type);
                $(this).toggleClass('fa-eye-slash');
            })
        });
    </script>
@endpush
