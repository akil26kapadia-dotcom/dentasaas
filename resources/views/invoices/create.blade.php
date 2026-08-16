<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">New Invoice</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="invoiceForm()" x-init="init()">
        @error('plan_limit')
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('invoices.store') }}" class="space-y-6">
            @csrf

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label value="Patient" />
                        <select x-ref="patientSelect" style="width:100%" class="block mt-1">
                            <option value="">Search patient by name or phone…</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->name }} — {{ $patient->phone }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="patient_id" x-model="patient_id">
                    </div>

                    <div>
                        <x-input-label for="patient_name" value="Patient Name" />
                        <x-text-input id="patient_name" name="patient_name" class="block mt-1 w-full" x-model="patient_name" required />
                        <x-input-error :messages="$errors->get('patient_name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="invoice_date" value="Invoice Date" />
                        <x-text-input id="invoice_date" type="date" name="invoice_date" class="block mt-1 w-full" value="{{ old('invoice_date', now()->format('Y-m-d')) }}" />
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Line Items</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 dark:text-gray-400">
                                <th class="py-2">Service</th>
                                <th class="py-2 w-24">Qty</th>
                                <th class="py-2 w-32">Unit Price</th>
                                <th class="py-2 w-32">Amount</th>
                                <th class="py-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="border-t border-gray-100 dark:border-gray-700">
                                    <td class="py-2 pr-2">
                                        <select @change="onServiceSelect(index, $event.target.value)"
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">Select service</option>
                                            <template x-for="s in services" :key="s.id">
                                                <option :value="s.id" x-text="s.name" :selected="item.service_id == s.id"></option>
                                            </template>
                                        </select>
                                        <input type="hidden" :name="`items[${index}][service]`" x-model="item.service">
                                    </td>
                                    <td class="py-2 pr-2">
                                        <input type="number" min="1" :name="`items[${index}][qty]`" x-model.number="item.qty"
                                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </td>
                                    <td class="py-2 pr-2">
                                        <input type="number" min="0" step="0.01" :name="`items[${index}][price]`" x-model.number="item.price"
                                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </td>
                                    <td class="py-2 pr-2 font-medium" x-text="'₹' + ((item.qty || 0) * (item.price || 0)).toFixed(2)"></td>
                                    <td class="py-2">
                                        <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <button type="button" @click="addItem()" class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-800">
                    <i class="fa-solid fa-plus"></i> Add Item
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="tax_pct" value="Tax / GST %" />
                            <x-text-input id="tax_pct" type="number" min="0" max="100" step="0.01" name="tax_pct" class="block mt-1 w-full" x-model.number="tax_pct" />
                        </div>
                        <div>
                            <x-input-label for="discount_pct" value="Discount %" />
                            <x-text-input id="discount_pct" type="number" min="0" max="100" step="0.01" name="discount_pct" class="block mt-1 w-full" x-model.number="discount_pct" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="notes" value="Notes" />
                        <textarea id="notes" name="notes" rows="3"
                            class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Summary</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Subtotal</dt>
                            <dd class="text-gray-800 dark:text-gray-200" x-text="'₹' + subtotal().toFixed(2)"></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Discount (<span x-text="discount_pct || 0"></span>%)</dt>
                            <dd class="text-red-600" x-text="'- ₹' + discountAmount().toFixed(2)"></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">GST (<span x-text="tax_pct || 0"></span>%)</dt>
                            <dd class="text-gray-800 dark:text-gray-200" x-text="'+ ₹' + taxAmount().toFixed(2)"></dd>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-gray-100 dark:border-gray-700 text-base font-semibold">
                            <dt class="text-gray-900 dark:text-white">Grand Total</dt>
                            <dd style="color:#1649FF;" x-text="'₹' + grandTotal().toFixed(2)"></dd>
                        </div>
                    </dl>

                    <button type="submit" class="w-full mt-6 inline-flex items-center justify-center px-4 py-2.5 rounded-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Save Invoice
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function invoiceForm() {
                return {
                    patient_id: '',
                    patient_name: '{{ old('patient_name') }}',
                    tax_pct: 0,
                    discount_pct: 0,
                    services: @json($services),
                    items: [{ service_id: '', service: '', qty: 1, price: 0 }],

                    init() {
                        const self = this;
                        $(this.$refs.patientSelect).select2({ width: '100%' }).on('change', function () {
                            self.onPatientChange(this.value);
                        });
                    },

                    patients: @json($patients),

                    onPatientChange(id) {
                        this.patient_id = id;
                        const p = this.patients.find(p => p.id == id);
                        if (p) this.patient_name = p.name;
                    },

                    onServiceSelect(index, id) {
                        const s = this.services.find(s => s.id == id);
                        if (s) {
                            this.items[index].service_id = s.id;
                            this.items[index].service = s.name;
                            this.items[index].price = parseFloat(s.price);
                        }
                    },

                    addItem() {
                        this.items.push({ service_id: '', service: '', qty: 1, price: 0 });
                    },

                    removeItem(index) {
                        if (this.items.length > 1) this.items.splice(index, 1);
                    },

                    subtotal() {
                        return this.items.reduce((sum, item) => sum + ((item.qty || 0) * (item.price || 0)), 0);
                    },

                    discountAmount() {
                        return this.subtotal() * ((this.discount_pct || 0) / 100);
                    },

                    taxAmount() {
                        return (this.subtotal() - this.discountAmount()) * ((this.tax_pct || 0) / 100);
                    },

                    grandTotal() {
                        return this.subtotal() - this.discountAmount() + this.taxAmount();
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
