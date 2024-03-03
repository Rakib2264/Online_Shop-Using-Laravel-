@extends('admin.layouts.master')
@section('admin_content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('product.index')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="" method="post" name="productForm" id="productForm">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" class="form-control"
                                                placeholder="Title">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="slug">Slug</label>
                                            <input type="text" readonly name="slug" id="slug"
                                                class="form-control" placeholder="Slug">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="short_description">Short Description</label>
                                            <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote"
                                                ></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote"
                                                placeholder="Description"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="shipping_returns">Shipping and Returns</label>
                                            <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote"
                                                placeholder="Description"></textarea>
                                        </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="product_gallery">

                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" name="price" id="price" class="form-control"
                                                placeholder="Price">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="text" name="compare_price" id="compare_price"
                                                class="form-control" placeholder="Compare Price">
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price into Compare at
                                                price. Enter a lower value into Price.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" name="sku" id="sku" class="form-control"
                                                placeholder="sku">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control"
                                                placeholder="Barcode">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="track_qty" value="No">
                                                <input class="custom-control-input" type="checkbox" value="Yes"
                                                    id="track_qty" name="track_qty" checked>
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty"
                                                class="form-control" placeholder="Qty">
                                            <p class="error"></p>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <h2 class="h4 mb-3">Related product</h2>
                                                <div class="mb-3">
                                                    <select multiple class="related_product w-100" name="related_products[]" id="related_products">


                                                     </select>


                                                 </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Block</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4  mb-3">Product category</h2>
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option selected disabled>-----Select Category-----</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="error"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="category">Sub category</label>
                                    <select name="sub_category_id" id="sub_category_id" class="form-control">
                                        <option>-----Select Sub Category-----</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="brand_id" id="brand_id" class="form-control">
                                        <option selected disabled>-----Select Brand-----</option>
                                        @if ($brands->isNotEmpty())
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Featured product</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>
@endsection

@section('customJs')
    <script>

$('.related_product').select2({
            ajax: {
                url: '{{ route('product.getProducts') }}',
                dataType: 'json',
                tags: true,
                multiple: true,
                minimumInputLength: 3,
                processResults: function (data) {
                    return {
                        results: data.tags
                    };
                }
            }
        });

        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Create slug
            $('#title').change(function() {
                var element = $(this);
                $.ajax({
                    url: '{{ route('getSlug') }}',
                    type: 'get',
                    data: {
                        title: element.val()
                    },
                    success: function(res) {
                        if (res.status == true) {
                            $("#slug").val(res.slug);
                        }
                    }
                });
            });
            // end
            // Product Form Submission
            $("#productForm").submit(function(e) {
                e.preventDefault();
                var formArray = $(this).serializeArray();
                $("button[type=submit]").prop('disabled', true);
                $.ajax({
                    url: '{{ route('product.store') }}',
                    type: 'post',
                    data: formArray,
                    dataType: 'json',
                    success: function(res) {
                        $("button[type=submit]").prop('disabled', false);

                        if (res.status === 'faild') {

                            var errors = res
                                .errors; // Assuming res is an object with errors property
                            // Clear previous errors
                            $(".error").removeClass("invalid-feedback").html('');
                            $("input[type='text'], select, input[type='number']").removeClass(
                                'is-invalid');
                            // Loop through errors and display them
                            $.each(errors, function(key, val) {
                                $("#" + key).addClass('is-invalid').siblings('p')
                                    .addClass("invalid-feedback").html(val);
                            });



                        } else {
                            // Handle success case
                            window.location.href = "{{ route('product.index') }}";
                        }
                    },
                    error: function() {
                        console.log("Something went wrong");
                    }
                });
            });
            // end

            // Update subcategory based on selected category
            $("#category_id").on('change', function() {
                var category_id = $(this).val();
                $.ajax({
                    url: '{{ route('product.sub.create') }}',
                    type: 'get',
                    data: {
                        category_id: category_id
                    },
                    dataType: 'json',
                    success: function(res) {
                          /*   explain
                           $("#sub_category").find("option").not(":first").remove();
                                এই লাইনে আমরা প্রথম বাছাইকৃত অপশন ছাড়াই সব অন্যান্য অপশনগুলি অপসারণ করছি।
                                এটি সাবক্যাটেগরির ড্রপডাউনের অন্যান্য অপশনগুলি রিফ্রেশ করার জন্য ব্যবহৃত হয়।
                                  $.each(res["subcategories"],function(key , val){

                                  এই লাইনে, প্রতিটি সাবক্যাটেগরির জন্য একটি লুপ চালানো হচ্ছে।
                                  প্রতিটি সাবক্যাটেগরির জন্য, প্রতিবার অ্যারের প্রতিটি উপাদান চেক করা হচ্ছে।
                                  $("#sub_category").append(<option value='${val.name}'>${val.name}</option>);

                                 এই লাইনে, সাবক্যাটেগরি ড্রপডাউনের অপশনগুলির সাথে নতুন অপশনগুলি সংযুক্ত করা হচ্ছে।
                                 প্রতিটি অপশনের মান সাবক্যাটেগরির নাম ব্যবহার করে সেট করা হচ্ছে। অপশনগুলি যুক্ত করার জন্য, append() ব্যবহৃত হয়।

                           */
                        $("#sub_category_id").find("option").not(":first").remove();
                        $.each(res["subcategories"], function(key, val) {
                            $("#sub_category_id").append(
                                `<option value='${val.id}'>${val.name}</option>`);
                        });

                    },
                    error: function() {
                        console.log("Something went wrong");
                    }
                });
            });
            // end




            Dropzone.autoDiscover = false;
        const dropzone = new Dropzone("#image", {
            url: "{{ route('temp-images.create') }}",
            maxFiles: 10,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                // $("#image_id").val(response.image_id);
                //console.log(response)

                var html = `<div class="col-md-3 id="img-row-${response.image_id}"> <div class="card">
                    <input type="hidden" name="imageArray[]" value="${response.image_id}">

                    <img src="${response.imgpath}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <a href="javascript:void(0)" onclick="deleteimg(${response.image_id})" class="btn btn-secondary">Delete</a>
                    </div>
                    </div></div>`;
                $("#product_gallery").append(html)
            }
            // complete:function(file){
            //   this.removeFile(file);
            // }



        });
        function deleteimg(id){
            $("#img-row-"+id).remove();
        }
        });








    </script>
@endsection
