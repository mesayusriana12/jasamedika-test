<script setup>
import { nextTick, onMounted, onUnmounted, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import Builder from '@/Components/DataTable/Builder.vue'
import Th from '@/Components/DataTable/Th.vue'
import Swal from 'sweetalert2'
import Modal from '@/Components/Modal.vue'
import Close from '@/Components/Button/Close.vue'
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'

const render = ref(true)

const form = useForm({
  id: null,
  name: null,
  district: null,
  city: null,
})

const table = ref(null)
const open = ref(false)

const show = () => open.value = true

const close = () => {
  open.value = false
  form.reset()
  render.value = false
  nextTick(() => render.value = true)
}

const store = () => {
  return form.post(route('subdistrict.store'), {
    onSuccess: () => close(),
    onError: () => show(),
  })
}

const edit = subdistrict => {
  form.id = subdistrict.id
  form.name = subdistrict.name
  form.district = subdistrict.district
  form.city = subdistrict.city

  show()
}

const update = () => {
  return form.patch(route('subdistrict.update', form.id), {
    onSuccess: () => close(),
    onError: () => show(),
  })
}

const destroy = async subdistrict => {
  const response = await Swal.fire({
    title: 'Apakah anda yakin?',
    text: 'Data kelurahan terpilih akan terhapus secara permanen!',
    icon: 'question',
    showCancelButton: true,
    showCloseButton: true,
  })

  if (response.isConfirmed) {
    Inertia.on('finish', () => close())

    return Inertia.delete(route('subdistrict.destroy', subdistrict.id))
  }
}

const submit = () => form.id ? update() : store()

const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))
</script>


<template>
  <DashboardLayout
    :title="'Kelurahan'"
  >
    <Card class="bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
      <template #header>
        <div class="flex items-center space-x-2 p-2 bg-gray-200 dark:bg-gray-800">
          <ButtonGreen
            v-if="can('create subdistrict')"
            @click.prevent="form.id = null; show()"
          >
            <Icon name="plus" />
            <p class="uppercase font-semibold">
              {{ __('tambah') }}
            </p>
          </ButtonGreen>
        </div>
      </template>

      <template #body>
        <div class="flex flex-col space-y-2">
          <Builder
            v-if="render"
            :url="route('subdistrict.paginate')"
            ref="table"
          >
            <template #thead="table">
              <tr class="bg-gray-200 dark:bg-gray-800 border-gray-300 dark:border-gray-900">
                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center"
                >
                  {{ __('no') }}
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="name"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  Kelurahan
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="district"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  Kecamatan
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="city"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  Kota
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  {{ __('#') }}
                </Th>
              </tr>
            </template>

            <template #tfoot="table">
              <tr class="bg-gray-200 dark:bg-gray-800 border-gray-300 dark:border-gray-900">
                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center"
                >
                  {{ __('no') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  Kelurahan
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="district"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  Kecamtan
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="city"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  Kota
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  {{ __('#') }}
                </Th>
              </tr>
            </template>

            <template #tbody="{ data, processing, empty }">
              <TransitionGroup
                enterActiveClass="transition-all duration-200"
                leaveActiveClass="transition-all duration-200"
                enterFromClass="opacity-0 -scale-y-100"
                leaveToClass="opacity-0 -scale-y-100"
              >
                <template v-if="empty">
                  <tr>
                    <td class="text-5xl text-center p-4" colspan="1000">
                      <p class="lowercase first-letter:capitalize font-semibold">
                        {{ __('Tidak ada data yang dapat ditampilkan') }}
                      </p>
                    </td>
                  </tr>
                </template>

                <template v-else>
                  <tr
                    v-for="(subdistrict, i) in data"
                    :key="subdistrict.id"
                    :class="processing && 'bg-gray-800'"
                    class="dark:hover:bg-gray-600 transition-all duration-300"
                  >
                    <td class="px-2 py-1 border dark:border-gray-800 text-center">
                      {{ i + 1 }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800 uppercase">
                      {{ __(subdistrict.name) }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800 uppercase">
                      {{ __(subdistrict.district) }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800 uppercase">
                      {{ __(subdistrict.city) }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800">
                      <div class="flex items-center space-x-2 justify-center">
                        <ButtonBlue
                          v-if="can('update subdistrict')"
                          @click.prevent="edit(subdistrict)"
                        >
                          <Icon name="edit" />
                          <p class="uppercase">
                            {{ __('edit') }}
                          </p>
                        </ButtonBlue>

                        <ButtonRed
                          v-if="can('delete subdistrict')"
                          @click.prevent="destroy(subdistrict)"
                        >
                          <Icon name="trash" />
                          <p class="uppercase">
                            {{ __('delete') }}
                          </p>
                        </ButtonRed>
                      </div>
                    </td>
                  </tr>
                </template>
              </TransitionGroup>
            </template>
          </Builder>
        </div>
      </template>
    </Card>

    <Modal :show="open">
      <form
        @submit.prevent="submit"
        class="w-full max-w-xl h-fit shadow-xl"
      >
        <Card class="bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
          <template #header>
            <div class="flex items-center justify-end bg-gray-200 dark:bg-gray-800 p-2">
              <Close @click.prevent="close" />
            </div>
          </template>

          <template #body>
            <div class="flex flex-col space-y-4 p-4">
              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 lowercase first-letter:capitalize">
                    {{ 'Nama Kelurahan' }}
                  </label>

                  <Input
                    v-model="form.name"
                    :placeholder="'Nama Kelurahan'"
                    type="text"
                    name="name"
                    required
                    autofocus
                  />
                </div>

                <InputError
                  :error="form.errors.name"
                />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 lowercase first-letter:capitalize">
                    {{ 'Nama kecamatan' }}
                  </label>

                  <Input
                    v-model="form.district"
                    :placeholder="'Nama kecamatan'"
                    type="text"
                    name="name"
                    required
                    autofocus
                  />
                </div>

                <InputError
                  :error="form.errors.district"
                />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 lowercase first-letter:capitalize">
                    {{ 'Nama kota' }}
                  </label>

                  <Input
                    v-model="form.city"
                    :placeholder="'Nama kota'"
                    type="text"
                    name="name"
                    required
                  />
                </div>

                <InputError
                  :error="form.errors.city"
                />
              </div>
            </div>
          </template>

          <template #footer>
            <div class="flex items-center justify-end space-x-2 bg-gray-200 dark:bg-gray-800 px-2 py-1">
              <ButtonGreen type="submit">
                <Icon name="check" />
                <p class="uppercase font-semibold">
                  {{ __(form.id ? 'update' : 'create') }}
                </p>
              </ButtonGreen>
            </div>
          </template>
        </Card>
      </form>
    </Modal>
  </DashboardLayout>
</template>