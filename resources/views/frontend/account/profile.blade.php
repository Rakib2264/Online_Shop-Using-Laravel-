@extends('frontend.layouts.master')
@section('frontend_content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                    <li class="breadcrumb-item">Settings</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-3">
                    @include('frontend.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                        </div>
                        <form action="" method="post" name="profileForm" id="profileForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" value="{{ $user->name }}"
                                            id="name" placeholder="Enter Your Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" id="email" value="{{ $user->email }}"
                                            id="email" placeholder="Enter Your Email" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" id="phone" value="{{ $user->phone }}"
                                            id="phone" placeholder="Enter Your Phone" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-dark">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card mt-5">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Address</h2>
                        </div>
                        <form action="" method="post" name="addressForm" id="addressForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name">First Name</label>
                                        <input type="text" name="first_name"
                                            value="{{ !empty($coustomerAddress) ? $coustomerAddress->first_name : '' }}"
                                            id="first_name" id="name" placeholder="Enter Your First Name"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="name">Last Name</label>
                                        <input type="text"
                                            value="{{ !empty($coustomerAddress) ? $coustomerAddress->last_name : '' }}"
                                            name="last_name" id="last_name" id="name"
                                            placeholder="Enter Your Last Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="email">Email</label>
                                        <input type="email"
                                            value="{{ !empty($coustomerAddress) ? $coustomerAddress->email : '' }}"
                                            name="email" id="email" placeholder="Enter Your Email"
                                            class="form-control">
                                            <p></p>

                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="phone">Phone</label>
                                        <input type="text"
                                            value="{{ !empty($coustomerAddress) ? $coustomerAddress->mobile : '' }}"
                                            name="mobile" id="mobile" placeholder="Enter Your Phone"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="country">Country</label>
                                        <select name="country" id="country" class="form-select">
                                            <option selected disabled>Select Country</option>

                                            @if ($country->isNotEmpty())
                                                @foreach ($country as $country)
                                                    <option
                                                        {{ !empty($coustomerAddress) && $coustomerAddress->country_id == $country->id ? 'selected' : '' }}
                                                        value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="apartment">Apartment</label>
                                        <input type="text"
                                            value="{{ !empty($coustomerAddress) ? $coustomerAddress->apartment : '' }}"
                                            name="apartment" id="apartment" placeholder="Enter Your apartment"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="city">City</label>
                                        <input type="text"
                                            value="{{ !empty($coustomerAddress) ? $coustomerAddress->city : '' }}"
                                            name="city" id="city" placeholder="Enter Your city"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="state">State</label>
                                        <input type="text"
                                            value="{{ !empty($coustomerAddress) ? $coustomerAddress->state : '' }}"
                                            name="state" id="state" placeholder="Enter Your state"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="zip">Zip</label>
                                        <input type="text"
                                            value="{{ !empty($coustomerAddress) ? $coustomerAddress->zip : '' }}"
                                            name="zip" id="zip" placeholder="Enter Your zip"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address">Address</label>
                                        <textarea name="address" id="address" cols="30" class="form-control" rows="5">{{ !empty($coustomerAddress) ? $coustomerAddress->address : '' }}</textarea>
                                        <p></p>
                                    </div>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-dark">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('customjs')
    <script>
        $("#profileForm").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('frontend.updateProfile') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(res) {
                    var errors = res.errors;
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
                    } else {

                        window.location.href = '{{ route('frontend.profile') }}'

                    }

                }
            });

        });

        // addressForm
        $("#addressForm").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('frontend.updateAddress') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(res) {
                    var errors = res.errors;
                    if (res.status == false) {
                        if (errors.first_name) {
                            $("#addressForm #first_name").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.first_name);
                        } else {
                            $("#addressForm #first_name").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                        if (errors.last_name) {
                            $("#addressForm #last_name").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.last_name);
                        } else {
                            $("#addressForm #last_name").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                        if (errors.email) {
                            $("#addressForm #email").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.email);
                        } else {
                            $("#addressForm #email").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }

                        if (errors.mobile) {
                            $("#addressForm #mobile").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.mobile);
                        } else {
                            $("#addressForm #mobile").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                        if (errors.country) {
                            $("#addressForm #country").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.country);
                        } else {
                            $("#addressForm #country").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                        if (errors.address) {
                            $("#addressForm #address").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.address);
                        } else {
                            $("#addressForm #address").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                        if (errors.city) {
                            $("#addressForm #city").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.city);
                        } else {
                            $("#addressForm #city").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                        if (errors.state) {
                            $("#addressForm #state").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.state);
                        } else {
                            $("#addressForm #state").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                        if (errors.zip) {
                            $("#addressForm #zip").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.zip);
                        } else {
                            $("#addressForm #zip").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html('');
                        }
                    } else {

                        window.location.href = '{{ route('frontend.profile') }}'









                    }

                }
            });

        });
    </script>
@endsection
