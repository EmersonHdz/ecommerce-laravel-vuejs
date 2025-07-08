<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-3xl font-semibold">Customers</h1>
      <router-link :to="{name: 'app.customers.create'}"
                 class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Add new Customer 
    </router-link>
  </div>
  <CustomersTable/>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import CustomersTable from "./CustomersTable.vue";

const DEFAULT_CUSTOMER = {
}

const customers = computed(() => store.state.customers);




</script>

<style scoped>

</style>