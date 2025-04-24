<template>
  <div>
    <!-- Label for the input field (hidden for accessibility) -->
    <label class="sr-only">{{ label }}</label>
    <div class="mt-1 flex rounded-md" :class="type === 'checkbox' ? 'items-center' : 'shadow-sm'">
      <!-- Prepend slot for icons or text before the input -->
      <span v-if="prepend" class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
        {{ prepend }}
      </span>

      <!-- Select dropdown field -->
      <template v-if="type === 'select'">
        <select :name="name" :required="required" :value="props.modelValue" :class="inputClasses" @change="onChange($event.target.value)">
          <option v-for="option of selectOptions" :value="option.key">{{ option.text }}</option>
        </select>
      </template>

      <!-- Textarea field -->
      <template v-else-if="type === 'textarea'">
        <textarea :name="name" :required="required" :value="props.modelValue" @input="emit('update:modelValue', $event.target.value)" :class="inputClasses" :placeholder="label"></textarea>
      </template>

      <!-- Rich text editor using CKEditor -->
      <template v-else-if="type === 'richtext'">
        <ckeditor :editor="editor" :required="required" :model-value="props.modelValue" @input="onChange" :class="inputClasses" :config="editorConfig"></ckeditor>
      </template>

      <!-- File input field -->
      <template v-else-if="type === 'file'">
        <input type="file" :name="name" :required="required" @change="handleFileChange" :class="inputClasses" :placeholder="label" />
      </template>

      <!-- Checkbox input field -->
      <template v-else-if="type === 'checkbox'">
        <input :id="id" :name="name" :type="type" :checked="props.modelValue" :required="required" @change="emit('update:modelValue', $event.target.checked)" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
        <label :for="id" class="ml-2 block text-sm text-gray-900"> {{ label }} </label>
      </template>

      <!-- Default input field for text, number, email, etc. -->
      <template v-else>
        <input :type="type" :name="name" :required="required" :value="props.modelValue" @input="emit('update:modelValue', $event.target.value)" :class="inputClasses" :placeholder="label" step="0.01"/>
      </template>

      <!-- Append slot for icons or text after the input -->
      <span v-if="append" class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
        {{ append }}
      </span>
    </div>
    
    <!-- Display validation errors if any -->
    <small v-if="errors && errors[0]" class="text-red-600">{{ errors[0] }}</small>
  </div>
</template>

<script setup>
import { computed, ref } from "vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

const editor = ClassicEditor;

// Define component props
const props = defineProps({
  modelValue: [String, Number, File, Boolean], // The input value
  label: String, // Label text
  type: { type: String, default: 'text' }, // Input type
  name: String, // Input name attribute
  required: Boolean, // If input is required
  id: String, // Unique input ID
  prepend: { type: String, default: '' }, // Prepend slot
  append: { type: String, default: '' }, // Append slot
  selectOptions: Array, // Options for select dropdown
  errors: { type: Array, required: false }, // Validation errors
  editorConfig: { type: Object, default: () => ({}) } // CKEditor configuration
});

// Compute a unique ID if none is provided
const id = computed(() => {
  if (props.id) return props.id;
  return `id-${Math.floor(1000000 + Math.random() * 1000000)}`;
});

// Compute CSS classes for the input field
const inputClasses = computed(() => {
  const cls = [
    `block w-full px-3 py-2 border border-gray-600 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm`,
  ];

  if (props.append && !props.prepend) {
    cls.push(`rounded-l-md`)
  } else if (props.prepend && !props.append) {
    cls.push(`rounded-r-md`)
  } else if (!props.prepend && !props.append) {
    cls.push('rounded-md')
  }
  if (props.errors && props.errors[0]) {
    cls.push('border-red-600 focus:border-red-600')
  }
  return cls.join(' ');
});

// Define event emitters
const emit = defineEmits(['update:modelValue', 'change']);

// Handle input changes and emit events
function onChange(value) {
  emit('update:modelValue', value);
  emit('change', value);
}
</script>
  
  <style scoped>
  :deep() .ck-editor {
    width: 100%;
  }
  
  :deep() .ck-content {
    min-height: 200px;
  }
  </style>