@extends('frontend.layouts.master')
@section('frontend_content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item">Forgot Password</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        <div class="login-form">
            <form action="{{route('frontend.processforgotPassword')}}" method="post" >
                @csrf
                <h4 class="modal-title">Login to Your Account</h4>
                <div class="form-group">
                    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email"  value="{{old('email')}}">
                    @error('email') <p class="invalid-feedback">{{$message}}</p> @enderror
                </div>

                <button type="submit" class="btn btn-dark btn-block btn-lg">Login</button>
            </form>
            <div class="text-center small"><a href="{{route('frontend.login')}}">Login</a></div>
        </div>
    </div>
</section>
@endsection
