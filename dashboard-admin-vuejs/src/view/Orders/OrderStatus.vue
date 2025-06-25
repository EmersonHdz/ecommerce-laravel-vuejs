<template>
  <span class="text-white py-1 px-2 rounded " :class="{
      'bg-gray-400': order.status === 'unpaid',
      'bg-orange-600': order.status === 'pending',
      'bg-orange-400': order.status === 'shipped',
      'bg-red-500': order.status === 'cancelled',
      
    }">{{ order.status }}</span>
</template>

<script setup>
const {order} = defineProps({
  order: Object
})
</script>

<style scoped>

</style>