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
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                            <option value="rest_of_world">Rest Of the world</option>
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="amount" id="amount" class="form-control"
                                    placeholder="Amount">
                                <p></p>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </div>
                    </form>
                    <div class="card mt-5">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 justify-content-center ">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Country</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($shippingcharges as $shippingcharge)
                                                <tr>
                                                    <td>{{ $shippingcharge->id }}</td>
                                                    <td>{{ $shippingcharge->country_id == 'rest_of_world' ? 'Rest Of the world' : $shippingcharge->name }}</td>
                                                    <td>${{ $shippingcharge->amount }}</td>
                                                    <td>
                                                        <a href="javascript:void(0)" onclick="deleteship({{ $shippingcharge->id }})" class="btn btn-sm btn-danger">Delete</a>
                                                    </td>
                                                </tr>
                                            @endforeach




                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
                    url: '{{ route('shipping.store') }}',
                    type: 'post',
                    dataType: 'json',
                    data: $(this).serializeArray(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        $("button[type=submit]").prop('disabled', false);

                        if (res.status == 'failed') {
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

        function deleteship(id) {

            var url = '{{ route('shipping.delete', 'ID') }}'
            var newUrl = url.replace("ID", id)


            if (confirm("Are You Sure You Want To Delete")) {
                $.ajax({
                    url: newUrl,
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        // Redirect to the category index page upon successful deletion
                        window.location.href = "{{ route('shipping.create') }}";
                    },
                    error: function(xhr, status, error) {
                        // Handle error, if any
                        console.error(xhr.responseText);
                    }
                });
            }

        }
    </script>
@endsection
