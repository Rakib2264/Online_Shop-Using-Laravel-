<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Email</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px;">
    <h1>Thanks For Your Order</h1>
    <h2>Your Order Id Is:#{{ $mailData['order']->id }}</h2>
    <h2>Shipping Address</h2>
    <address>
        <strong> {{ $mailData['order']->first_name . ' ' . $mailData['order']->last_name }}</strong><br>
        Address: {{ $mailData['order']->address }}<br>
        Country Name:  {{getCountry($mailData['order']->country_id)->name }}<br>
        City: {{ $mailData['order']->city }} <br>
        Zip: {{ $mailData['order']->zip }} <br>
        Phone: {{ $mailData['order']->mobile }}<br>
        Email: {{$mailData['order']->email }}
    </address>
    <h2>Products</h2>
    <table cellpadding="3" cellspaching="3" border="0" width="700">
        <thead>
            <tr style="background: #ccc">
                <th>Product</th>
                <th width="100">Price</th>
                <th width="100">Qty</th>
                <th width="100">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mailData['order']->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach

            <tr>
                <th colspan="3" align="right">Subtotal:</th>
                <td>${{ number_format($mailData['order']->subtotal, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3"align="right">
                    Discount:{{ !empty($mailData['order']->coupon_code) ? '(' . $mailData['order']->coupon_code . ')' : '' }}
                </th>
                <td>${{ number_format($mailData['order']->discount, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3" align="right">Shipping:</th>
                <td>${{ number_format($mailData['order']->shipping, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3" align="right">Grand Total:</th>
                <td>${{ number_format($mailData['order']->grand_total, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
