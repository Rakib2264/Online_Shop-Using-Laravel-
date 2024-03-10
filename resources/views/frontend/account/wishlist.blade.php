@extends('frontend.layouts.master')
@section('frontend_content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                        <li class="breadcrumb-item">My Wishlist</li>
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
                                <h2 class="h5 mb-0 pt-2 pb-2">My Orders</h2>
                            </div>
                            <div class="card-body p-4">

                                @if ($wishlist->isNotEmpty())
                                    @foreach ($wishlist as $wishlist)
                                        <div
                                            class="d-sm-flex justify-content-between mt-lg-4 mb-4 pb-3 pb-sm-2 border-bottom">
                                            <div class="d-block d-sm-flex align-items-start text-center text-sm-start"><a
                                                    class="d-block flex-shrink-0 mx-auto me-sm-4" href="{{route('frontend.product_detail',$wishlist->product->slug)}}"
                                                    style="width: 10rem;">
                                                    @php
                                                        $productImg = getProductImage($wishlist->product_id);
                                                    @endphp
                                                    @if (!empty($productImg))
                                                        <img src="{{ asset('products/small/' . $productImg->image) }}"
                                                            class="img-thumbnail">
                                                    @else
                                                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                                            class="img-thumbnail" alt="" />
                                                    @endif

                                                    <div class="pt-2">
                                                        <h3 class="product-title fs-base mb-2"><a
                                                                href="{{route('frontend.product_detail',$wishlist->product->slug)}}">{{ $wishlist->product->title }}</a>
                                                        </h3>
                                                        <div class="fs-lg text-accent pt-2">

                                                            <span
                                                                class="h5"><strong>${{ $wishlist->product->price }}</strong></span>
                                                            <span
                                                                class="h6 text-underline"><del>${{ $wishlist->product->compare_price }}</del></span>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="pt-2 ps-sm-3 mx-auto mx-sm-0 text-center">
                                                <button onclick="removeProductFromWishList({{$wishlist->product_id}})" class="btn btn-outline-danger btn-sm" type="button"><i
                                                        class="fas fa-trash-alt me-2"></i>Remove</button>
                                            </div>
                                        </div>
                                    @endforeach
                                    @else
                                    <div>
                                        <h3 class=" h5  text-center ">Your Wish List Is Empty</h3>
                                    </div>
                                @endif



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
        function removeProductFromWishList(id){
            $.ajax({
                url: "{{ route('frontend.removeProductFromWishList') }}",
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status == true) {
                        window.location.href = "{{ route('frontend.wishlist') }}";
                    }

                }
            });
        }
     </script>
@endsection
