@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-briefcase"></i> จัดการใบสมัครงาน - เลือกงาน</h4>
                    <div class="d-flex gap-2">
                        @can('job-dashboard')
                        <a href="{{ route('jobs.dashboard') }}" class="btn btn-outline-primary">
                            <i class="bi bi-graph-up"></i> Dashboard
                        </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if(session('warning'))
                        <div class="alert alert-warning">{{ session('warning') }}</div>
                    @endif

                    <!-- Search Form -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('job-leads.index') }}" class="d-flex">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="ค้นหางาน (ชื่องาน, หมายเลขงาน, ประเทศ)" 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-primary ms-2">
                                    <i class="bi bi-search"></i> ค้นหา
                                </button>
                                @if(request('search'))
                                <a href="{{ route('job-leads.index') }}" class="btn btn-outline-secondary ms-2">
                                    <i class="bi bi-x-circle"></i> ล้าง
                                </a>
                                @endif
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="text-muted">พบ {{ $jobs->count() }} งานที่มีใบสมัคร</span>
                        </div>
                    </div>

                    @if($jobs->count() > 0)
                    <!-- Jobs Grid -->
                    <div class="row">
                        @foreach($jobs as $job)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-primary job-card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-1 text-primary">{{ $job->job_number }}</h6>
                                    <small class="text-muted">{{ $job->country->country_name_th ?? 'ไม่ระบุ' }}</small>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title">{{ Str::limit($job->job_name, 50) }}</h6>
                                    <p class="card-text small text-muted mb-2">
                                        {{ $job->demand->dm_com_name ?? 'ไม่ระบุบริษัท' }}
                                    </p>
                                    
                                    <!-- Statistics -->
                                    <div class="row text-center mb-3">
                                        <div class="col-4">
                                            <div class="bg-primary bg-opacity-10 rounded p-2">
                                                <h6 class="mb-0 text-primary">{{ $job->job_leads_count }}</h6>
                                                <small class="text-muted">ผู้สมัครทั้งหมด</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="bg-success bg-opacity-10 rounded p-2">
                                                <h6 class="mb-0 text-success">{{ $job->accepted_count }}</h6>
                                                <small class="text-muted">ได้งานแล้ว</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="bg-warning bg-opacity-10 rounded p-2">
                                                <h6 class="mb-0 text-warning">{{ $job->locked_count }}</h6>
                                                <small class="text-muted">ล็อคอยู่</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status Breakdown -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <small class="text-muted">สถานะการสมัคร:</small>
                                        </div>
                                        <div class="row small">
                                            @if($job->draft_count > 0)
                                            <div class="col-6">
                                                <span class="badge bg-secondary">ร่าง: {{ $job->draft_count }}</span>
                                            </div>
                                            @endif
                                            @if($job->sent_count > 0)
                                            <div class="col-6">
                                                <span class="badge bg-info">ส่งแล้ว: {{ $job->sent_count }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mb-2">
                                        @php
                                            $acceptedPercent = $job->job_total > 0 ? ($job->accepted_count / $job->job_total) * 100 : 0;
                                        @endphp
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: {{ $acceptedPercent }}%" 
                                                 title="ได้งานแล้ว {{ $job->accepted_count }}/{{ $job->job_total }}">
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $job->accepted_count }}/{{ $job->job_total }} ตำแหน่ง</small>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-grid">
                                        <a href="{{ route('job-leads.job-applicants', $job->job_id) }}" 
                                           class="btn btn-primary">
                                            <i class="bi bi-people"></i> จัดการผู้สมัคร ({{ $job->job_leads_count }})
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <!-- No Jobs -->
                    <div class="text-center py-5">
                        <i class="bi bi-briefcase fs-1 text-muted"></i>
                        <h4 class="text-muted mt-3">ไม่พบงานที่มีใบสมัคร</h4>
                        <p class="text-muted">
                            @if(request('search'))
                                ไม่พบงานที่ตรงกับ "{{ request('search') }}"
                            @else
                                ยังไม่มีใบสมัครในระบบ
                            @endif
                        </p>
                        @if(request('search'))
                        <a href="{{ route('job-leads.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> ดูทั้งหมด
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.job-card:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease-in-out;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    cursor: pointer;
}

.job-card .card-body {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.stat-box {
    background: rgba(108, 117, 125, 0.1);
    border-radius: 8px;
    padding: 8px 12px;
    text-align: center;
}

.stat-number {
    font-size: 1.25rem;
    font-weight: bold;
    margin-bottom: 2px;
}

.stat-label {
    font-size: 0.75rem;
    color: #6c757d;
}
</style>
@endsection