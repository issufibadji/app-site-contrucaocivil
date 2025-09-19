<script setup>
import { ref } from 'vue'
import LoginForm from './Auth/LoginForm.vue'
import RegisterForm from '../Pages/Auth/RegisterForm.vue'
const isLogin = ref(true)
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <div class="min-h-screen flex">
      <!-- Left Side - Auth Forms -->
      <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
          <!-- Form Container -->
          <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Logo -->
            <div class="text-center mb-8">
              <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                <i :class="isLogin ? 'fas fa-sign-in-alt text-blue-600 fa-lg' : 'fas fa-user-plus text-blue-600 fa-lg'"></i>
              </div>
              <h2 class="text-2xl font-bold text-gray-800">
                {{ isLogin ? 'Welcome Back!' : 'Create Account' }}
              </h2>
              <p class="text-gray-600 mt-2">
                {{ isLogin ? 'Please sign in to continue' : 'Get started with your account' }}
              </p>
            </div>

            <!-- Dynamic Component -->
            <component :is="isLogin ? LoginForm : RegisterForm" />

            <!-- Switch -->
            <p class="mt-6 text-center text-gray-600">
              {{ isLogin ? "Don't have an account?" : 'Already have an account?' }}
              <button
                type="button"
                class="ml-1 text-blue-600 hover:text-blue-700 font-semibold"
                @click="isLogin = !isLogin"
              >
                {{ isLogin ? 'Sign up' : 'Sign in' }}
              </button>
            </p>
          </div>
        </div>
      </div>

      <!-- Right Side - Image -->
      <div
        class="hidden lg:block lg:w-1/2 bg-cover bg-center"
        style="background-image: url('/images/lgama.jpg');"
      >
        <div class="h-full bg-black bg-opacity-50 flex items-center justify-center">
          <div class="text-center text-white px-12">
            <h2 class="text-4xl font-bold mb-6">Your Title</h2>
            <p class="text-xl">Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis, expedita.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
