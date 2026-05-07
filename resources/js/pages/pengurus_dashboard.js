const token = localStorage.getItem('auth_token');
    const role = localStorage.getItem('user_role');
    
    const validRoles = ['admin', 'admin_wilayah', 'petugas', 'pengurus'];
    if (!token || !validRoles.includes(role)) window.location.href = '/login';

    let mainChartObj = null;
    let genderChartObj = null;
    let occupationChartObj = null;

    document.addEventListener('DOMContentLoaded', () => {
        updateDashboard();
    });



    async function updateDashboard() {
        try {
            // Gunakan API dashboard bersama (dengan prefix member/)
            const res = await axios.get(`member/dashboard?range=12`);
            const d = res.data.data;
            
            // Set initials
            const name = localStorage.getItem('user_name') || 'Pengurus';
            if(document.getElementById('user-initials')) document.getElementById('user-initials').innerText = name.substring(0, 2).toUpperCase();

            if(document.getElementById('count-total')) document.getElementById('count-total').innerText = d.summary.total_members.toLocaleString('id-ID');
            if(document.getElementById('count-month')) document.getElementById('count-month').innerText = d.summary.new_this_month.toLocaleString('id-ID');
            if(document.getElementById('count-info')) document.getElementById('count-info').innerText = d.summary.total_provinces;

            renderMainChart(d.growth);
            // Gender
            const genderLabels = Object.keys(d.distribution.gender).map(k => k === 'L' ? 'Laki-laki' : 'Perempuan');
            renderPieChart('genderChart', genderLabels, Object.values(d.distribution.gender), ['#3b82f6', '#f43f5e']);
            // Occupation
            renderPieChart('occupationChart', Object.keys(d.distribution.occupation), Object.values(d.distribution.occupation), ['#6366f1', '#8b5cf6', '#ec4899', '#f97316', '#10b981', '#64748b', '#94a3b8']);
        } catch (e) {
            console.error(e);
            window.showToast('Gagal memuat data dashboard', 'error');
        }
    }

    function renderMainChart(data) {
        if (mainChartObj) mainChartObj.destroy();
        const ctx = document.getElementById('mainChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(0, 74, 173, 0.2)');
        gradient.addColorStop(1, 'rgba(0, 74, 173, 0)');

        mainChartObj = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(i => i.month),
                datasets: [{
                    label: 'Registrasi Baru',
                    data: data.map(i => i.total),
                    borderColor: '#004aad',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#004aad',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { 
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }

    function renderPieChart(id, labels, data, colors) {
        const ctx = document.getElementById(id).getContext('2d');
        const chartMap = { genderChartObj, occupationChartObj };
        if (chartMap[id]) chartMap[id].destroy();
        
        const newChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15, font: { size: 10, weight: '600' } } } }
            }
        });

        if (id === 'genderChart') genderChartObj = newChart;
        if (id === 'occupationChart') occupationChartObj = newChart;
    }

    // logout is handled globally by pengurus-layout