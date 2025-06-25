<x-app-layout>

<div class="max-w-4xl mx-auto p-6">
    <!-- Header with back button -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('order.index') }}" class="flex items-center text-white hover:text-gray-300 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to All Orders
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Order #{{$order->id}}</h1>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Order Summary Card -->
        <div class="p-6 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-3">Order Information</h2>
                    <div class="space-y-2">
                        <div class="flex">
                            <span class="text-gray-600 font-medium w-32">Order #:</span>
                            <span>{{$order->id}}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 font-medium w-32">Order Date:</span>
                            <span>{{$order->created_at->format('F j, Y \a\t g:i a')}}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 font-medium w-32">Status:</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{$order->isPaid() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'}}">
                                {{$order->status}}
                            </span>
                        </div>

                        <div class="flex">
                            <span class="text-gray-600 font-medium w-32">Payment Status:</span>
                                 @if($order->payment)
                             <span class="px-4 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                {{ $order->payment->status }}
                            </span>
                            @else
                               <span class="text-sm text-gray-400 italic">Not Available</span>
                           @endif
                        </div>   
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-3">Payment Summary</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span>£{{ number_format($order->total_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping:</span>
                            <span>Free</span>
                        </div>
                        <div class="flex justify-between font-medium border-t border-gray-200 pt-2 mt-2">
                            <span>Total:</span>
                            <span class="text-emerald-600">£{{ number_format($order->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Order Items ({{ $order->items->count() }})</h2>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex flex-col sm:flex-row items-start gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <a href="{{ route('product.view', $item->product) }}" class="flex-shrink-0">
                        <img src="{{$item->product->image}}" 
                             alt="{{$item->product->title}}" 
                             class="w-24 h-24 object-contain bg-white p-2 rounded border border-gray-200">
                    </a>
                    <div class="flex-1 w-full">
                        <div class="flex flex-col sm:flex-row sm:justify-between">
                            <div class="mb-2 sm:mb-0">
                                <a href="{{ route('product.view', $item->product) }}" class="font-medium text-gray-900 hover:text-emerald-600">
                                    {{$item->product->title}}
                                </a>
                                <p class="text-sm text-gray-500 mt-1">SKU: {{$item->product->id}}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-900 font-medium">${{ number_format($item->unit_price, 2) }}</p>
                                <p class="text-sm text-gray-500">Qty: {{$item->quantity}}</p>
                            </div>
                        </div>
                        <div class="mt-2 sm:mt-4 flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">
                                Item Total: ${{ number_format($item->unit_price * $item->quantity, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Payment Button (if not paid) -->
        @if (!$order->isPaid())
        <div class="p-6 border-t border-gray-200 bg-gray-50">
            <form action="{{ route('cart.checkout-order', $order) }}" method="POST" 
                  x-data="{ loading: false }" @submit="loading = true">
                @csrf
                <button class="w-full md:w-auto flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors"
                        type="submit"
                        :disabled="loading">
                <template x-if="loading">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"> </path>
                </svg>
                </template>
                <span x-text="loading ? 'Processing...' : 'Complete Payment'"></span>
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
</x-app-layout>