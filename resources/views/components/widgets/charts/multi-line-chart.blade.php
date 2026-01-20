<div class="bg-white rounded-lg border border-gray-200 p-6" x-data="multiLineChart()">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Analytics Overview</h3>
        
        <!-- Time Period Selector -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" type="button"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white flex items-center justify-between gap-2 min-w-[150px]">
                <span x-text="period == '7' ? 'Last 7 Days' : period == '30' ? 'Last 30 Days' : period == '90' ? 'Last 3 Months' : period == '180' ? 'Last 6 Months' : 'Last Year'"></span>
                <svg class="w-4 h-4 text-gray-600 transition-transform duration-200" :class="open ? 'rotate-90' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div x-show="open" @click.away="open = false" x-transition
                 class="absolute left-0 sm:right-0 sm:left-auto mt-2 w-full sm:w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
                <button @click="period = '7'; updateChart(); open = false" type="button"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Last 7 Days</button>
                <button @click="period = '30'; updateChart(); open = false" type="button"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Last 30 Days</button>
                <button @click="period = '90'; updateChart(); open = false" type="button"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Last 3 Months</button>
                <button @click="period = '180'; updateChart(); open = false" type="button"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Last 6 Months</button>
                <button @click="period = '365'; updateChart(); open = false" type="button"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Last Year</button>
            </div>
        </div>
    </div>

    <!-- Line Toggles -->
    <div class="flex flex-wrap gap-4 mb-6">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" x-model="showRevenue" @change="toggleLine('revenue')" class="w-4 h-4 text-blue-600 rounded">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                <span class="text-sm font-medium text-gray-700">Revenue</span>
            </div>
        </label>
        
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" x-model="showOrders" @change="toggleLine('orders')" class="w-4 h-4 text-green-600 rounded">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-green-500"></span>
                <span class="text-sm font-medium text-gray-700">Orders</span>
            </div>
        </label>
        
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" x-model="showUsers" @change="toggleLine('users')" class="w-4 h-4 text-purple-600 rounded">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                <span class="text-sm font-medium text-gray-700">New Users</span>
            </div>
        </label>
        
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" x-model="showProducts" @change="toggleLine('products')" class="w-4 h-4 text-orange-600 rounded">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                <span class="text-sm font-medium text-gray-700">Products Added</span>
            </div>
        </label>
    </div>

    <!-- Chart Canvas -->
    <div class="relative" style="height: 400px;">
        <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-2"></div>
                <p class="text-sm text-gray-600">Loading chart data...</p>
            </div>
        </div>
        <canvas x-ref="chart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
function multiLineChart() {
    return {
        period: '7',
        showRevenue: true,
        showOrders: true,
        showUsers: false,
        showProducts: false,
        chart: null,
        loading: false,
        
        init() {
            this.createChart();
            this.updateChart();
        },
        
        createChart() {
            const ctx = this.$refs.chart.getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Revenue (₦)',
                            data: [],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.1,
                            fill: false,
                            borderWidth: 4,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointBackgroundColor: 'rgb(59, 130, 246)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 3,
                            hidden: !this.showRevenue
                        },
                        {
                            label: 'Orders',
                            data: [],
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.1,
                            fill: false,
                            borderWidth: 4,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointBackgroundColor: 'rgb(34, 197, 94)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 3,
                            hidden: !this.showOrders
                        },
                        {
                            label: 'New Users',
                            data: [],
                            borderColor: 'rgb(168, 85, 247)',
                            backgroundColor: 'rgba(168, 85, 247, 0.1)',
                            tension: 0.1,
                            fill: false,
                            borderWidth: 4,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointBackgroundColor: 'rgb(168, 85, 247)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 3,
                            hidden: !this.showUsers
                        },
                        {
                            label: 'Products Added',
                            data: [],
                            borderColor: 'rgb(249, 115, 22)',
                            backgroundColor: 'rgba(249, 115, 22, 0.1)',
                            tension: 0.1,
                            fill: false,
                            borderWidth: 4,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointBackgroundColor: 'rgb(249, 115, 22)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 3,
                            hidden: !this.showProducts
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    scales: {
                        x: {
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grace: '5%',
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000) {
                                        return (value / 1000).toFixed(1) + 'K';
                                    }
                                    return value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        },
        
        async updateChart() {
            this.loading = true;
            try {
                const response = await fetch(`/management/portal/admin/analytics/chart-data?period=${this.period}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch data');
                }
                const data = await response.json();
                
                console.log('Chart data:', data); // Debug
                
                this.chart.data.labels = data.labels;
                this.chart.data.datasets[0].data = data.revenue;
                this.chart.data.datasets[1].data = data.orders;
                this.chart.data.datasets[2].data = data.users;
                this.chart.data.datasets[3].data = data.products;
                this.chart.update();
            } catch (error) {
                console.error('Error fetching chart data:', error);
                alert('Failed to load chart data. Please refresh the page.');
            } finally {
                this.loading = false;
            }
        },
        
        toggleLine(type) {
            const index = { revenue: 0, orders: 1, users: 2, products: 3 }[type];
            this.chart.data.datasets[index].hidden = !this[`show${type.charAt(0).toUpperCase() + type.slice(1)}`];
            this.chart.update();
        }
    }
}
</script>
