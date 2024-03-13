@extends('admin.layouts.master')
@section('admin_content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit User</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('users.index')}}" class="btn btn-primary">Back</a>
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
                <form id="usersForm" method="POST" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" name="name" id="name" value="{{$user->name}}" class="form-control custom-input"
                                    placeholder="Enter brand name">
                                <p class="p"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" name="email" id="email" value="{{$user->email}}" class="form-control custom-input"
                                    placeholder="Enter email">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone:</label>
                                <input type="tel" name="phone" id="phone" value="{{$user->phone}}" class="form-control custom-input"
                                    placeholder="Enter phone">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" name="password" id="password" class="form-control custom-input"
                                    placeholder="Enter password">
                                    <span>To change password you have to enter a value , otherwise leave blank.</span>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="status" class="form-label">Status:</label>
                                <select name="status" id="status" class="form-control custom-select text-center ">
                                    <option selected disabled>--------Select Status--------</option>
                                    <option {{($user->status == 1 ) ? 'selected' : ''}} value="1">Active</option>
                                    <option {{($user->status == 0 ) ? 'selected' : ''}} value="0">Block</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary custom-button">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@section('customJs')
    <script>
        $('#usersForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this);
            $("button[type=submit]").prop('disabled',true);
            //    console.log(formData.serializeArray());
            //    return false;
            $.ajax({
                url: '{{ route('users.update',$user->id) }}',
                type: 'put',
                dataType: 'json',
                data: formData.serializeArray(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    var errors = res.errors;
                    $("button[type=submit]").prop('disabled',false);

                    if (res.status == false) {
                        if (errors.name) {
                            $("#name").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.name);
                        } else {
                            $("#name").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }

                        if (errors.email) {
                            $("#email").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.email);
                        } else {
                            $("#email").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }

                        if (errors.phone) {
                            $("#phone").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.phone);
                        } else {
                            $("#phone").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                        if (errors.status) {
                            $("#status").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.status);
                        } else {
                            $("#status").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                        if (errors.password) {
                            $("#password").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.password);
                        } else {
                            $("#password").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }

                    } else {
                        window.location.href = "{{ route('users.index') }}";
                    }


                },
                error: function(jqXHR, exceotion) {
                    console.log('something went wrong');

                }

            });
        });
    </script>
@endsection
