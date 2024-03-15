@extends('admin.layouts.master')

@section('admin_content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Page</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('page.update', $page->id) }}" method="post" id="pageForm">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" value="{{ $page->name }}" name="name" id="name" class="form-control" placeholder="Name">
                                    @error('name')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" value="{{ $page->slug }}" name="slug" id="slug" class="form-control" placeholder="Slug">
                                    @error('slug')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="content">Content</label>
                                    <textarea name="content" id="content" class="summernote" cols="30" rows="10">{{ $page->content }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $(document).ready(function() {
            $('#pageForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $("button[type=submit]").prop('disabled', true);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'PUT',
                    dataType: 'json',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        $("button[type=submit]").prop('disabled', false);

                        if (res.status === false) {
                            $('#name').addClass('is-invalid').next('.invalid-feedback').html(res.errors.name);
                            $('#slug').addClass('is-invalid').next('.invalid-feedback').html(res.errors.slug);
                        } else {
                            window.location.href = "{{ route('page.index') }}";
                        }
                    },
                    error: function(jqXHR, exception) {
                        console.log('something went wrong');
                    }
                });
            });

            // Create slug
            $('#name').change(function() {
                var element = $(this);
                $.ajax({
                    url: '{{ route('getSlug') }}',
                    type: 'GET',
                    data: {
                        title: element.val()
                    },
                    success: function(res) {
                        if (res.status === true) {
                            $("#slug").val(res.slug);
                        }
                    }
                });
            });
        });
    </script>
@endsection
