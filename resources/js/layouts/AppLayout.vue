<script setup lang="ts">
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline';
import { onMounted, ref } from 'vue';
import { useToast } from 'vue-toastification';
import type { AuthUser } from '@/interfaces/auth';
import { useAuthStore } from '@/store/auth';
import { useRouter } from 'vue-router';

const user = ref<AuthUser | null>(null);

const router = useRouter();

const navigation = [
  {
    name: 'Users',
    href: '/',
    current: router.currentRoute.value.name === 'home',
  },
  {
    name: 'Messages',
    href: '/messages',
    current: router.currentRoute.value.name === 'messages',
  },
];

const auth = useAuthStore();

const handleLogout = async () => {
  await auth.logout();
};

const toast = useToast();

onMounted(async () => {
  await auth.bootstrap();
  user.value = auth.user!;

  window.Echo.private(`users.${user.value.id}`).listen('.send.message', (e) => {
    toast.success(`User ${e.sender} sent you new message!`, {
      timeout: false,
    });
  });
});
</script>

<template>
  <div class="min-h-full">
    <Disclosure
      as="nav"
      class="bg-gray-800 dark:bg-gray-800/50"
      v-slot="{ open }"
    >
      <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <div class="flex items-center">
            <div class="shrink-0">
              <h2 class="text-2xl font-bold text-white">Socket</h2>
            </div>
            <div class="hidden md:block">
              <div class="ml-10 flex items-baseline space-x-4">
                <RouterLink
                  v-for="item in navigation"
                  :key="item.name"
                  :to="item.href"
                  :class="[
                    item.current
                      ? 'bg-gray-900 text-white dark:bg-gray-950/50'
                      : 'text-gray-300 hover:bg-white/5 hover:text-white',
                    'rounded-md px-3 py-2 text-sm font-medium',
                  ]"
                  :aria-current="item.current ? 'page' : undefined"
                >
                  {{ item.name }}
                </RouterLink>
              </div>
            </div>
          </div>
          <div class="hidden md:block">
            <div class="ml-4 flex items-center gap-4 md:ml-6">
              <div class="text-right">
                <div class="text-sm font-medium text-white">
                  {{ user?.name }}
                </div>
                <div class="text-xs text-gray-400">
                  {{ user?.email }}
                </div>
              </div>

              <button
                type="button"
                class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white"
                @click="handleLogout"
              >
                Log out
              </button>
            </div>
          </div>
          <div class="-mr-2 flex md:hidden">
            <DisclosureButton
              class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-white/5 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500"
            >
              <span class="absolute -inset-0.5"></span>
              <span class="sr-only">Open main menu</span>
              <Bars3Icon v-if="!open" class="block size-6" aria-hidden="true" />
              <XMarkIcon v-else class="block size-6" aria-hidden="true" />
            </DisclosureButton>
          </div>
        </div>
      </div>

      <DisclosurePanel class="md:hidden">
        <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
          <DisclosureButton
            v-for="item in navigation"
            :key="item.name"
            as="a"
            :href="item.href"
            :class="[
              item.current
                ? 'bg-gray-900 text-white'
                : 'text-gray-300 hover:bg-white/5 hover:text-white',
              'block rounded-md px-3 py-2 text-base font-medium',
            ]"
            :aria-current="item.current ? 'page' : undefined"
            >{{ item.name }}</DisclosureButton
          >
        </div>
        <div class="border-t border-white/10 pt-4 pb-3">
          <div class="flex items-center px-5">
            <div>
              <div class="text-base font-medium text-white">
                {{ user?.name }}
              </div>
              <div class="text-sm font-medium text-gray-400">
                {{ user?.email }}
              </div>
            </div>
          </div>
          <div class="mt-3 space-y-1 px-2">
            <DisclosureButton
              @click="handleLogout"
              class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-white/5 hover:text-white"
            >
              Log out
            </DisclosureButton>
          </div>
        </div>
      </DisclosurePanel>
    </Disclosure>

    <main>
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <slot />
      </div>
    </main>
  </div>
</template>
