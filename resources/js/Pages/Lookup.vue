<template>
  <Head title="KDI Photo - Order Form" />
  <div class="bg-gray-200">
    <div class="container mx-auto text-center">
      <img class ="inline-block" src="/kdi-logo.png" alt="KDI Photo">
      <h1 class="invisible h-0">KDI Photo</h1>
      <span class="text-lg font-bold">502-724-5429 | <a class="text-blue-500" href="mailto:kdiphoto@yahoo.com">kdiphoto@yahoo.com</a></span>
    </div>
  </div>
  <div class="container mx-auto my-4">
    <div class="container text-center my-4">
      <h2 class="text-4xl font-bold">Order Form</h2>
    </div>
    <div class="flex flex-col md:flex-row-reverse">
      <img class="grow-0 px-4 mb-4 max-w-screen-sm h-full" :src="photoUrl">
      <div class="flex flex-col grow px-4">
        <form @submit.prevent="reviewingOrder = true" name="lookup">
          <h3 class="text-2xl font-bold mb-4">Packages</h3>
          <ul class="flex flex-col md:flex-row md:flex-wrap mb-4">
            <li v-for="(pack, index) in packages" :key="index" class="flex flex-col p-4 my-2 md:mr-4 border-2 border-gray-300 rounded w-full md:w-1/4">
              <h4 class="text-xl font-bold mb-2">{{ pack.name }}</h4>
              <p class="my-2">{{ pack.description }}</p>
              <span class="text-2xl font-bold">${{ pack.price.replace('.00', '') }}</span>
              <div class="flex flex-col my-4">
                <label class="font-bold text-gray-600" :for="'qty-' + index">Qty</label>
                <div class="flex flex-row">
                  <input class="border-gray-300 rounded w-16" :name="'qty-' + index" type="number" min="0" v-model="pack.qty"/>
                </div>
              </div>
            </li>
          </ul>
          <h3 class="text-2xl font-bold mb-4">Package Sheet Choices</h3>
          <ul v-if="orderedPackages.length > 0" class="flex flex-col md:flex-row mb-4">
            <li v-for="(pack, index) in orderedPackages" :key="index" class="flex flex-col mr-4 mb-4">
              <h4 class="text-xl font-bold mb-2">{{ pack.name }}</h4>
              <div v-if="pack.input_label != ''" class="flex flex-row mb-4">
                <label class="text-gray-600 mt-2 mr-4" :for="'input-' + index">{{ pack.input_label }}</label>
                <input class="border-gray-300 rounded" :name="'input-' + index" type="text" v-model="pack.input"/>
              </div>
              <div v-for="(sheetType, key) in orderedPackageSheetTypes[pack.id]" :key="key" class="flex flex-row">
                <label class="text-gray-600 mt-2 mr-4" :for="'sheet-' + key">Sheet {{ key + 1 }}</label>
                <select class="border-gray-300 rounded w-64 mb-4" :name="'sheet-' + key" v-model="orderedPackageSheetTypes[pack.id][key]">
                  <option v-for="(sheetOption, index) in sheetOptions" :key="index" :value="sheetOption">
                    {{ sheetOption }}
                  </option>
                </select>
              </div>
            </li>
          </ul>
          <p v-else class="mb-4">Select a package to choose your sheets.</p>
          <div class="flex flex-col md:flex-row mb-4">
            <h3 class="text-2xl font-bold py-4 mr-4">Individual Sheets</h3>
            <div v-if="project.sheet_promo.length > 0" class="flex flex-row grow bg-gray-200 p-4 rounded">
              <span class="text-lg font-bold">{{ project.sheet_promo }}</span>
            </div>
          </div>
          <p v-if="project.sheet_prices.length > 0" class="mb-4">Choose the quantity of sheets and then choose which sheet types to purchase</p>
          <div v-if="project.sheet_prices.length > 0" class="flex flex-col md:flex-row">
            <div class="flex flex-col mr-4 mb-4">
              <label class="font-bold text-gray-600" for="sheet-qty">Sheet Quantity</label>
              <select name="sheet-qty" class="border-gray-300 rounded w-64" v-model="selectedSheetQty">
                <option v-for="(sheet, index) in sheets" :key="index" :value="sheet.value">
                  {{ sheet.text }}
                </option>
              </select>
            </div>
            <div class="flex flex-col">
              <label class="font-bold text-gray-600 mb-4" for="sheet-types">Sheet Types</label>
              <p v-if="selectedSheetQty == 0"> No Sheets Selected</p>
              <div v-for="(sheetType, key) in sheetTypes" :key="key" class="flex flex-row">
                <label class="text-gray-600 mt-2 mr-4" :for="'sheet-' + key">Sheet {{ key + 1 }}</label>
                <select class="border-gray-300 rounded w-64 mb-4" :name="'sheet-' + key" v-model="sheetTypes[key]">
                  <option v-for="(sheetOption, index) in sheetOptions" :key="index" :value="sheetOption">
                    {{ sheetOption }}
                  </option>
                </select>
              </div>
            </div>
          </div>
          <h3 class="text-2xl font-bold py-4">Indvidual Items and Add Ons</h3>
          <ul class="flex flex-col md:flex-row md:flex-wrap mb-4">
            <li v-for="(item, index) in items" :key="index" class="flex flex-col p-4 my-2 md:mr-4 border-2 border-gray-300 rounded w-full md:w-1/4">
              <h4 class="text-xl font-bold mb-2">{{ item.name }}</h4>
              <p class="mb-2 ml-2">{{ item.description }}</p>
              <span class="text-2xl font-bold">${{ item.price.replace('.00', '') }}</span>
              <div v-if="item.input_option" class="flex flex-col my-4">
                <label class="font-bold text-gray-600 mb-2" :for="'label-' + index">{{ item.input_label }}</label>
                <input class="border-gray-300 rounded" :name="'label-' + index" type="text" v-model="item.user_input"/>
              </div>
              <div v-if="item.multi" class="flex flex-col grow justify-end mt-4">
                <label class="font-bold text-gray-600" :for="'qty-' + index">Qty</label>
                <div class="flex flex-row">
                  <input class="border-gray-300 rounded w-16" :name="'qty-' + index" type="number" min="0" v-model="item.qty"/>
                </div>
              </div>
              <div v-else class="flex flex-col grow justify-end mt-4">
                <div class="flex flex-row">
                  <input class="border-gray-300 rounded h-8 w-8" :name="'include-' + index" type="checkbox" v-model="item.selected"/>
                  <label class="font-bold text-gray-600 my-1 mx-4 w-full" :for="'include-' + index">Add to Order</label>
                </div>
              </div>
            </li>
          </ul>
          <div class="flex-auto flex-col">
            <button class="rounded-full bg-gray-200 px-8 py-4 text-gray-600 font-bold disabled:text-gray-300 disabled:bg-gray-100 grow-0" :disabled="missingPackageInput" type="submit">Review Order</button>
            <span v-if="missingPackageInput" class="text-gray-400 block my-2">Please provide a value for package choices.</span>
          </div>
        </form>
      </div>
    </div>
  </div>
  <ConfirmationModal :show="reviewingOrder" @close="reviewingOrder = false">
    <template #title>
      Your Order
    </template>

    <template #content>
      <p v-if="emptyOrder">You have no items in your order. Please add Packages, Sheets, or Items to your order.</p>
      <p v-else>
        Please review your order for accuracy.<br/>
        You will be taken to Paypal upon confirmation to complete your order.<br/>
        In case your order needs to be shipped, please make sure your address is updated in Paypal.
      </p>
      <ul v-if="!emptyOrder" class="my-4">
        <span v-if="orderedPackages.length > 0" class="text-lg font-bold">Packages</span>
        <li class="ml-0 md:ml-4" v-for="(pack, index) in orderedPackages" :key="index">
          <span>{{ pack.qty }}x</span> {{ pack.name }} {{ pack.input_label }}{{ pack.input_label != '' ? ':' : '' }} {{ pack.input }} - ${{ (pack.price * pack.qty).toFixed(2) }}
        </li>
        <span v-if="selectedSheetQty > 0" class="text-lg font-bold">{{ selectedSheetQty }} Sheets</span>
        <li v-if="selectedSheetQty > 0" class="ml-0 md:ml-4">
          {{ sheetTypes.join(', ') }} - ${{ sheetPrice }}
        </li>
        <span v-if="orderedItems.length > 0" class="text-lg font-bold">Items</span>
        <li class="ml-0 md:ml-4" v-for="(item, index) in orderedItems" :key="index">
          {{ item.selected ? '1' : item.qty }}x {{ item.name }} {{ item.input_option ? '(' + item.user_input + ')' : ''}} - ${{ (item.price * item.qty).toFixed(2) }}
        </li>
        <span v-if="autoItems.length > 0" class="text-lg font-bold">Additional Charges</span>
        <li class="ml-0 md:ml-4" v-for="(item, index) in autoItems" :key="index">
          {{ item.name }} - ${{ item.price }}
        </li>
      </ul>
      <span class="text-lg font-bold">Total: ${{ orderTotal }}</span>
      <form id="paypal-form" ref="paypalform" action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input v-for="(param, key) in paypalParams" :key="key" type="hidden" :name="key" :value="param"/>
      </form>
    </template>

    <template #footer>
      <SecondaryButton @click="reviewingOrder = false">
        Make Changes
      </SecondaryButton>

      <Button class="ml-2" @click="placeOrder" :disabled="emptyOrder">
        Place Order
      </Button>
    </template>
  </ConfirmationModal>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Head } from '@inertiajs/inertia-vue3';
    import ConfirmationModal from '../Components/ConfirmationModal.vue';
    import SecondaryButton from '../Jetstream/SecondaryButton.vue';
    import Button from '../Jetstream/Button.vue';

    export default defineComponent({
        components: {
            Head,
            ConfirmationModal,
            SecondaryButton,
            Button
        },
        props: {
          project: Object,
          photo_id: String
        },
        data () {
          return {
            selectedSheetQty: 0,
            sheetTypes: [],
            packages: this.project.packages.map(pack => {
              pack.qty = 0
              pack.input = ''
              return pack
            }),
            orderedPackageSheetTypes: {},
            items: this.project.items.filter(item => item.auto == false).map(item => {
              item.qty = 0
              item.selected = false
              item.user_input = ''
              return item
            }),
            reviewingOrder: false
          }
        },
        computed: {
          photoUrl: function () {
            return 'https://s3.amazonaws.com/kdi-photo/' + this.project.directory + '/' + this.photo_id + '.jpg'
          },
          sheets: function () {
            let sheets = this.project.sheet_prices.replace(', ', ',').split(',').map((sheet_price, index) => {
              return {
                text: (index + 1) + ' Sheets - ' + sheet_price,
                value: index + 1
              }
            })
            sheets.unshift({
              text: 'No Sheets',
              value: 0
            })
            return sheets
          },
          sheetOptions: function () {
            return this.project.sheet_options.replace(', ', ',').split(',')
          },
          orderedPackages: function () {
            return this.packages.filter(pack => pack.qty > 0)
          },
          missingPackageInput: function () {
            return this.orderedPackages.filter(pack => pack.input_label !== '' && pack.input == '').length > 0
          },
          orderedItems: function () {
            return this.items.filter(item => {
              if(item.selected == true) {
                item.qty = 1
                return true
              }
              return item.qty > 0
            })
          },
          autoItems: function () {
            return this.project.items.filter(item => item.auto)
          },
          emptyOrder: function () {
            return this.orderedPackages.length == 0 && this.orderedItems == 0 && this.selectedSheetQty == 0
          },
          sheetPrice: function () {
            if (this.sheetTypes.length > 0) {
              let sheetPriceList = this.project.sheet_prices.replace(/\$/g, '').replace(/, /g, ',').split(',')
              return sheetPriceList[this.sheetTypes.length - 1]
            }
            return 0;
          },
          paypalParams: function () {
            let paypalIndex = 1
            let paypalData = {}

            this.orderedPackages.forEach(pack => {
              let optionalInput = ''
              if (pack.input_label != '') {
                optionalInput = ' ' + pack.input_label + ': ' + pack.input
              }
              paypalData['item_name_' + paypalIndex] = pack.name + optionalInput + ' Sheets: ' + this.orderedPackageSheetTypes[pack.id].join(', ')
              paypalData['amount_' + paypalIndex] = pack.price
              paypalData['quantity_' + paypalIndex] = pack.qty
              paypalData['on0_' + paypalIndex] = 'Photo ID'
              paypalData['os0_' + paypalIndex] = this.photo_id
              paypalData['on1_' + paypalIndex] = 'Project ID'
              paypalData['os1_' + paypalIndex] = this.project.lookup_id
              paypalIndex += 1
            })

            if (this.sheetPrice > 0) {
              paypalData['item_name_' + paypalIndex] = 'Sheets: ' + this.sheetTypes.join(', ')
              paypalData['amount_' + paypalIndex] = this.sheetPrice
              paypalData['quantity_' + paypalIndex] = 1
              paypalData['on0_' + paypalIndex] = 'Photo ID'
              paypalData['os0_' + paypalIndex] = this.photo_id
              paypalData['on1_' + paypalIndex] = 'Project ID'
              paypalData['os1_' + paypalIndex] = this.project.lookup_id
              paypalIndex += 1
            }

            this.orderedItems.forEach(item => {
              paypalData['item_name_' + paypalIndex] = item.input_option ? item.name + ': ' + item.user_input : item.name
              paypalData['amount_' + paypalIndex] = item.price
              paypalData['quantity_' + paypalIndex] = item.selected ? 1 : item.qty
              paypalData['on0_' + paypalIndex] = 'Photo ID'
              paypalData['os0_' + paypalIndex] = this.photo_id
              paypalData['on1_' + paypalIndex] = 'Project ID'
              paypalData['os1_' + paypalIndex] = this.project.lookup_id
              paypalIndex += 1
            })

            this.autoItems.forEach(item => {
              paypalData['item_name_' + paypalIndex] = item.name
              paypalData['amount_' + paypalIndex] = item.price
              paypalData['quantity_' + paypalIndex] = 1
              paypalData['on0_' + paypalIndex] = 'Photo ID'
              paypalData['os0_' + paypalIndex] = this.photo_id
              paypalData['on1_' + paypalIndex] = 'Project ID'
              paypalData['os1_' + paypalIndex] = this.project.lookup_id
              paypalIndex += 1
            })

            paypalData['cmd'] = '_cart'
            paypalData['upload'] = '1'
            paypalData['business'] = 'kdiphoto@yahoo.com'
            paypalData['return'] = 'http://kdiphoto.com'
            paypalData['cancel_return'] = 'http://kdiphoto.com'

            return paypalData
          },
          orderTotal: function () {
            let packages = this.orderedPackages.reduce((totalPrice, pack) => {
              return totalPrice + (parseFloat(pack.price) * pack.qty)
            }, 0)
            let items = this.orderedItems.reduce((totalPrice, item) => {
              return totalPrice + (parseFloat(item.price) * item.qty)
            }, 0)
            let grandTotal = parseFloat(packages) + parseFloat(this.sheetPrice) + parseFloat(items)
            if (grandTotal > 0) {
              let autoItems = this.autoItems.reduce((totalPrice, item) => {
                return totalPrice + parseFloat(item.price)
              }, 0)
              grandTotal += parseFloat(autoItems)
            }
            return grandTotal.toFixed(2)
          }
        },
        watch: {
          selectedSheetQty(newQty) {
            this.sheetTypes = []
            for(let i=0; i < newQty; i++) {
              this.sheetTypes.push(this.sheetOptions[0])
            }
          },
          packages: {
            handler(newPackages) {
              newPackages.forEach((pack, index) => {
                this.orderedPackageSheetTypes[pack.id] = []
                if (pack.qty > 0) {
                  for(let i=0; i < (pack.qty * pack.sheet_options); i++) {
                    this.orderedPackageSheetTypes[pack.id].push(this.sheetOptions[0])
                  }
                }
              })
            },
            deep: true
          }
        },
        methods: {
          placeOrder: function () {
            this.$refs.paypalform.submit()
          }
        }
    })
</script>
