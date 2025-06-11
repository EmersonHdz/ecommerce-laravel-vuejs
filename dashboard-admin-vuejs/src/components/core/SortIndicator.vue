<template>
  <span v-if="field === currentField" class="ml-1">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      class="h-4 w-4"
      viewBox="0 0 20 20"
      fill="currentColor"
      :class="{
        'text-gray-400': direction === 'asc',
        'text-gray-400 rotate-180': direction === 'desc'
      }"
    >
      <path
        fill-rule="evenodd"
        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
        clip-rule="evenodd"
      />
    </svg>
  </span>
</template>

<script setup>
defineProps({
  field: String,
  currentField: String,
  direction: String
});
</script>