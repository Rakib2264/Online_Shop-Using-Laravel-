@extends('admin.layouts.master')
@section('admin_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">

                <div class="col text-right mt-4">
                    <a href="{{ route('subcategory.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <h1 class="text-center mt-2">Edit Sub Category</h1>
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
                                                        <option
                                                            {{ $subcategories->category_id == $category->id ? 'selected' : '' }}
                                                            value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Sub Category Name</label>
                                            <input type="text" value="{{ $subcategories->name }}" name="name"
                                                id="name" class="form-control" placeholder="Name">
                                            <p></p>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option selected disabled>Select Status</option>
                                                <option value="1"
                                                    {{ $subcategories->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0"
                                                    {{ $subcategories->status == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="slug">Slug</label>
                                            <input value="{{ $subcategories->slug }}" type="text" readonly name="slug"
                                                id="slug" class="form-control" placeholder="Slug">
                                            <p></p>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="showHome" class="form-label">Show On Home</label>
                                            <select name="showHome" id="showHome" class="form-control">
                                                <option value="Yes" {{ $subcategories->showHome == 'Yes' ? 'selected' : '' }}>Yes
                                                </option>
                                                <option value="No" {{ $subcategories->showHome == 'No' ? 'selected' : '' }}>No
                                                </option>
                                            </select>
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
        $('#subCategoryForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this);
            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{ route('subcategory.update', $subcategories->id) }}',
                type: 'put',
                dataType: 'json',
                data: formData.serializeArray(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
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

                        if (res.errors.hasOwnProperty('category')) {
                            $('#category').addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(res.errors.category);
                        }

                        if (res.errors.hasOwnProperty('status')) {
                            $('#status').addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(res.errors.status);
                        }
                    } else {
                        // Handle success case
                        window.location.href = "{{ route('subcategory.index') }}";
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
