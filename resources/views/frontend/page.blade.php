@extends('frontend.layouts.master')
@section('frontend_content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('frontend.home')}}">Home</a></li>
                    <li class="breadcrumb-item">{{$pages->name}}</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">
        <div class="container">
            <h1 class="my-3">{{$pages->name}}</h1>
            <p>{!! $pages->content !!}</p>

        </div>
    </section>
</main>
@endsection
