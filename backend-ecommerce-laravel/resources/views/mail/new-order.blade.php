<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(to right, #10B981, #059669);
            padding: 30px 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            color: white;
            margin: 0;
            font-size: 24px;
        }
        .content {
            border: 1px solid #E5E7EB;
            border-top: none;
            border-radius: 0 0 8px 8px;
            padding: 25px;
            background: white;
        }
        .order-summary {
            background: #F3F4F6;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .order-table th {
            text-align: left;
            padding: 12px;
            background-color: #F9FAFB;
            border-bottom: 1px solid #E5E7EB;
        }
        .order-table td {
            padding: 12px;
            border-bottom: 1px solid #E5E7EB;
        }
        .product-image {
            width: 80px;
            height: auto;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #059669;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin-top: 15px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            font-size: 14px;
            color: #6B7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Your Order Confirmation</h1>
    </div>
    
    <div class="content">
        <p>Hello,</p>
        <p>Thank you for your purchase! We're preparing your order and will notify you when it's on its way.</p>
        
        <div class="order-summary">
            <h2 style="margin-top: 0; color: #111827;">Order Summary</h2>
            
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 8px 0; color: #6B7280;">Order Number:</td>
                    <td style="padding: 8px 0; font-weight: 500;">
                        <a href="{{ $forAdmin ? env('BACKEND_URL').'/app/orders/'.$order->id : route('order.view', $order, true) }}" style="color: #059669; text-decoration: none;">
                            #{{$order->id}}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6B7280;">Status:</td>
                    <td style="padding: 8px 0; font-weight: 500;">{{ ucfirst($order->status) }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6B7280;">Order Total:</td>
                    <td style="padding: 8px 0; font-weight: 500;">${{number_format($order->total_price, 2)}}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6B7280;">Order Date:</td>
                    <td style="padding: 8px 0; font-weight: 500;">{{$order->created_at->format('F j, Y \a\t g:i a')}}</td>
                </tr>
            </table>
        </div>
        
        <h2 style="color: #111827; margin-bottom: 15px;">Order Details</h2>
        
        <table class="order-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th style="text-align: right;">Price</th>
                    <th style="text-align: right;">Qty</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{$item->product->title}}</td>
                    <td style="text-align: right;">${{number_format($item->unit_price, 2)}}</td>
                    <td style="text-align: right;">{{$item->quantity}}</td>
                    <td style="text-align: right; font-weight: 500;">${{number_format($item->unit_price * $item->quantity, 2)}}</td>
                </tr>
                @endforeach
                <tr style="font-weight: 500;">
                    <td colspan="4" style="text-align: right; border-top: 1px solid #E5E7EB;">Order Total:</td>
                    <td style="text-align: right; border-top: 1px solid #E5E7EB;">${{number_format($order->total_price, 2)}}</td>
                </tr>
            </tbody>
        </table>
        
        <a href="{{ $forAdmin ? env('BACKEND_URL').'/app/orders/'.$order->id : route('order.view', $order, true) }}" class="button">
            View Your Order
        </a>
        
        <div class="footer">
            <p>If you have any questions about your order, please reply to this email or contact our support team.</p>
            <p>Thank you for shopping with us!</p>
        </div>
    </div>
</body>
</html>