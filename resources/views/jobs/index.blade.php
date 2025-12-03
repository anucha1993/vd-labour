@extends('layouts.main')

@section('content')
<script>
function confirmJobDelete(lockedCount, jobNumber) {
    let message = 'คุณแน่ใจหรือไม่ที่จะลบงาน "' + jobNumber + '" นี้?';
    
    if (lockedCount > 0) {
        message += '\n\nงานนี้มีใบสมัครที่ล็อคคนงานอยู่ ' + lockedCount + ' รายการ\nระบบจะปลดล็อคคนงานทั้งหมดและลบงานให้อัตโนมัติ';
    }
    
    return confirm(message);
}
</script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">จัดการงาน (Job Management)</h4>
                    @can('job-create')
                    <a href="{{ route('jobs.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle-fill"></i> เพิ่มงานใหม่
                    </a>
                    @endcan
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
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('jobs.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="ค้นหา (หมายเลขงาน, ชื่องาน, ประเทศ)" 
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="country_id" class="form-select">
                                        <option value="">-- ทุกประเทศ --</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->country_id }}" 
                                                    {{ request('country_id') == $country->country_id ? 'selected' : '' }}>
                                                {{ $country->country_name_th }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="job_status" class="form-select">
                                        <option value="">-- ทุกสถานะ --</option>
                                        <option value="เปิดรับสมัคร" {{ request('job_status') == 'เปิดรับสมัคร' ? 'selected' : '' }}>เปิดรับสมัคร</option>
                                        <option value="ปิดรับสมัคร" {{ request('job_status') == 'ปิดรับสมัคร' ? 'selected' : '' }}>ปิดรับสมัคร</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="bi bi-search"></i> ค้นหา
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-clockwise"></i> รีเซ็ต
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Jobs Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>หมายเลขงาน</th>
                                    <th>ชื่องาน</th>
                                    <th>ประเทศ</th>
                                    <th>จำนวนรับ</th>
                                    <th>จำนวนใบสมัคร</th>
                                    <th>ได้งานแล้ว</th>
                                    <th>เหลือ</th>
                                    <th>วันเริ่ม-สิ้นสุด</th>
                                    <th>สถานะ</th>
                                    <th>ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobs as $job)
                                <tr class="{{ $job->is_expired ? 'table-warning' : '' }}">
                                    <td>
                                        <strong>{{ $job->job_number }}</strong>
                                        @if($job->is_expired)
                                            <br><small class="text-danger">หมดอายุแล้ว</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $job->job_name }}</div>
                                        <small class="text-muted">{{ $job->demand->dm_com_name ?? 'N/A' }}</small> <br>
                                        <small class=" text-primary">บริษัทนายจ้าง: {{ $job->customer->customer_name ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <i class="flag-icon flag-icon-{{ strtolower($job->country->country_code ?? 'xx') }}"></i>
                                        {{ $job->country->country_name_th ?? 'N/A' }}
                                    </td>
                                    <td class="text-center">{{ number_format($job->job_total) }}</td>
                                    <td class="text-center">{{ number_format($job->job_leads_count) }}</td>
                                    <td class="text-center text-success">{{ number_format($job->accepted_count) }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $job->remaining_positions > 0 ? 'bg-info' : 'bg-secondary' }}">
                                            {{ number_format($job->remaining_positions) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>{{ $job->job_start_date->format('d/m/Y') }}</div>
                                        @if($job->job_end_date)
                                            <small class="text-muted">ถึง {{ $job->job_end_date->format('d/m/Y') }}</small>
                                        @else
                                            <small class="text-info">ต่อเนื่อง</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $job->job_status == 'เปิดรับสมัคร' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $job->job_status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('job-list')
                                            <a href="{{ route('jobs.show', $job->job_id) }}" 
                                               class="btn btn-sm btn-info" title="ดูรายละเอียด">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @endcan
                                            
                                            @can('job-edit')
                                            <a href="{{ route('jobs.edit', $job->job_id) }}" 
                                               class="btn btn-sm btn-warning" title="แก้ไข">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @endcan
                                            
                                            @can('job-status-toggle')
                                            <form action="{{ route('jobs.toggle-status', $job->job_id) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-secondary" 
                                                        title="เปลี่ยนสถานะ">
                                                    <i class="bi bi-toggle-{{ $job->job_status == 'เปิดรับสมัคร' ? 'on' : 'off' }}"></i>
                                                </button>
                                            </form>
                                            @endcan
                                            
                                            @if($job->remaining_positions > 0)
                                                @can('job-lead-create')
                                                <a href="{{ route('job-leads.create', ['job_id' => $job->job_id]) }}" 
                                                   class="btn btn-sm btn-success" title="เพิ่มใบสมัคร">
                                                    <i class="bi bi-person-plus"></i>
                                                </a>
                                                @endcan
                                            @endif
                                            
                                            @can('job-delete')
                                            <form action="{{ route('jobs.destroy', $job->job_id) }}" 
                                                  method="POST" style="display: inline;"
                                                  onsubmit="return confirmJobDelete({{ $job->locked_leads_count ?? 0 }}, '{{ $job->job_number }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="ลบงาน">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <i class="bi bi-inbox fs-1 text-muted"></i>
                                        <p class="text-muted">ไม่พบข้อมูลงาน</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($jobs->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $jobs->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.flag-icon {
    width: 1.5em;
    height: 1em;
    margin-right: 0.5em;
}
</style>
@endsection