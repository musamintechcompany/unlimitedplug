<div class="bg-white rounded-lg border border-gray-200 p-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Performance Analytics</h3>
        
        <select onchange="updatePeriod(this.value)" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="7" selected>Last 7 Days</option>
            <option value="30">Last 30 Days</option>
            <option value="90">Last 3 Months</option>
        </select>
    </div>

    <div style="position: relative; height: 400px;">
        <canvas id="analyticsChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
let analyticsChart;
let currentPeriod = '7';

function initChart() {
    const ctx = document.getElementById('analyticsChart').getContext('2d');
    
    analyticsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Orders',
                    data: [],
                    borderColor: '#4cc9f0',
                    backgroundColor: 'rgba(76, 201, 240, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#4cc9f0',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'New Users',
                    data: [],
                    borderColor: '#ff6b6b',
                    backgroundColor: 'rgba(255, 107, 107, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#ff6b6b',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 13 },
                    bodyFont: { size: 12 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grace: '10%',
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'nearest'
            }
        }
    });
    
    loadChartData();
}

async function loadChartData() {
    try {
        const response = await fetch(`/management/portal/admin/analytics/chart-data?period=${currentPeriod}`);
        const data = await response.json();
        
        analyticsChart.data.labels = data.labels;
        analyticsChart.data.datasets[0].data = data.orders;
        analyticsChart.data.datasets[1].data = data.users;
        analyticsChart.update('active');
    } catch (error) {
        console.error('Error loading chart data:', error);
    }
}

function updatePeriod(period) {
    currentPeriod = period;
    loadChartData();
}

// Initialize on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initChart);
} else {
    initChart();
}
</script>
