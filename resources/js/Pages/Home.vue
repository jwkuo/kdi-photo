<template>
  <Head title="KDI Photo" />
  <div class="bg-gray-200">
    <div class="container mx-auto text-center">
      <img class ="inline-block" src="/kdi-logo.png" alt="KDI Photo">
      <h1 class="invisible h-0">KDI Photo</h1>
      <span class="text-lg font-bold">502-724-5429 | <a class="text-blue-500" href="mailto:kdiphoto@yahoo.com">kdiphoto@yahoo.com</a></span>
    </div>
  </div>
  <div class="bg-[#33c4b5]">
    <div class="container mx-auto text-center">
      <a class="inline justify-center md:flex" rel="external" target="_blank" href="https://www.etsy.com/shop/ShopKDIcreations">
        <div class="flex flex-col justify-center mx-4 md:flex-row md:items-center">
          <div class="">
            <img class ="inline-block max-w-[250px]" src="/kdic-logo.png" alt="Shop KDI Creations">
          </div>
          <h3 class="text-xl font-bold">Check out our Etsy Store for all kinds of Personalized Items!</h3>
        </div>
      </a>
    </div>
  </div>
  <div class="container mx-auto my-4">
    <div class="grid grid-cols-1 md:grid-cols-2">
      <div class="flex flex-col justify-center">
        <img class="max-w-1/2" src="https://s3.amazonaws.com/kdi-photo/front-page/featured-image.jpg">
      </div>
      <div class="flex flex-col justify-center content-center my-4">
        <div class="container content-center px-8">
          <h3 class="text-4xl font-bold mb-4">Order your prints online</h3>
          <p class="max-w-full md:max-w-[75%] mb-4">If you've received a code and photo ID, enter the information to order prints. Your order will be shipped or delivered to the school.</p>
          <form @submit.prevent="lookup" name="lookup">
            <ul class="flex flex-col">
              <div class="flex flex-col lg:flex-row">
                <li class="flex flex-col mb-4 mr-4">
                  <label class="font-bold text-gray-600 mb-2" for="school-id">School ID</label>
                  <input class="border-gray-300 rounded" name="school-id" type="text" v-model="form.lookup_id">
                  <p class="text-sm text-red-400" v-if="form.errors.lookup_id">{{ form.errors.lookup_id }}</p>
                </li>
                <li class="flex flex-col mb-4 mr-4">
                  <label class="font-bold text-gray-600 mb-2" for="photo-id">Photo ID</label>
                  <input class="border-gray-300 rounded" name="photo-id" type="text" v-model="form.photo_id">
                  <p class="text-sm text-red-400" v-if="form.errors.photo_id">{{ form.errors.photo_id }}</p>
                </li>
              </div>
              <div class="flex">
                <li class="flex flex-col mb-4 mr-4">
                  <button class="rounded-full bg-gray-200 px-8 py-4 text-gray-600 font-bold" type="submit">Lookup</button>
                </li>
              </div>
            </ul>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="container mx-auto">
    <ul class="flex flex-wrap justify-center after:content-[''] after:block after:grow-[10]">
      <li v-for="(slide, index) in slides" :key="index" class="w-full md:w-fit flex-grow h-full md:h-[40vh] lg:h-[38vh] my-2">
        <img class="min-w-full max-h-full sm:object-fill md:object-contain align-bottom" :src="slide">
      </li>
    </ul>
  </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Head } from '@inertiajs/inertia-vue3';
import Button from '../Jetstream/Button.vue';

    export default defineComponent({
        components: {
            Head
        },
        props: {
          slides: Array
        },
        data () {
          return {
            form: this.$inertia.form({
                lookup_id: '',
                photo_id: ''
            })
          }
        },
        methods: {
          lookup: function () {
            console.log('submitted')
            this.form.post('/')
          }
        }
    })
</script>
