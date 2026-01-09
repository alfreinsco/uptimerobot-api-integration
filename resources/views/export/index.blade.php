<x-layouts.app>
    <div class="mx-auto max-w-7xl">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Exports</h1>
            <p class="mt-1 text-sm text-gray-500">Daftar file export yang telah dibuat</p>
        </div>

        <!-- Filters -->
        <div class="mb-6 flex flex-wrap items-center gap-4 rounded-lg bg-white p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Dari Tanggal:</label>
                <input type="date" id="filter-start-date"
                    class="filter-date rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Sampai Tanggal:</label>
                <input type="date" id="filter-end-date"
                    class="filter-date rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <button id="filter-apply-btn" type="button"
                class="ml-auto rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                Terapkan Filter
            </button>
        </div>

        <!-- Exports Table -->
        <div class="overflow-hidden rounded-lg bg-white shadow-sm border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="w-16 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                No
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Filename
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Total Records
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Created At
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody id="exports-tbody" class="divide-y divide-gray-200 bg-white">
                        <!-- Loading indicator -->
                        <tr id="loading-row">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="h-8 w-8 animate-spin rounded-full border-4 border-indigo-200 border-t-indigo-600">
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Memuat exports...</p>
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
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-4 text-base font-medium text-gray-900">Tidak ada export</p>
                <p class="mt-1 text-sm text-gray-500">Belum ada file export yang dibuat.</p>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let isLoading = false;
        let hasMore = true;
        let totalLoaded = 0;
        const perPage = 20;
        const apiUrl = '{{ route('exports.api') }}';

        // Filter state
        const filters = {
            startDate: '',
            endDate: '',
        };

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

        function getStatusClasses(status) {
            const statusMap = {
                'pending': {
                    badge: 'bg-yellow-100 text-yellow-800',
                    label: 'Pending'
                },
                'processing': {
                    badge: 'bg-blue-100 text-blue-800',
                    label: 'Processing'
                },
                'completed': {
                    badge: 'bg-green-100 text-green-800',
                    label: 'Completed'
                },
                'failed': {
                    badge: 'bg-red-100 text-red-800',
                    label: 'Failed'
                },
            };
            return statusMap[status] || {
                badge: 'bg-gray-100 text-gray-800',
                label: 'Unknown'
            };
        }

        function renderExportRow(exportItem, index) {
            const status = getStatusClasses(exportItem.status);
            const createdAt = formatDateTime(exportItem.created_at);
            const rowNumber = totalLoaded + index + 1;

            return `
                <tr class="hover:bg-gray-50">
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 text-center">
                        ${rowNumber}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        ${exportItem.filename}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4">
                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold ${status.badge}">${status.label}</span>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        ${exportItem.total_records || 0}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                        ${createdAt}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                        <div class="flex items-center gap-2">
                            ${exportItem.status === 'completed' ? `
                                <a href="/exports/${exportItem.id}/download"
                                    class="text-indigo-600 hover:text-indigo-900 font-medium">
                                    Download
                                </a>
                            ` : ''}
                            <button type="button" onclick="deleteExport(${exportItem.id})"
                                class="text-red-600 hover:text-red-900 font-medium">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }

        async function loadExports() {
            if (isLoading || !hasMore) return;

            isLoading = true;
            const loadingMore = document.getElementById('loading-more');
            const tbody = document.getElementById('exports-tbody');
            const loadingRow = document.getElementById('loading-row');
            const emptyState = document.getElementById('empty-state');

            try {
                if (currentPage === 1) {
                    loadingRow.style.display = 'table-row';
                } else {
                    loadingMore.classList.remove('hidden');
                }

                const params = new URLSearchParams({
                    page: currentPage,
                    per_page: perPage,
                });

                if (filters.startDate) {
                    params.append('start_date', filters.startDate);
                }
                if (filters.endDate) {
                    params.append('end_date', filters.endDate);
                }

                const response = await fetch(`${apiUrl}?${params.toString()}`);
                const result = await response.json();

                if (!response.ok) {
                    throw new Error('Failed to load exports');
                }

                loadingRow.style.display = 'none';
                loadingMore.classList.add('hidden');

                if (result.data && result.data.length > 0) {
                    const fragment = document.createDocumentFragment();
                    const tempDiv = document.createElement('tbody');

                    result.data.forEach((exportItem, index) => {
                        tempDiv.innerHTML = renderExportRow(exportItem, index);
                        const rows = tempDiv.querySelectorAll('tr');
                        rows.forEach(row => fragment.appendChild(row.cloneNode(true)));
                    });

                    tbody.appendChild(fragment);

                    totalLoaded += result.data.length;

                    currentPage++;
                    hasMore = result.has_more;

                    emptyState.classList.add('hidden');
                } else {
                    hasMore = false;
                    if (currentPage === 1) {
                        emptyState.classList.remove('hidden');
                        loadingRow.style.display = 'none';
                    } else {
                        loadingMore.classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('Error loading exports:', error);
                loadingRow.innerHTML = `
                    <td colspan="6" class="px-6 py-12 text-center">
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

        function resetAndReload() {
            const tbody = document.getElementById('exports-tbody');
            tbody.innerHTML =
                '<tr id="loading-row"><td colspan="6" class="px-6 py-12 text-center"><div class="flex flex-col items-center"><div class="h-8 w-8 animate-spin rounded-full border-4 border-indigo-200 border-t-indigo-600"></div><p class="mt-2 text-sm text-gray-500">Memuat exports...</p></div></td></tr>';

            currentPage = 1;
            totalLoaded = 0;
            hasMore = true;
            isLoading = false;

            loadExports();
        }

        function handleScroll() {
            if (isLoading || !hasMore) return;

            const scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
            const windowHeight = window.innerHeight || document.documentElement.clientHeight;
            const documentHeight = document.documentElement.scrollHeight || document.body.scrollHeight;

            const threshold = 400;
            const distanceFromBottom = documentHeight - (scrollTop + windowHeight);

            if (distanceFromBottom <= threshold) {
                loadExports();
            }
        }

        async function deleteExport(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus export ini?')) {
                return;
            }

            try {
                const response = await fetch(`/exports/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    resetAndReload();
                } else {
                    alert('Gagal menghapus export');
                }
            } catch (error) {
                console.error('Error deleting export:', error);
                alert('Terjadi error saat menghapus export');
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadExports();

            const scrollTrigger = document.getElementById('scroll-trigger');
            if (scrollTrigger) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && !isLoading && hasMore) {
                            loadExports();
                        }
                    });
                }, {
                    root: null,
                    rootMargin: '400px',
                    threshold: 0.1
                });

                observer.observe(scrollTrigger);
            }

            let scrollTimeout;
            window.addEventListener('scroll', function() {
                if (scrollTimeout) {
                    clearTimeout(scrollTimeout);
                }
                scrollTimeout = setTimeout(handleScroll, 100);
            }, {
                passive: true
            });

            document.getElementById('filter-apply-btn').addEventListener('click', function() {
                filters.startDate = document.getElementById('filter-start-date').value;
                filters.endDate = document.getElementById('filter-end-date').value;
                resetAndReload();
            });
        });
    </script>
</x-layouts.app>
