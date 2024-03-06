@extends('admin.layouts.master')
@section('admin_content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Shipping</h1>
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
            @include('admin.message')
            <div class="card">
                <div class="card-body">
                    <form action="" id="shippingForm" name="shippingForm" method="POST">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <select name="country_id" id="country_id" class="form-control">
                                        <option value="" disabled selected>Secect A Country</option>
                                        @if ($countries->isNotEmpty())
                                            @foreach ($countries as $country)
                                                <option {{($shippingcharge->country_id == $country->id) ? 'selected' : ''}} value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                            <option value="rest_of_world">Rest Of the world</option>
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="amount" value="{{$shippingcharge->amount}}" id="amount" class="form-control"
                                    placeholder="Amount">
                                <p></p>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
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
            $("#shippingForm").submit(function(e) {
                e.preventDefault();
                $("button[type=submit]").prop('disabled', true);

                $.ajax({
                    url: '{{ route('shipping.update',$shippingcharge->id) }}',
                    type: 'put',
                    dataType: 'json',
                    data: $(this).serializeArray(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        $("button[type=submit]").prop('disabled', false);

                        if (res.status == false) {
                            if (res.errors.country_id) {
                                $("#country_id").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(res.errors.country_id[0]);
                            } else {
                                $("#country_id").removeClass('is-invalid').siblings('p')
                                    .removeClass(
                                        'invalid-feedback').html('');
                            }

                            if (res.errors.amount) {
                                $("#amount").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(res.errors.amount[0]);
                            } else {
                                $("#amount").removeClass('is-invalid').siblings('p')
                                    .removeClass(
                                        'invalid-feedback').html('');
                            }
                        } else {
                            // Redirect only upon successful submission
                            window.location.href = "{{ route('shipping.create') }}";
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('something went wrong:', textStatus, errorThrown);
                    }
                });
            });
        });

      
    </script>
@endsection
