<template>
    <div>
        <div class="p-6 sm:px-20 bg-grey-300 border-b border-gray-200">
            
            <div class="mt-8 text-2xl">
                Projects
            </div>

            <Link :href="route('CreateProject')" class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                <div>Create Project</div>
                <div class="ml-1 text-indigo-500">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </div>
            </Link>

            <ul class="flex flex-wrap justify-center my-4">
              <li class="flex flex-col justify-center shadow-lg m-4 rounded border-b border-gray-200" v-for="(project, index) in projects" :key="index">
                <Link :href="'/admin/projects/' + project.id">
                  <div class="flex justify-center mb-4">
                    <img class="w-cover max-h-[50vh]" :src="project.featured_image">
                  </div>
                  <div class="container mx-auto p-4">
                    <h3 class="text-center text-2xl text-indigo-700 font-bold mb-4">{{ project.name }}</h3>
                    <p><span class="font-bold">Lookup ID: </span>{{ project.lookup_id }}</p>
                    <p><span class="font-bold">Directory: </span>{{ project.directory }}</p>
                  </div>
                </Link>
              </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Link
        },
        props: {
          projects: Object
        }
    })
</script>
