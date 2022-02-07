<template>
  <app-layout title="Dashboard">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Project
        </h2>
    </template>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            
                    <div class="mt-8 text-2xl">
                        Create Project
                    </div>

                    <form class="my-4" @submit.prevent="createProject" name="create-project">
                        <ul class="flex flex-col">
                            <div class="flex flex-col md:flex-row">
                                <li class="flex flex-col mb-4 mr-4">
                                    <label class="font-bold text-gray-600 mb-2" for="project-name">Project Name</label>
                                    <input class="border-gray-300 rounded" name="project-name" type="text" v-model="form.projectName"/>
                                    <p class="text-sm text-red-400" v-if="errors.projectName">{{ errors.projectName }}</p>
                                </li>
                                <li class="flex flex-col mb-4 mr-4">
                                    <label class="font-bold text-gray-600 mb-2" for="lookup-id">Lookup ID</label>
                                    <input class="border-gray-300 rounded" name="lookup-id" type="text" v-model="form.lookupId"/>
                                    <p class="text-sm text-red-400" v-if="errors.lookupId">{{ errors.lookupId }}</p>
                                    <p class="text-sm text-gray-400">Set the ID that customers will use to lookup their photo. (i.e. lfpdo-2015) The lookup ID may match the directory name if you choose.</p>
                                </li>
                                <li class="flex flex-col mb-4 mr-4">
                                    <label class="font-bold text-gray-600 mb-2" for="directory">Directory</label>
                                    <input class="border-gray-300 rounded" name="directory" type="text" v-model="form.directory"/>
                                    <p class="text-sm text-red-400" v-if="errors.directory">{{ errors.directory }}</p>
                                    <p class="text-sm text-gray-400">Set the directory (folder) name where the photos will reside. (i.e. 2105lfpdo) The directory name may match the lookup ID if you choose.</p>
                                </li>
                            </div>
                            <li class="flex flex-col md:flex-row md:justify-between mb-4 mr-4">
                                <button class="rounded-full bg-gray-200 px-8 py-4 text-gray-600 font-bold" type="submit">Create and Modify</button>
                                <Link :href="route('Projects')" class="mt-6 text-right flex justify-end text-sm font-semibold text-indigo-700">
                                    <div>Cancel</div>
                                    <div class="ml-1 text-indigo-500">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </Link>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
  </app-layout>
</template>

<script>
  import { defineComponent } from 'vue'
  import { Link } from '@inertiajs/inertia-vue3'
  import AppLayout from '@/Layouts/AppLayout.vue'

  export default defineComponent({
    
      components: {
          Link,
          AppLayout
      },
      props: {
          errors: Object
      },
      data () {
          return {
              form: this.$inertia.form({
                  projectName: '',
                  lookupId: '',
                  directory: ''
              })
          }
      },
      methods: {
          createProject: function () {
              this.form.post('/admin/projects/create')
          }
      }
  })
</script>
