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
                  <div class="flex justify-center mb-4">
                    <Link :href="'/admin/projects/' + project.id"> 
                      <img class="w-cover max-h-[25vh]" :src="project.featured_image">
                    </Link>
                  </div>
                  <div class="container mx-auto text-center p-4">
                    <Link class="text-2xl text-indigo-700 font-bold" :href="'/admin/projects/' + project.id">
                      {{ project.name }}
                    </Link>
                    <div class="inline-block relative">
                      <svg @click="toggleOptions(index)" :id="'menu-' + index" class="inline-block ml-2 mb-2 cursor-pointer" width="20px" height="20px" fill="rgb(67, 56, 202)" stroke="rgb(67, 56, 202)" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 12a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0-6a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 12a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>
                      <div v-if="openedOptionMenu === index" class="absolute top-8 left-2">
                        <ul class="bg-white drop-shadow text-left">
                          <li @click="duplicateProject(index)" class="p-4 cursor-pointer hover:bg-gray-300">Duplicate</li>
                          <hr/>
                          <li @click="deleteProject(index)" class="p-4 cursor-pointer hover:bg-gray-300">Delete</li>
                        </ul>
                      </div>
                    </div>
                    <p class="my-2"><span class="font-bold">Lookup ID: </span>{{ project.lookup_id }}</p>
                    <p class="my-2"><span class="font-bold">Directory: </span>{{ project.directory }}</p>
                  </div>
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
        },
        data () {
          return {
            openedOptionMenu: false
          }
        },
        methods: {
          toggleOptions: function (index) {
            this.openedOptionMenu = this.openedOptionMenu !== index ? index : false
          },
          clickAway: function (event) {
            if (event.target.id !== 'menu-' + this.openedOptionMenu && event.target.parentElement.id !== 'menu-' + this.openedOptionMenu) {
              this.openedOptionMenu = false
            }
          },
          duplicateProject: function(index) {
            this.$inertia.post('/admin/projects/duplicate', {
              project_id: this.projects[index].id
            })
          },
          deleteProject: function(index) {
            this.$inertia.post('/admin/projects/delete', {
              project_id: this.projects[index].id
            })
            this.openedOptionMenu = false
          }
        },
        created: function() {
          window.addEventListener('click',this.clickAway)
        },
        destroyed: function() {
          window.removeEventListener('click', this.clickAway)
        }
    })
</script>
