<script setup lang="ts">
import { ExclamationCircleIcon } from '@heroicons/vue/16/solid';
import clsx from 'clsx';
import { ref } from 'vue';
import type { RegisterPayload } from '@/interfaces/auth';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { useAuthStore } from '@/store/auth';
import { clearErrors, hasError } from '@/utils/formHelpers';

interface Errors {
  name: string[];
  email: string[];
  password: string[];
}

const errors = ref<Errors>({
  name: [],
  email: [],
  password: [],
});
const formData = ref<RegisterPayload>({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});
const loading = ref(false);

const onSubmit = () => {
  if (!formData.value.name) {
    errors.value.name.push('Name is required');
  }

  if (!formData.value.email) {
    errors.value.email.push('Email is required');
  }

  if (!formData.value.password) {
    errors.value.password.push('Password is required');
  }

  if (
    !formData.value.name ||
    !formData.value.email ||
    !formData.value.password
  ) {
    return;
  }

  const auth = useAuthStore();
  loading.value = true;
  clearErrors(errors.value);

  auth
    .register(formData.value)
    .catch((err) => {
      const data = err.response?.data?.errors ?? {};

      for (const field in data) {
        const key = field as keyof typeof errors.value;

        data[key].map((e: string) => {
          errors.value[key].push(e);
        });
      }
    })
    .finally(() => {
      loading.value = false;
    });
};
</script>

<template>
  <AuthLayout>
    <div
      class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8"
    >
      <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2
          class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900 dark:text-white"
        >
          Sign up your account
        </h2>
      </div>

      <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="space-y-6" method="POST" @submit.prevent="onSubmit">
          <div>
            <label
              for="name"
              class="block text-sm/6 font-medium text-gray-900 dark:text-gray-100"
            >
              Name
            </label>

            <div class="mt-2 grid grid-cols-1">
              <input
                id="name"
                v-model="formData.name"
                type="text"
                name="name"
                autocomplete="name"
                placeholder="John Doe"
                :aria-invalid="hasError(errors, 'name')"
                aria-describedby="name-error"
                required
                :class="
                  clsx(
                    'col-start-1 row-start-1 block w-full rounded-md border border-gray-300 bg-white py-1.5 pr-10 pl-3 text-gray-900 placeholder:text-gray-400 focus:border-indigo-600 focus:ring-0 focus:outline-none sm:pr-9 sm:text-sm/6 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-gray-500',
                    {
                      'border-red-500! text-red-400! placeholder:text-red-300! focus:border-red-500! dark:border-red-500/70! dark:text-red-400!':
                        hasError(errors, 'name'),
                    },
                  )
                "
              />

              <ExclamationCircleIcon
                v-if="hasError(errors, 'name')"
                class="pointer-events-none col-start-1 row-start-1 mr-3 size-5 self-center justify-self-end text-red-500 sm:size-4 dark:text-red-400"
                aria-hidden="true"
              />
            </div>

            <p
              v-for="err in errors.name"
              :key="err"
              id="name-error"
              class="mt-2 text-sm text-red-600 dark:text-red-400"
            >
              {{ err }}
            </p>
          </div>

          <div>
            <label
              for="email"
              class="block text-sm/6 font-medium text-gray-900 dark:text-gray-100"
            >
              Email address
            </label>

            <div class="mt-2 grid grid-cols-1">
              <input
                id="email"
                v-model="formData.email"
                type="email"
                name="email"
                autocomplete="email"
                placeholder="you@example.com"
                :aria-invalid="hasError(errors, 'email')"
                aria-describedby="email-error"
                :class="
                  clsx(
                    'col-start-1 row-start-1 block w-full rounded-md border border-gray-300 bg-white py-1.5 pr-10 pl-3 text-gray-900 placeholder:text-gray-400 focus:border-indigo-600 focus:ring-0 focus:outline-none sm:pr-9 sm:text-sm/6 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-gray-500',
                    {
                      'border-red-500! text-red-400! placeholder:text-red-300! focus:border-red-500! dark:border-red-500/70! dark:text-red-400!':
                        hasError(errors, 'email'),
                    },
                  )
                "
                required
              />

              <ExclamationCircleIcon
                v-if="hasError(errors, 'email')"
                class="pointer-events-none col-start-1 row-start-1 mr-3 size-5 self-center justify-self-end text-red-500 sm:size-4 dark:text-red-400"
                aria-hidden="true"
              />
            </div>

            <p
              v-if="hasError(errors, 'email')"
              id="email-error"
              class="mt-2 text-sm text-red-600 dark:text-red-400"
            >
              {{ errors.email }}
            </p>
          </div>

          <div>
            <label
              for="password"
              class="block text-sm/6 font-medium text-gray-900 dark:text-gray-100"
            >
              Password
            </label>

            <div class="mt-2 grid grid-cols-1">
              <input
                id="password"
                v-model="formData.password"
                type="password"
                name="password"
                autocomplete="current-password"
                :aria-invalid="hasError(errors, 'password')"
                aria-describedby="password-error"
                :class="
                  clsx(
                    'col-start-1 row-start-1 block w-full rounded-md border border-gray-300 bg-white py-1.5 pr-10 pl-3 text-gray-900 placeholder:text-gray-400 focus:border-indigo-600 focus:ring-0 focus:outline-none sm:pr-9 sm:text-sm/6 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-gray-500',
                    {
                      'border-red-500! text-red-400! placeholder:text-red-300! focus:border-red-500! dark:border-red-500/70! dark:text-red-400!':
                        hasError(errors, 'password'),
                    },
                  )
                "
                required
              />

              <ExclamationCircleIcon
                v-if="hasError(errors, 'password')"
                class="pointer-events-none col-start-1 row-start-1 mr-3 size-5 self-center justify-self-end text-red-500 sm:size-4 dark:text-red-400"
                aria-hidden="true"
              />
            </div>

            <p
              v-if="hasError(errors, 'password')"
              id="password-error"
              class="mt-2 text-sm text-red-600 dark:text-red-400"
            >
              {{ errors.password }}
            </p>
          </div>

          <div>
            <label
              for="password_confirmation"
              class="block text-sm/6 font-medium text-gray-900 dark:text-gray-100"
            >
              Confirm password
            </label>

            <div class="mt-2 grid grid-cols-1">
              <input
                id="password_confirmation"
                v-model="formData.password_confirmation"
                type="password"
                name="password_confirmation"
                autocomplete="current-password"
                class="'col-start-1 dark:placeholder:text-gray-500', row-start-1 block w-full rounded-md border border-gray-300 bg-white py-1.5 pr-10 pl-3 text-gray-900 placeholder:text-gray-400 focus:border-indigo-600 focus:ring-0 focus:outline-none sm:pr-9 sm:text-sm/6 dark:border-white/10 dark:bg-white/5 dark:text-white"
              />
            </div>
          </div>

          <div>
            <button
              type="submit"
              :disabled="loading"
              class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 disabled:opacity-60 dark:bg-indigo-500 dark:shadow-none dark:hover:bg-indigo-400"
            >
              {{ loading ? 'Loading...' : 'Sign up' }}
            </button>
          </div>
        </form>
        <p class="mt-5 text-center text-sm/6 text-gray-500 dark:text-gray-400">
          Already have an account?
          {{ ' ' }}
          <RouterLink
            to="/login"
            class="font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
          >
            Sign in now!
          </RouterLink>
        </p>
      </div>
    </div>
  </AuthLayout>
</template>
