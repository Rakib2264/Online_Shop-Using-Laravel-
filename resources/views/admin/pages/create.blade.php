@extends('admin.layouts.master')
@section('admin_content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Page</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="pages.html" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" method="post" id="pageForm" name="pageForm">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Slug</label>
                                    <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="content">Content</label>
                                    <textarea name="content" id="content" class="summernote" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
@endsection
@section('customJs')
    <script>
        $('#pageForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this);
            $("button[type=submit]").prop('disabled', true);
            //    console.log(formData.serializeArray());
            //    return false;
            $.ajax({
                url: '{{ route('page.store') }}',
                type: 'post',
                dataType: 'json',
                data: formData.serializeArray(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    $("button[type=submit]").prop('disabled', false);

                    if (res.status == false) {
                        $('#name').addClass('is-invalid');

                        $('#name').siblings('p').addClass('invalid-feedback').html(res.errors.name);

                        $('#slug').addClass('is-invalid');
                        $('#slug').siblings('p').addClass('invalid-feedback').html(res.errors.slug);
                    } else {


                        window.location.href = "{{ route('page.index') }}";

                    }


                },
                error: function(jqXHR, exceotion) {
                    console.log('something went wrong');

                }

            });
        });
        // create slug
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
