<script setup>
import store from "../../store/index.js";
import { onMounted, ref, } from 'vue'
import Spinner from "../../components/core/Spinner.vue";
import { useRoute, useRouter } from "vue-router";
// import the styles
import 'vue3-treeselect/dist/vue3-treeselect.css'
import axiosClient from "../../lib/axios";
import CustomInput from "../../components/core/CustomInput.vue";

const route = useRoute()
const router = useRouter()

const user = ref({
  id: null,
  name: null,
  last_name: null,
  email: null,
  phone: null,
  avatar: null
})

const errors = ref({});
const loading = ref(false)

onMounted(() => {
  if (route.params.id) {
    loading.value = true
    store.dispatch('getUser', route.params.id)
      .then((response) => {
        loading.value = false;
        user.value = response.data
      })
  }
})

function onSubmit($event, close = false){
     loading.value = true
     errors.value = {};

     if(user.value.id) {
       store.dispatch('updateUser', user.value)
         .then(response => {
            loading.value = false;
            if(response.status === 200) {
               user.value = response.data
               store.commit('showToast', 'User was successfully updated');
               store.dispatch('getUsers')
               if(close) {
                 router.push({name: 'app.users'})
               }
            }
         })

         .catch(err=> {
          loading.value = false;
          errors.value = err.response.data.errors
         })
         
     } else {
      store.dispatch('createUser', user.value)
        .then(response => {
          loading.value = false;
          if(response.status === 201 ) {
            user.value = response.data
            store.commit('showToast', 'User was successfully created');
            store.dispatch('getUsers')
            if(close) {
              router.push({name: 'app.users'})
          }
        } else {
          user.value = response.data
          router.push({name: 'app.users.edit', params: {id: response.data.id}})
        }

        })
         .catch(err=> {
          loading.value = false;
          errors.value = err.response.data.errors
         })
     }
}
</script>

<template>
   <div class="flex items-center justify-between mb-3">
    <!--dinamic h1 -->
    <h1 v-if="!loading" class="text-3xl font-semibold">
      {{ user.id ? `Update User: "${user.name}"` : 'Create new User' }}
    </h1>
  </div>

  <div class="bg-white rounded-lg shadow animate-fade-in-down">
     
    <Spinner v-if="loading"
      class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center z-50"
    />

    <form v-if="!loading" @submit.prevent="onSubmit" >

      <div class="grid grid-cols-4">

        <div class="col-span-2 px-4 pt-5 pb-4">
          <CustomInput class="mb-2"
           v-model="user.name" 
           label="User Name"
           :errors="errors['name']"
          />
      

          <CustomInput type="email" class="mb-2"
           v-model="user.email" 
           label="Email"
           :errors="errors['email']"/>

           <CustomInput type="file" class="mb-2"
           v-model="user.avatar"/>

        </div>

        <div class="col-span-2 px-4 pt-5 pb-4">
          <CustomInput class="mb-2"
           v-model="user.last_name" 
           label="User Last name"
           :errors="errors['last_name']"
          />

          
          <CustomInput type="number" class="mb-2"
           v-model="user.phone" 
           label="Phone Number"
           :errors="errors['phone']"/>

            
          <CustomInput type="password" class="mb-2"
           v-model="user.password" 
           label="Password"
           :errors="errors['password']"/>
           
        </div>
      </div>
      <footer class="bg-gray-50 rounded-b-lg px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button type="submit"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                          text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500">
          Save
        </button>
        <button type="button"
                @click="onSubmit($event, true)"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                          text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500">
          Save & Close
        </button>
        <router-link :to="{name: 'app.users'}"
                     class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                     ref="cancelButtonRef">
          Cancel
        </router-link>
      </footer>

    </form>

  </div>
    
</template>