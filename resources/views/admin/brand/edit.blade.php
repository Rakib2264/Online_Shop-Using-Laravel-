@extends('admin.layouts.master')
@section('admin_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">

                <div class="col text-right mt-4">
                    <a href="{{route('brand.index')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <h1 class="text-center mt-2">Edit Brand</h1>
                    <div class="card">
                        <div class="card-body">
                            <form action="" name="subBrandForm" id="subBrandForm">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Brand Name</label>
                                            <input type="text" value="{{$brand->name}}" name="name" id="name" class="form-control"
                                                placeholder="Name">
                                            <p></p>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="slug">Slug</label>
                                            <input value="{{$brand->slug}}" type="text" readonly name="slug" id="slug"
                                                class="form-control" placeholder="Slug">
                                            <p></p>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option selected disabled>Select Status</option>
                                                <option value="1" {{($brand->status == 1) ? 'selected' : ''}}>Active</option>
                                                <option value="0" {{($brand->status == 0) ? 'selected' : ''}}>Inactive</option>
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>

                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $('#subBrandForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this);
            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{ route('brand.store') }}',
                type: 'post',
                dataType: 'json',
                data: formData.serializeArray(),
                success: function(res) {
                    $("button[type=submit]").prop('disabled', false);

                    if (res.status === 'faild') {
                        // Clear existing error messages and classes for all fields
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').empty();

                        // Display validation errors for each field
                        if (res.errors.hasOwnProperty('name')) {
                            $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(res.errors.name);
                        }

                        if (res.errors.hasOwnProperty('slug')) {
                            $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(res.errors.slug);
                        }



                        if (res.errors.hasOwnProperty('status')) {
                            $('#status').addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(res.errors.status);
                        }
                    } else {
                        // Handle success case
                        window.location.href = "{{ route('brand.index') }}";
                    }
                },
                error: function(jqXHR, exceotion) {
                    console.log('something went wrong');
                }
            });
        });





        // slug
        $('#name').change(function() {
            var element = $(this);
            $.ajax({
                url: '{{ route('getSlug') }}',
                type: 'get',
                data: {
                    title: element.val()
                },
                success: function(res) {
                    if (res['status'] == true) {
                        $("#slug").val(res['slug']);
                    }

                }
            });
        })
    </script>
@endsection
