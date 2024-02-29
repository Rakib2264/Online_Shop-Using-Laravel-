@extends('admin.layouts.master')
@section('admin_content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('category.index')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form id="categoryForm" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name:</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Enter brand name">
                                    <p class="p"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug:</label>
                                    <input type="text" readonly name="slug" id="slug" class="form-control"
                                        placeholder="Enter slug">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">

                                     <div class="mb-3">
                                        <input type="hidden" name="image_id" id="image_id" value="">
                                        <label for="image"></label>
                                        <div id="image" multiple class="dropzone dz-clickable">
                                            <div class="dz-message needsclick">
                                                <br>Drop files here or click to upload.<br><br>
                                            </div>
                                        </div>
                                     </div>

                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status:</label>
                                    <select name="status" id="status" class="form-control">
                                        <option selected disabled>Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="showHome" class="form-label">Show On Home</label>
                                    <select name="showHome" id="showHome" class="form-control">
                                         <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="pb-5 pt-3">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('customJs')
    <script>
        $('#categoryForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this);
            $("button[type=submit]").prop('disabled',true);
            //    console.log(formData.serializeArray());
            //    return false;
            $.ajax({
                url: '{{ route('category.store') }}',
                type: 'post',
                dataType: 'json',
                data: formData.serializeArray(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    $("button[type=submit]").prop('disabled',false);

                    if (res.status === 'faild') {
                        $('#name').addClass('is-invalid');

                        $('#name').siblings('p').addClass('invalid-feedback').html(res.errors.name);

                        $('#slug').addClass('is-invalid');
                        $('#slug').siblings('p').addClass('invalid-feedback').html(res.errors.slug);
                    } else {


                        window.location.href="{{ route('category.index') }}";

                    }


                },
                error: function(jqXHR, exceotion) {
                    console.log('something went wrong');

                }

            });
        });

        // create slug
        $('#name').change(function(){
            var element=$(this);
            $.ajax({
                        url: '{{ route('getSlug') }}',
                        type: 'get',
                         data: {title:element.val()},
                        success: function(res) {
                             if (res['status']==true) {
                                 $("#slug").val(res['slug']);
                             }

                        }


                    });
        })


        Dropzone.autoDiscover = false;
            const dropzone = new Dropzone("#image", {
                init: function() {
                    this.on('addedfile', function(file) {
                        if (this.files.length > 1) {
                            this.removeFile(this.files[0]);
                        }
                    });
                },
                url: "{{ route('temp-images.create') }}",
                maxFiles: 1,
                paramName: 'image',
                addRemoveLinks: true,
                acceptedFiles: "image/jpeg,image/png,image/gif",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(file, response) {
                    $("#image_id").val(response.image_id);
                    //console.log(response)
                }
            });


    </script>
@endsection
