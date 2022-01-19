@extends('layouts.app')

@section('content')

<!-- Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form method="post" id="AddProductForm" enctype="multipart/form-data">

            <div class="modal-body">
                <ul class="alert alert-warning d-none" id="errorList"></ul>
                <div class="form-group mb-3">
                    <label>Name</label>
                    <select name="subcategory_id" id="brand_id" class="form-control" >

                        <option value="" selected="" disabled="">Select SubCategory</option>
                        @foreach ($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}">{{ $subcategory->title }}</option>
                        @endforeach

                    </select>
                </div>
                <div class="form-group mb-3">
                    <label>Product Name</label>
                    <input type="text" name="title" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Description</label>
                    <input type="text" name="description" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Price</label>
                    <input type="text" name="price" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Thambnail</label>
                    <input type="file" name="thumbnail" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary submit">Save</button>
            </div>
        </form>

      </div>
    </div>
  </div>


  <!-- Modal delete -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

            <div class="modal-body">
                <h4>Are you want to delete?</h4>
                <input type="hidden" id="delete_pro_id">
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="delete_product_btn btn btn-primary submit">Yes Delete</button>
            </div>

      </div>
    </div>
  </div>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Product</h4>
                    <a href="#" class="btn btn-primary btn-sm float-end"  data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</a>
                    <input type="text" name="search" id="inputSearch" class="form-control" placeholder="search here">
                </div>
                <div class="card-body" >
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Subcategory</th>
                                    <th>Thumbnail</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody id="searchResult">
                                {{-- <tr>
                                <td>1</td>
                                <td>Title</td>
                                <td>Description</td>
                                <td>Price</td>
                                <td>Subcategory</td>
                                <td><img src="" width="50px" height="50px">Thumbnail</td>
                                <td> <button type="button" value="" class="btn btn-danger btn-sm delete_btn">Delete</button> </td>
                            </tr> --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>

// ClassicEditor


//         .create( document.querySelector( '#editor' ) )
//         .catch( error => {
//             console.error( error );
//         });

$(document).ready(function () {

    // get_product();
    // function get_product(query=''){
    //     $.ajax({
    //         type: "GET",
    //         url: "{{ route('search') }}",
    //         data: {query:query},
    //         dataType: "json",
    //         success: function (response) {
    //             $('tbody').html(response.table_data);
    //             $('#total_records').text(response.total_data);
    //         }
    //     });
    // }

    // $(document).on('keyup','#search', function () {
    //     var query = $(this).val();
    //     get_product(query);
    // });

    getProduct();
    function getProduct(){
        $.ajax({
            type: "GET",
            url: "/get-product",
            dataType: "json",
            success: function (response) {
                // console.log(response.product);
                $('tbody').html("");
                $.each(response.product, function (key, item) {
                     $('tbody').append('<tr>\
                                <td>'+item.id+'</td>\
                                <td>'+item.title+'</td>\
                                <td>'+item.description+'</td>\
                                <td>'+item.price+'</td>\
                                <td>'+item.subcategory_id+'</td>\
                                <td><img src="upload/products/'+item.thumbnail+'" width="50px" height="50px">Thumbnail</td>\
                                <td> <button type="button" value="'+item.id+'" class="btn btn-danger btn-sm delete_btn">Delete</button> </td>\
                            </tr>');
                });
            }
        });
    }
    getProduct();
    $('#inputSearch').on('keyup', function () {
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
        $inputSearch = $(this).val();
        // alert($inputSearch);
        if($inputSearch ==''){
            $('#searchResult').html('');
            $('#searchResult').show();
            getProduct();
        }else{
            $.ajax({
                type: "post",
                url: "/search-pro",
                data: JSON.stringify({
                    inputSearch:$inputSearch
                }),
                headers:{
                    'Accept':'application/json',
                    'Content-type':'application/json',
                },
                success: function (response) {
                    // console.log(response);

                    $('tbody').html("");
                $.each(response.product, function (key, item) {
                     $('tbody').append('<tr>\
                                <td>'+item.id+'</td>\
                                <td>'+item.title+'</td>\
                                <td>'+item.description+'</td>\
                                <td>'+item.price+'</td>\
                                <td>'+item.subcategory_id+'</td>\
                                <td><img src="upload/products/'+item.thumbnail+'" width="50px" height="50px">Thumbnail</td>\
                                <td> <button type="button" value="'+item.id+'" class="btn btn-danger btn-sm delete_btn">Delete</button> </td>\
                            </tr>');
                });

                    // var searchResultajax='';
                    // response = JSON.parse(response);
                    // // console.log(data);
                    // $('#searchResult').show();
                    // for(let i=0;i<data.length;i++){
                    //     searchResultajax +=`<tr>
                    //         <td>${i.id}</td>
                    //             <td>${response.title}</td>
                    //             <td>${data.description}</td>
                    //             <td>${data.price}</td>
                    //             <td>${data.subcategory_id}</td>
                    //             <td><img src="${data.thumbnail}" width="50px" height="50px">Thumbnail</td>
                    //             <td> <button type="button" value="" class="btn btn-danger btn-sm delete_btn">Delete</button> </td>
                    //         </tr>`
                    // }
                    // $('#searchResult').html(searchResultajax);
                }
            });
        }

    });

    $(document).on('click','.delete_btn', function (e) {
        e.preventDefault();

        var pro_id = $(this).val();
        $('#deleteProductModal').modal('show');
        $('#delete_pro_id').val(pro_id);

        $(document).on('click','.delete_product_btn', function (e) {
            e.preventDefault();
            var id = $('#delete_pro_id').val();

            $.ajax({
                type: "GET",
                url: "/delete-product/"+id,
                dataType: "json",
                success: function (response) {
                    if(response.status == 404)
                    {
                        alert(response.message);
                        $('#deleteProductModal').modal('hide');

                    }else if(response.status == 200){

                        alert(response.message);
                        $('#deleteProductModal').modal('hide');
                        getProduct();
                    }
                }
            });
        });

    });




$(document).on('submit','#AddProductForm', function (e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // for ( instance in CKEDITOR.instances ) {
    //     CKEDITOR.instances[instance].updateElement();
    // }



    let formData = new FormData($('#AddProductForm','Edit')[0]);

    $.ajax({
        type: "POST",
        url: "/product-store",
        data: formData,
        contentType:false,
        processData:false,
        success: function (response) {
            console.log(response);
            if(response.status == 400){

                $('#errorList').html("");
                $('#errorList').removeClass('d-none');
                $.each(response.errors, function (key, err_values) {
                     $('#errorList').append('<li>'+err_values+'</li>');
                });
            }else if(response.status == 200){
                $('#errorList').html("");
                $('#errorList').addClass('d-none');
                $('#AddProductForm').find('input').val('');
                $('#addProductModal').modal('hide');
                getProduct();
                alert('response.message');
            }
        }
    });
});





});

</script>

@endsection
