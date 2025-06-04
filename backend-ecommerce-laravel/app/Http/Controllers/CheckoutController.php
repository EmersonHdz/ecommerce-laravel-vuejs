<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Helpers\Cart;
use App\Mail\NewOrderEmail;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $customer = $user->customer;
        if (!$customer->billingAddress || !$customer->shippingAddress) {
            return redirect()->route('profile.billing')->with('flash_message', 'Please complete your shipping address before continuing with your order.');
        }

        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $orderItems = [];
        $lineItems = [];
        $totalPrice = 0;

        DB::beginTransaction();

        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            if ($product->quantity !== null && $product->quantity < $quantity) {
                $message = match ($product->quantity) {
                    0 => 'The product "'.$product->title.'" is out of stock',
                    1 => 'There is only one item left for product "'.$product->title,
                    default => 'There are only ' . $product->quantity . ' items left for product "'.$product->title,
                };
                return redirect()->back()->with('error', $message);
            }
        }

        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->title,
                        'images' => $product->image ? [$product->image] : []
                    ],
                    'unit_amount' => $product->price * 100,
                ],
                'quantity' => $quantity,
            ];
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price
            ];

            if ($product->quantity !== null) {
                $product->quantity -= $quantity;
                $product->save();
            }
        }


        $session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'customer_creation' => 'always',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
        ]);

        try {

            // Create Order
            $orderData = [
                'total_price' => $totalPrice,
                'status' => OrderStatus::Unpaid,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ];
            $order = Order::create($orderData);

            // Create Order Items
            foreach ($orderItems as $orderItem) {
                $orderItem['order_id'] = $order->id;
                OrderItem::create($orderItem);
            }

            // Create Payment
            $paymentData = [
                'order_id' => $order->id,
                'amount' => $totalPrice,
                'status' => PaymentStatus::Pending,
                'type' => 'cc',
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'session_id' => $session->id
            ];
            Payment::create($paymentData);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::critical(__METHOD__ . ' method does not work. '. $e->getMessage());
            throw $e;
        }

        DB::commit();

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        try {
            $session_id = $request->get('session_id');
            $session = \Stripe\Checkout\Session::retrieve($session_id);
            if (!$session) {
                return view('checkout.failure', ['message' => 'Invalid Session ID']);
            }

            $payment = Payment::query()
                ->where(['session_id' => $session_id])
                ->whereIn('status', [PaymentStatus::Pending, PaymentStatus::Paid])
                ->first();
            if (!$payment) {
                throw new NotFoundHttpException();
            }
            if ($payment->status === PaymentStatus::Pending->value) {
                $this->updateOrderAndSession($payment);
            }
            CartItem::where('user_id', $user->id)->delete();

            $customer = \Stripe\Customer::retrieve($session->customer);

            return view('checkout.success', compact('customer'));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            return view('checkout.failure', ['message' => $e->getMessage()]);
        }
    }

    public function failure(Request $request)
    {
        return view('checkout.failure', ['message' => ""]);
    }

    public function checkoutOrder(Order $order, Request $request)
    {
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->title,
                    // 'images' => [$product->image]
                    ],
                    'unit_amount' => $item->unit_price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }

        $session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
        ]);

        $order->payment->session_id = $session->id;
        $order->payment->save();


        return redirect($session->url);
    }
    
    public function webhook(Request $request)
{
    \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
    $endpoint_secret = env('WEBHOOK_SECRET_KEY');

    $payload = $request->getContent();
    $sig_header = $request->header('Stripe-Signature');
    $event = null;

    try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
    } catch (\UnexpectedValueException $e) {
        Log::error('Invalid Stripe payload', ['message' => $e->getMessage()]);
        return response('Invalid payload', 401);
    } catch (\Stripe\Exception\SignatureVerificationException $e) {
        Log::error('Invalid Stripe signature', ['message' => $e->getMessage()]);
        return response('Invalid signature', 402);
    }

    switch ($event->type) {
        case 'checkout.session.completed':
            $session = $event->data->object;

            $payment = Payment::query()
                ->where('session_id', $session->id)
                ->where('status', PaymentStatus::Pending)
                ->first();

            if ($payment) {
                $this->updateOrderAndSession($payment);

                // ✅delete cart items for the user
                if ($payment->order && $payment->order->user_id) {
                    CartItem::where('user_id', $payment->order->user_id)->delete();
                    Log::info("delete cart to user ID: " . $payment->order->user_id);
                }
            } else {
                Log::warning("No Payment was found with session_id: " . $session->id);
            }

            break;

        default:
            Log::info('Not managed event: ' . $event->type);
            break;
    }

    return response('Webhook recevied', 200);
}


    private function updateOrderAndSession(Payment $payment)
    {
        DB::beginTransaction();
        try {
            $payment->status = PaymentStatus::Paid->value;
            $payment->update();

            $order = $payment->order;

            $order->status = OrderStatus::Paid->value;
            $order->update();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::critical(__METHOD__ . ' method does not work. '. $e->getMessage());
            throw $e;
        }

        DB::commit();

        try {
            $adminUsers = User::where('is_admin', 1)->get();

            foreach ([...$adminUsers, $order->user] as $user) {
                Mail::to($user)->send(new NewOrderEmail($order, (bool)$user->is_admin));
            }
        } catch (\Exception $e) {
            Log::critical('Email sending does not work. '. $e->getMessage());
        }
    }
}