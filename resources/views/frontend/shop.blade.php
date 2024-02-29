@extends('frontend.layouts.master')
@section('frontend_content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                        <li class="breadcrumb-item active">Shop</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="section-6 pt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 sidebar">
                        <div class="sub-title">
                            <h2>Categories</h3>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="accordion accordion-flush" id="accordionExample">
                                    @if ($categories->isNotEmpty())
                                        @foreach ($categories as $category)
                                            <div class="accordion-item">
                                                @if ($category->sub_category->isNotEmpty())
                                                    <h2 class="accordion-header" id="headingOne">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapseOne{{ $category->id }}"
                                                            aria-expanded="false" aria-controls="collapseOne">
                                                            {{ $category->name }}
                                                        </button>
                                                    </h2>
                                                  @else
                                                    <a href="#" class="nav-item nav-link">{{ $category->name }}</a>
                                                @endif
                                                <div id="collapseOne{{ $category->id }}" class="accordion-collapse collapse"
                                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample"
                                                    style="">
                                                    <div class="accordion-body">
                                                        <div class="navbar-nav">
                                                            @if ($category->sub_category->isNotEmpty())
                                                                @foreach ($category->sub_category as $sub_category)
                                                                    <a href="#"
                                                                        class="nav-item nav-link">{{ $sub_category->name }}</a>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif


                                </div>
                            </div>
                        </div>

                        <div class="sub-title mt-5">
                            <h2>Brand</h3>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                @if ($brands->isNotEmpty())
                                @foreach ($brands as $brand)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="{{$brand->id}}" name="brand[]" id="brand-{{$brand->id}}">
                                    <label class="form-check-label" for="brand-{{$brand->id}}">
                                        {{$brand->name}}
                                    </label>
                                </div>
                                @endforeach
                                @endif

                            </div>
                        </div>

                        <div class="sub-title mt-5">
                            <h2>Price</h3>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        $0-$100
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                    <label class="form-check-label" for="flexCheckChecked">
                                        $100-$200
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                    <label class="form-check-label" for="flexCheckChecked">
                                        $200-$500
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                    <label class="form-check-label" for="flexCheckChecked">
                                        $500+
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row pb-3">
                            <div class="col-12 pb-1">
                                <div class="d-flex align-items-center justify-content-end mb-4">
                                    <div class="ml-2">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-light dropdown-toggle"
                                                data-bs-toggle="dropdown">Sorting</button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#">Latest</a>
                                                <a class="dropdown-item" href="#">Price High</a>
                                                <a class="dropdown-item" href="#">Price Low</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($products->isNotEmpty())
                            @foreach ($products as $product)
                                @php
                                    $productImg = $product->productmages->first();
                                @endphp
                                <div class="col-md-3">
                                    <div class="card product-card">
                                        <div class="product-image position-relative">
                                            <a href="" class="product-img">

                                                @if (!empty($productImg->image))
                                                    <img src="{{ asset('product/small/' . $productImg->image) }}"
                                                        class="img-thumbnail">
                                                @else
                                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                                        class="img-thumbnail" alt="" />
                                                @endif

                                            </a>
                                            <a class="whishlist" href="222"><i class="far fa-heart"></i></a>

                                            <div class="product-action">
                                                <a class="btn btn-dark" href="#">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body text-center mt-3">
                                            <a class="h6 link" href="product.php">{{ $product->title }}</a>
                                            <div class="price mt-2">
                                                <span class="h5"><strong>${{ $product->price }}</strong></span>
                                                <span
                                                    class="h6 text-underline"><del>${{ $product->compare_price }}</del></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif


                            <div class="col-md-12 pt-5">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-end">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1"
                                                aria-disabled="true">Previous</a>
                                        </li>
                                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>







    </main>
@endsection
