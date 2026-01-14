<x-layouts.app>
    <div class="mx-auto max-w-7xl">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Monitors</h1>
                <p class="mt-1 text-sm text-gray-500">Kelola dan pantau status monitor Anda</p>
            </div>
            <div class="flex items-center gap-3">
                <button id="delete-btn" type="button"
                    class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Hapus Data</span>
                </button>
                <button id="export-btn" type="button"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Export Excel</span>
                </button>
                <button id="refresh-btn" type="button"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg id="refresh-icon" class="h-4 w-4 transition-transform duration-300" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Segarkan</span>
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-6 flex flex-wrap items-center gap-4 rounded-lg bg-white p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Status:</label>
                <select id="filter-status"
                    class="filter-select rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="Semua">Semua</option>
                    <option value="Online">Online</option>
                    <option value="Offline">Offline</option>
                    <option value="Paused">Paused</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Tipe:</label>
                <select id="filter-type"
                    class="filter-select rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="Semua">Semua</option>
                    <option value="HTTP(S)">HTTP(S)</option>
                    <option value="Keyword">Keyword</option>
                    <option value="Ping">Ping</option>
                    <option value="Port">Port</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">URL Group:</label>
                <select id="filter-url-group"
                    class="filter-select rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="Semua ...">Memuat...</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Dari Tanggal:</label>
                <input type="date" id="filter-date-from"
                    class="filter-date rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Sampai Tanggal:</label>
                <input type="date" id="filter-date-to"
                    class="filter-date rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="ml-auto">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="filter-search" placeholder="Cari monitor..."
                        class="filter-input block w-64 rounded-lg border-gray-300 bg-gray-50 py-2 pl-10 pr-3 text-sm placeholder-gray-500 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div id="delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true" id="delete-modal-overlay">
            </div>

            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Center modal -->
                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div
                    class="relative inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                    Hapus Data Monitor
                                </h3>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-500 mb-4">
                                        Pilih rentang tanggal untuk menghapus data monitor. Tindakan ini tidak dapat
                                        dibatalkan.
                                    </p>
                                    <form id="delete-form">
                                        <div class="space-y-4">
                                            <div>
                                                <label for="delete-start-datetime"
                                                    class="block text-sm font-medium text-gray-700 mb-1">Dari
                                                    Tanggal & Waktu:</label>
                                                <input type="datetime-local" id="delete-start-datetime"
                                                    name="start_date" required
                                                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label for="delete-end-datetime"
                                                    class="block text-sm font-medium text-gray-700 mb-1">Sampai
                                                    Tanggal & Waktu:</label>
                                                <input type="datetime-local" id="delete-end-datetime" name="end_date"
                                                    required
                                                    class="block w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" id="delete-confirm-btn"
                            class="inline-flex w-full justify-center rounded-lg border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                            Hapus
                        </button>
                        <button type="button" id="delete-cancel-btn"
                            class="mt-3 inline-flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monitors Table -->
        <div class="overflow-hidden rounded-lg bg-white shadow-sm border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="w-12 px-4 py-3"></th>
                            <th scope="col"
                                class="w-16 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                No
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Monitor
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Tipe
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Interval
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Timeout
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Created At
                            </th>
                        </tr>
                    </thead>
                    <tbody id="monitors-tbody" class="divide-y divide-gray-200 bg-white">
                        <!-- Loading indicator -->
                        <tr id="loading-row">
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="h-8 w-8 animate-spin rounded-full border-4 border-indigo-200 border-t-indigo-600">
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Memuat monitor...</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Loading more indicator -->
        <div id="loading-more" class="hidden mt-4 text-center">
            <div class="inline-flex items-center gap-2 text-sm text-gray-500">
                <div class="h-4 w-4 animate-spin rounded-full border-2 border-indigo-200 border-t-indigo-600"></div>
                Memuat lebih banyak...
            </div>
        </div>

        <!-- Scroll trigger for infinite scroll -->
        <div id="scroll-trigger" class="h-1"></div>

        <!-- Empty state -->
        <div id="empty-state" class="hidden mt-6 text-center">
            <div class="rounded-lg bg-white p-12 shadow-sm border border-gray-200">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <p class="mt-4 text-base font-medium text-gray-900">Tidak ada monitor</p>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan monitor pertama Anda.</p>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let isLoading = false;
        let hasMore = true;
        let totalLoaded = 0; // Track total records loaded for numbering
        const perPage = 20;
        const apiUrl = '{{ route('monitors.api') }}';
        const urlGroupsUrl = '{{ route('monitors.url-groups') }}';
        let searchTimeout = null;

        // Filter state
        const filters = {
            status: 'Semua',
            type: 'Semua',
            urlGroup: 'Semua',
            search: '',
            dateFrom: '',
            dateTo: '',
        };

        // Helper functions untuk status dan type
        function getStatusClasses(status) {
            const statusMap = {
                0: {
                    dot: 'bg-yellow-500',
                    badge: 'bg-yellow-100 text-yellow-800',
                    label: 'Paused'
                },
                1: {
                    dot: 'bg-gray-500',
                    badge: 'bg-gray-100 text-gray-800',
                    label: 'Not Checked'
                },
                2: {
                    dot: 'bg-green-500',
                    badge: 'bg-green-100 text-green-800',
                    label: 'Up'
                },
                8: {
                    dot: 'bg-orange-500',
                    badge: 'bg-orange-100 text-orange-800',
                    label: 'Seems Down'
                },
                9: {
                    dot: 'bg-red-500',
                    badge: 'bg-red-100 text-red-800',
                    label: 'Down'
                },
            };
            return statusMap[status] || {
                dot: 'bg-gray-500',
                badge: 'bg-gray-100 text-gray-800',
                label: 'Unknown'
            };
        }

        function getTypeClasses(type) {
            const typeMap = {
                1: {
                    badge: 'bg-blue-100 text-blue-800',
                    label: 'HTTP(S)'
                },
                2: {
                    badge: 'bg-purple-100 text-purple-800',
                    label: 'Keyword'
                },
                3: {
                    badge: 'bg-indigo-100 text-indigo-800',
                    label: 'Ping'
                },
                4: {
                    badge: 'bg-purple-100 text-purple-800',
                    label: 'Port'
                },
            };
            return typeMap[type] || {
                badge: 'bg-gray-100 text-gray-800',
                label: 'Unknown'
            };
        }

        function formatDateTime(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        function formatDateTimeShort(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function renderMonitorRow(monitor, index) {
            const status = getStatusClasses(monitor.status);
            const type = getTypeClasses(monitor.type);
            const createDatetime = formatDateTime(monitor.create_datetime); // Full format for detail
            const createdAt = formatDateTimeShort(monitor.created_at); // Short format for table
            const updatedAt = formatDateTime(monitor.updated_at);
            const rowNumber = totalLoaded + index + 1;

            return `
                <!-- Main Row -->
                <tr class="hover:bg-gray-50 cursor-pointer" onclick="toggleRow(${monitor.id})">
                    <td class="px-4 py-4">
                        <button type="button" class="expand-btn" data-id="${monitor.id}">
                            <svg class="h-5 w-5 text-gray-400 transition-transform duration-200"
                                id="icon-${monitor.id}" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 text-center">
                        ${rowNumber}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-2 w-2 rounded-full ${status.dot} mr-3"></div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">${monitor.friendly_name || '-'}</div>
                                <div class="text-sm text-gray-500">${monitor.url || '-'}</div>
                            </div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4">
                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold ${type.badge}">${type.label}</span>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4">
                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold ${status.badge}">${status.label}</span>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        ${monitor.interval || 0} detik
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        ${monitor.timeout || 0} detik
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                        ${createdAt}
                    </td>
                </tr>
                <!-- Expandable Detail Row -->
                <tr id="detail-${monitor.id}" class="hidden bg-gray-50">
                    <td colspan="8" class="px-6 py-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                            <!-- Basic Information -->
                            <div>
                                <h4 class="mb-3 text-sm font-semibold text-gray-700 uppercase tracking-wider">Informasi Dasar</h4>
                                <dl class="space-y-2">
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Monitor ID</dt>
                                        <dd class="mt-1 text-sm text-gray-900">${monitor.monitor_id || '-'}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">URL</dt>
                                        <dd class="mt-1 text-sm text-gray-900 break-all">${monitor.url || '-'}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Sub Type</dt>
                                        <dd class="mt-1 text-sm text-gray-900">${monitor.sub_type || '-'}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Port</dt>
                                        <dd class="mt-1 text-sm text-gray-900">${monitor.port || '-'}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Monitoring Settings -->
                            <div>
                                <h4 class="mb-3 text-sm font-semibold text-gray-700 uppercase tracking-wider">Pengaturan Monitoring</h4>
                                <dl class="space-y-2">
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Interval</dt>
                                        <dd class="mt-1 text-sm text-gray-900">${monitor.interval || 0} detik</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Timeout</dt>
                                        <dd class="mt-1 text-sm text-gray-900">${monitor.timeout || 0} detik</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Status</dt>
                                        <dd class="mt-1">
                                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold ${status.badge}">${status.label}</span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            ${monitor.type == 2 ? `
                                                                                <!-- Keyword Settings -->
                                                                                <div>
                                                                                    <h4 class="mb-3 text-sm font-semibold text-gray-700 uppercase tracking-wider">Pengaturan Keyword</h4>
                                                                                    <dl class="space-y-2">
                                                                                        <div>
                                                                                            <dt class="text-xs font-medium text-gray-500">Keyword Type</dt>
                                                                                            <dd class="mt-1 text-sm text-gray-900">${monitor.keyword_type || '-'}</dd>
                                                                                        </div>
                                                                                        <div>
                                                                                            <dt class="text-xs font-medium text-gray-500">Keyword Case Type</dt>
                                                                                            <dd class="mt-1 text-sm text-gray-900">${monitor.keyword_case_type || '-'}</dd>
                                                                                        </div>
                                                                                        <div>
                                                                                            <dt class="text-xs font-medium text-gray-500">Keyword Value</dt>
                                                                                            <dd class="mt-1 text-sm text-gray-900 break-all">${monitor.keyword_value || '-'}</dd>
                                                                                        </div>
                                                                                    </dl>
                                                                                </div>
                                                                                ` : ''}

                            ${(monitor.http_username || monitor.http_password) ? `
                                                                                <!-- HTTP Authentication -->
                                                                                <div>
                                                                                    <h4 class="mb-3 text-sm font-semibold text-gray-700 uppercase tracking-wider">HTTP Authentication</h4>
                                                                                    <dl class="space-y-2">
                                                                                        <div>
                                                                                            <dt class="text-xs font-medium text-gray-500">Username</dt>
                                                                                            <dd class="mt-1 text-sm text-gray-900">${monitor.http_username || '-'}</dd>
                                                                                        </div>
                                                                                        <div>
                                                                                            <dt class="text-xs font-medium text-gray-500">Password</dt>
                                                                                            <dd class="mt-1 text-sm text-gray-900">${monitor.http_password ? '*'.repeat(Math.min(monitor.http_password.length, 10)) : '-'}</dd>
                                                                                        </div>
                                                                                    </dl>
                                                                                </div>
                                                                                ` : ''}

                            <!-- Timestamps -->
                            <div>
                                <h4 class="mb-3 text-sm font-semibold text-gray-700 uppercase tracking-wider">Waktu</h4>
                                <dl class="space-y-2">
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Dibuat</dt>
                                        <dd class="mt-1 text-sm text-gray-900">${createDatetime}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Created At</dt>
                                        <dd class="mt-1 text-sm text-gray-900">${createdAt}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Updated At</dt>
                                        <dd class="mt-1 text-sm text-gray-900">${updatedAt}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </td>
                </tr>
            `;
        }

        async function loadUrlGroups() {
            try {
                const response = await fetch(urlGroupsUrl);
                const result = await response.json();

                if (result.data && result.data.length > 0) {
                    const select = document.getElementById('filter-url-group');
                    select.innerHTML = '<option value="Semua">Semua</option>';
                    result.data.forEach(url => {
                        const option = document.createElement('option');
                        option.value = url;
                        option.textContent = url;
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading URL groups:', error);
            }
        }

        function resetAndReload() {
            // Clear existing data
            const tbody = document.getElementById('monitors-tbody');
            tbody.innerHTML =
                '<tr id="loading-row"><td colspan="8" class="px-6 py-12 text-center"><div class="flex flex-col items-center"><div class="h-8 w-8 animate-spin rounded-full border-4 border-indigo-200 border-t-indigo-600"></div><p class="mt-2 text-sm text-gray-500">Memuat monitor...</p></div></td></tr>';

            // Reset pagination and counters
            currentPage = 1;
            totalLoaded = 0;
            hasMore = true;
            isLoading = false;

            // Reload data
            loadMonitors();
        }

        function refreshData() {
            // Prevent multiple simultaneous refreshes
            if (isLoading) return;

            // Reset all filters to default
            filters.status = 'Semua';
            filters.type = 'Semua';
            filters.urlGroup = 'Semua';
            filters.search = '';
            filters.dateFrom = '';
            filters.dateTo = '';

            // Reset filter UI elements
            document.getElementById('filter-status').value = 'Semua';
            document.getElementById('filter-type').value = 'Semua';
            document.getElementById('filter-url-group').value = 'Semua';
            document.getElementById('filter-search').value = '';
            document.getElementById('filter-date-from').value = '';
            document.getElementById('filter-date-to').value = '';

            // Show loading animation on refresh button
            const refreshBtn = document.getElementById('refresh-btn');
            const refreshIcon = document.getElementById('refresh-icon');
            refreshBtn.disabled = true;
            refreshIcon.classList.add('animate-spin');

            // Reload URL groups
            loadUrlGroups();

            // Reset and reload data
            resetAndReload();

            // Remove loading animation when data is loaded
            // We'll check periodically if loading is done
            const checkLoading = setInterval(() => {
                if (!isLoading) {
                    refreshBtn.disabled = false;
                    refreshIcon.classList.remove('animate-spin');
                    clearInterval(checkLoading);
                }
            }, 100);
        }

        async function loadMonitors() {
            if (isLoading || !hasMore) return;

            isLoading = true;
            const loadingMore = document.getElementById('loading-more');
            const tbody = document.getElementById('monitors-tbody');
            const loadingRow = document.getElementById('loading-row');
            const emptyState = document.getElementById('empty-state');

            try {
                // Show loading more indicator (hide initial loading)
                if (currentPage === 1) {
                    loadingRow.style.display = 'table-row';
                } else {
                    loadingMore.classList.remove('hidden');
                }

                // Build query parameters
                const params = new URLSearchParams({
                    page: currentPage,
                    per_page: perPage,
                });

                if (filters.status && filters.status !== 'Semua') {
                    params.append('status', filters.status);
                }
                if (filters.type && filters.type !== 'Semua') {
                    params.append('type', filters.type);
                }
                if (filters.urlGroup && filters.urlGroup !== 'Semua') {
                    params.append('url_group', filters.urlGroup);
                }
                if (filters.search) {
                    params.append('search', filters.search);
                }
                if (filters.dateFrom) {
                    params.append('date_from', filters.dateFrom);
                }
                if (filters.dateTo) {
                    params.append('date_to', filters.dateTo);
                }

                const response = await fetch(`${apiUrl}?${params.toString()}`);
                const result = await response.json();

                if (!response.ok) {
                    throw new Error('Failed to load monitors');
                }

                // Hide loading indicators
                loadingRow.style.display = 'none';
                loadingMore.classList.add('hidden');

                if (result.data && result.data.length > 0) {
                    // Render monitors
                    const fragment = document.createDocumentFragment();
                    const tempDiv = document.createElement('tbody');

                    result.data.forEach((monitor, index) => {
                        tempDiv.innerHTML = renderMonitorRow(monitor, index);
                        const rows = tempDiv.querySelectorAll('tr');
                        rows.forEach(row => fragment.appendChild(row.cloneNode(true)));
                    });

                    tbody.appendChild(fragment);

                    // Update total loaded count
                    totalLoaded += result.data.length;

                    // Re-attach expand button listeners
                    attachExpandListeners();

                    currentPage++;
                    hasMore = result.has_more;

                    // Hide empty state if we have data
                    emptyState.classList.add('hidden');

                    // If page is not tall enough to scroll and has more data, load more immediately
                    // This ensures we always have enough content to scroll
                    requestAnimationFrame(() => {
                        const documentHeight = document.documentElement.scrollHeight || document.body
                            .scrollHeight;
                        const windowHeight = window.innerHeight || document.documentElement.clientHeight;
                        if (documentHeight <= windowHeight && hasMore && !isLoading) {
                            loadMonitors();
                        }
                    });
                } else {
                    hasMore = false;
                    if (currentPage === 1) {
                        // Show empty state only on first load
                        emptyState.classList.remove('hidden');
                        loadingRow.style.display = 'none';
                    } else {
                        // Hide loading more when no more data
                        loadingMore.classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('Error loading monitors:', error);
                loadingRow.innerHTML = `
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="h-12 w-12 text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-base font-medium text-gray-900">Error memuat data</p>
                            <p class="mt-1 text-sm text-gray-500">Silakan refresh halaman</p>
                        </div>
                    </td>
                `;
                loadingMore.classList.add('hidden');
            } finally {
                isLoading = false;
            }
        }

        function attachExpandListeners() {
            document.querySelectorAll('.expand-btn').forEach(btn => {
                // Remove existing listeners to prevent duplicates
                const newBtn = btn.cloneNode(true);
                btn.parentNode.replaceChild(newBtn, btn);

                newBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const id = this.getAttribute('data-id');
                    toggleRow(id);
                });
            });
        }

        function toggleRow(id) {
            const detailRow = document.getElementById('detail-' + id);
            const icon = document.getElementById('icon-' + id);

            if (detailRow && icon) {
                if (detailRow.classList.contains('hidden')) {
                    detailRow.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                } else {
                    detailRow.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                }
            }
        }

        // Infinite scroll handler
        function handleScroll() {
            if (isLoading || !hasMore) return;

            // Get scroll position and document height
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
            const windowHeight = window.innerHeight || document.documentElement.clientHeight;
            const documentHeight = document.documentElement.scrollHeight || document.body.scrollHeight;

            // Load more when user is 400px from bottom (threshold)
            const threshold = 400;
            const distanceFromBottom = documentHeight - (scrollTop + windowHeight);

            if (distanceFromBottom <= threshold) {
                loadMonitors();
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Load URL groups
            loadUrlGroups();

            // Load initial data
            loadMonitors();

            // Use Intersection Observer for better performance
            const scrollTrigger = document.getElementById('scroll-trigger');
            if (scrollTrigger) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && !isLoading && hasMore) {
                            loadMonitors();
                        }
                    });
                }, {
                    root: null,
                    rootMargin: '400px',
                    threshold: 0.1
                });

                observer.observe(scrollTrigger);
            }

            // Fallback: Add scroll listener with throttling for older browsers
            let scrollTimeout;
            window.addEventListener('scroll', function() {
                if (scrollTimeout) {
                    clearTimeout(scrollTimeout);
                }
                scrollTimeout = setTimeout(handleScroll, 100);
            }, {
                passive: true
            });

            // Add filter event listeners
            document.getElementById('filter-status').addEventListener('change', function() {
                filters.status = this.value;
                resetAndReload();
            });

            document.getElementById('filter-type').addEventListener('change', function() {
                filters.type = this.value;
                resetAndReload();
            });

            document.getElementById('filter-url-group').addEventListener('change', function() {
                filters.urlGroup = this.value;
                resetAndReload();
            });

            document.getElementById('filter-date-from').addEventListener('change', function() {
                filters.dateFrom = this.value;
                resetAndReload();
            });

            document.getElementById('filter-date-to').addEventListener('change', function() {
                filters.dateTo = this.value;
                resetAndReload();
            });

            // Search with debounce
            document.getElementById('filter-search').addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    filters.search = this.value.trim();
                    resetAndReload();
                }, 500); // Wait 500ms after user stops typing
            });

            // Add refresh button listener
            document.getElementById('refresh-btn').addEventListener('click', function() {
                refreshData();
            });

            // Add export button listener
            document.getElementById('export-btn').addEventListener('click', function() {
                exportToExcel();
            });

            // Delete modal handlers
            const deleteModal = document.getElementById('delete-modal');
            const deleteBtn = document.getElementById('delete-btn');
            const deleteCancelBtn = document.getElementById('delete-cancel-btn');
            const deleteConfirmBtn = document.getElementById('delete-confirm-btn');
            const deleteForm = document.getElementById('delete-form');
            const deleteModalOverlay = document.getElementById('delete-modal-overlay');

            // Open modal
            deleteBtn.addEventListener('click', function() {
                deleteModal.classList.remove('hidden');
            });

            // Close modal on cancel
            deleteCancelBtn.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
                deleteForm.reset();
            });

            // Close modal on overlay click
            deleteModalOverlay.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
                deleteForm.reset();
            });

            // Handle form submission
            deleteConfirmBtn.addEventListener('click', function() {
                const startDate = document.getElementById('delete-start-datetime').value;
                const endDate = document.getElementById('delete-end-datetime').value;

                if (!startDate || !endDate) {
                    alert('Mohon isi kedua tanggal!');
                    return;
                }

                if (new Date(startDate) > new Date(endDate)) {
                    alert('Tanggal akhir harus lebih besar atau sama dengan tanggal awal!');
                    return;
                }

                if (!confirm(
                        'Apakah Anda yakin ingin menghapus data monitor dari tanggal tersebut? Tindakan ini tidak dapat dibatalkan!'
                    )) {
                    return;
                }

                // Disable button
                deleteConfirmBtn.disabled = true;
                deleteConfirmBtn.innerHTML =
                    '<svg class="animate-spin h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> Menghapus...';

                // Submit delete request
                fetch('{{ route('monitors.delete-by-date-range') }}', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            start_date: startDate,
                            end_date: endDate
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message || 'Data berhasil dihapus');
                        deleteModal.classList.add('hidden');
                        deleteForm.reset();
                        resetAndReload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi error saat menghapus data');
                    })
                    .finally(() => {
                        deleteConfirmBtn.disabled = false;
                        deleteConfirmBtn.innerHTML = 'Hapus';
                    });
            });
        });

        function exportToExcel() {
            const exportBtn = document.getElementById('export-btn');
            const originalText = exportBtn.innerHTML;

            // Disable button and show loading
            exportBtn.disabled = true;
            exportBtn.innerHTML = `
                <svg class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Mengekspor...</span>
            `;

            // Build query parameters from current filters
            const params = new URLSearchParams();

            if (filters.status && filters.status !== 'Semua') {
                params.append('status', filters.status);
            }
            if (filters.type && filters.type !== 'Semua') {
                params.append('type', filters.type);
            }
            if (filters.urlGroup && filters.urlGroup !== 'Semua') {
                params.append('url_group', filters.urlGroup);
            }
            if (filters.search) {
                params.append('search', filters.search);
            }
            if (filters.dateFrom) {
                params.append('date_from', filters.dateFrom);
            }
            if (filters.dateTo) {
                params.append('date_to', filters.dateTo);
            }

            // Submit export request
            const exportUrl = '{{ route('monitors.export') }}' + (params.toString() ? '?' + params.toString() : '');

            fetch(exportUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert('Export sedang diproses. Silakan cek di halaman Exports.');
                    // Re-enable button
                    exportBtn.disabled = false;
                    exportBtn.innerHTML = originalText;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi error saat memproses export');
                    // Re-enable button
                    exportBtn.disabled = false;
                    exportBtn.innerHTML = originalText;
                });
        }
    </script>
</x-layouts.app>
