<div x-show="detailModal" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center px-4"
     style="background-color: rgba(15,23,42,0.5);">
    <div @click.outside="detailModal = false" class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
        <template x-if="! detailPlan">
            <div class="py-16 text-center text-gray-400">
                <i class="fa-solid fa-spinner fa-spin text-2xl"></i>
            </div>
        </template>

        <template x-if="detailPlan">
            <div>
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="font-semibold text-lg text-gray-900 dark:text-white" x-text="detailPlan.patient_name"></h3>
                        <p class="text-sm" style="color:#1649FF;" x-text="detailPlan.treatment"></p>
                    </div>
                    <button type="button" @click="detailModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="mb-6">
                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                        <span x-text="detailPlan.completed_count + ' / ' + detailPlan.total_sessions + ' visits completed'"></span>
                        <span x-text="detailPlan.progress_pct + '%'"></span>
                    </div>
                    <div class="w-full h-2.5 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                        <div class="h-2.5 rounded-full bg-green-500 transition-all duration-500" :style="`width: ${detailPlan.progress_pct}%`"></div>
                    </div>
                </div>

                <div class="space-y-4">
                    <template x-for="session in detailPlan.sessions" :key="session.id">
                        <div class="border border-gray-100 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold text-white"
                                          :class="{
                                              'bg-purple-500': session.status === 'planned',
                                              'bg-amber-500': session.status === 'in_progress',
                                              'bg-green-500': session.status === 'completed',
                                              'bg-red-500': session.status === 'cancelled',
                                          }"
                                          x-text="session.session_no"></span>
                                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200" x-text="session.title"></span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <button type="button" @click="togglePaid(session)"
                                            class="text-xs px-2 py-1 rounded-full font-medium"
                                            :class="session.is_paid ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'">
                                        <i class="fa-solid fa-indian-rupee-sign"></i>
                                        <span x-text="session.is_paid ? 'Paid' : 'Unpaid'"></span>
                                    </button>

                                    <a x-show="session.scheduled_date" x-cloak :href="session.whatsapp_url" target="_blank" rel="noopener" title="WhatsApp Reminder" class="text-green-500 hover:text-green-700">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Date</label>
                                    <input type="date" :value="session.scheduled_date ? session.scheduled_date.substring(0,10) : ''"
                                           @change="saveSession(session, 'scheduled_date', $event.target.value)"
                                           class="block mt-1 w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Time</label>
                                    <input type="time" :value="session.scheduled_time ? session.scheduled_time.substring(0,5) : ''"
                                           @change="saveSession(session, 'scheduled_time', $event.target.value)"
                                           class="block mt-1 w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Doctor</label>
                                    <input type="text" :value="session.doctor_name"
                                           @blur="saveSession(session, 'doctor_name', $event.target.value)"
                                           class="block mt-1 w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Status</label>
                                    <select :value="session.status" @change="saveSession(session, 'status', $event.target.value)"
                                            class="block mt-1 w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="planned">Planned</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="text-xs text-gray-500 dark:text-gray-400">Notes</label>
                                <input type="text" :value="session.notes"
                                       @blur="saveSession(session, 'notes', $event.target.value)"
                                       class="block mt-1 w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>
