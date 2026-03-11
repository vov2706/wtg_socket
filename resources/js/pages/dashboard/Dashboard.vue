<script setup lang="ts">
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue';
import { onMounted, ref } from 'vue';
import { sendMessage } from '@/api/messages';
import { getUsers } from '@/api/users';
import Pagination from '@/components/pagination/Pagination.vue';
import type { PaginationMeta } from '@/interfaces/pagination';
import type { User } from '@/interfaces/users';
import AppLayout from '@/layouts/AppLayout.vue';

const loading = ref(false);
const users = ref<User[]>([]);
const paginationData = ref<PaginationMeta>({
  current_page: 1,
  last_page: 1,
  total: 0,
  per_page: 15,
  from: 0,
  to: 0,
});
const isNotifyModalOpen = ref(false);
const selectedUser = ref<User | null>(null);
const notifyMessage = ref('');
const notifyLoading = ref(false);

const openNotifyModal = (user: User) => {
  selectedUser.value = user;
  notifyMessage.value = '';
  isNotifyModalOpen.value = true;
};

const closeNotifyModal = () => {
  isNotifyModalOpen.value = false;
  selectedUser.value = null;
  notifyMessage.value = '';
};

const sendNotification = async () => {
  if (!selectedUser.value || !notifyMessage.value.trim()) return;

  try {
    notifyLoading.value = true;

    await sendMessage({
      receiver_id: selectedUser.value.id,
      text: notifyMessage.value,
    });

    closeNotifyModal();
  } catch (error) {
    console.error(error);
  } finally {
    notifyLoading.value = false;
  }
};

const handlePageChange = (page: number) => {
  loadUsers(page);
};

const loadUsers = (page: number = 1) => {
  loading.value = true;

  getUsers(page)
    .then((res) => {
      users.value = res.data;
      paginationData.value = res.meta;
    })
    .finally(() => (loading.value = false));
};

onMounted(() => {
  loadUsers();
});
</script>

<template>
  <AppLayout>
    <div>
      <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div
            class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"
          >
            <div
              class="overflow-hidden shadow-sm outline-1 outline-black/5 sm:rounded-lg dark:shadow-none dark:-outline-offset-1 dark:outline-white/10"
            >
              <table
                class="relative min-w-full divide-y divide-gray-300 dark:divide-white/15"
              >
                <thead class="bg-gray-50 dark:bg-gray-800/75">
                  <tr>
                    <th
                      scope="col"
                      class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-6 dark:text-gray-200"
                    >
                      Name
                    </th>
                    <th
                      scope="col"
                      class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200"
                    >
                      Email
                    </th>
                    <th
                      scope="col"
                      class="py-3.5 pr-4 pl-3 text-sm font-semibold text-gray-900 sm:pr-6 dark:text-gray-200"
                    >
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody
                  class="divide-y divide-gray-200 bg-white dark:divide-white/10 dark:bg-gray-800/50"
                >
                  <tr v-for="user in users" :key="user.id">
                    <td
                      class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-6 dark:text-white"
                    >
                      {{ user.name }}
                    </td>
                    <td
                      class="px-3 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400"
                    >
                      {{ user.email }}
                    </td>
                    <td
                      class="py-4 pr-4 pl-3 text-right text-sm font-medium whitespace-nowrap sm:pr-6"
                    >
                      <button
                        @click="openNotifyModal(user)"
                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                      >
                        Notify
                      </button>
                    </td>
                  </tr>
                  <tr v-if="users.length <= 0">
                    <td
                      colspan="3"
                      class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-6 dark:text-white"
                    >
                      No records found
                    </td>
                  </tr>
                </tbody>
              </table>
              <Pagination
                :current-page="paginationData.current_page"
                :last-page="paginationData.last_page"
                :total="paginationData.total"
                :per-page="paginationData.per_page"
                :from="paginationData.from"
                :to="paginationData.to"
                @change="handlePageChange"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
  <TransitionRoot as="template" :show="isNotifyModalOpen">
    <Dialog as="div" class="relative z-50" @close="closeNotifyModal">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/50 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <DialogPanel
              class="w-full max-w-lg transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all dark:bg-gray-900"
            >
              <DialogTitle
                as="h3"
                class="text-lg font-semibold text-gray-900 dark:text-white"
              >
                Send notification
              </DialogTitle>

              <div class="mt-2">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Recipient:
                  <span class="font-medium text-gray-900 dark:text-white">
                    {{ selectedUser?.name }}
                  </span>
                  <span class="ml-1"> ({{ selectedUser?.email }}) </span>
                </p>
              </div>

              <div class="mt-4">
                <label
                  for="notify-message"
                  class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-200"
                >
                  Message
                </label>

                <textarea
                  id="notify-message"
                  v-model="notifyMessage"
                  rows="6"
                  placeholder="Write your message..."
                  class="block w-full rounded-lg border-0 bg-white px-3 py-2 text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                />
              </div>

              <div class="mt-6 flex items-center justify-end gap-3">
                <button
                  type="button"
                  class="rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 outline-1 -outline-offset-1 outline-gray-300 hover:bg-gray-50 dark:bg-white/5 dark:text-gray-200 dark:outline-white/10 dark:hover:bg-white/10"
                  @click="closeNotifyModal"
                >
                  Cancel
                </button>

                <button
                  type="button"
                  :disabled="notifyLoading || !notifyMessage.trim()"
                  class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-50"
                  @click="sendNotification"
                >
                  {{ notifyLoading ? 'Sending...' : 'Send' }}
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>
