
<x-app-layout>

<div x-data="{ step: 1 }" class="relative space-y-5 m-10">

  <!-- Navigation -->
  <div class="flex justify-center gap-4 mb-6">
    <button @click="step = 1"
            :class="step === 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-white'"
            class="px-4 py-2 rounded-lg transition-all duration-300">Profile</button>

    <button @click="step = 2"
            :class="step === 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-white'"
            class="px-4 py-2 rounded-lg transition-all duration-300">Password</button>

    <button @click="step = 3"
            :class="step === 3 ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-white'"
            class="px-4 py-2 rounded-lg transition-all duration-300">Shipping</button>

    <button @click="step = 4"
            :class="step === 4 ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-white'"
            class="px-4 py-2 rounded-lg transition-all duration-300">Billing</button>
  </div>

  <div class="">
    <!-- Step 1 -->
  <div x-show="step === 1"
       x-transition
       x-cloak
       class="p-5 dark:bg-gray-800 sm:rounded-lg shadow rounded-lg">
    @include('profile.profile-details')
  </div>

  <!-- Step 2 -->
  <div x-show="step === 2"
       x-transition
       x-cloak
       class="p-5 dark:bg-gray-800 sm:rounded-lg shadow rounded-lg">
    @include('profile.password-update')
  </div>

  <!-- Step 3 -->
  <div x-show="step === 3"
       x-transition
       x-cloak
       class="p-5 mb-10 dark:bg-gray-800 sm:rounded-lg shadow rounded-lg">
    @include('profile.shipping-address')
  </div>

  <!-- Step 4 -->
  <div x-show="step === 4"
       x-transition
       x-cloak
       class="p-5 dark:bg-gray-800 sm:rounded-lg shadow rounded-lg">
    @include('profile.billing-address')
  </div>
</div>



</x-app-layout>