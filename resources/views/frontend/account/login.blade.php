@extends('frontend.layouts.master')
@section('frontend_content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item">Login</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        <div class="login-form">
            <form action="{{route('frontend.authenticate')}}" method="post" >
                @csrf
                <h4 class="modal-title">Login to Your Account</h4>
                <div class="form-group">
                    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email"  value="{{old('email')}}">
                    @error('email') <p class="invalid-feedback">{{$message}}</p> @enderror
                </div>
                <div class="form-group">
                    <input type="password" name="password"  class="form-control @error('password') is-invalid @enderror" placeholder="Password"  >
                    @error('password') <p class="invalid-feedback">{{$message}}</p> @enderror
                </div>
                <div class="form-group small">
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>
                <button type="submit" class="btn btn-dark btn-block btn-lg">Login</button>
            </form>
            <div class="text-center small">Don't have an account? <a href="{{route('frontend.register')}}">Sign up</a></div>
        </div>
    </div>
</section>
@endsection
