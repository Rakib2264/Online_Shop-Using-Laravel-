@extends('frontend.layouts.master')
@section('frontend_content')
    <main>
        <section class="section-5 py-3 mb-3 bg-light">
            <div class="container">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-white">Home</a></li>
                    <li class="breadcrumb-item active">Register</li>
                </ol>
            </div>
        </section>

        <section class="section-10">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-body">
                                <h4 class="card-title text-center mb-4">Register Now</h4>
                                <form action="" method="post" name="registationform" id="registationform">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Name" id="name"
                                            name="name">
                                            <p></p>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="Email" id="email"
                                            name="email">
                                            <p></p>
                                    </div>
                                    <div class="form-group">
                                        <input type="tel" class="form-control" placeholder="Phone" id="phone"
                                            name="phone">
                                            <p></p>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password" id="password"
                                            name="password">
                                            <p></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                        <p></p>
                                    </div>

                                    <button type="submit" class="btn btn-dark btn-block">Register</button>
                                </form>
                                <div class="text-center mt-3">Already have an account? <a href="{{route('frontend.login')}}">Login Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
@section('customjs')
    <script>
$("#registationform").submit(function(e) {
    e.preventDefault();
    $("button[type='submit']").prop('disabled', true);

    $.ajax({
        url: '{{ route('frontend.processRegister') }}',
        type: 'post',
        data: $(this).serializeArray(),
        dataType: 'json',
        success: function(res) {
            if (res.status == false) {
                $("button[type='submit']").prop('disabled', false);

                var errors = res.errors;

                if (errors.name) {
                    $("#name").siblings('p').addClass('invalid-feedback').html(errors.name);
                    $("#name").addClass('is-invalid');
                } else {
                    $("#name").siblings('p').removeClass('invalid-feedback').html('');
                    $("#name").removeClass('is-invalid');
                }

                if (errors.email) {
                    $("#email").siblings('p').addClass('invalid-feedback').html(errors.email);
                    $("#email").addClass('is-invalid');
                } else {
                    $("#email").siblings('p').removeClass('invalid-feedback').html('');
                    $("#email").removeClass('is-invalid');
                }

                if (errors.password) {
                    $("#password").siblings('p').addClass('invalid-feedback').html(errors.password);
                    $("#password").addClass('is-invalid');
                } else {
                    $("#password").siblings('p').removeClass('invalid-feedback').html('');
                    $("#password").removeClass('is-invalid');
                }


            }else{
                window.location.href="{{ route('frontend.login') }}";

            }
        },
        error: function(jqXHR, exception) {
            console.log("something went wrong");
        }
    });
});


    </script>
@endsection
