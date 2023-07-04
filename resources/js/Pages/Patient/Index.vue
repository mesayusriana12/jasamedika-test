<script setup>
import { getCurrentInstance, nextTick, onMounted, onUnmounted, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import Builder from '@/Components/DataTable/Builder.vue'
import Th from '@/Components/DataTable/Th.vue'
import Swal from 'sweetalert2'
import Select from '@vueform/multiselect'
import Modal from '@/Components/Modal.vue'
import Close from '@/Components/Button/Close.vue'
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'

const { subdistricts } = defineProps({
  subdistricts : Array
})

const render = ref(true)

const form = useForm({
  id: null,
  name: null,
  phone: null,
  gender: null,
  birth_date: null,
  address: null,
  rt: null,
  rw: null,
  subdistrict_id: null,
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
  return form.post(route('patient.store'), {
    onSuccess: () => close(),
    onError: () => show(),
  })
}

const edit = patient => {
  form.id = patient.id
  form.name = patient.name
  form.phone = patient.phone
  form.gender = patient.gender
  form.birth_date = patient.birth_date
  form.address = patient.address
  form.rt = patient.rt
  form.rw = patient.rw
  form.subdistrict_id = patient.subdistrict_id

  show()
}

const update = () => {
  return form.patch(route('patient.update', form.id), {
    onSuccess: () => close(),
    onError: () => show(),
  })
}

const destroy = async patient => {
  const response = await Swal.fire({
    title: 'Apakah anda yakin?',
    text: 'Data registrasi pasien terpilih akan terhapus secara permanen!',
    icon: 'question',
    showCancelButton: true,
    showCloseButton: true,
  })

  if (response.isConfirmed) {
    Inertia.on('finish', () => close())

    return Inertia.delete(route('patient.destroy', patient.id))
  }
}

const submit = () => form.id ? update() : store()

const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
  <DashboardLayout
    :title="'Registrasi Pasien'"
  >
    <Card class="bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
      <template #header>
        <div class="flex items-center space-x-2 p-2 bg-gray-200 dark:bg-gray-800">
          <ButtonGreen
            v-if="can('create patient')"
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
            :url="route('patient.paginate')"
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
                  Nama
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="phone"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  No Telepon
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="gender"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  Jenis Kelamin
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="birth_date"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  Tanggal Lahir
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="address"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  Alamat
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="rt"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  RT
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="rw"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  RW
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="subdistrict_id"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  Kelurahan
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
                  name="name"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  Nama
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  name="phone"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  No Telepon
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  name="gender"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  Jenis Kelamin
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  name="birth_date"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  Tanggal Lahir
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  name="address"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  Alamat
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  name="rt"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  RT
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  name="rw"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  RW
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  name="subdistrict_id"
                  class="border px-3 py-2 text-center whitespace-nowrap"
                >
                  Kelurahan
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
                    v-for="(patient, i) in data"
                    :key="patient.id"
                    :class="processing && 'bg-gray-800'"
                    class="dark:hover:bg-gray-600 transition-all duration-300"
                  >
                    <td class="px-2 py-1 border dark:border-gray-800 text-center">
                      {{ i + 1 }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800 capitalize">
                      {{ patient.name }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800">
                      {{ patient.phone }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800">
                      {{ patient.gender === 'P' ? 'Perempuan' : 'Laki-laki' }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800">
                      {{ patient.birth_date ? new Date(patient.birth_date).toLocaleDateString('en-GB') : '-' }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800">
                      {{ patient.address }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800">
                      {{ patient.rt }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800">
                      {{ patient.rw }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800 capitalize">
                      {{ patient.subdistrict?.name }}
                    </td>

                    <td class="px-2 py-1 border dark:border-gray-800">
                      <div class="flex items-center space-x-2 justify-center">
                        <ButtonBlue
                          v-if="can('update patient')"
                          @click.prevent="edit(patient)"
                        >
                          <Icon name="edit" />
                          <p class="uppercase">
                            {{ __('edit') }}
                          </p>
                        </ButtonBlue>

                        <ButtonRed
                          v-if="can('delete patient')"
                          @click.prevent="destroy(patient)"
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
                    {{ 'Nama Pasien' }}
                  </label>

                  <Input
                    v-model="form.name"
                    :placeholder="'Nama Pasien'"
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
                    {{ 'Nomor Telepon' }}
                  </label>

                  <Input
                    v-model="form.phone"
                    :placeholder="'Nomor Telepon'"
                    type="tel"
                    name="name"
                    required
                  />
                </div>

                <InputError
                  :error="form.errors.district"
                />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 lowercase first-letter:capitalize">
                    {{ 'Jenis Kelamin' }}
                  </label>

                  <Select
                    v-model="form.gender"
                    :options="[{
                      label: 'Laki-laki',
                      value: 'L'
                    }, {
                      label: 'Perempuan',
                      value: 'P'
                    }]"
                    :closeOnSelect="true"
                    :searchable="true"
                    :placeholder="'Jenis Kelamin'"
                  />
                </div>

                <InputError
                  :error="form.errors.gender"
                />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 lowercase first-letter:capitalize">
                    {{ 'Tanggal Lahir' }}
                  </label>

                  <Input
                    v-model="form.birth_date"
                    :placeholder="'Tanggal Lahir'"
                    type="date"
                    name="name"
                    required
                  />
                </div>

                <InputError
                  :error="form.errors.birth_date"
                />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 lowercase first-letter:capitalize">
                    {{ 'Alamat' }}
                  </label>

                  <Input
                    v-model="form.address"
                    :placeholder="'alamat'"
                    type="text"
                    name="name"
                    required
                  />
                </div>

                <InputError
                  :error="form.errors.address"
                />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 lowercase first-letter:capitalize">
                    {{ 'RT' }}
                  </label>

                  <Input
                    v-model="form.rt"
                    :placeholder="'RT'"
                    type="number"
                    name="name"
                    required
                  />
                </div>

                <InputError
                  :error="form.errors.rt"
                />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 lowercase first-letter:capitalize">
                    {{ 'RW' }}
                  </label>

                  <Input
                    v-model="form.rw"
                    :placeholder="'RW'"
                    type="number"
                    name="name"
                    required
                  />
                </div>

                <InputError
                  :error="form.errors.rw"
                />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 lowercase first-letter:capitalize">
                    {{ 'Kelurahan' }}
                  </label>

                  <Select
                    v-model="form.subdistrict_id"
                    :options="subdistricts.map(p => ({
                      label: `${p.name} - ${p.district} - ${p.city}`,
                      value: p.id,
                    }))"
                    :closeOnSelect="true"
                    :searchable="true"
                    :placeholder="'Kelurahan'"
                    class="capitalize"
                  />
                </div>

                <InputError
                  :error="form.errors.subdistrict_id"
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