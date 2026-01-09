<x-layouts.app>
    <div class="mx-auto max-w-7xl">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500">Ringkasan status monitor dan aktivitas terbaru</p>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <div class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></div>
                <span>Realtime</span>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
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
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Status</h2>
                <div class="h-80 flex items-center justify-center">
                    <canvas id="status-distribution-chart" class="max-w-full"></canvas>
                </div>
            </div>
            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Trend Status (1 Jam Terakhir)</h2>
                <div class="h-80">
                    <canvas id="status-trend-1hour-chart"></canvas>
                </div>
            </div>
            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Trend Status (1 Menit Terakhir)</h2>
                <div class="h-80">
                    <canvas id="status-trend-1minute-chart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Monitor Terbaru</h2>
                <div id="recent-monitors" class="space-y-4">
                    @forelse(array_slice($monitors, 0, 5) as $monitor)
                        @php
                            $statusDotClass = match ($monitor['status'] ?? 1) {
                                0 => 'bg-yellow-500',
                                1 => 'bg-gray-500',
                                2 => 'bg-green-500',
                                8 => 'bg-orange-500',
                                9 => 'bg-red-500',
                                default => 'bg-gray-500',
                            };

                            $typeLabel = match ($monitor['type'] ?? 1) {
                                1 => 'HTTP(S)',
                                2 => 'Keyword',
                                3 => 'Ping',
                                4 => 'Port',
                                default => 'Unknown',
                            };

                            $createTime = isset($monitor['created_at'])
                                ? \Carbon\Carbon::parse($monitor['created_at'])->diffForHumans()
                                : (isset($monitor['create_datetime'])
                                    ? \Carbon\Carbon::parse($monitor['create_datetime'])->diffForHumans()
                                    : '-');
                        @endphp
                        <div
                            class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                            <div class="flex items-center gap-3">
                                <div class="h-2 w-2 rounded-full {{ $statusDotClass }}"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $monitor['friendly_name'] ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ $typeLabel }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $createTime }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Tidak ada monitor</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Monitor</h2>
                <div id="status-monitors" class="space-y-4">
                    @forelse($monitors as $monitor)
                        @php
                            $statusDotClass = match ($monitor['status'] ?? 1) {
                                0 => 'bg-yellow-500',
                                1 => 'bg-gray-500',
                                2 => 'bg-green-500',
                                8 => 'bg-orange-500',
                                9 => 'bg-red-500',
                                default => 'bg-gray-500',
                            };

                            $statusBadgeClass = match ($monitor['status'] ?? 1) {
                                0 => 'bg-yellow-100 text-yellow-800',
                                1 => 'bg-gray-100 text-gray-800',
                                2 => 'bg-green-100 text-green-800',
                                8 => 'bg-orange-100 text-orange-800',
                                9 => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800',
                            };

                            $statusLabel = match ($monitor['status'] ?? 1) {
                                0 => 'Paused',
                                1 => 'Not Checked',
                                2 => 'Up',
                                8 => 'Seems Down',
                                9 => 'Down',
                                default => 'Unknown',
                            };
                        @endphp
                        <div
                            class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                            <div class="flex items-center gap-3">
                                <div class="h-2 w-2 rounded-full {{ $statusDotClass }}"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $monitor['friendly_name'] ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ $monitor['url'] ?? '-' }}</p>
                                </div>
                            </div>
                            <span
                                class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $statusBadgeClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Tidak ada monitor</p>
                    @endforelse
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
        const CHART_1HOUR_API_URL = '{{ route('dashboard.chart.1hour') }}';
        const CHART_1MINUTE_API_URL = '{{ route('dashboard.chart.1minute') }}';

        // Chart instances
        let statusTrendChart = null;
        let statusDistributionChart = null;
        let statusTrend1HourChart = null;
        let statusTrend1MinuteChart = null;

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
            document.getElementById('stat-up').textContent = stats.up || 0;
            document.getElementById('stat-down').textContent = stats.down || 0;
            document.getElementById('stat-paused').textContent = stats.paused || 0;
        }

        function updateRecentMonitors(monitors) {
            const container = document.getElementById('recent-monitors');
            const recentMonitors = monitors.slice(0, 5);

            if (recentMonitors.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">Tidak ada monitor</p>';
                return;
            }

            container.innerHTML = recentMonitors.map(monitor => {
                const status = monitor.status || 1;
                const type = monitor.type || 1;
                return `
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                        <div class="flex items-center gap-3">
                            <div class="h-2 w-2 rounded-full ${getStatusDotClass(status)}"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">${monitor.friendly_name || 'Unknown'}</p>
                                <p class="text-xs text-gray-500">${getTypeLabel(type)}</p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">${formatTime(monitor.created_at || monitor.create_datetime)}</span>
                    </div>
                `;
            }).join('');
        }

        function updateStatusMonitors(monitors) {
            const container = document.getElementById('status-monitors');

            if (monitors.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">Tidak ada monitor</p>';
                return;
            }

            container.innerHTML = monitors.map(monitor => {
                const status = monitor.status || 1;
                return `
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                        <div class="flex items-center gap-3">
                            <div class="h-2 w-2 rounded-full ${getStatusDotClass(status)}"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">${monitor.friendly_name || 'Unknown'}</p>
                                <p class="text-xs text-gray-500">${monitor.url || '-'}</p>
                            </div>
                        </div>
                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold ${getStatusBadgeClass(status)}">
                            ${getStatusLabel(status)}
                        </span>
                    </div>
                `;
            }).join('');
        }

        async function fetchDashboardData() {
            try {
                const response = await fetch(API_URL);
                const data = await response.json();

                if (data.success) {
                    updateStats(data.stats);
                    updateRecentMonitors(data.monitors);
                    updateStatusMonitors(data.monitors);
                }
            } catch (error) {
                console.error('Error fetching dashboard data:', error);
            }
        }

        async function fetchChartData() {
            try {
                const response = await fetch(CHART_API_URL);
                const result = await response.json();

                if (result.success && result.data) {
                    updateTrendChart(result.data);
                    updateDistributionChart(result.data);
                }
            } catch (error) {
                console.error('Error fetching chart data:', error);
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
                        datasets: [
                            {
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

        function updateDistributionChart(chartData) {
            const ctx = document.getElementById('status-distribution-chart');
            if (!ctx) return;

            // Get latest data point
            const latestData = chartData[chartData.length - 1]?.stats || {
                up: 0,
                down: 0,
                paused: 0,
                not_checked: 0,
            };

            if (statusDistributionChart) {
                statusDistributionChart.data.datasets[0].data = [
                    latestData.up,
                    latestData.down,
                    latestData.paused,
                    latestData.not_checked,
                ];
                statusDistributionChart.update('none');
            } else {
                statusDistributionChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Online', 'Offline', 'Paused', 'Not Checked'],
                        datasets: [
                            {
                                data: [
                                    latestData.up,
                                    latestData.down,
                                    latestData.paused,
                                    latestData.not_checked,
                                ],
                                backgroundColor: [
                                    'rgb(34, 197, 94)',
                                    'rgb(239, 68, 68)',
                                    'rgb(234, 179, 8)',
                                    'rgb(107, 114, 128)',
                                ],
                                borderWidth: 2,
                                borderColor: '#fff',
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        resizeDelay: 0,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return `${label}: ${value} (${percentage}%)`;
                                    },
                                },
                            },
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

        function updateTrendChart1Hour(chartData) {
            const ctx = document.getElementById('status-trend-1hour-chart');
            if (!ctx) return;

            const labels = chartData.map(item => item.time);
            const upData = chartData.map(item => item.stats.up);
            const downData = chartData.map(item => item.stats.down);
            const pausedData = chartData.map(item => item.stats.paused);
            const notCheckedData = chartData.map(item => item.stats.not_checked);

            if (statusTrend1HourChart) {
                statusTrend1HourChart.data.labels = labels;
                statusTrend1HourChart.data.datasets[0].data = upData;
                statusTrend1HourChart.data.datasets[1].data = downData;
                statusTrend1HourChart.data.datasets[2].data = pausedData;
                statusTrend1HourChart.data.datasets[3].data = notCheckedData;
                statusTrend1HourChart.update('none');
            } else {
                statusTrend1HourChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
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

        function updateTrendChart1Minute(chartData) {
            const ctx = document.getElementById('status-trend-1minute-chart');
            if (!ctx) return;

            const labels = chartData.map(item => item.time);
            const upData = chartData.map(item => item.stats.up);
            const downData = chartData.map(item => item.stats.down);
            const pausedData = chartData.map(item => item.stats.paused);
            const notCheckedData = chartData.map(item => item.stats.not_checked);

            if (statusTrend1MinuteChart) {
                statusTrend1MinuteChart.data.labels = labels;
                statusTrend1MinuteChart.data.datasets[0].data = upData;
                statusTrend1MinuteChart.data.datasets[1].data = downData;
                statusTrend1MinuteChart.data.datasets[2].data = pausedData;
                statusTrend1MinuteChart.data.datasets[3].data = notCheckedData;
                statusTrend1MinuteChart.update('none');
            } else {
                statusTrend1MinuteChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
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

        async function fetchChartData1Hour() {
            try {
                const response = await fetch(CHART_1HOUR_API_URL);
                const result = await response.json();

                if (result.success && result.data) {
                    updateTrendChart1Hour(result.data);
                }
            } catch (error) {
                console.error('Error fetching 1 hour chart data:', error);
            }
        }

        async function fetchChartData1Minute() {
            try {
                const response = await fetch(CHART_1MINUTE_API_URL);
                const result = await response.json();

                if (result.success && result.data) {
                    updateTrendChart1Minute(result.data);
                }
            } catch (error) {
                console.error('Error fetching 1 minute chart data:', error);
            }
        }

        // Fetch data setiap 10 detik
        setInterval(fetchDashboardData, 10000);
        setInterval(fetchChartData, 10000);
        setInterval(fetchChartData1Hour, 10000);
        setInterval(fetchChartData1Minute, 5000); // Update lebih sering untuk 1 menit

        // Fetch data saat halaman dimuat
        fetchDashboardData();
        fetchChartData();
        fetchChartData1Hour();
        fetchChartData1Minute();
    </script>
</x-layouts.app>
