@extends('frontend.layouts.master')
@section('frontend_content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item">Reset Password</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        <div class="login-form">
            <form action="{{route('frontend.processResetPassword')}}" method="post" >
                @csrf
                <input type="hidden" name="token" value="{{$token}}">
                <h4 class="modal-title">New Password Set</h4>
                <div class="form-group">
                    <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="New Password"   >
                    @error('new_password') <p class="invalid-feedback">{{$message}}</p> @enderror
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Confirm Password">
                    @error('confirm_password') <p class="invalid-feedback">{{$message}}</p> @enderror
                </div>

                <button type="submit" class="btn btn-dark btn-block btn-lg">Update Password</button>
            </form>
            <div class="text-center small"><a href="{{route('frontend.login')}}">Click Here Login</a></div>
        </div>
    </div>
</section>
@endsection
