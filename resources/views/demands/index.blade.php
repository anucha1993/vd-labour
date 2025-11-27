@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">จัดการ Demands</h4>
                    @can('create demand')
                    <a href="{{ route('demands.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle-fill"></i> เพิ่ม Demand ใหม่
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

                    <!-- Search Form -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('demands.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="ค้นหา (หมายเลขหนังสือ, ชื่อบริษัท)" 
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="industry_type" class="form-select">
                                        <option value="">-- ประเภทอุตสาหกรรม --</option>
                                        @foreach($industryTypes ?? [] as $industryType)
                                            <option value="{{ $industryType->inducstry_type_id }}" 
                                                    {{ request('industry_type') == $industryType->inducstry_type_id ? 'selected' : '' }}>
                                                {{ $industryType->industry_type_name_th ?? $industryType->inducstry_type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date_from" class="form-control" 
                                           placeholder="วันที่เริ่มต้น" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date_to" class="form-control" 
                                           placeholder="วันที่สิ้นสุด" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary me-1">
                                        <i class="bi bi-search"></i> ค้นหา
                                    </button>
                                    <a href="{{ route('demands.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>วันที่ออกหนังสือ</th>
                                    <th>หมายเลขหนังสือ</th>
                                    <th>ชื่อบริษัท</th>
                                    <th>ประเภทอุตสาหกรรม</th>
                                    <th>จำนวนตำแหน่ง</th>
                                    <th>ผู้สร้าง</th>
                                    <th>สถานะ</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($demands as $demand)
                                <tr>
                                    <td>{{ $demand->dm_id }}</td>
                                    <td>{{ $demand->dm_issue_date->format('d/m/Y') }}</td>
                                    <td>{{ $demand->dm_let_no }}</td>
                                    <td>{{ $demand->dm_com_name }}</td>
                                    <td>{{ $demand->industryType->industry_type_name_th ?? $demand->industryType->inducstry_type_name ?? 'N/A' }}</td>
                                  
                                    <td>{{ $demand->positions->sum('position_dm_amount') }} คน</td>
                                    <td>{{ $demand->createdBy->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-success">🟢 Active</span>
                                    </td>
                                    <td>
                                        @can('view demand')
                                        <a href="{{ route('demands.show', $demand->dm_id) }}" class="btn btn-info btn-sm" title="ดูรายละเอียด">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        @endcan
                                        
                                        @can('view demand')
                                        <a href="{{ route('demands.print', $demand->dm_id) }}" class="btn btn-success btn-sm" title="พิมพ์เอกสาร" target="_blank">
                                            <i class="bi bi-printer-fill"></i>
                                        </a>
                                        @endcan
                                        
                                        @can('update demand')
                                        <a href="{{ route('demands.edit', $demand->dm_id) }}" class="btn btn-warning btn-sm" title="แก้ไข">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        @endcan
                                        
                                        @can('print demand')
                                        <a href="{{ route('demands.pdf', $demand->dm_id) }}" class="btn btn-secondary btn-sm" title="พิมพ์ PDF" target="_blank">
                                            <i class="bi bi-printer-fill"></i>
                                        </a>
                                        @endcan
                                        
                                        @can('delete demand')
                                        <form method="POST" action="{{ route('demands.destroy', $demand->dm_id) }}" style="display:inline;" 
                                              onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบ Demand นี้?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="ลบ">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">ไม่มีข้อมูล</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Search Results Info -->
                    @if(request()->hasAny(['search', 'industry_type', 'date_from', 'date_to']))
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        พบข้อมูล {{ $demands->total() }} รายการ จากการค้นหา
                        @if(request('search'))
                            : "{{ request('search') }}"
                        @endif
                    </div>
                    @endif

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                          {!! $demands->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection