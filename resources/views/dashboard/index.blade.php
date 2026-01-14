<x-layouts.app>
    <div class="mx-auto max-w-7xl">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500">Ringkasan status monitor dan aktivitas terbaru</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 mb-6">
            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Monitors</p>
                        <p id="stat-total" class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                    <div class="rounded-full bg-indigo-100 p-3">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Data</p>
                        <p id="stat-total-data" class="mt-2 text-3xl font-bold text-blue-600">{{ $stats['total_data'] ?? 0 }}</p>
                    </div>
                    <div class="rounded-full bg-blue-100 p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Online</p>
                        <p id="stat-up" class="mt-2 text-3xl font-bold text-green-600">{{ $stats['up'] }}</p>
                    </div>
                    <div class="rounded-full bg-green-100 p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Offline</p>
                        <p id="stat-down" class="mt-2 text-3xl font-bold text-red-600">{{ $stats['down'] }}</p>
                    </div>
                    <div class="rounded-full bg-red-100 p-3">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Paused</p>
                        <p id="stat-paused" class="mt-2 text-3xl font-bold text-yellow-600">{{ $stats['paused'] }}</p>
                    </div>
                    <div class="rounded-full bg-yellow-100 p-3">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Trend Status (24 Jam Terakhir)</h2>
                <div class="h-80">
                    <canvas id="status-trend-chart"></canvas>
                </div>
            </div>
            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Trend Status (1 Minggu Terakhir)</h2>
                <div class="h-80">
                    <canvas id="status-trend-week-chart"></canvas>
                </div>
            </div>
            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Trend Status (1 Bulan Terakhir)</h2>
                <div class="h-80">
                    <canvas id="status-trend-month-chart"></canvas>
                </div>
            </div>
            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Trend Status (1 Tahun Terakhir)</h2>
                <div class="h-80">
                    <canvas id="status-trend-year-chart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Realtime update script -->
    <script>
        const API_URL = '{{ route('dashboard.api') }}';
        const CHART_API_URL = '{{ route('dashboard.chart') }}';
        const CHART_WEEK_API_URL = '{{ route('dashboard.chart.week') }}';
        const CHART_MONTH_API_URL = '{{ route('dashboard.chart.month') }}';
        const CHART_YEAR_API_URL = '{{ route('dashboard.chart.year') }}';

        // Chart instances
        let statusTrendChart = null;
        let statusTrendWeekChart = null;
        let statusTrendMonthChart = null;
        let statusTrendYearChart = null;

        // Retry configuration
        const MAX_RETRIES = 3;
        const RETRY_DELAYS = [2000, 5000, 10000]; // Exponential backoff

        // Helper function untuk fetch dengan error handling dan retry
        async function fetchWithRetry(url, options = {}) {
            let retryCount = 0;

            while (retryCount <= MAX_RETRIES) {
                try {
                    const response = await fetch(url, {
                        ...options,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            ...options.headers,
                        },
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    return data;
                } catch (error) {
                    if (retryCount < MAX_RETRIES) {
                        const delay = RETRY_DELAYS[retryCount] || 10000;
                        retryCount++;

                        console.warn(
                            `Request failed, retrying in ${delay}ms (attempt ${retryCount}/${MAX_RETRIES})`
                        );

                        await new Promise(resolve => setTimeout(resolve, delay));
                    } else {
                        console.error(`Request failed after ${MAX_RETRIES} retries:`, error);
                        return null;
                    }
                }
            }

            return null;
        }

        function getStatusDotClass(status) {
            const statusMap = {
                0: 'bg-yellow-500',
                1: 'bg-gray-500',
                2: 'bg-green-500',
                8: 'bg-orange-500',
                9: 'bg-red-500',
            };
            return statusMap[status] || 'bg-gray-500';
        }

        function getStatusBadgeClass(status) {
            const statusMap = {
                0: 'bg-yellow-100 text-yellow-800',
                1: 'bg-gray-100 text-gray-800',
                2: 'bg-green-100 text-green-800',
                8: 'bg-orange-100 text-orange-800',
                9: 'bg-red-100 text-red-800',
            };
            return statusMap[status] || 'bg-gray-100 text-gray-800';
        }

        function getStatusLabel(status) {
            const statusMap = {
                0: 'Paused',
                1: 'Not Checked',
                2: 'Up',
                8: 'Seems Down',
                9: 'Down',
            };
            return statusMap[status] || 'Unknown';
        }

        function getTypeLabel(type) {
            const typeMap = {
                1: 'HTTP(S)',
                2: 'Keyword',
                3: 'Ping',
                4: 'Port',
            };
            return typeMap[type] || 'Unknown';
        }

        function formatTime(timestamp) {
            if (!timestamp) return '-';
            const date = new Date(timestamp);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000);

            if (diff < 60) return 'Baru saja';
            if (diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
            if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
            return Math.floor(diff / 86400) + ' hari lalu';
        }

        function updateStats(stats) {
            document.getElementById('stat-total').textContent = stats.total || 0;
            document.getElementById('stat-total-data').textContent = stats.total_data || 0;
            document.getElementById('stat-up').textContent = stats.up || 0;
            document.getElementById('stat-down').textContent = stats.down || 0;
            document.getElementById('stat-paused').textContent = stats.paused || 0;
        }

        async function fetchDashboardData() {
            const data = await fetchWithRetry(API_URL);

            if (data && data.success) {
                updateStats(data.stats);
            }
        }

        async function fetchChartData() {
            const result = await fetchWithRetry(CHART_API_URL);

            if (result && result.success && result.data) {
                updateTrendChart(result.data);
            }
        }

        async function fetchChartDataWeek() {
            const result = await fetchWithRetry(CHART_WEEK_API_URL);

            if (result && result.success && result.data) {
                updateTrendChartWeek(result.data);
            }
        }

        async function fetchChartDataMonth() {
            const result = await fetchWithRetry(CHART_MONTH_API_URL);

            if (result && result.success && result.data) {
                updateTrendChartMonth(result.data);
            }
        }

        async function fetchChartDataYear() {
            const result = await fetchWithRetry(CHART_YEAR_API_URL);

            if (result && result.success && result.data) {
                updateTrendChartYear(result.data);
            }
        }

        function updateTrendChart(chartData) {
            const ctx = document.getElementById('status-trend-chart');
            if (!ctx) return;

            const labels = chartData.map(item => item.time);
            const upData = chartData.map(item => item.stats.up);
            const downData = chartData.map(item => item.stats.down);
            const pausedData = chartData.map(item => item.stats.paused);
            const notCheckedData = chartData.map(item => item.stats.not_checked);

            if (statusTrendChart) {
                statusTrendChart.data.labels = labels;
                statusTrendChart.data.datasets[0].data = upData;
                statusTrendChart.data.datasets[1].data = downData;
                statusTrendChart.data.datasets[2].data = pausedData;
                statusTrendChart.data.datasets[3].data = notCheckedData;
                statusTrendChart.update('none');
            } else {
                statusTrendChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Online',
                                data: upData,
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                tension: 0.4,
                                fill: true,
                            },
                            {
                                label: 'Offline',
                                data: downData,
                                borderColor: 'rgb(239, 68, 68)',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                tension: 0.4,
                                fill: true,
                            },
                            {
                                label: 'Paused',
                                data: pausedData,
                                borderColor: 'rgb(234, 179, 8)',
                                backgroundColor: 'rgba(234, 179, 8, 0.1)',
                                tension: 0.4,
                                fill: true,
                            },
                            {
                                label: 'Not Checked',
                                data: notCheckedData,
                                borderColor: 'rgb(107, 114, 128)',
                                backgroundColor: 'rgba(107, 114, 128, 0.1)',
                                tension: 0.4,
                                fill: true,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        resizeDelay: 0,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                },
                            },
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false,
                        },
                        layout: {
                            padding: {
                                top: 10,
                                bottom: 10,
                            },
                        },
                    },
                });
            }
        }

        function updateTrendChartWeek(chartData) {
            const ctx = document.getElementById('status-trend-week-chart');
            if (!ctx) return;

            const labels = chartData.map(item => item.time);
            const upData = chartData.map(item => item.stats.up);
            const downData = chartData.map(item => item.stats.down);
            const pausedData = chartData.map(item => item.stats.paused);
            const notCheckedData = chartData.map(item => item.stats.not_checked);

            if (statusTrendWeekChart) {
                statusTrendWeekChart.data.labels = labels;
                statusTrendWeekChart.data.datasets[0].data = upData;
                statusTrendWeekChart.data.datasets[1].data = downData;
                statusTrendWeekChart.data.datasets[2].data = pausedData;
                statusTrendWeekChart.data.datasets[3].data = notCheckedData;
                statusTrendWeekChart.update('none');
            } else {
                statusTrendWeekChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Online',
                            data: upData,
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }, {
                            label: 'Offline',
                            data: downData,
                            borderColor: 'rgb(239, 68, 68)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }, {
                            label: 'Paused',
                            data: pausedData,
                            borderColor: 'rgb(234, 179, 8)',
                            backgroundColor: 'rgba(234, 179, 8, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }, {
                            label: 'Not Checked',
                            data: notCheckedData,
                            borderColor: 'rgb(107, 114, 128)',
                            backgroundColor: 'rgba(107, 114, 128, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        resizeDelay: 0,
                        animation: {
                            duration: 0,
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                },
                            },
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false,
                        },
                        layout: {
                            padding: {
                                top: 10,
                                bottom: 10,
                            },
                        },
                    },
                });
            }
        }

        function updateTrendChartMonth(chartData) {
            const ctx = document.getElementById('status-trend-month-chart');
            if (!ctx) return;

            const labels = chartData.map(item => item.time);
            const upData = chartData.map(item => item.stats.up);
            const downData = chartData.map(item => item.stats.down);
            const pausedData = chartData.map(item => item.stats.paused);
            const notCheckedData = chartData.map(item => item.stats.not_checked);

            if (statusTrendMonthChart) {
                statusTrendMonthChart.data.labels = labels;
                statusTrendMonthChart.data.datasets[0].data = upData;
                statusTrendMonthChart.data.datasets[1].data = downData;
                statusTrendMonthChart.data.datasets[2].data = pausedData;
                statusTrendMonthChart.data.datasets[3].data = notCheckedData;
                statusTrendMonthChart.update('none');
            } else {
                statusTrendMonthChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Online',
                            data: upData,
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }, {
                            label: 'Offline',
                            data: downData,
                            borderColor: 'rgb(239, 68, 68)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }, {
                            label: 'Paused',
                            data: pausedData,
                            borderColor: 'rgb(234, 179, 8)',
                            backgroundColor: 'rgba(234, 179, 8, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }, {
                            label: 'Not Checked',
                            data: notCheckedData,
                            borderColor: 'rgb(107, 114, 128)',
                            backgroundColor: 'rgba(107, 114, 128, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        resizeDelay: 0,
                        animation: {
                            duration: 0,
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                },
                            },
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false,
                        },
                        layout: {
                            padding: {
                                top: 10,
                                bottom: 10,
                            },
                        },
                    },
                });
            }
        }


        function updateTrendChartYear(chartData) {
            const ctx = document.getElementById('status-trend-year-chart');
            if (!ctx) return;

            const labels = chartData.map(item => item.time);
            const upData = chartData.map(item => item.stats.up);
            const downData = chartData.map(item => item.stats.down);
            const pausedData = chartData.map(item => item.stats.paused);
            const notCheckedData = chartData.map(item => item.stats.not_checked);

            if (statusTrendYearChart) {
                statusTrendYearChart.data.labels = labels;
                statusTrendYearChart.data.datasets[0].data = upData;
                statusTrendYearChart.data.datasets[1].data = downData;
                statusTrendYearChart.data.datasets[2].data = pausedData;
                statusTrendYearChart.data.datasets[3].data = notCheckedData;
                statusTrendYearChart.update('none');
            } else {
                statusTrendYearChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Online',
                            data: upData,
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }, {
                            label: 'Offline',
                            data: downData,
                            borderColor: 'rgb(239, 68, 68)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }, {
                            label: 'Paused',
                            data: pausedData,
                            borderColor: 'rgb(234, 179, 8)',
                            backgroundColor: 'rgba(234, 179, 8, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }, {
                            label: 'Not Checked',
                            data: notCheckedData,
                            borderColor: 'rgb(107, 114, 128)',
                            backgroundColor: 'rgba(107, 114, 128, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        resizeDelay: 0,
                        animation: {
                            duration: 0,
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                },
                            },
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false,
                        },
                        layout: {
                            padding: {
                                top: 10,
                                bottom: 10,
                            },
                        },
                    },
                });
            }
        }

        // Fetch semua data sekali saat halaman dimuat
        async function fetchAllData() {
            await Promise.all([
                fetchDashboardData(),
                fetchChartData(),
                fetchChartDataWeek(),
                fetchChartDataMonth(),
                fetchChartDataYear(),
            ]);
        }

        // Initialize - fetch data sekali saat halaman dimuat
        fetchAllData();
    </script>
</x-layouts.app>
