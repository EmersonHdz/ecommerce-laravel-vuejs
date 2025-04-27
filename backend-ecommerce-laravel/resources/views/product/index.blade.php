<x-app-layout>

    
    
        <!-- Título Principal -->
        <section class="px-4 mb-6 mt-9">
            <h2 class="text-3xl font-bold text-gray-200 text-center mb-2">Nuestros Productos</h2>
            <p class="text-center text-gray-400">Seleccionados cuidadosamente para ti.</p>
        </section>
    <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4  gap-6 auto-rows-fr px-4 py-8">

        @foreach($products as $product)
        <div 
            x-data="{
                selectedColor: 'bg-blue-500',
                colors: ['bg-blue-500', 'bg-emerald-500', 'bg-rose-500', 'bg-amber-500'],
                quantity: 1,
                isFavorite: false,
                isAdded: false,
                addToCart() {
                    this.isAdded = true;
                }
            }"
            class="bg-white rounded-2xl shadow-md hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 flex flex-col h-full"
        >
    
            <!-- Imagen del producto -->
            <div class="relative h-64 overflow-hidden rounded-t-2xl">
                <a href="{{ route('product.view', $product->slug) }}">
                <img 
                    src="{{ $product->image }}" 
                    alt="{{ $product->title }}"
                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-500 ease-in-out"
                >
                </a>
            </div>
    
            <!-- Contenido de la tarjeta -->
            <div class="p-6 flex flex-col justify-between flex-1">
                <a href="{{ route('product.view', $product->slug) }}">
                <!-- Título -->
                <h3 class="text-lg font-bold text-gray-800 mb-2 truncate">{{ $product->title }}</h3>
    
                <!-- Descripción -->
                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $product->description }}</p>
                </a>
    
               <!-- Precio y botón de compra -->
               <div class="flex flex-col items-start gap-4 mt-auto">

               <!-- Precio -->
              <div class="text-2xl font-extrabold text-gray-900">
                   ${{ $product->price }}
              </div>

    <!-- Botón de añadir -->
    <button 
        @click="addToCart"
        class="w-full flex justify-center items-center gap-2 px-5 py-3 text-white bg-gray-900 rounded-xl font-semibold hover:bg-gray-800 hover:scale-105 active:scale-95 transition-all duration-300 ease-in-out"
    >
        <template x-if="!isAdded">
            <span class="flex items-center gap-2">
                <i class="fas fa-shopping-cart"></i>
                Add to cart
            </span>
        </template>
        <template x-if="isAdded">
            <span class="flex items-center gap-2 ">
                <i class="fas fa-check"></i>
                Added
            </span>
        </template>
    </button>

</div>

            </div>
    
        </div>
        @endforeach
    
    </section>
    
    
   <div class="flex justify-center mt-6 mb-9">
        {{ $products->links() }}
    </div>
</x-app-layout>