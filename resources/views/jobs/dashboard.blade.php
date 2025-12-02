@extends('layouts.main')

@section('content')
<div class="container-fluid">
    
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">📊 Dashboard - ระบบจัดการใบสมัคร</h2>
                <div class="text-muted">
                    <i class="bi bi-clock"></i> อัปเดตล่าสุด: {{ now()->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(count($alerts) > 0)
    <div class="row mb-4">
        <div class="col-md-12">
            @foreach($alerts as $alert)
            <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show" role="alert">
                <i class="bi bi-{{ $alert['type'] == 'warning' ? 'exclamation-triangle' : 'info-circle' }}"></i>
                {{ $alert['message'] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Main Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-1">{{ number_format($totalJobs) }}</h3>
                            <p class="mb-0">งานทั้งหมด</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-briefcase fs-1 opacity-75"></i>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <small>เปิด: {{ $activeJobs }}</small>
                        </div>
                        <div class="col-6">
                            <small>ปิด: {{ $closedJobs }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-1">{{ number_format($totalApplications) }}</h3>
                            <p class="mb-0">ใบสมัครทั้งหมด</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-file-earmark-text fs-1 opacity-75"></i>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <small>ล็อค: {{ $lockedLeads }}</small>
                        </div>
                        <div class="col-6">
                            <small>ว่าง: {{ $totalApplications - $lockedLeads }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-1">{{ number_format($applicationsByStatus['ตอบรับ'] ?? 0) }}</h3>
                            <p class="mb-0">ได้งานแล้ว</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle fs-1 opacity-75"></i>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <small>Success Rate: {{ $successRate }}%</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-1">{{ number_format($expiredJobs) }}</h3>
                            <p class="mb-0">งานหมดอายุ</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-clock-history fs-1 opacity-75"></i>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <small>ต้องติดตาม</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Application Status Chart -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">สถานะใบสมัคร</h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="applicationStatusChart"></canvas>
                    </div>
                    
                    <div class="row mt-3">
                        @foreach($applicationsByStatus as $status => $count)
                        @if($count > 0)
                        <div class="col-6 mb-2">
                            <div class="d-flex justify-content-between">
                                <span class="small">{{ $status }}:</span>
                                <span class="badge bg-secondary">{{ number_format($count) }}</span>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Countries -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">🏆 Top Countries</h5>
                </div>
                <div class="card-body">
                    @forelse($topCountries as $index => $country)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-1">
                                <span class="badge bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'dark') }} me-1">
                                    {{ $index + 1 }}
                                </span>
                                {{ $country['country_name'] }}
                            </h6>
                            <small class="text-muted">
                                {{ number_format($country['applications']) }} ใบสมัคร | 
                                {{ number_format($country['accepted']) }} ได้งาน
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="fs-5 fw-bold">{{ number_format($country['job_count']) }}</div>
                            <small class="text-muted">งาน</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-muted">ไม่มีข้อมูล</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trend -->
    @if($monthlyStats->count() > 0)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">📈 แนวโน้มรายเดือน (6 เดือนล่าสุด)</h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 250px; width: 100%;">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Jobs -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">งานล่าสุด</h5>
                    <a href="{{ route('jobs.index') }}" class="btn btn-sm btn-outline-primary">
                        ดูทั้งหมด <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>หมายเลขงาน</th>
                                    <th>ชื่องาน</th>
                                    <th>ประเทศ</th>
                                    <th>จำนวนรับ</th>
                                    <th>ใบสมัคร</th>
                                    <th>ได้งาน</th>
                                    <th>สถานะ</th>
                                    <th>วันที่สร้าง</th>
                                    <th>ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentJobs as $job)
                                <tr>
                                    <td>
                                        <strong>{{ $job->job_number }}</strong>
                                    </td>
                                    <td>{{ Str::limit($job->job_name, 30) }}</td>
                                    <td>{{ $job->country->country_name_th ?? 'N/A' }}</td>
                                    <td class="text-center">{{ number_format($job->job_total) }}</td>
                                    <td class="text-center">{{ number_format($job->job_leads_count) }}</td>
                                    <td class="text-center text-success">{{ number_format($job->accepted_count) }}</td>
                                    <td>
                                        <span class="badge {{ $job->job_status == 'เปิดรับสมัคร' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $job->job_status }}
                                        </span>
                                    </td>
                                    <td>{{ $job->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('jobs.show', $job->job_id) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="bi bi-inbox fs-1 text-muted"></i>
                                        <p class="text-muted">ไม่พบข้อมูลงาน</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.chart-container {
    position: relative;
    overflow: hidden;
}

.chart-container canvas {
    max-height: 100% !important;
    max-width: 100% !important;
}

/* ป้องกัน chart ขยายเกินขอบเขต */
#applicationStatusChart, #monthlyTrendChart {
    max-height: 100% !important;
    height: auto !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Application Status Pie Chart
    const statusCtx = document.getElementById('applicationStatusChart');
    if (statusCtx) {
        try {
            const statusLabels = @json(array_keys($applicationsByStatus ?? []));
            const statusData = @json(array_values($applicationsByStatus ?? []));
            const statusColors = ['#6c757d', '#0d6efd', '#17a2b8', '#ffc107', '#fd7e14', '#28a745', '#dc3545', '#6f42c1'];
            
            // ถ้าไม่มีข้อมูล ให้แสดง "ไม่มีข้อมูล"
            if (statusData.length === 0) {
                statusLabels.push('ไม่มีข้อมูล');
                statusData.push(1);
                statusColors = ['#e9ecef'];
            }
    
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: statusColors,
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    aspectRatio: 1,
                    devicePixelRatio: 1,
                    layout: {
                        padding: {
                            top: 10,
                            bottom: 20,
                            left: 10,
                            right: 10
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            maxHeight: 80,
                            labels: {
                                usePointStyle: true,
                                padding: 10,
                                font: {
                                    size: 11
                                },
                                boxWidth: 12
                            }
                        }
                    },
                    elements: {
                        arc: {
                            borderWidth: 2
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating status chart:', error);
            statusCtx.parentElement.innerHTML = '<div class="text-center text-muted py-5"><i class="bi bi-exclamation-triangle"></i><br>ไม่สามารถโหลดกราฟได้<br><small>กรุณารีเฟรชหน้าใหม่</small></div>';
        }
    } else {
        console.error('Status chart canvas not found');
    }

    // Monthly Trend Chart
    const trendCtx = document.getElementById('monthlyTrendChart');
    if (trendCtx) {
        try {
            @if(isset($monthlyStats) && $monthlyStats->count() > 0)
            const monthlyLabels = @json($monthlyStats->pluck('month')->toArray());
            const monthlyTotal = @json($monthlyStats->pluck('total')->toArray());
            const monthlyAccepted = @json($monthlyStats->pluck('accepted')->toArray());
    
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [
                {
                    label: 'ใบสมัครทั้งหมด',
                    data: monthlyTotal,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'ได้งานแล้ว',
                    data: monthlyAccepted,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
            @else
            // ถ้าไม่มีข้อมูลรายเดือน
            trendCtx.parentElement.innerHTML = '<div class="text-center text-muted py-5"><i class="bi bi-info-circle"></i><br>ยังไม่มีข้อมูลแนวโน้มรายเดือน<br><small>ข้อมูลจะแสดงเมื่อมีใบสมัครในระบบ</small></div>';
            @endif
        } catch (error) {
            console.error('Error creating trend chart:', error);
            trendCtx.parentElement.innerHTML = '<div class="text-center text-muted py-5"><i class="bi bi-exclamation-triangle"></i><br>ไม่สามารถโหลดกราฟได้<br><small>กรุณารีเฟรชหน้าใหม่</small></div>';
        }
    } else {
        console.error('Trend chart canvas not found');
    }

});
</script>
@endsection