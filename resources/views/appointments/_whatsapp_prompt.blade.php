<div x-show="whatsappModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4"
    style="background-color: rgba(15,23,42,0.5);">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6 text-center">
        <div
            class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-green-100 text-green-600 text-2xl mb-4">
            <i class="fa-brands fa-whatsapp"></i>
        </div>
        <h3 class="font-semibold text-lg text-gray-900">Appointment Saved</h3>
        <p class="text-sm text-gray-500 mt-1">Send a WhatsApp confirmation to the patient?</p>

        <div class="flex flex-col gap-2 mt-6">
            <a :href="whatsappUrl" target="_blank" rel="noopener" @click="whatsappModalOpen = false; reload();"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-green-500 text-white font-medium hover:bg-green-600">
                <i class="fa-brands fa-whatsapp"></i> Open WhatsApp
            </a>
            <button type="button" @click="whatsappModalOpen = false; reload();"
                class="px-4 py-2 text-sm text-gray-500">
                Skip
            </button>
        </div>
    </div>
</div>
