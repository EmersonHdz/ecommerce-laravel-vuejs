<x-app-layout>
    <div x-data="productItem({{ json_encode([
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'image' => $product->image ?: '/img/noimage.png',
                    'title' => $product->title,
                    'description' => $product->description,
                    'price' => $product->price,
                ]) }})" class="container mx-auto px-4 py-8">
        <div class="grid gap-8 grid-cols-1 lg:grid-cols-5">
            <!-- Image Gallery -->
            <div class="lg:col-span-3">
                <div x-data="{
                  images: {{$product->images->count() ? $product->images->map(fn($im) => $im->url) : json_encode(['/img/noimage.png'])}},
                  activeImage: null,
                  prev() {
                      let index = this.images.indexOf(this.activeImage);
                      if (index === 0) index = this.images.length;
                      this.activeImage = this.images[index - 1];
                  },
                  next() {
                      let index = this.images.indexOf(this.activeImage);
                      if (index === this.images.length - 1) index = -1;
                      this.activeImage = this.images[index + 1];
                  },
                  init() {
                      this.activeImage = this.images.length > 0 ? this.images[0] : null
                  }
                }">
                    <!-- Main Image -->
                    <div class="relative bg-gray-100 rounded-lg overflow-hidden shadow-md">
                        <template x-for="image in images">
                            <div x-show="activeImage === image" 
                                 class="w-full aspect-square flex items-center justify-center p-4">
                                <img :src="image" :alt="$product->title" 
                                     class="w-auto h-auto max-w-full max-h-[500px] object-contain mx-auto"/>
                            </div>
                        </template>
                        
                        <!-- Navigation Arrows -->
                        <button @click.prevent="prev"
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 text-gray-800 rounded-full p-2 shadow-md hover:bg-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button @click.prevent="next"
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 text-gray-800 rounded-full p-2 shadow-md hover:bg-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Thumbnails -->
                    <div class="flex gap-2 mt-4">
                        <template x-for="image in images">
                            <button @click.prevent="activeImage = image"
                                    class="w-16 h-16 border-2 rounded-md overflow-hidden hover:border-gray-400 transition-all"
                                    :class="{'border-gray-800': activeImage === image, 'border-transparent': activeImage !== image}">
                                <img :src="image" alt="Thumbnail" class="w-full h-full object-cover"/>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="lg:col-span-2">
                <div class="sticky top-4">
                    <!-- Title & Price -->
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-white mb-2">{{ $product->title }}</h1>
                        <div class="text-3xl font-bold text-white">${{ number_format($product->price, 2) }}</div>
                        @if($product->quantity === 0)
                            <div class="bg-red-100 text-red-800 py-2 px-4 rounded-md mt-4 inline-block">
                                Out of stock
                            </div>
                        @else
                            <div class="text-green-600 mt-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                In stock
                            </div>
                        @endif
                    </div>
                    
                    <!-- Quantity Selector -->
                    <div class="mb-6">
                        <label for="quantity" class="block text-sm font-medium text-white mb-2">Quantity</label>
                        <div class="flex items-center border border-gray-300 rounded-md w-32">
                            <button class="px-3 py-2 text-white hover:text-white" @click="$refs.quantityEl.stepDown()">-</button>
                            <input type="number" id="quantity" x-ref="quantityEl" 
                                   value="1" min="1" max="{{ $product->quantity }}"
                                   class="w-full text-center border-0 focus:ring-0">
                            <button class="px-3 py-2 text-white hover:text-white" @click="$refs.quantityEl.stepUp()">+</button>
                        </div>
                    </div>
                    
                    <!-- Add to Cart -->
                    <button class="w-full  bg-emerald-600 hover:bg-emerald-700 text-white  py-3 px-4 rounded-md font-medium transition-all flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Add to Cart
                    </button>
                    
                    <!-- Product Description -->
                    <div class="border-t border-gray-200 pt-6" x-data="{expanded: false}">
                        <h3 class="text-lg font-medium text-white mb-3">Product Details</h3>
                        <div x-show="expanded" x-collapse.min.120px class="prose prose-sm max-w-none text-white">
                            {!! $product->description !!}
                        </div>
                        <button @click="expanded = !expanded" 
                                class="text-sm font-medium text-white hover:text-white mt-2 flex items-center">
                            <span x-text="expanded ? 'Show Less' : 'Show More'"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 :class="{'rotate-180': expanded}">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
