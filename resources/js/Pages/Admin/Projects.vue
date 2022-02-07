<template>
  <app-layout title="Dashboard">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Projects
        </h2>
    </template>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <projects-list :projects="projects" />
            </div>
        </div>
    </div>
  </app-layout>
</template>

<script>
  import { defineComponent } from 'vue'
  import { Link } from '@inertiajs/inertia-vue3'
  import AppLayout from '@/Layouts/AppLayout.vue'
  import ProjectsList from '@/Admin/ProjectsList.vue'

  export default defineComponent({
    
      components: {
          Link,
          AppLayout,
          ProjectsList
      },

      props: {
          projects: Array
      }
  })
</script>
