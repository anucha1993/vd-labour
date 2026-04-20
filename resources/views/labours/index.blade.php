@extends('layouts.main')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <strong>{{ $message }}</strong>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            <strong>{{ $message }}</strong>
        </div>
    @endif



    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog " style="max-width: 70%;">
            <div class="modal-content">
                <div class="card">
                    <div class="card-body">
                        <h4>ค้นหาข้อมูล</h4>
                        <hr>
                        <form action="" method="get">
                            <div class="row">
                                <div class="col-md-3 mt">
                                    <label>FirstName</label>
                                    <input type="text" class="form-control" name="labour_firstname"
                                        placeholder="First Name">
                                </div>
                                <div class="col-md-3 mt">
                                    <label>LastName</label>
                                    <input type="text" class="form-control" name="labour_lastname"
                                        placeholder="Last Name">
                                </div>
                                <div class="col-md-3 mt">
                                    <label>Phone</label>
                                    <input type="text" class="form-control" name="labour_phone" placeholder="++66">
                                </div>
                                <div class="col-md-3 mt">
                                    <label>Passport No.</label>
                                    <input type="text" class="form-control" name="labour_passport_number"
                                        placeholder="Passport No.">
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-3 mt-3">
                                    <label>Date Start (ผลโรคหมดอายุ) </label>
                                    <input type="date" name="labour_disease_date_start" class="form-control"
                                        placeholder="Register Number">
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label>Date End (ผลโรคหมดอายุ) </label>
                                    <input type="date" name="labour_disease_date_end" class="form-control"
                                        placeholder="Register Number">
                                </div>


                                <div class="col-md-3 mt-3">
                                    <label>Data Start CID Expiry</label>
                                    <input type="date" name="labour_cid_start" class="form-control"
                                        placeholder="Register Number">
                                </div>

                                <div class="col-md-3 mt-3">
                                    <label> Data End CID Expiry</label>
                                    <input type="date" name="labour_cid_end" class="form-control"
                                        placeholder="Register Number">
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-md-3 mt-3">
                                    <label> Country Name</label>
                                    <select name="labour_country" class="form-select">
                                        <option value="all">All</option>
                                        @forelse ($customers as $item)
                                            <option value="{{ $item->customer_id }}">{{ $item->customer_name }}</option>
                                        @empty
                                        @endforelse

                                    </select>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label> Job Group</label>
                                    <select name="labour_job_group" class="form-select">
                                        <option value="all">All</option>
                                        @forelse ($jobGroup as $item)
                                            <option value="{{ $item->job_group_id }}">{{ $item->job_group_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label> Staff Name</label>
                                    <select name="labour_staff" class="form-select">
                                        <option value="all">All</option>
                                        @forelse ($staffs as $item)
                                            <option value="{{ $item->staff_id }}">
                                                {{ $item->staff_name }}({{ $item->staff_nickname }})</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>

                                <div class="col-md-3 mt-3">
                                    <label> Status </label>
                                    <select name="labour_status" class="form-select">
                                        <option value="all">All</option>
                                        <option value="wait">กำลังดำเนินการ</option>
                                        <option value="hold">ชะลอดำเนินการ</option>
                                        <option value="success">บินแล้ว</option>
                                        <option value="cancel">ยกเลิก</option>
                                    </select>
                                </div>

                            </div>

                            <button type="submit" class="btn btn-outline-secondary mt-3 float-end">
                                Search
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="bi bi-people-fill me-2 text-primary"></i>ข้อมูลคนงาน</h4>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                        data-bs-target=".bd-example-modal-lg">
                        <i class="bi bi-search"></i> ค้นหา
                    </button>
                    <a href="{{ route('labour.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus-fill"></i> เพิ่มข้อมูล
                    </a>
                </div>
            </div>
            <div class="table-responsive card card-custom p-0">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th><i class="bi bi-person-badge me-1"></i> Full-Name</th>
                            <th><i class="bi bi-passport me-1"></i> Passport No.</th>
                            <th><i class="bi bi-telephone me-1"></i> Phone</th>
                            <th><i class="bi bi-folder-check me-1"></i> Docs.</th>
                            <th><i class="bi bi-flag me-1"></i> Status</th>
                            <th><i class="bi bi-person-lines-fill me-1"></i> Staff</th>
                            <th class="text-center"><i class="bi bi-gear me-1"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($labours as $key => $item)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $item->labour_prefix . '.' . $item->labour_firstname . ' ' . $item->labour_lastname }}</br>
                                    <span class="text-muted">นายจ้าง :
                                        {{ $item->customer->customer_name ?? 'ยังไม่มีนายจ้าง' }}</span>

                                </td>
                                <td>
                                    {{ $item->labour_passport_number ? $item->labour_passport_number : 'ไม่พบข้อมูล' }}</br>
                                    <span class="text-success">อายุ :
                                        {{ $item->labour_birthday ? \Carbon\Carbon::parse($item->labour_birthday)->age : 'ไม่พบข้อมูล' }}</span>

                                </td>
                                <td>{{ $item->labour_phone }}</br>
                                    <span class="text-primary">Country :
                                        {{ $item->country->country_name_th ?? 'ไม่พบข้อมูล' }}</span>
                                </td>
                                <td>
                                    @php
                                        $progress =
                                            $item->labour_file_count > 0
                                                ? ($item->labour_file_list / $item->labour_file_count) * 100
                                                : 0;
                                    @endphp
                                    <div class="progress" style="height: 18px; background: #fbeee6;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ round($progress) }}%
                                        </div>

                                    </div>
                                    </br>
                                    <span class="text-muted">Job :
                                        {{ $item->jobGroup->job_group_name ?? 'ไม่พบข้อมูล' }}</span>

                                </td>
                                <td>
                                    @if ($item->labour_status === 'wait')
                                        <span class="badge rounded-pill bg-primary"><i class="bi bi-hourglass-split"></i>
                                            กำลังดำเนินการ</span>
                                    @elseif ($item->labour_status === 'hold')
                                        <span class="badge rounded-pill bg-warning text-dark"><i class="bi bi-pause-circle"></i>
                                            ชะลอดำเนินการ</span>
                                    @elseif ($item->labour_status === 'success')
                                        <span class="badge rounded-pill bg-success"><i class="bi bi-check-circle"></i>
                                            บินแล้ว</span>
                                    @elseif ($item->labour_status === 'cancel')
                                        <span class="badge rounded-pill bg-danger"><i class="bi bi-x-circle"></i>
                                            ยกเลิก</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary">-</span>
                                    @endif
                                    </br>

                                    <span class="text-muted">Position :
                                        {{ $item->position->position_name ?? 'ไม่พบข้อมูล' }}</span>
                                </td>
                                <td>{{ $item->staff_nickname }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('labour.edit', $item->labour_id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="แก้ไขข้อมูล">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="{{ route('labour.viewDocs', $item->labour_id) }}" 
                                           class="btn btn-sm btn-outline-secondary view-doc" 
                                           title="ดูเอกสาร">
                                            <i class="bi bi-folder2-open"></i>
                                        </a>
                                        <a href="{{ route('labour.print', $item->labour_id) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           target="_blank" 
                                           title="พิมพ์ข้อมูล">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                        @can('delete labour')
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="if(confirm('คุณแน่ใจหรือไม่ว่าต้องการลบแรงงานนี้? ข้อมูลทั้งหมดรวมถึงไฟล์จะถูกลบถาวร')) document.getElementById('delete-form-{{ $item->labour_id }}').submit();"
                                                title="ลบ">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                        <form id="delete-form-{{ $item->labour_id }}" action="{{ route('labour.destroy', $item->labour_id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pt-3">
                    {!! $labours->withQueryString()->links('pagination::bootstrap-5') !!}
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="view-doc" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">แสดงเอกสาร</h5>
                    <button type="button" class="close-modal-btn btn-close" data-bs-dismiss="modal"
                        aria-label="ปิด"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="text-center">กำลังโหลด...</div>
                </div>
            </div>
        </div>
    </div>
    <style>
        @media (max-width: 576px) {
            .modal-body {
                max-height: 80vh;
                overflow-y: auto;
            }
        }
    </style>

    <script>
        let viewModal; // ตัวแปร global

        $(document).on("click", ".view-doc", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            const modalEl = document.getElementById("view-doc");

            viewModal = new bootstrap.Modal(modalEl);
            $("#view-doc .modal-body").html("กำลังโหลด...");
            $("#view-doc .modal-body").load(url, function() {
                viewModal.show();
            });
        });
        // ปุ่มสั่งปิด
        $(document).on("click", ".close-modal-btn", function() {
            if (viewModal) viewModal.hide();
        });

        // ...existing code...
    </script>
@endsection
