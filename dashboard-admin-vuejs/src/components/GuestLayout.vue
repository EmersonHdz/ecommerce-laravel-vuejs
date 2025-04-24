<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-[5rem] md:pt-0">
      <div class="md:w-full  sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden rounded-lg">
        <div>
          <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            {{ title }}
          </h2>
        </div>
  
        <slot></slot>
      </div>
    </div>
  </template>
  
  <script setup>
    const {title} = defineProps({
      title: String
    })
  </script>
  
  <style scoped>
  
  </style>