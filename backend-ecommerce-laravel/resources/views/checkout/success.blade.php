<x-app-layout>
<div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl my-12">
  <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 text-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <h1 class="text-2xl font-bold text-white mt-4">¡Payment Complete with Success!</h1>
  </div>
  
  <div class="p-8">
    <div class="flex items-center mb-6">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
      </svg>
      <p class="text-gray-700">We have sent your purchase details to the registered email.</p>
    </div>
    
    <div class="flex items-center mb-6">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
      </svg>
      <p class="text-gray-700">Your order #{{$order->id ?? '12345'}} has been processed correctly.</p>
    </div>
    
    @if($message ?? false)
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-blue-700">{{$message}}</p>
        </div>
      </div>
    </div>
    @endif
    
    <div class="mt-8">
      <a href="#" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
        See Order Details
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </a>
      
      <a href="{{route('home')}}" class="ml-4 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
        Buy More Products
      </a>
    </div>
    
    <div class="mt-8 pt-6 border-t border-gray-200 text-center">
      <p class="text-sm text-gray-500">¿Need help? <a href="#" class="text-emerald-600 hover:text-emerald-500">Contact</a></p>
    </div>
  </div>
</div>
</x-app-layout>