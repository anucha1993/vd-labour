@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-check-circle"></i> ได้แล้ว รอ Convert</h4>
                    <a href="{{ route('job-leads.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left"></i> กลับ
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="ค้นหา (ชื่อ, Passport, เบอร์โทร)" 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-primary ms-2">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form method="GET">
                                @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                <select name="job_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">-- ทุกงาน --</option>
                                    @foreach($jobs as $job)
                                        <option value="{{ $job->job_id }}" {{ request('job_id') == $job->job_id ? 'selected' : '' }}>
                                            {{ $job->job_number }} - {{ $job->job_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> 
                                พบ <strong>{{ $jobLeads->total() }}</strong> ใบสมัครที่พร้อมสำหรับการ Convert 
                                (สถานะ = ตอบรับ, ยังไม่ Convert)
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    @if($jobLeads->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>หมายเลขใบสมัคร</th>
                                     <th class="text-center" width="80"><i class="bi bi-image me-1"></i></th>
                                    <th>ชื่อผู้สมัคร</th>
                                    <th>Passport</th>
                                    <th>เบอร์โทร</th>
                                    <th>งาน</th>
                                    <th>ประเทศ</th>
                                    <th>วันที่สมัคร</th>
                                    <th width="100">การดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobLeads as $jobLead)
                                <tr>
                                     

                                    <td>
                                        <a href="{{ route('job-leads.conversion.show', $jobLead->job_lead_id) }}" 
                                           class="text-decoration-none fw-bold">
                                            {{ $jobLead->job_lead_number }}
                                        </a>
                                    </td>

                                    <td class="text-center">
                                    @if($jobLead->lead->lead_photo)
                                        <img src="{{ asset('storage/' . $jobLead->lead->lead_photo) }}" 
                                             class="rounded-circle border cursor-pointer" 
                                             style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                             alt="รูปถ่าย {{ $jobLead->lead->getFullNameAttribute() }}"
                                             data-bs-toggle="modal"
                                             data-bs-target="#photoModal"
                                             onclick="showPhoto('{{ asset('storage/' . $jobLead->lead->lead_photo) }}', '{{ $jobLead->lead->fullName }}')"
                                             title="คลิกเพื่อดูรูปใหญ่">
                                    @else
                                        <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-person text-muted"></i>
                                        </div>
                                    @endif
                                </td>

                                    <td>
                                        <strong>{{ $jobLead->lead ? $jobLead->lead->getFullNameAttribute() : 'ไม่พบข้อมูล' }}</strong>
                                        @if($jobLead->lead && $jobLead->lead->lead_birthday)
                                            <br><small class="text-muted">อายุ {{ \Carbon\Carbon::parse($jobLead->lead->lead_birthday)->age }} ปี</small>
                                        @endif
                                    </td>
                                    <td>
                                        <code>{{ $jobLead->lead ? ($jobLead->lead->lead_passport_number ?: '-') : '-' }}</code>
                                    </td>
                                    <td>{{ $jobLead->lead ? ($jobLead->lead->lead_phone ?: '-') : '-' }}</td>
                                    <td>
                                        <small>{{ $jobLead->job ? ($jobLead->job->job_number . ' - ' . $jobLead->job->job_name) : '-' }}</small><br>
                                        <small>บริษัทนายจ้าง: {{ $jobLead->job ? ($jobLead->job->customer->customer_name . ' - ' . $jobLead->job->customer->customer_name_th) : '-' }}</small><br>
                                        <small>ตำแหน่งงาน: {{ $jobLead->job ? ($jobLead->job->position->position_name . ' - ' . $jobLead->job->position->position_name_th) : '-' }}</small>
                                    </td>
                                    <td>
                                        {{ $jobLead->job && $jobLead->job->country ? $jobLead->job->country->country_name_th : '-' }}
                                    </td>
                                    <td>
                                        {{ $jobLead->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('job-leads.conversion.show', $jobLead->job_lead_id) }}" 
                                           class="btn btn-sm btn-primary" title="ดูรายละเอียด">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $jobLeads->appends(request()->query())->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <h4 class="text-muted mt-3">ไม่มีใบสมัครที่พร้อมสำหรับการ Convert</h4>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'job_id']))
                                ไม่พบใบสมัครที่ตรงกับเงื่อนไขการค้นหา
                            @else
                                ทุกใบสมัครเสร็จสิ้นการ Convert แล้ว หรือยังไม่มีใบสมัครที่สถานะ "ตอบรับ"
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
