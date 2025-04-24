<template>
  <!-- Sidebar component -->
  <aside class="w-[200px] bg-indigo-900 transition-all duration-300 text-gray-100 py-4 px-1">
    <!-- Logo Area -->
    <div class="rounded-lg text-center">
      <h1 class="text-2xl font-bold text-white my-2">Brand Name</h1>
      <p class="text-white text-base text-center">Admin</p>
    </div>
    
    <!-- Navigation Area -->
    <nav class="mt-[3rem]">
      <!-- Loop through navigation links -->
      <router-link v-for="link in links" :key="link.name" :to="{ name: link.name }">
        <!-- Navigation link -->
        <div
          class="p-2 mt-3 flex items-center rounded-md transition-colors duration-300 cursor-pointer hover:bg-indigo-700"
          :class="{ 'bg-indigo-600': isLinkActive(link.name) }">
          <!-- Icon -->
          <i :class="link.icon"></i>
          <!-- Label -->
          <span class="text-[1.2rem] font-semibold ml-4 text-gray-200">{{ link.label }}</span>
        </div>
      </router-link>
    </nav>
  </aside>
</template>

<script setup>
import { useRoute } from "vue-router";

// Array of navigation links
const links = [
  { name: 'app.dashboard', icon: 'bi bi-house-door-fill', label: 'Home' },
  { name: 'app.products', icon: 'bi bi-cart-fill', label: 'Products' },
  { name: 'app.orders', icon: 'bi bi-bag-fill', label: 'Orders' },
  { name: 'app.users', icon: 'bi bi-person-circle', label: 'Users' },
];

// Function to check if a link is active
const isLinkActive = (routeName) => {
  const route = useRoute();
  return route.name === routeName;
};
</script>

<style scoped>
/* Scoped styles */
</style>
