

<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-cyan-100">Shopping Cart</h1>
            <a href="{{ route('home') }}" class="flex items-center rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-cyan-100 hover:text-cyan-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
               <span class="text-cyan-100 hover:text-cyan-300"> Continue Shopping</span>
            </a>
        </div>

        <div 
            x-data="{
                cartItems: {{
                    json_encode(
                        $products->map(fn($product) => [
                            'id' => $product->id,
                            'slug' => $product->slug,
                            'image' => $product->image ?: '/img/noimage.png',
                            'title' => $product->title,
                            'price' => $product->price,
                            'quantity' => $cartItems[$product->id]['quantity'],
                            'href' => route('product.view', $product->slug),
                            'removeUrl' => route('cart.remove', $product),
                            'updateQuantityUrl' => route('cart.update-quantity', $product)
                        ])
                    )
                }},
                get cartTotal() {
                    return this.cartItems.reduce((sum, item) => sum + item.price * item.quantity, 0).toFixed(2)
                },
            }" 
            class="bg-white rounded-xl shadow-md p-4 space-y-6">

            <!-- If items exist -->
            <template x-if="cartItems.length">
                <div class="space-y-6">
                    <template x-for="product in cartItems" :key="product.id">
                        <div x-data="productItem(product)" class="flex flex-col sm:flex-row items-center gap-4 border-b pb-4">
                            <!-- Product Image -->
                            <a :href="product.href" class="w-full sm:w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                <img :src="product.image" class="object-cover w-full h-full" alt="Product image">
                            </a>

                            <!-- Product Info -->
                            <div class="flex-1 w-full space-y-2">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-medium text-gray-800" x-text="product.title"></h3>
                                    <span class="text-purple-700 font-semibold text-base sm:text-lg">
                                        £<span x-text="product.price"></span>
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center justify-between gap-3 w-full">
                                    <!-- Quantity input -->
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-500">Quantity:</span>
                                        <input 
                                            type="number" 
                                            min="1" 
                                            x-model.number="product.quantity"
                                            @change="changeQuantity(product)"
                                            class="w-20 px-2 py-1 rounded border border-gray-300 focus:outline-none focus:ring focus:ring-purple-400"
                                        />
                                    </div>
                                
                                    <!-- Remove button with SVG icon -->
                                    <button 
                                        @click.prevent="removeItemFromCart(product)"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-red-600 bg-red-100 hover:bg-red-200 hover:text-red-700 rounded-lg transition duration-150 ease-in-out"
                                    >
                                
                                        Remove
                                    </button>
                                </div>
                                
                                
                                
                            </div>
                        </div>
                    </template>

                    <!-- Totals and Checkout -->
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-lg font-semibold">Subtotal</span>
                            <span class="text-lg font-bold text-gray-700" x-text="`$${cartTotal}`"></span>
                        </div>
                        <p class="text-sm text-gray-500 mb-4">
                            Shipping and taxes calculated at checkout.
                        </p>

                        <form action="#" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 rounded-lg text-lg transition">
                                Proceed to Checkout
                            </button>
                        </form>
                    </div>
                </div>
            </template>

            <!-- Empty Cart -->
            <template x-if="!cartItems.length">
                <div class="text-center text-gray-500 py-10">
                    <p class="text-xl">🛍️ Your cart is empty</p>
                    <a href="{{ route('home') }}" class="mt-4 inline-block text-purple-600 hover:underline text-sm">
                        Go back to shop
                    </a>
                </div>
            </template>
        </div>
    </div>
</x-app-layout>

