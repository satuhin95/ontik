@extends('admin.layout')

@section('style')
 <link rel="stylesheet" type="text/css" href="https://jhollingworth.github.io/bootstrap-wysihtml5//lib/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://jhollingworth.github.io/bootstrap-wysihtml5//lib/css/prettify.css"><
    <link rel="stylesheet" type="text/css" href="https://jhollingworth.github.io/bootstrap-wysihtml5//src/bootstrap-wysihtml5.css">
@endsection
@section('contain')
<div class="card-body">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <a class="btn btn-success " href="{{ route('product') }}">Product List</a>
            </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form  id="productForm">
                        @csrf
                        <div class="card-body">
                            <div class="form-group {{ $errors->has('category_id') ? 'has-error' : '' }}">
                                <label>Category Name <span style="color: red;">*</span> </label>
                                <select class="form-control select2" name="category_id" id="category_id"
                                    style="width: 100%;">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                            </div>
                            <div class="form-group {{ $errors->has('subcategory_id') ? 'has-error' : '' }}">
                                <label>Subcategory Name <span style="color: red;">*</span> </label>
                                <select class="form-control select2" name="subcategory_id" id="subcategory_id"
                                    style="width: 100%;">
                                    <option value="">Select Category</option>
 
                                </select>
                                @error('category_id')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                            </div>
                            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                <label>Title <span style="color: red;">*</span> </label>
                                <input type="text" class="form-control" name="title"
                                    value="{{ old('title') }}" id="title"
                                    placeholder="Enter Title">
                                @error('title')
                                    <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                <label>Description<span style="color: red;">*</span> </label>
                                <input type="text" class="form-control description" name="description"
                                    value="{{ old('description') }}"  id="description" placeholder="Enter Description">
                                @error('description')
                                    <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
            
                        
                            <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
                                <label for="price">Price</label>
                                <input type="text" class="form-control" name="price"
                                    value="{{ old('price') }}" id="price" placeholder="Enter price">
                                @error('price')
                                    <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group {{ $errors->has('thumbnail') ? 'has-error' : '' }}">
                                <label for="thumbnail">Thumbnail</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="thumbnail"
                                            id="thumbnail" value="{{ old('thumbnail') }}">
                                        <label class="custom-file-label" for="thumbnail">Choose file</label>
                                        @error('thumbnail')
                                            <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->


            </div>
        </div>
        <!-- /.card-body -->
    </div>

</div>

 
@endsection
@section('script')


<script src="https://jhollingworth.github.io/bootstrap-wysihtml5//lib/js/wysihtml5-0.3.0.js"></script>
<script src="https://jhollingworth.github.io/bootstrap-wysihtml5//src/bootstrap-wysihtml5.js"></script>

    <script>
        $(document).ready(function() {
          $('.description').wysihtml5();
        });
                 
        $(function () {
            // get subcategory start  
            var subcategorySelected = '{{ old('subcategory_id') }}';
            $('#category_id').change(function () {
                var categoryID = $(this).val();
                $('#subcategory_id').html('<option value="">Select SubCategory</option>');
                if (categoryID != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_subcategory') }}",
                        data: { categoryID: categoryID }
                    }).done(function( data ) {
                        $.each(data, function( index, item ) {
                            if (subcategorySelected == item.id)
                                $('#subcategory_id').append('<option value="'+item.id+'" selected>'+item.title+'</option>');
                            else
                                $('#subcategory_id').append('<option value="'+item.id+'">'+item.title+'</option>');
                        });
                    });
                }
            });

            $('#category_id').trigger('change');
              // get subcategory end     

            // create product 
            $('#productForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '/product/create',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire('Created!', 'Product Create Successfully.', 'success').then(function(){
                            location.reload();
                        })
                    },
                    error: function(xhr, status, error) {
                        alert('Error creating product: ' + error);
                        Swal.fire('Error!', 'An error occurred while create the product.', 'error');
                    }
                });
            });
        });
    </script>
@endsection