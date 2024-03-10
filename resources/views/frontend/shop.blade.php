@extends('frontend.layouts.master')
@section('frontend_content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.home') }}">Home</a></li>
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
                                                    <a href="{{ route('frontend.shop', $category->slug) }}"
                                                        class="nav-item nav-link {{ $categorySelected == $category->id ? 'text-primary' : '' }}">{{ $category->name }}</a>
                                                @endif
                                                <div id="collapseOne{{ $category->id }}"
                                                    class="accordion-collapse collapse {{ $categorySelected == $category->id ? 'show' : '' }}"
                                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample"
                                                    style="">
                                                    <div class="accordion-body">
                                                        <div class="navbar-nav">
                                                            @if ($category->sub_category->isNotEmpty())
                                                                @foreach ($category->sub_category as $sub_category)
                                                                    <a href="{{ route('frontend.shop', [$category->slug, $sub_category->slug]) }}"
                                                                        class="nav-item nav-link {{ $subcategorySelected == $sub_category->id ? 'text-primary' : '' }}">{{ $sub_category->name }}</a>
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
                                            <input {{ in_array($brand->id, $brandsArray) ? 'checked' : '' }}
                                                class="form-check-input brand-label" type="checkbox"
                                                value="{{ $brand->id }}" name="brand[]" id="brand-{{ $brand->id }}">
                                            <label class="form-check-label" for="brand-{{ $brand->id }}">
                                                {{ $brand->name }}
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
                                <input type="text" class="js-range-slider" name="my_range" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row pb-3">
                            <div class="col-12 pb-1">
                                <div class="d-flex align-items-center justify-content-end mb-4">
                                    <div class="ml-2">
                                        <select name="sort" id="sort" class="form-select">
                                            <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Latest
                                            </option>
                                            <option value="price_dec" {{ $sort == 'price_dec' ? 'selected' : '' }}>Price
                                                High</option>
                                            <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Price
                                                Low
                                            </option>
                                        </select>
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
                                                <a href="{{route('frontend.product_detail',$product->slug)}}" class="product-img">

                                                    @if (!empty($productImg->image))
                                                        <img src="{{ asset('products/small/' . $productImg->image) }}"
                                                            class="img-thumbnail">
                                                    @else
                                                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                                            class="img-thumbnail" alt="" />
                                                    @endif

                                                </a>
                                                <a class="whishlist" onclick="addtowishlist({{$product->id}})" href="javascript:void(0)"><i class="far fa-heart"></i></a>

                                                <div class="product-action">
                                                    <a class="btn btn-dark" href="javascript:void(0)" onclick="addToCart({{ $product->id }})">
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
                                {{ $products->links() }}
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
        $(document).ready(function() {
            // Initialize the ionRangeSlider for price range selection
            $(".js-range-slider").ionRangeSlider({
                type: "double",
                min: 0,
                max: 1000,
                from: {{ $pricemin }}, // Set the initial minimum price
                step: 5,
                to: {{ $pricemax }}, // Set the initial maximum price
                skin: "round",
                max_postfix: "+",
                prefix: "$",
                onFinish: function() {
                    // When the range selection is finished, call the apply_filters function
                    apply_filters();
                }
            });

            // Get the ionRangeSlider instance
            var slider = $(".js-range-slider").data('ionRangeSlider');

            // Event listener for changes in brand selection
            $('.brand-label').change(function() {
                // When brand selection changes, call the apply_filters function
                apply_filters();
            });

            // Event listener for changes in sorting selection
            $("#sort").change(function() {
                // When sorting selection changes, call the apply_filters function
                apply_filters();
            });

            // Define the apply_filters function
            function apply_filters() {
                // Initialize an empty array to store selected brands
                var brands = [];

                // Iterate over each element with the class 'brand-label'
                $(".brand-label").each(function() {
                    // Check if the current element is checked
                    if ($(this).is(":checked") == true) {
                        // If checked, add the value of the element to the brands array
                        brands.push($(this).val());
                    }
                });

                // Construct the URL for filtering and sorting
                var url = '{{ url()->current() }}?';

                // Append the selected price range to the URL
                url += '&price_min=' + slider.result.from + '&price_max=' + slider.result.to;

                // Append the selected brands to the URL
                if (brands.length > 0) {
                    url += '&brand=' + brands.toString();
                }

                // Append the selected sorting option to the URL
                url += '&sort=' + $("#sort").val();

                // Redirect to the constructed URL
                window.location.href = url;
            }
        });
    </script>
@endsection
