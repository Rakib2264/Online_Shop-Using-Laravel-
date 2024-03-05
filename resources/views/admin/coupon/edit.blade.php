@extends('admin.layouts.master')
@section('admin_content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Coupon Code</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('coupon.index') }}" class="btn btn-primary">Back</a>
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
                <form action="" id="discountForm" name="discountForm" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Code:</label>
                                <input value="{{ $coupon->code}}" type="text" name="code" id="code" class="form-control"
                                    placeholder="Enter brand Coupon Code">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" value="{{ $coupon->name}}" name="name" id="name" class="form-control"
                                    placeholder="Enter Coupon Code Name">
                                <p></p>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_uses" class="form-label">Max Uses:</label>
                                <input type="number" value="{{ $coupon->max_uses}}" name="max_uses" id="max_uses" class="form-control"
                                    placeholder="Enter Max Uses User">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_uses_user" class="form-label">Max Uses User:</label>
                                <input type="number" value="{{ $coupon->max_uses_user}}" name="max_uses_user" id="max_uses_user" class="form-control"
                                    placeholder="Enter Max Uses User">
                                <p></p>
                            </div>
                        </div>



                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Type:</label>
                                <select name="type" id="type" class="form-control">
                                    <option selected disabled>Select Status</option>
                                    <option {{($coupon->type == 'parcent') ? 'selected' : ''}} value="parcent">Parcent</option>
                                    <option {{($coupon->type == 'fixed') ? 'selected' : ''}} value="fixed">Fixed</option>
                                </select>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="discount_amount" class="form-label">Discount Amount:</label>
                                <input type="number" value="{{ $coupon->discount_amount}}" name="discount_amount" id="discount_amount" class="form-control"
                                    placeholder="Enter Discount Amount">
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="min_amount" class="form-label">Minmum Amount:</label>
                                <input type="number" value="{{ $coupon->min_amount}}" name="min_amount" id="min_amount" class="form-control"
                                    placeholder="Enter Minmum Amount">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status:</label>
                                <select name="status" id="status" class="form-control">
                                    <option selected disabled>Select Status</option>
                                    <option {{($coupon->status == 1) ? 'selected' : ''}} value="1">Active</option>
                                    <option {{($coupon->status == 0) ? 'selected' : ''}} value="0">Block</option>
                                </select>
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="starts_at" class="form-label">Starts At:</label>
                                <input value="{{ $coupon->starts_at}}" autocomplete="off" type="text" name="starts_at" id="starts_at" class="form-control"
                                    placeholder="Enter Start">
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expires_at" class="form-label">Expire At:</label>
                                <input value="{{ $coupon->expires_at}}" autocomplete="off" type="text" name="expires_at" id="expires_at" class="form-control"
                                    placeholder="Enter Expire">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="name" class="form-label">Description:</label>
                                <textarea name="des" class="form-control" id="des" cols="30" rows="5">{{ $coupon->des}}</textarea>
                                <p></p>
                            </div>
                        </div>


                    </div>
                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@section('customJs')
    <script>
        $(document).ready(function() {
            $('#starts_at').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });

            $('#expires_at').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });
        });
        // discount
        $('#discountForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this);
            $("button[type=submit]").prop('disabled', true);
            //    console.log(formData.serializeArray());
            //    return false;
            $.ajax({
                url: '{{ route('coupon.update',$coupon->id) }}',
                type: 'put',
                dataType: 'json',
                data: formData.serializeArray(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    $("button[type=submit]").prop('disabled', false);
                    var errors = res.errors;
                    if (res.status == false) {
                        if (errors.code) {
                            $("#code").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.code);
                        } else {
                            $("#code").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }

                        if (errors.des) {
                            $("#des").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.des);
                        } else {
                            $("#code").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                        if (errors.max_uses) {
                            $("#max_uses").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.max_uses);
                        } else {
                            $("#max_uses").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                        if (errors.max_uses_user) {
                            $("#max_uses_user").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.max_uses_user);
                        } else {
                            $("#max_uses_user").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                        if (errors.type) {
                            $("#type").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.type);
                        } else {
                            $("#type").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                        if (errors.discount_amount) {
                            $("#discount_amount").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.discount_amount);
                        } else {
                            $("#discount_amount").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                        if (errors.status) {
                            $("#status").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.status);
                        } else {
                            $("#status").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }

                        if (errors.starts_at) {
                            $("#starts_at").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.starts_at);
                        } else {
                            $("#starts_at").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }

                        if (errors.expires_at) {
                            $("#expires_at").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.expires_at);
                        } else {
                            $("#expires_at").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }




                    } else {
                        window.location.href = "{{ route('coupon.index') }}/";
                    }
                },
                error: function(jqXHR, exceotion) {
                    console.log('something went wrong');

                }

            });
        });
    </script>
@endsection
