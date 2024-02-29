@extends('admin.layouts.master')
@section('admin_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">

                <div class="col text-right mt-4">
                    <a href="{{route('subcategory.index')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <h1 class="text-center mt-2">Create Sub Category</h1>
                    <div class="card">
                        <div class="card-body">
                            <form action="" name="subCategoryForm" id="subCategoryForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category">Category</label>
                                            <select name="category" id="category" class="form-control">
                                                <option selected disabled>Select Category</option>
                                                @if ($categories->isNotEmpty())
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Sub Category Name</label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                placeholder="Name">
                                                <p></p>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option selected disabled>Select Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="slug">Slug</label>
                                            <input type="text" readonly name="slug" id="slug"
                                                class="form-control" placeholder="Slug">
                                                <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="showHome" class="form-label">Show On Home</label>
                                            <select name="showHome" id="showHome" class="form-control">
                                                <option value="Yes">Yes
                                                </option>
                                                <option value="No">No
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Create</button>
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
     $(document).ready(function(){
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


        $('#subCategoryForm').submit(function(e) {
    e.preventDefault();
    var formData = $(this);
    $("button[type=submit]").prop('disabled', true);

    $.ajax({
        url: '{{ route('subcategory.store') }}',
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
                    $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(res.errors.name);
                }

                if (res.errors.hasOwnProperty('slug')) {
                    $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(res.errors.slug);
                }

                if (res.errors.hasOwnProperty('category')) {
                    $('#category').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(res.errors.category);
                }

                if (res.errors.hasOwnProperty('status')) {
                    $('#status').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(res.errors.status);
                }
            } else {
                // Handle success case
                window.location.href="{{ route('subcategory.index') }}";
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
        });
     })
    </script>
@endsection
