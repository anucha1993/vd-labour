@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">รายละเอียด Demand #{{ $demand->dm_id }}</h4>
                    <div>
                        @can('view demand')
                        <a href="{{ route('demands.print', $demand->dm_id) }}" class="btn btn-success" target="_blank">
                            <i class="bi bi-printer-fill"></i> พิมพ์เอกสาร
                        </a>
                        @endcan
                        
                        {{-- @can('print demand')
                        <a href="{{ route('demands.pdf', $demand->dm_id) }}" class="btn btn-secondary" target="_blank">
                            <i class="fas fa-print"></i> พิมพ์ PDF
                        </a>
                        @endcan --}}
                        
                        @can('update demand')
                        <a href="{{ route('demands.edit', $demand->dm_id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> แก้ไข
                        </a>
                        @endcan
                        
                        <a href="{{ route('demands.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> ย้อนกลับ
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>ข้อมูลทั่วไป</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">วันที่ออกหนังสือ:</th>
                                    <td>{{ $demand->dm_issue_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>หมายเลขหนังสือ:</th>
                                    <td>{{ $demand->dm_let_no }}</td>
                                </tr>
                                <tr>
                                    <th>ชื่อบริษัท:</th>
                                    <td>{{ $demand->dm_com_name }}</td>
                                </tr>
                                <tr>
                                    <th>หมายเลขทะเบียน:</th>
                                    <td>{{ $demand->dm_reg_no }}</td>
                                </tr>
                                <tr>
                                    <th>ประเภทอุตสาหกรรม:</th>
                                    <td>{{ $demand->industryType->industry_type_name_th ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>ข้อมูลเพิ่มเติม</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">BMI:</th>
                                    <td>{{ $demand->dm_bmi ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>เวลาทำงาน:</th>
                                    <td>{{ $demand->dm_time_work ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>รายละเอียดงานและคุณสมบัติ:</th>
                                    <td>
                                        @if($demand->dm_exp)
                                            @php
                                                $expOptions = [
                                                    'accomm' => 'Accommodation (ที่พัก)',
                                                    'food' => 'Food (อาหาร)', 
                                                    'med' => 'Medical (การแพทย์)',
                                                    'shuttle' => 'Shuttle (รถรับส่ง)'
                                                ];
                                                $selectedExps = is_array($demand->dm_exp) ? $demand->dm_exp : [$demand->dm_exp];
                                            @endphp
                                            @foreach($selectedExps as $exp)
                                                @if(isset($expOptions[$exp]))
                                                    <span class="badge badge-primary me-1">{{ $expOptions[$exp] }}</span>
                                                @endif
                                            @endforeach
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>ที่อยู่บริษัท</h5>
                            <p class="border p-3 rounded">{{ $demand->dm_com_addr }}</p>
                        </div>
                    </div>

                    @if($demand->dm_sa)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>เงินเดือน/สวัสดิการ</h5>
                            <p class="border p-3 rounded">{{ $demand->dm_sa }}</p>
                        </div>
                    </div>
                    @endif

                    @if($demand->dm_job)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>รายละเอียดงาน</h5>
                            <p class="border p-3 rounded">{{ $demand->dm_job }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Positions -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>ตำแหน่งงานที่ต้องการ</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ลำดับ</th>
                                            <th>ตำแหน่ง</th>
                                            <th>จำนวน</th>
                                            <th>ระยะเวลา</th>
                                            <th>อายุ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($demand->positions as $index => $position)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $position->position->position_name ?? 'N/A' }}</td>
                                            <td>{{ number_format($position->position_dm_amount, 0) }} คน</td>
                                            <td>{{ $position->position_dm_period }}</td>
                                            <td>{{ $position->position_dm_age }}</td>
                                        </tr>
                                        @endforeach
                                        <tr class="table-info">
                                            <td colspan="2"><strong>รวมทั้งหมด</strong></td>
                                            <td><strong>{{ number_format($demand->positions->sum('position_dm_amount'), 0) }} คน</strong></td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection