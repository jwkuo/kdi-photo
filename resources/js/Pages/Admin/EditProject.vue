<template>
  <app-layout title="Dashboard">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Project
        </h2>
    </template>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            
                    <div class="mt-8">
                        <h3 class="text-2xl">Edit Project - {{ project.name }}</h3>
                    </div>

                    <form class="my-4" @submit.prevent="saveProject" name="create-project">
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
                           
                        </ul>
                        <div class="my-4">
                            <div class="flex flex-wrap">
                                <h3 class="my-2 text-2xl">Packages</h3>
                                <button class="rounded-full bg-gray-200 px-4 py-2 mx-4 text-gray-600 font-bold" type="button" @click.stop="addPackage">Add Package</button>
                            </div>
                            <p class="my-2">A package is a collection of sheets for a special price. You can set an amount of sheet choices the customer can choose for him or herself.</p>
                        </div>
                        <ul class="flex flex-col">
                            <div class="flex flex-col md:flex-row" v-for="(pack, index) in form.packages" :key="index">
                                <li class="flex flex-col mb-4 mr-4">
                                    <label class="font-bold text-gray-600 mb-2" for="package-name">Package Name</label>
                                    <input class="border-gray-300 rounded" name="package-name" type="text" v-model="pack.name"/>
                                    <p class="text-sm text-red-400" v-if="errors.packageName">{{ errors.packageName }}</p>
                                </li>
                                <li class="flex flex-col mb-4 mr-4">
                                    <label class="font-bold text-gray-600 mb-2" for="price">Price</label>
                                    <input class="border-gray-300 rounded" name="price" type="text" v-model="pack.price"/>
                                    <p class="text-sm text-red-400" v-if="errors.price">{{ errors.price }}</p>
                                </li>
                                <li class="flex flex-col mb-4 mr-4">
                                    <label class="font-bold text-gray-600 mb-2" for="description">Description</label>
                                    <input class="border-gray-300 rounded" name="description" type="text" v-model="pack.description"/>
                                    <p class="text-sm text-red-400" v-if="errors.description">{{ errors.description }}</p>
                                </li>
                                <li class="flex flex-col mb-4 mr-4">
                                    <label class="font-bold text-gray-600 mb-2" for="sheet-options">Sheet Choices</label>
                                    <input class="border-gray-300 rounded w-32" name="sheet-options" type="number" v-model="pack.sheet_options"/>
                                    <p class="text-sm text-red-400" v-if="errors.sheet_options">{{ errors.sheet_options }}</p>
                                </li>
                                <li class="flex flex-col justify-end mb-4 mr-4">
                                    <button class="rounded-full bg-gray-200 px-4 py-2 mx-4 text-gray-600 font-bold" type="button" @click.stop="removePackage(index)">Delete Package</button>
                                </li>
                            </div>
                        </ul>
                        <div class="my-4">
                            <div class="flex flex-wrap">
                                <h3 class="my-2 text-2xl">Sheet Options</h3>
                            </div>
                            <p class="my-2">Provide a list of Sheet Options that a customer can choose from. List the names of your sheets separated by a comma. (i.e. 8X10 (1), 5X7 (2), Wallets (8))</p>
                        </div>
                        <ul class="flex flex-col">
                            <div class="flex flex-col md:flex-row">
                                <li class="flex flex-col grow mb-4 mr-4">
                                    <label class="font-bold text-gray-600 mb-2" for="sheet-options">Sheet Options</label>
                                    <input class="border-gray-300 rounded" name="sheet-options" type="text" v-model="form.sheet_options"/>
                                    <p class="text-sm text-red-400" v-if="errors.sheet_options">{{ errors.sheet_options }}</p>
                                </li>
                            </div>
                        </ul>
                        <div class="my-4">
                            <div class="flex flex-wrap">
                                <h3 class="my-2 text-2xl">Sheet Prices</h3>
                            </div>
                            <p class="my-2">Set the prices in order of quantity. The number of prices determines the number of sheets a customer may buy. List the prices of your sheets separated by a comma. (i.e. $12.00, $22.00, $30.00)</p>
                        </div>
                        <ul class="flex flex-col">
                            <div class="flex flex-col md:flex-row">
                                <li class="flex flex-col grow mb-4 mr-4">
                                    <label class="font-bold text-gray-600 mb-2" for="sheet-prices">Sheet Prices</label>
                                    <input class="border-gray-300 rounded" name="sheet-prices" type="text" v-model="form.sheet_prices"/>
                                    <p class="text-sm text-red-400" v-if="errors.sheet_prices">{{ errors.sheet_prices }}</p>
                                </li>
                            </div>
                        </ul>
                        <div class="my-4">
                            <div class="flex flex-wrap">
                                <h3 class="my-2 text-2xl">Sheet Promotion (Optional)</h3>
                            </div>
                            <p class="my-2">Provide a promotional text for purchasing sheets. (i.e. FREE 5.x7 class photo with 3 or more sheets!)</p>
                        </div>
                        <ul class="flex flex-col">
                            <div class="flex flex-col md:flex-row">
                                <li class="flex flex-col grow mb-4 mr-4">
                                    <label class="font-bold text-gray-600 mb-2" for="sheet-promo">Sheet Promo Text</label>
                                    <input class="border-gray-300 rounded" name="sheet-promo" type="text" v-model="form.sheet_promo"/>
                                    <p class="text-sm text-red-400" v-if="errors.sheet_promo">{{ errors.sheet_promo }}</p>
                                </li>
                            </div>
                        </ul>
                        <div class="my-4">
                            <div class="flex flex-wrap">
                                <h3 class="my-2 text-2xl">Individuals, Add Ons, and Tack Ons</h3>
                                <button class="rounded-full bg-gray-200 px-4 py-2 mx-4 text-gray-600 font-bold" type="button" @click.stop="addItem">Create Item</button>
                            </div>
                            <p class="my-2">Got a special sheet for a fixed price? An option to add to all sheets? Need to tack on a shipping fee? These items will get the job done!</p>
                        </div>
                        <ul class="flex flex-col border-2 border-gray-300 rounded p-4 my-4" v-for="(item, index) in form.items" :key="index">
                            <div class="flex flex-col md:flex-row">
                                <li class="flex flex-col mb-4 mr-4">
                                    <label class="font-bold text-gray-600 mb-2" for="item-name">Item Name</label>
                                    <input class="border-gray-300 rounded" name="item-name" type="text" v-model="item.name"/>
                                    <p class="text-sm text-red-400" v-if="errors.packageName">{{ errors.packageName }}</p>
                                </li>
                                <li class="flex flex-col mb-4 mr-4">
                                    <label class="font-bold text-gray-600 mb-2" for="item-price">Price</label>
                                    <input class="border-gray-300 rounded" name="item-price" type="text" v-model="item.price"/>
                                    <p class="text-sm text-red-400" v-if="errors.price">{{ errors.price }}</p>
                                </li>
                                <li class="flex flex-col mb-4 mr-4">
                                    <label class="font-bold text-gray-600 mb-2" for="item-description">Description</label>
                                    <input class="border-gray-300 rounded" name="item-description" type="text" v-model="item.description"/>
                                    <p class="text-sm text-red-400" v-if="errors.description">{{ errors.description }}</p>
                                </li>
                            </div>
                            <div class="flex flex-col md:flex-row">
                                <li class="flex flex-col mb-4 mr-4">
                                    <ul class="flex flex-col">
                                        <li class="flex flex-row">
                                            <input class="border-gray-300 rounded h-8 w-8 m-4" name="item-multi" type="checkbox" v-model="item.multi"/>
                                            <label class="font-bold text-gray-600 my-4 w-full" for="item-multi">Multiple: Customer can buy more than one of this item</label>
                                            <p class="text-sm text-red-400" v-if="errors.sheet_options">{{ errors.sheet_options }}</p>
                                        </li>
                                        <li class="flex flex-row">
                                            <input class="border-gray-300 rounded h-8 w-8 m-4" name="item-auto" type="checkbox" v-model="item.auto"/>
                                            <label class="font-bold text-gray-600 my-4 w-full" for="item-auto">Auto Add to Order: Automatically add this item to the order</label>
                                            <p class="text-sm text-red-400" v-if="errors.sheet_options">{{ errors.sheet_options }}</p>
                                        </li>
                                        <li class="flex flex-row">
                                            <input class="border-gray-300 rounded h-8 w-8 m-4" name="item-input-option" type="checkbox" v-model="item.input_option"/>
                                            <label class="font-bold text-gray-600 my-4 w-full" for="item-input-option">Input Option: Add a text input option for the customer to provide</label>
                                            <p class="text-sm text-red-400" v-if="errors.sheet_options">{{ errors.sheet_options }}</p>
                                        </li>
                                        <li v-if="item.input_option" class="flex flex-col ml-16">
                                            <label class="font-bold text-gray-600 mb-2" for="item-input-label">Text Input Label</label>
                                            <input class="border-gray-300 rounded" name="item-input-label" type="text" v-model="item.input_label"/>
                                            <p class="text-sm text-red-400" v-if="errors.sheet_options">{{ errors.sheet_options }}</p>
                                        </li>
                                    </ul>
                                </li>
                            </div>
                            <div class="flex flex-wrap">
                                <li class="flex flex-col mb-4 mr-4">
                                    <button class="rounded-full bg-gray-200 px-4 py-2 mx-4 text-gray-600 font-bold" type="button" @click.stop="removeItem(index)">Delete Item</button>
                                </li>
                            </div>
                        </ul>
                        <ul class="flex flex-col">
                            <li class="flex flex-col md:flex-row md:justify-between mb-4 mr-4">
                                <button class="rounded-full bg-gray-200 px-8 py-4 text-gray-600 font-bold" type="submit" :disabled="form.processing">Save Project</button>
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
          errors: Object,
          project: Object
      },
      data () {
          return {
              form: this.$inertia.form({
                  projectName: this.project.name,
                  lookupId: this.project.lookup_id,
                  directory: this.project.directory,
                  packages: this.project.packages,
                  items: this.project.items,
                  sheet_options: this.project.sheet_options,
                  sheet_prices: this.project.sheet_prices,
                  sheet_promo: this.project.sheet_promo
              })
          }
      },
      methods: {
          saveProject: function () {
              this.form.post('/admin/projects/' + this.project.id)
          },
          addPackage: function () {
              this.form.packages.push({
                id: null,
                name: '',
                price: '',
                description: '',
                sheet_options: 0
              })
          },
          removePackage: function(index) {
              this.form.packages.splice(index, 1)
          },
          addItem: function () {
              this.form.items.push({
                  id: null,
                  name: '',
                  price: '',
                  description: '',
                  multi: false,
                  auto: false,
                  input_option: false,
                  input_label: ''
              })
          },
          removeItem: function (index) {
              this.form.items.splice(index, 1)
          }
      }
  })
</script>
