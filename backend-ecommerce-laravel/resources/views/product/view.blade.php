<x-app-layout>
    <div 
      x-data="productItem({{ json_encode([
        'id' => $product->id,
        'slug' => $product->slug,
        'image' => $product->image,
        'title' => $product->title,
        'price' => $product->price,
        'addToCartUrl' => route('cart.add', $product)
      ]) }})" 
      class="max-w-6xl mx-auto px-4 py-8"
    >
      <div class="grid gap-10 lg:grid-cols-5">
        <!-- Image Gallery -->
        <div class="lg:col-span-3" 
          x-data="{
            images: ['{{$product->image}}'],
            activeImage: null,
            prev() {
              let i = this.images.indexOf(this.activeImage);
              this.activeImage = this.images[(i - 1 + this.images.length) % this.images.length];
            },
            next() {
              let i = this.images.indexOf(this.activeImage);
              this.activeImage = this.images[(i + 1) % this.images.length];
            },
            init() {
              this.activeImage = this.images[0];
            }
          }"
          x-init="init"
        >
          <div class="relative rounded-xl overflow-hidden">
            <template x-for="image in images" :key="image">
              <div x-show="activeImage === image" class="aspect-w-4 aspect-h-3">
                <img :src="image" alt="Product image" class="object-cover w-full h-full transition duration-300 rounded-xl" />
              </div>
            </template>
            <!-- Controls -->
            <button @click.prevent="prev" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-black/40 text-white p-2 rounded-r-md hover:bg-black/60">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <button @click.prevent="next" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-black/40 text-white p-2 rounded-l-md hover:bg-black/60">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>
          <!-- Thumbnails -->
          <div class="flex gap-4 mt-4 overflow-x-auto">
            <template x-for="image in images" :key="image">
              <div @click="activeImage = image" 
                class="w-20 h-20 border-2 rounded-md flex items-center justify-center cursor-pointer"
                :class="activeImage === image ? 'border-emerald-300' : 'border-gray-300'"
              >
                <img :src="image" alt="Thumbnail" class="object-contain max-w-full max-h-full rounded" />
              </div>
            </template>
          </div>
        </div>
  
        <!-- Product Info -->
        <div class="lg:col-span-2 space-y-6">
          <h1 class="text-2xl font-bold text-gray-100">{{ $product->title }}</h1>
          <div class="text-3xl font-extrabold text-gray-200">${{ $product->price }}</div>
  
          <!-- Quantity -->
          <div class="flex items-center gap-4">
            <label for="quantity" class="font-semibold text-gray-100">Quantity:</label>
            <input type="number" x-ref="quantityEl" value="1" min="1"
              class="w-24 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-300" />
          </div>
  
          <!-- Add to Cart Button -->
          <button @click="addToCart($refs.quantityEl.value)"
            class="w-full py-3 px-4 text-white bg-emerald-600 font-semibold rounded-lg flex items-center justify-center gap-2 hover:bg-emerald-700 active:bg-emerald-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Add to Cart
          </button>
  
      <!-- Description -->
<div x-data="{ expanded: false }" class="text-gray-100">
    <!-- 3 lines resum -->
    <div
      x-show="!expanded"
      x-cloak
      class="line-clamp-3 transition-all duration-300"
    >
      {!! $product->description !!}
    </div>
  
    <!-- Descripción -->
    <div
      x-show="expanded"
      x-collapse
      class="transition-all duration-300"
    >
      {!! $product->description !!}
    </div>
  
    <!--Read More / Less -->
    <div class="text-right">
      <button
        @click="expanded = !expanded"
        class="text-emerald-400 hover:underline mt-2"
        x-text="expanded ? 'Read Less' : 'Read More'"
      ></button>
    </div>
  </div>
  
      </div>
    </div>
  </x-app-layout>
