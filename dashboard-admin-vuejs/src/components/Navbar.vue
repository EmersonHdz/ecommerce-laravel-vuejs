<template>
    <header class="flex justify-between items-center p-3 shadow-xl  ">

       <!--burguer icon menu--> 
      <button @click="emit('toggle-sidebar')" class="flex items-center justify-center rounded transition-colors w-10 h-10 text-gray-700 hover:bg-black/20">
        <MenuIcon class="w-8"/>
      </button>

      <!--Menu perfile user-->
      <Menu as="div" class="relative inline-block text-left mr-10  shadow-lg p-3 ">

        <MenuButton class="flex items-center justify-center">
          <span class="text-sm font-semibold tracking-wide mr-5">Welcome</span>
          <img src="https://randomuser.me/api/portraits/men/1.jpg" class="rounded-full w-8 mr-2">
          <small class="text-sm font-semibold tracking-wide">{{currentUser.name}}</small>
          <ChevronDownIcon class="h-5 w-5 ml-4 text-violet-200 bg-indigo-600 hover:text-violet-100" aria-hidden="true"/>
        </MenuButton>
  
        <MenuItems class="absolute w-[14rem] rounded-md bg-indigo-900">
          <div class="px-2 py-2 ">

          <div>
            <MenuItem v-slot="{ active }">
            <button :class="[ active ? 'bg-indigo-600 text-white' : 'text-white',
            'group flex w-full items-center rounded-md px-2 py-2 text-sm',]">
            <UserIcon :active="active" class="mr-2 h-5 w-5 text-white" aria-hidden="true"/>
               Profile
            </button>
            </MenuItem>
          </div>

          <div>
            <MenuItem v-slot="{ active }">
            <button @click="logout"
            :class="[ active ? 'bg-red-500 text-white' : 'text-white',
            'group flex w-full items-center rounded-md px-2 py-2 text-sm',]">
            <LogoutIcon class="mr-2 h-5 w-5 text-white" aria-hidden="true"/>
               Logout
            </button>
            </MenuItem>
          </div>

          </div>
        </MenuItems>

      </Menu>
    </header>
  </template>
  
  <script setup>
  import {MenuIcon, LogoutIcon, UserIcon} from '@heroicons/vue/outline'
  import {Menu, MenuButton, MenuItems, MenuItem} from '@headlessui/vue'
  import {ChevronDownIcon} from '@heroicons/vue/solid'
  import store from "../store";
  import {useRouter} from 'vue-router' 
  import {computed} from "vue";
  
  const router = useRouter();
  
  const emit = defineEmits(['toggle-sidebar'])
  
  const currentUser = computed(() => store.state.user.data);

  
  
  function logout() {
    store.dispatch('logout')
      .then(() => {
        router.push({name: 'login'})
      })
  }
  
  </script>
  
  <style scoped>
  
  </style>