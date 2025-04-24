<template>
    <div class="flex flex-col items-center justify-center h-screen bg-gray-100">
      <div class="max-w-lg mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-8">Oops! Page Not Found</h1>
        <p class="text-gray-600 mb-8">The page you are looking for might have been removed or doesn't exist.</p>
        <router-link :to="{name: 'app.dashboard'}" class="btn-indigo">Go back home</router-link>
      </div>
    </div>
  </template>
  
  
  
  
  <script setup>
  import { useRoute } from "vue-router";


  const router = useRoute();
  </script>