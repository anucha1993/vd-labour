@extends('layouts.main')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="bi bi-person-plus-fill me-2 text-primary"></i>จัดการข้อมูลผู้สนใจ (Leads)</h4>
                <div class="d-flex gap-2">
                    @can('view lead')
                    <div class="btn-group">
                        <a href="{{ route('pdf.leads.list', request()->query()) }}" class="btn btn-success" target="_blank">
                            <i class="bi bi-file-pdf"></i> Preview PDF
                        </a>
                        <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('pdf.download.leads.list', request()->query()) }}">
                                <i class="bi bi-download"></i> ดาวน์โหลด PDF
                            </a></li>
                        </ul>
                    </div>
                    @endcan
                    @can('create lead')
                    <a href="{{ route('leads.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle-fill"></i> เพิ่มผู้สนใจ
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('leads.index') }}" class="mb-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" placeholder="ค้นหา ชื่อ, โทร, พาสปอร์ต..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="lead_status">
                            <option value="all">-- สถานะทั้งหมด --</option>
                            <option value="new" {{ request('lead_status') == 'new' ? 'selected' : '' }}>ใหม่</option>
                            <option value="contacted" {{ request('lead_status') == 'contacted' ? 'selected' : '' }}>ติดต่อแล้ว</option>
                            <option value="interview" {{ request('lead_status') == 'interview' ? 'selected' : '' }}>นัดสัมภาษณ์</option>
                            <option value="qualified" {{ request('lead_status') == 'qualified' ? 'selected' : '' }}>ผ่านคุณสมบัติ</option>
                            <option value="converted" {{ request('lead_status') == 'converted' ? 'selected' : '' }}>Convert แล้ว</option>
                            <option value="rejected" {{ request('lead_status') == 'rejected' ? 'selected' : '' }}>ไม่ผ่าน</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="position_id">
                            <option value="all">-- ตำแหน่งทั้งหมด --</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->position_id }}" {{ request('position_id') == $position->position_id ? 'selected' : '' }}>
                                    {{ $position->position_name_th ?? $position->position_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> ค้นหา</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('leads.index') }}" class="btn btn-secondary w-100"><i class="bi bi-arrow-clockwise"></i> รีเซ็ต</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive card card-custom p-0">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="80">#</th>
                            <th class="text-center" width="80"><i class="bi bi-image me-1"></i> รูปถ่าย</th>
                            <th><i class="bi bi-person me-1"></i> ชื่อ-นามสกุล</th>
                            <th><i class="bi bi-telephone me-1"></i> โทรศัพท์</th>
                            <th><i class="bi bi-briefcase me-1"></i> ตำแหน่ง</th>
                            <th class="text-center"><i class="bi bi-flag me-1"></i> ประเทศ</th>
                            <th class="text-center"><i class="bi bi-info-circle me-1"></i> สถานะ</th>
                            <th class="text-center"><i class="bi bi-calendar me-1"></i> วันที่สร้าง</th>
                            <th class="text-center" width="280"><i class="bi bi-gear me-1"></i> จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leads as $key => $item)
                            <tr>
                                <td class="text-center">{{ $leads->firstItem() + $key }}</td>
                                <td class="text-center">
                                    @if($item->lead_photo)
                                        <img src="{{ asset('storage/' . $item->lead_photo) }}" 
                                             class="rounded-circle border cursor-pointer" 
                                             style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                             alt="รูปถ่าย {{ $item->fullName }}"
                                             data-bs-toggle="modal"
                                             data-bs-target="#photoModal"
                                             onclick="showPhoto('{{ asset('storage/' . $item->lead_photo) }}', '{{ $item->fullName }}')"
                                             title="คลิกเพื่อดูรูปใหญ่">
                                    @else
                                        <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-person text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><strong>{{ $item->fullName }}</strong></td>
                                <td>{{ $item->lead_phone ?? '-' }}</td>
                                <td>
                                    @if($item->position)
                                        <span class="badge bg-info">{{ $item->position->position_name_th ?? $item->position->position_name }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->country)
                                        {{ $item->country->country_name_th ?? $item->country->country_name_en }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">{!! $item->statusBadge !!}</td>
                                <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        @can('view lead')
                                        <a href="{{ route('leads.show', $item->lead_id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="ดูรายละเอียด">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        @endcan

                                        @can('view lead')
                                        <div class="btn-group">
                                            <a href="{{ route('pdf.cv.form', $item->lead_id) }}" 
                                               class="btn btn-sm btn-outline-success" 
                                               title="Preview PDF" 
                                               target="_blank">
                                                <i class="bi bi-file-pdf-fill"></i>
                                            </a>
                                            
                                            {{-- <button type="button" class="btn btn-sm btn-outline-success dropdown-toggle dropdown-toggle-split" 
                                                    data-bs-toggle="dropdown" title="ตัวเลือกเพิ่มเติม">
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('pdf.download.lead', $item->lead_id) }}">
                                                    <i class="bi bi-download"></i> ดาวน์โหลดรายงาน
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="{{ route('pdf.cv.form', $item->lead_id) }}" target="_blank">
                                                    <i class="bi bi-file-earmark-text"></i> ดูฟอร์ม CV
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('pdf.download.cv.form', $item->lead_id) }}">
                                                    <i class="bi bi-download"></i> ดาวน์โหลดฟอร์ม CV
                                                </a></li> --}}
                                            </ul>
                                        </div>
                                        @endcan

                                        @can('update lead')
                                        <a href="{{ route('leads.edit', $item->lead_id) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="แก้ไข">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        @endcan

                                        @if(!$item->isConverted())
                                            <a href="{{ route('leads.convertForm', $item->lead_id) }}" 
                                               class="btn btn-sm btn-outline-success" 
                                               title="Convert เป็น Labour">
                                                <i class="bi bi-arrow-right-circle-fill"></i>
                                            </a>
                                        @endif

                                        @can('delete lead')
                                        @if(!$item->isConverted())
                                        <form action="{{ route('leads.destroy', $item->lead_id) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบผู้สนใจนี้?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="ลบ">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    ไม่มีข้อมูลผู้สนใจ
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                 {!! $leads->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">
                    <i class="bi bi-image me-2"></i>รูปถ่าย: <span id="modalName"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalPhoto" src="" class="img-fluid rounded" alt="รูปถ่าย" style="max-height: 400px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showPhoto(photoUrl, name) {
        document.getElementById('modalPhoto').src = photoUrl;
        document.getElementById('modalName').textContent = name;
    }
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@endsection
