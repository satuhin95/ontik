@extends('admin.layout')
@section('style')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<!-- Theme style -->
@endsection
@section('contain')
  <div class="card-body">
    <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <a class="btn btn-success" href="{{route('product.create')}}">Create Product</a>
          </h3>
        </div>
    <div>
      <form method="GET" action="{{ route('product') }}">
        <div class="row">
            <div class="col-md-2">
                <input type="text" name="title" class="form-control" placeholder="Search by Title">
            </div>
            <div class="col-md-2">
                <select name="category_id" class="form-control" id="category_id">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="subcategory_id" class="form-control" id="subcategory_id">
                  <option value="">Select Subcategory</option>
                </select>
            </div>
            <div class="col-md-2">
              <input type="number" name="min_price" class="form-control" placeholder="Min Price">
          </div>
          <div class="col-md-2">
              <input type="number" name="max_price" class="form-control" placeholder="Max Price">
          </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>
    </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
              <th>Id</th>
              <th>Title</th>
              <th>Category</th>
              <th>Subcategory</th>
              <th>Price</th>
              <th>Thumbnail</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
              @foreach ($products as $product)
                  
            <tr>
              <td>{{$product->id}}</td>
              <td>{{$product->title}}</td>
              <td>{{$product->category->title}}</td>
              <td>{{$product->subcategory->title}}</td>
              <td>{{$product->price}}</td>
              <td>
                <img src="{{$product->thumbnail}}" alt="{{$product->name}}"/>
              </td>
  
              <td>
                <button class="deleteProduct btn btn-danger" data-product-id="{{ $product->id }}">Delete</button>

              </td>
            </tr>
            @endforeach
            

            </tbody>
    
          </table>
        </div>
        <!-- /.card-body -->
      </div>

  </div>


@endsection
@section('script')

<!-- DataTables  & Plugins -->
<script src="{{asset('admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>

<script>
       $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

      // get subcategory
        var subcategorySelected = '{{ old('subcategory_id') }}';
            $('#category_id').change(function () {
                var categoryID = $(this).val();
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

    // delete product 
    $('.deleteProduct').click(function() {
        let productId = $(this).data('product-id');
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
        $.ajax({
            type: 'DELETE',
            url: '/product/destroy/' + productId,
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
              Swal.fire('Deleted!', 'Product has been deleted.', 'success').then(function(){
                location.reload();
              })
            },
            error: function(xhr, status, error) {
              Swal.fire('Error!', 'An error occurred while deleting the product.', 'error');

            }
        });
      }
        })
    });
        
</script>
@endsection