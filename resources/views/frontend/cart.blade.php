@extends('frontend.layouts.master')
@section('frontend_content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.shop') }}">Shop</a></li>
                        <li class="breadcrumb-item">Cart</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class=" section-9 pt-4">
            <div class="container">
                <div class="row">
                    @if (Cart::count() > 0)
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table" id="cart">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($cartContent as $cartContent)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if (!empty($cartContent->options->productmages->image))
                                                            <img src="{{ asset('products/small/' . $cartContent->options->productmages->image) }}"
                                                                class="img-thumbnail">
                                                        @else
                                                            <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                                                class="img-thumbnail" alt="" />
                                                        @endif
                                                        <h2>{{ $cartContent->name }}</h2>
                                                    </div>
                                                </td>
                                                <td>$100</td>
                                                <td>
                                                    <div class="input-group quantity mx-auto" style="width: 100px;">
                                                        <div class="input-group-btn">
                                                            <button class="btn btn-sm btn-dark btn-minus sub p-2 pt-1 pb-1"
                                                                data-id="{{ $cartContent->rowId }}">
                                                                <i class="fa fa-minus"></i>
                                                            </button>
                                                        </div>
                                                        <input type="text"
                                                            class="form-control form-control-sm  border-0  text-center"
                                                            value="{{ $cartContent->qty }}">
                                                        <div class="input-group-btn">
                                                            <button class="btn btn-sm btn-dark btn-plus p-2 add pt-1 pb-1"
                                                                data-id="{{ $cartContent->rowId }}">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    ${{ $cartContent->price * $cartContent->qty }}
                                                </td>
                                                <td>
                                                    <button onclick="deleteCart('{{ $cartContent->rowId }}')"
                                                        class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card cart-summery">

                                <div class="card-body">
                                    <div class="sub-title">
                                        <h2 class="bg-white">Cart Summery</h3>
                                    </div>
                                    <div class="d-flex justify-content-between pb-2">
                                        <div>Subtotal</div>
                                        <div>${{ Cart::subtotal() }}</div>
                                    </div>


                                    <div class="pt-5">
                                        <a href="{{ route('frontend.checkout') }}"
                                            class="btn-dark btn btn-block w-100">Proceed to Checkout</a>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group apply-coupan mt-4">
                                <input type="text" placeholder="Coupon Code" class="form-control">
                                <button class="btn btn-dark" type="button" id="button-addon2">Apply Coupon</button>
                            </div>
                        </div>
                    @else
                        <tr>
                            <td colspan="5" class=" text-center ">Your Cart Is Empty</td>
                        </tr>
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection
@section('customjs')
    <script>
        $('.add').click(function() {
            var qtyElement = $(this).parent().prev(); // Qty Input
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue < 10) {
                qtyElement.val(qtyValue + 1);
                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId, newQty);
            }
        });

        $('.sub').click(function() {
            var qtyElement = $(this).parent().next();
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue > 1) {
                qtyElement.val(qtyValue - 1);
                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId, newQty);
            }
        });

        function updateCart(rowId, qty) {
            $.ajax({
                url: '{{ route('frontend.updatecart') }}',
                type: 'post',
                data: {
                    rowId: rowId,
                    qty: qty
                },
                dataType: 'json',
                success: function(res) {
                    window.location.href = "{{ route('frontend.cart') }}";



                }
            });
        }

        function deleteCart(rowId) {

            if (confirm("Are You Sure To Delete This Cart?")) {
                $.ajax({
                    url: '{{ route('frontend.delete') }}',
                    type: 'delete',
                    data: {
                        rowId: rowId,

                    },
                    dataType: 'json',
                    success: function(res) {
                        window.location.href = "{{ route('frontend.cart') }}";



                    }
                });
            }
        }
    </script>
@endsection
