@extends('admin.layouts.master')
@section('admin_content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Change Password</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('category.index') }}" class="btn btn-primary">Back</a>
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
                    <form id="changepassForm" name="changepassForm" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="old_password" class="form-label">Old Password</label>
                                    <input type="password" name="old_password" id="old_password" class="form-control"
                                        placeholder="Enter Old Password">
                                    <p class="p"></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" name="new_password" id="new_password" class="form-control"
                                        placeholder="Enter New Password">
                                    <p class="p"></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name">Confirm Password</label>
                                    <input type="password" name="confirm_password" id="confirm_password"
                                        placeholder="Confirm Password" class="form-control">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                        <div class="pb-5 pt-3">
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('customJs')
    <script>
        $("#changepassForm").submit(function(e) {
            e.preventDefault();
            $('button[type="submit"]').prop('disabled', true);
            $.ajax({
                url: '{{ route('admin.processChangePassword') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    $('button[type="submit"]').prop('disabled', false);
                    var errors = res.errors;
                    if (res.status == 'faild') {
                        if (errors.old_password) {
                            $("#old_password").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.old_password);
                        } else {
                            $("#old_password").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }

                        if (errors.new_password) {
                            $("#new_password").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.new_password);
                        } else {
                            $("#new_password").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }

                        if (errors.confirm_password) {
                            $("#confirm_password").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.confirm_password);
                        } else {
                            $("#confirm_password").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                    } else {
                        window.location.reload();
                    }
                }
            });
        });
    </script>
@endsection
