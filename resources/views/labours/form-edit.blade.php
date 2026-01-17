@extends('layouts.main')
@section('content')
    <style>
        /* เอกสารใหม่ที่เพิ่มเข้ามา */
        .card.border-warning {
            animation: pulse-warning 2s infinite;
        }

        @keyframes pulse-warning {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4);
            }

            50% {
                box-shadow: 0 0 0 10px rgba(255, 193, 7, 0);
            }
        }
    </style>

    <div class="row">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>กรุณาแก้ไขข้อผิดพลาดต่อไปนี้:
                </h6>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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

        {{-- @if ($debug = Session::get('upload_debug'))
            <div class="alert alert-info">
                <strong>Upload Debug Info:</strong>
                <pre>{{ json_encode($debug, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endif --}}

    </div>

    <!-- ส่วนหัวของฟอร์ม -->
    <div class="card card-custom shadow-sm mb-4">
        <form action="{{ route('labour.update', $labourModel->labour_id) }}" enctype="multipart/form-data" method="post"
            id="form-create" novalidate>
            @csrf
            @method('put')

            <div class="card-body bg-light rounded-4 p-4">

                <strong class="text-success"> ชื่อ : {{ $labourModel->labour_prefix }}.{{ $labourModel->labour_firstname }}
                    {{ $labourModel->labour_lastname }} | อายุ :
                    {{ $labourModel->labour_birthday ? \Carbon\Carbon::parse($labourModel->labour_birthday)->age : 'ไม่พบข้อมูล' }}
                    ปี</strong>

                <div class="row float-end mb-4">

                    <div class="col-md-12 text-success">
                        <label>สถานะคนงาน (Status)</label>
                        <select name="labour_status" class="form-select" @cannot('update labour') disabled @endcannot>
                            <option @if ($labourModel->labour_status === 'wait') selected @endif value="wait">กำลังดำเนินการ
                            </option>
                            <option @if ($labourModel->labour_status === 'success') selected @endif value="success">บินแล้ว
                            </option>
                            <option @if ($labourModel->labour_status === 'cancel') selected @endif value="cancel">ยกเลิก</option>
                        </select>
                    </div>
                </div>
                <br>
                <br>

                <div>
                    <!-- เพิ่ม Nav Tabs -->
                    <ul class="nav nav-tabs mb-4" id="labourTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-basic" data-bs-toggle="tab" data-bs-target="#basic"
                                type="button" role="tab">
                                <i class="bi bi-person-badge-fill me-1"></i> ข้อมูลคนงาน
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-passport" data-bs-toggle="tab" data-bs-target="#passport"
                                type="button" role="tab">
                                <i class="bi bi-journal-medical me-1"></i> หนังสือเดินทาง & สุขภาพ
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-group" data-bs-toggle="tab" data-bs-target="#group"
                                type="button" role="tab">
                                <i class="bi bi-people-fill me-1"></i> กลุ่มงาน
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-docs" data-bs-toggle="tab" data-bs-target="#docs"
                                type="button" role="tab">
                                <i class="bi bi-folder2-open me-1"></i> เอกสาร
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-status" data-bs-toggle="tab" data-bs-target="#status"
                                type="button" role="tab">
                                <i class="bi bi-wallet2 me-1"></i> การเงิน บัญชี
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-visa" data-bs-toggle="tab" data-bs-target="#visa"
                                type="button" role="tab">
                                <i class="bi bi-passport me-1"></i> VISA
                            </button>
                        </li>
                    </ul>

                </div>

                <!-- เนื้อหาในแต่ละ Tab -->
                <div class="tab-content" id="labourTabContent">
                    <!-- Tab 1: ข้อมูลคนงาน -->
                    <div class="tab-pane fade show active" id="basic" role="tabpanel">
                        <div class="row g-3 align-items-end">
                            <div class="row g-3 align-items-end">

                                <div class="col-md-1">
                                    <label class="form-label">Prefix <span class="text-danger">*</span></label>
                                    <select name="labour_prefix" class="form-select" required
                                        @cannot('update labour') disabled @endcannot>
                                        <option @if ($labourModel->labour_prefix === 'MR') selected @endif value="MR">MR.
                                        </option>
                                        <option @if ($labourModel->labour_prefix === 'MS') selected @endif value="MS">MS.
                                        </option>
                                        <option @if ($labourModel->labour_prefix === 'MRS') selected @endif value="MRS">MRS.
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">กรุณาเลือก Prefix</div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="labour_firstname"
                                        placeholder="Firstname" @cannot('update labour') disabled @endcannot
                                        value="{{ $labourModel->labour_firstname }}" required>
                                    <div class="invalid-feedback">กรุณากรอกชื่อ</div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Lastname <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="labour_lastname"
                                        placeholder="Lastname" @cannot('update labour') disabled @endcannot
                                        value="{{ $labourModel->labour_lastname }}" required>
                                    <div class="invalid-feedback">กรุณากรอกนามสกุล</div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Birthday <span class="text-danger">*</span> <span
                                            id="age_result" class="text-info"></span></label>
                                    <input type="date" class="form-control" name="labour_birthday"
                                        placeholder="birthday" @cannot('update labour') disabled @endcannot
                                        id="labour_birthday" value="{{ $labourModel->labour_birthday }}" required>
                                    <div class="invalid-feedback">กรุณาเลือกวันเกิด</div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="labour_phone" placeholder="+66"
                                        @cannot('update labour') disabled @endcannot
                                        value="{{ $labourModel->labour_phone }}">
                                </div>


                            </div>
                            <br>


                        </div>
                    </div>

                    <!-- Tab 2: Passport, สุขภาพ -->
                    <div class="tab-pane fade" id="passport" role="tabpanel">
                        <div class="row g-3">
                            <div class="row g-3 mt-2">

                                <div class="col-md-4">
                                    <label class="form-label">Passport No.</label>
                                    <input type="text" name="labour_passport_number" class="form-control"
                                        @cannot('update labour') disabled @endcannot placeholder="Passport Number"
                                        value="{{ $labourModel->labour_passport_number }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Data Issue</label>
                                    <input type="date" name="labour_passport_issue" class="form-control"
                                        placeholder="Date Issue" @cannot('update labour') disabled @endcannot
                                        value="{{ $labourModel->labour_passport_issue }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Data Expiry <span id="daysLeft"
                                            class="text-info small"></span></label>
                                    <input type="date" name="labour_passport_expiry" id="labour_passport_expiry"
                                        @cannot('update labour') disabled @endcannot class="form-control"
                                        placeholder="Date Expiry" value="{{ $labourModel->labour_passport_expiry }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Register Number</label>
                                    <input type="text" name="labour_register_number" class="form-control"
                                        @cannot('update labour') disabled @endcannot placeholder="Register Number"
                                        value="{{ $labourModel->labour_register_number }}">
                                </div>



                                <div class="col-md-4">
                                    <label>Disease Start (วันออกผลโรค) </label>
                                    <input type="date" name="labour_disease_start" id="labour_disease_start"
                                        class="form-control" @cannot('update labour') disabled @endcannot
                                        placeholder="Disease Start" value="{{ $labourModel->labour_disease_start }}">
                                </div>
                                <div class="col-md-4">
                                    <label>Disease Expiry (ผลโรคหมดอายุ) คำนวน 90 วัน </label>
                                    <input type="date" name="labour_disease_expriry" id="labour_disease_expriry"
                                        @cannot('update labour') disabled @endcannot class="form-control">
                                </div>


                                <div class="col-md-4">
                                    <label>date disease results (วันรับผลโรค) </label>
                                    <input type="date" name="labour_disease_results_date" id="labour_disease_expriry"
                                        @cannot('update labour') disabled @endcannot
                                        value="{{ $labourModel->labour_disease_results_date }}" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label>Status Disease (สถานะผลโรค)</label>
                                    <select name="labour_disease_status" class="form-select">
                                        <option value="5">--ไม่ระบุ---</option>
                                        <option @if ($labourModel->labour_disease_status == 0) selected @endif value="0">
                                            รอตรวจผลโรค
                                        </option>
                                        <option @if ($labourModel->labour_disease_status == 1) selected @endif value="1">
                                            ผลโรคผ่าน
                                        </option>
                                        <option @if ($labourModel->labour_disease_status == 2) selected @endif value="2">
                                            รอตรวจผลโรคซ้ำ</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>วันที่ยื่น CID</label>
                                    <input type="date" name="labour_cid_stand_date" class="form-control"
                                        @cannot('update labour') disabled @endcannot
                                        value="{{ $labourModel->labour_cid_stand_date }}">
                                </div>
                                <div class="col-md-4 ">
                                    <label>Affidavit issues Date :</label>
                                    <input type="date" name="labour_affidavit_start" class="form-control"
                                        id="labour_affidavit_start" @cannot('update labour') disabled @endcannot
                                        value="{{ $labourModel->labour_affidavit_start }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Affidavit Date Expriry :</label>
                                    <input type="date" name="labour_affidavit_expriry" class="form-control"
                                        id="labour_affidavit_expriry" @cannot('update labour') disabled @endcannot
                                        value="{{ $labourModel->labour_affidavit_expriry }}">
                                </div>


                                <div class="col-md-4 mt-3">
                                    <label>CID Start</label>
                                    <input type="date" name="labour_cid_start" class="form-control"
                                        id="labour_cid_start" @cannot('update labour') disabled @endcannot
                                        placeholder="Register Number" value="{{ $labourModel->labour_cid_start }}">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label>CID Expiry</label>
                                    <input type="date" name="labour_cid_expriry" class="form-control"
                                        id="labour_cid_expriry" @cannot('update labour') disabled @endcannot
                                        placeholder="CID Expiry" value="">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label>CID Results</label>
                                    <select name="labour_cid_results" class="form-select"
                                        @cannot('update labour') disabled @endcannot>
                                        <option value="">ไม่ระบุ</option>
                                        @forelse ($CidResults as $item)
                                            <option @if ($item->cid_results_id === $labourModel->labour_cid_results) selected @endif
                                                value="{{ $item->cid_results_id }}">{{ $item->cid_results_name }}
                                            </option>
                                        @empty
                                            ไม่มีข้อมูล
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label>CID File</label><br>
                                    @if ($labourModel->labour_cid_results_file)
                                        <div class="border p-2 rounded">
                                            <p>📄 <strong>{{ $labourModel->labour_cid_results_file }}</strong></p>

                                            <a href="{{ asset('storage/LABOURS/' . $labourModel->labour_path . '/' . $labourModel->labour_cid_results_file) }}"
                                                target="_blank" class="btn btn-sm btn-primary">
                                                ดูไฟล์
                                            </a>

                                            <a href="{{ route('labour.cidfile.delete', $labourModel->labour_id) }}"
                                                @cannot('delete labour file file') disabled @endcannot
                                                onclick="return confirm('คุณแน่ใจว่าต้องการลบไฟล์นี้?')"
                                                class="btn btn-sm btn-danger">
                                                ลบไฟล์
                                            </a>
                                        </div>
                                    @else
                                        <input type="file" name="cid_file" class="form-control"
                                            @cannot('update labour') disabled @endcannot>
                                    @endif
                                </div>
                            </div>
                            {{-- End labour info  --}}
                        </div>
                    </div>

                    <!-- Tab 3: กลุ่มงาน -->
                    <div class="tab-pane fade" id="group" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>Customer (นายจ้าง)</label>
                                <select name="labour_customer" class="form-select select2-customer"
                                    @cannot('update labour') disabled @endcannot>
                                    <option value="">Select a Customer</option>
                                    @forelse ($customers as $item)
                                        <option @if ($item->customer_id === $labourModel->labour_customer) selected @endif
                                            value="{{ $item->customer_id }}">
                                            {{ $item->customer_name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                <input type="hidden" name="labour_customer_old"
                                    value="{{ $labourModel->labour_customer }}">
                            </div>


                            <div class="col-md-4">
                                <label>Examination round (รอบสอบ) </label>
                                <select name="labour_examination" class="form-select select2-examination"
                                    @cannot('update labour') disabled @endcannot>
                                    <option value="">เลือกรอบสอบ</option>
                                    @forelse ($examinationRound as $item)
                                        <option 
                                            @if(
                                                $item->examination_round_id == $labourModel->labour_examination || 
                                                $item->examination_round_name == $labourModel->labour_examination
                                            ) selected @endif 
                                            value="{{ $item->examination_round_id }}">
                                            {{ date('d-m-Y', strtotime($item->examination_round_name)) }}-{{ $item->examination_round_note }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Country <span class="text-danger">*</span></label>
                                <select name="labour_country" class="form-select country" required
                                    @cannot('update labour') disabled @endcannot>
                                    <option value="">Select a Country</option>
                                    @forelse ($country as $item)
                                        <option @if ($item->country_id === $labourModel->labour_country) selected @endif
                                            value="{{ $item->country_id }}">
                                            {{ $item->country_name_en }}</option>
                                    @empty
                                        No date
                                    @endforelse

                                </select>
                                <div class="invalid-feedback">กรุณาเลือกประเทศ</div>
                            </div>
                            <div class="col-md-4">
                                <label>Job Group <span class="text-danger">*</span></label>
                                <select name="labour_job_group" class="form-select job-group"
                                    @cannot('update labour') disabled @endcannot required>
                                    <option value="">Select a Job Group</option>

                                    @forelse ($jobGroup as $item)
                                        <option @if ($item->job_group_id === $labourModel->labour_job_group) selected @endif
                                            value="{{ $item->job_group_id }}">
                                            {{ $item->job_group_name }}</option>
                                    @empty
                                    @endforelse

                                </select>
                                <div class="invalid-feedback">กรุณาเลือก Job Group</div>
                            </div>
                            <div class="col-md-4">
                                <label>Position <span class="text-danger">*</span></label>
                                <select name="labour_position" class="form-select select2-position" id="position" required
                                    @cannot('update labour') disabled @endcannot>
                                    <option value="">Select a Position</option>
                                    @forelse ($positions as $pos)
                                        <option @if ($pos->position_id === $labourModel->labour_position) selected @endif
                                            value="{{ $pos->position_id }}">
                                            {{ $pos->position_name }}
                                        </option>
                                    @empty
                                        <option value="{{ $labourModel->labour_position }}" selected>
                                            {{ $position->position_name ?? 'ไม่พบข้อมูล' }}
                                        </option>
                                    @endforelse
                                </select>
                                <div class="invalid-feedback">กรุณาเลือก Position</div>
                            </div>

                            {{-- <div class="col-md-4">
                                <label>lacation Test</label>
                                <select name="labour_location_test" class="form-select" required
                                    @cannot('update labour') disabled @endcannot>
                                    <option value="">Select a Localtion Test</option>
                                    @forelse ($locationtest as $item)
                                        <option @if ($item->location_test_id === $labourModel->labour_location_test) selected @endif
                                            value="{{ $item->location_test_id }}">{{ $item->location_test_name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div> --}}


                            <div class="col-md-4">
                                <label>สายหาคน</label>
                                <select name="labour_staff_sub" class="form-select select2-staff-sub" required
                                    @cannot('update labour staff') disabled @endcannot>
                                    <option @if ($labourModel->labour_staff_sub === 'no-sub') selected @endif value="no-sub">ไม่ระบุ
                                    </option>

                                    @forelse ($staffSub as $item)
                                        <option @if ($item->staff_sub_id == $labourModel->labour_staff_sub) selected @endif
                                            value="{{ $item->staff_sub_id }}">
                                            {{ $item->staff_sub_name }}
                                            {{ $item->staff_sub_phone ? '(' . $item->staff_sub_phone . ')' : '' }}
                                        </option>

                                    @empty
                                        <option disabled>ไม่มีข้อมูลพนักงาน</option>
                                    @endforelse
                                </select>

                            </div>

                            <div class="col-md-4">
                                <label>Staff</label>
                                <select name="labour_staff" class="form-select select2-staff"
                                    @cannot('update labour staff') disabled @endcannot>
                                    <option value="">Select a Staff</option>
                                    @forelse ($staffs as $item)
                                        <option @if ($item->staff_id === $labourModel->labour_staff) selected @endif
                                            value="{{ $item->staff_id }}">
                                            {{ $item->staff_name }}({{ $item->staff_nickname }}) {{ $item->staff_phone }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>



                        </div>

                    </div>


                    <!-- Tab 4: การเงินบัญชี -->
                    <div class="tab-pane fade" id="status" role="tabpanel">
                        <div class="row g-3">

                            <div class="col-md-3 mt-3">
                                <label>วันที่ วางเงินประกัน </label>
                                <input type="date" name="labour_cid_deposit_date" class="form-control"
                                    @cannot('account update labour') disabled @endcannot
                                    value="{{ $labourModel->labour_cid_deposit_date }}">
                            </div>
                            <div class="col-md-3 mt-3">
                                <label>จำนวนเงิน วางเงินประกัน</label>
                                <input type="number" name="labour_cid_deposit_total" class="form-control"
                                    @cannot('account update labour') disabled @endcannot
                                    value="{{ $labourModel->labour_cid_deposit_total }}" step="0.01"
                                    placeholder="0.00">
                            </div>
                            <div class="col-md-3 mt-3">
                                <label>Date CID-P</label>
                                <input type="date" name="labour_cidp_date" class="form-control"
                                    @cannot('account update labour') disabled @endcannot
                                    value="{{ $labourModel->labour_cidp_date }}">
                            </div>
                            <div class="col-md-3 mt-3">
                                <label>CID-P Total</label>
                                <select name="labour_cidp_total" class="form-select"
                                    @cannot('account update labour') disabled @endcannot>
                                    <option value="">--Select--</option>
                                    <option @if ($labourModel->labour_cidp_total === 'V1') selected @endif value="V1">V1</option>
                                    <option @if ($labourModel->labour_cidp_total === 'V2') selected @endif value="V2">V2</option>
                                </select>
                            </div>

                            <div class="col-md-3 mt-3">
                                <label>ประเภทการชำระเงิน</label>
                                <select name="payment_type" class="form-select"
                                    @cannot('account update labour') disabled @endcannot>
                                    <option value="">--Select--</option>
                                    <option @if ($labourModel->payment_type === 'เงินสด') selected @endif value="เงินสด">เงินสด
                                    </option>
                                    <option @if ($labourModel->payment_type === 'SCB') selected @endif value="SCB">SCB</option>
                                    <option @if ($labourModel->payment_type === 'BBL') selected @endif value="BBL">BBL</option>
                                </select>
                            </div>

                            <div class="col-md-3 mt-3">
                                <label>วันที่รับ Date CID-P</label>
                                <input type="date" name="labour_cidp_in_date" class="form-control"
                                    @cannot('account update labour') disabled @endcannot
                                    value="{{ $labourModel->labour_cidp_in_date }}">
                            </div>
                            <div class="col-md-3 mt-3">
                                <label>จำนวนเงิน รับ CID-P Total</label>
                                <select name="labour_cidp_in_total" class="form-select"
                                    @cannot('account update labour') disabled @endcannot>
                                    <option value="">--Select--</option>
                                    <option @if ($labourModel->labour_cidp_in_total === 'V1') selected @endif value="V1">V1</option>
                                    <option @if ($labourModel->labour_cidp_in_total === 'V2') selected @endif value="V2">V2</option>
                                </select>
                            </div>

                            <div class="col-md-3 mt-3">
                                <label>สถานะการคืนเงินประกัน</label>
                                <select name="labour_cid_deposit_status" id="" class="form-select"
                                    @cannot('account update labour') disabled @endcannot>
                                    <option @if ($labourModel->labour_cid_deposit_status === 'None') selected @endif value="None">None
                                    </option>
                                    <option @if ($labourModel->labour_cid_deposit_status === 'ยกเลิก-คืนเงินประกัน') selected @endif
                                        value="ยกเลิก-คืนเงินประกัน">
                                        ยกเลิก-คืนเงินประกัน</option>
                                    <option @if ($labourModel->labour_cid_deposit_status === 'ยกเลิก-ไม่คืนเงินประกัน') selected @endif
                                        value="ยกเลิก-ไม่คืนเงินประกัน">
                                        ยกเลิก-ไม่คืนเงินประกัน</option>
                                </select>
                            </div>

                            <div class="col-md-3 mt-3">
                                <label>วันที่คืนเงินวางประกัน</label>
                                <input type="date" name="labour_refund_deposit_date" class="form-control"
                                    @cannot('account update labour') disabled @endcannot
                                    value="{{ $labourModel->labour_refund_deposit_date }}">
                            </div>
                            <div class="col-md-3 mt-3">
                                <label>จำนวนเงินคืนวางเงินประกัน</label>
                                <input type="number" name="labour_refund_deposit_total" class="form-control"
                                    @cannot('account update labour') disabled @endcannot
                                    value="{{ $labourModel->labour_refund_deposit_total }}" step="0.01"
                                    placeholder="0.00">
                            </div>


                        </div>

                    </div>

                    <div class="tab-pane fade" id="docs" role="tabpanel">
                        <div class="row g-3">

                            <h4 class=" mt-3">จัดเก็บเอกสาร</h4>

                            @if ($labourModel->lead_id)
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle-fill"></i> <strong>แปลงมาจาก Lead</strong> -
                                        สามารถเปลี่ยน Docs. Type ได้ (เฉพาะครั้งแรกเท่านั้น หากยังไม่มีการตั้งค่า)
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-3">
                                <label>Docs. Type. (Path จัดเก็บเอกสาร)</label>
                                @if (!empty($labourModel->labour_location_doc) && empty($labourModel->lead_id))
                                    {{-- ถ้ามีค่าแล้วและไม่ได้มาจาก Lead ให้ล็อก --}}
                                    <select class="form-select" disabled>
                                        <option value="">Select a File Manage</option>
                                        @forelse ($fileManage as $item)
                                            <option @if ($item->file_manage_id === $labourModel->labour_location_doc) selected @endif
                                                value="{{ $item->file_manage_id }}">{{ $item->file_manage_name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                    <input type="hidden" name="labour_location_doc"
                                        value="{{ $labourModel->labour_location_doc }}">
                                    <small class="text-warning">
                                        <i class="bi bi-lock-fill"></i> ล็อกแล้ว - ไม่สามารถเปลี่ยนได้หลังจากตั้งค่าแล้ว
                                    </small>
                                @elseif(!empty($labourModel->labour_location_doc) && !empty($labourModel->lead_id))
                                    {{-- ถ้ามาจาก Lead และมี location_doc อยู่แล้ว ให้ส่งค่าแต่แสดงปกติ --}}
                                    <select name="labour_location_doc" class="form-select" required>
                                        <option value="">Select a File Manage</option>
                                        @forelse ($fileManage as $item)
                                            <option @if ($item->file_manage_id === $labourModel->labour_location_doc) selected @endif
                                                value="{{ $item->file_manage_id }}">{{ $item->file_manage_name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                    <small class="text-info">
                                        <i class="bi bi-info-circle-fill"></i> แปลงมาจาก Lead - มี Docs Type อยู่แล้ว
                                    </small>
                                @else
                                    {{-- ถ้ายังไม่มีค่า ให้เลือกได้ --}}
                                    <select name="labour_location_doc" class="form-select" required>
                                        <option value="">Select a File Manage</option>
                                        @forelse ($fileManage as $item)
                                            <option @if ($item->file_manage_id === $labourModel->labour_location_doc) selected @endif
                                                value="{{ $item->file_manage_id }}">{{ $item->file_manage_name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                    <small class="text-success">
                                        <i class="bi bi-unlock-fill"></i> ยังไม่มีการตั้งค่า - สามารถเลือกได้
                                    </small>
                                @endif
                            </div>

                            <div class="col-md-3 mb-2">
                                {{-- <label for="">Actions</label> --}}
                                <a href="{{ route('labour.CombinePDF', $labourModel->labour_id) }}"
                                    class="create-CombinePDF btn btn-danger float-end"><i class="fas fa-file-pdf"></i>
                                    CombinePDF</a>
                            </div>
                            <br>
                            <br>
                            <hr>

                            <div class="row g-3">

                                @foreach ($labourfiles as $key => $item)
                                {{-- {{$item->list_file_id}} --}}
                                    @php
                                        $filePath = asset(
                                            'storage/LABOURS/' .
                                                str_replace('\\', '/', $labourModel->labour_path) .
                                                '/' .
                                                str_replace('\\', '/', $item->labour_file_path),
                                        );
                                        $timestamp = $item->updated_at ? $item->updated_at->timestamp : time();
                                        $ext = strtolower(pathinfo($item->labour_file_path, PATHINFO_EXTENSION));
                                    @endphp
                                    @if (!empty($item->labour_file_path))
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                            <div class="card shadow-sm h-100 border-0">
                                                <div class="card-body d-flex flex-column align-items-center p-3">
                                                    @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                                                        <a href="{{ $filePath . '?v=' . $timestamp }}" target="_blank">
                                                            <img src="{{ $filePath . '?v=' . $timestamp }}"
                                                                alt="preview"
                                                                style="width:90px; height:120px; object-fit:contain; border:1px solid #eee; border-radius:8px; background:#fafbfc;" />
                                                        </a>
                                                    @elseif($ext === 'pdf')
                                                        <a href="{{ $filePath . '?v=' . $timestamp }}" target="_blank"
                                                            style="display:block;">
                                                            <iframe src="{{ $filePath . '?v=' . $timestamp }}"
                                                                style="width:150px; height:200px; border:1px solid #eee; border-radius:8px; background:#fafbfc;"
                                                                frameborder="0" loading="lazy"
                                                                onerror="this.style.display='none'; this.parentNode.querySelector('.pdf-icon').style.display='block';"></iframe>
                                                            <img class="pdf-icon"
                                                                src="https://cdn.jsdelivr.net/gh/walkxcode/dashboard-icons/svg/pdf.svg"
                                                                alt="PDF"
                                                                style="width:60px; height:80px; margin-top:10px; display:none; position:absolute; left:50%; transform:translateX(-50%);" />
                                                        </a>
                                                    @else
                                                        <a href="{{ $filePath . '?v=' . $timestamp }}" target="_blank">
                                                            <i class="fas fa-file-alt fa-3x text-secondary"></i>
                                                        </a>
                                                    @endif
                                                    <div class="mt-2 text-center small"
                                                        style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:110px;">
                                                        {{ $item->labour_file_path }}</div>
                                                    <div class="mt-1 text-muted small">{{ $item->labour_file_note }} |
                                                        {{ $item->labour_file_path ?? 'ไม่พบไฟล์' }}</div>
                                                    <div class="d-flex gap-2 mt-2">
                                                        <a href="{{ $filePath . '?v=' . $timestamp }}"
                                                            class="btn btn-sm btn-outline-primary" target="_blank"><i
                                                                class="fas fa-eye"></i> ดู</a>

                                                        {{-- @can('delete labour file')
                                                        <a href="" data-file-id="{{ $item->labour_file_id }}"
                                                            data-labour-id="{{ $labourModel->labour_id }}"
                                                            data-path="{{ $labourModel->labour_path . '/' . $item->labour_file_path }}"
                                                            class="delete-file text-danger"> <i class="fa fa-trash"></i>
                                                            Delete</a>
                                                    @endcan --}}


                                                        @can('delete labour file')
                                                            <a href="#" data-file-id="{{ $item->labour_file_id }}"
                                                                data-labour-id="{{ $labourModel->labour_id }}"
                                                                data-path="{{ $filePath }}"
                                                                class="delete-file btn btn-sm btn-outline-danger">ลบ</a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                            <div
                                                class="card shadow-sm h-100 border-0 d-flex flex-column align-items-center justify-content-center p-4 text-center">
                                                <div class="mb-2"><i
                                                        class="fas fa-cloud-upload-alt fa-3x text-secondary"></i></div>
                                                <div class="mb-2 text-muted">
                                                    </b>{{ $item->labour_file_note }}-({{ $item->labour_file_name }}</div>
                                                <input type="file" name="file_{{ $key }}"
                                                    class="form-control form-control-sm mb-2"
                                                    data-index="{{ $key }}">
                                                <input type="hidden" name="labour_file_name_{{ $key }}"
                                                    value="{{ $item->labour_file_name }}">
                                                <input type="hidden" name="labour_file_id_{{ $key }}"
                                                    value="{{ $item->labour_file_id }}">

                                                <div class="small text-muted">เลือกไฟล์เพื่ออัปโหลด</div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                {{-- แสดงรายการเอกสารใหม่ที่เพิ่มเข้ามาหลังจากสร้าง Labour --}}
                                @if ($listFiles->count() > 0)
                                    <div class="col-12">
                                        <hr class="my-3">
                                        <h6 class="text-primary mb-3">
                                            <i class="bi bi-plus-circle me-2"></i>เอกสารใหม่ที่เพิ่มเข้ามา
                                            ({{ $listFiles->count() }} รายการ)
                                        </h6>
                                    </div>
                                    @foreach ($listFiles as $key => $itemNew)
                                    {{-- {{ $itemNew->list_file_id }} --}}
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                            <div
                                                class="card shadow-sm h-100 border-warning border-2 d-flex flex-column align-items-center justify-content-center p-3">
                                                <div class="badge bg-warning text-dark mb-2">เอกสารใหม่</div>
                                                <div class="mb-2"><i class="fas fa-file-upload fa-3x text-warning"></i>
                                                </div>
                                                <div class="text-center fw-bold mb-2">{{ $itemNew->list_file_note }}</div>
                                                <div class="text-muted small mb-3">{{ $itemNew->list_file_name }}</div>

                                                <input type="file" name="file_new_{{ $key }}"
                                                    class="form-control form-control-sm mb-2"
                                                    data-index-new="{{ $key }}"
                                                    @cannot('update labour') disabled @endcannot>

                                                <input type="hidden" name="labour_file_name_new_{{ $key }}"
                                                    value="{{ $itemNew->list_file_name }}">
                                                <input type="hidden" name="list_file_id_new_{{ $key }}"
                                                    value="{{ $itemNew->list_file_id }}">

                                                <small class="text-muted text-center">กรุณาเลือกไฟล์แล้วกดบันทึก</small>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            {{-- labour File  --}}
                        </div>
                    </div>



                </div>

                <!-- Tab VISA -->
                <div class="tab-pane fade" id="visa" role="tabpanel">
                    <div class="row g-3 mt-2">
                        <div class="col-md-3">
                            <label class="form-label">วันที่ยืนวีซ่า</label>
                            <input type="date" name="labour_visa_submit_date" class="form-control"
                                value="{{ $labourModel->labour_visa_submit_date }}"
                                @cannot('update labour') disabled @endcannot>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">วันที่ Approved VISA</label>
                            <input type="date" name="labour_visa_approved_date" class="form-control"
                                value="{{ $labourModel->labour_visa_approved_date }}"
                                @cannot('update labour') disabled @endcannot>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status VISA</label>
                            <select name="labour_visa_status" class="form-select" id="visa_status"
                                @cannot('update labour') disabled @endcannot>
                                <option value="none" @if ($labourModel->labour_visa_status === 'none' || $labourModel->labour_visa_status === null) selected @endif>None</option>
                                <option value="approved" @if ($labourModel->labour_visa_status === 'approved') selected @endif>Approved
                                </option>
                                <option value="rejected" @if ($labourModel->labour_visa_status === 'rejected') selected @endif>Rejected
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">วันที่ Reject VISA</label>
                            <input type="date" name="labour_visa_reject_date" class="form-control"
                                value="{{ $labourModel->labour_visa_reject_date }}" id="visa_reject_date"
                                @cannot('update labour') disabled @endcannot>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">วันที่ออก VISA หรือ วันที่เริ่มต้น VISA</label>
                            <input type="date" name="labour_visa_start_date" class="form-control"
                                value="{{ $labourModel->labour_visa_start_date }}"
                                @cannot('update labour') disabled @endcannot>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Visa Note (กรณี VISA มี Status rejected)</label>
                            <textarea name="labour_visa_note" class="form-control" rows="3" id="visa_note"
                                placeholder="กรอกหมายเหตุกรณี VISA ถูกปฏิเสธ" @cannot('update labour') disabled @endcannot>{{ $labourModel->labour_visa_note }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ไฟล์เอกสาร VISA</label><br>
                            @if ($labourModel->labour_visa_file)
                                <div class="border p-2 rounded mb-2">
                                    <p>📄 <strong>{{ $labourModel->labour_visa_file }}</strong></p>
                                    <a href="{{ asset('storage/LABOURS/' . $labourModel->labour_path . '/' . $labourModel->labour_visa_file) }}"
                                        target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> ดูไฟล์
                                    </a>
                                    @can('delete labour file file')
                                        <a href="{{ route('labour.visafile.delete', $labourModel->labour_id) }}"
                                            onclick="return confirm('คุณแน่ใจว่าต้องการลบไฟล์นี้?')"
                                            class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> ลบไฟล์
                                        </a>
                                    @endcan
                                </div>
                            @else
                                <input type="file" name="visa_file" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" @cannot('update labour') disabled @endcannot>
                                <small class="text-muted">รองรับไฟล์: PDF, JPG, PNG, DOC, DOCX</small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <hr class="text-success">
                    <label for="">บันทึกเพิ่มเติม</label>
                    <textarea name="labour_note" class="form-control" cols="30" rows="3" placeholder="บันทึกเพิ่มเติม">{{ $labourModel->labour_note }}</textarea>
                </div>


                <button type="submit" class="btn btn-success float-end"><i class="fa fa-save"></i> อัพเดทข้อมูล</button>
                <br>
            </div>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="add-CombinePDF" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Combine PDF</h5>
                    <button type="button" class="btn-close close-modal-btn" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>เนื้อหา Modal จะโหลดที่นี่</p>
                </div>
            </div>
        </div>
    </div>



    <style>
        @media (max-width: 575.98px) {
            .modal.custom-modal .modal-dialog {
                max-width: 100% !important;
                margin: 0;
            }

            .modal.custom-modal .modal-content {
                height: 100vh;
                border-radius: 0;
            }
        }
    </style>


    <script>
        // Form Validation
        $(document).ready(function() {
            $('#form-create').on('submit', function(e) {
                const form = this;

                // Check if form is valid using HTML5 validation
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Add Bootstrap validation classes
                    $(form).addClass('was-validated');

                    // Find first invalid field and scroll to it
                    const firstInvalid = $(form).find(':invalid').first();
                    if (firstInvalid.length) {
                        // Find which tab contains the invalid field
                        const tabPane = firstInvalid.closest('.tab-pane');
                        if (tabPane.length) {
                            const tabId = tabPane.attr('id');
                            // Switch to the tab containing the invalid field
                            $(`button[data-bs-target="#${tabId}"]`).tab('show');
                        }

                        // Scroll to the invalid field
                        $('html, body').animate({
                            scrollTop: firstInvalid.offset().top - 100
                        }, 500);

                        // Focus on the field
                        firstInvalid.focus();
                    }

                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                        text: 'มีฟิลด์บางฟิลด์ที่จำเป็นต้องกรอก กรุณาตรวจสอบและกรอกข้อมูลให้ครบถ้วน',
                        confirmButtonText: 'ตกลง'
                    });

                    return false;
                }
            });
        });

        $(document).ready(function() {
            // ฟังก์ชันคำนวณอายุ
            function calculateAge() {
                const birthDate = new Date($('#labour_birthday').val());
                const today = new Date();

                if (isNaN(birthDate)) return; // ตรวจสอบว่ามีการกรอกวันที่หรือยัง

                let years = today.getFullYear() - birthDate.getFullYear();
                let months = today.getMonth() - birthDate.getMonth();
                let days = today.getDate() - birthDate.getDate();

                // ปรับเดือนและวันกรณีที่คำนวณเป็นลบ
                if (days < 0) {
                    months--;
                    days += new Date(today.getFullYear(), today.getMonth(), 0)
                        .getDate(); // วันสุดท้ายของเดือนก่อนหน้า
                }
                if (months < 0) {
                    years--;
                    months += 12;
                }

                // แสดงผลลัพธ์
                $('#age_result').text(`${years} ปี ${months} เดือน ${days} วัน`);
            }

            // เรียกฟังก์ชันเมื่อโหลดหน้าและเมื่อมีการเปลี่ยนแปลงวันที่เกิด
            calculateAge();
            $('#labour_birthday').on('change', calculateAge);
        });
    </script>


    <script>
        $(document).ready(function() {
            // เมื่อผู้ใช้เปลี่ยนวันที่เริ่มต้นของโรค
            $('#labour_disease_start').on('change', function() {
                calculateDiseaseExpiry();
            });
            $('#labour_cid_start').on('change', function() {
                calculateCIDExpiry();
            });
            $('#labour_affidavit_start').on('change', function() {
                calculateAffidavitExpiry();
            });
            calculateDiseaseExpiry();
            calculateCIDExpiry();
            calculateAffidavitExpiry();
        });


        // ฟังก์ชันสำหรับคำนวณวันหมดอายุของ CID โดยไม่นับวันเสาร์และอาทิตย์
        // ฟังก์ชันสำหรับคำนวณวันหมดอายุของ CID โดยนับรวมวันเสาร์และอาทิตย์ (180 วัน)
        function calculateCIDExpiry() {
            const startValue = $('#labour_cid_start').val();

            if (!startValue) {
                $('#labour_cid_expriry').val('');
                return;
            }

            const startDate = new Date(startValue);
            const cidDuration = 180; // 180 วัน (รวมเสาร์อาทิตย์)

            const expiryDate = new Date(startDate);
            expiryDate.setDate(expiryDate.getDate() + cidDuration); // เพิ่มไปอีก 180 วันรวมวันหยุด

            const yyyy = expiryDate.getFullYear();
            const mm = String(expiryDate.getMonth() + 1).padStart(2, '0');
            const dd = String(expiryDate.getDate()).padStart(2, '0');
            const formattedDate = `${yyyy}-${mm}-${dd}`;

            $('#labour_cid_expriry').val(formattedDate);
        }

        function calculateAffidavitExpiry() {
            const startValue = $('#labour_affidavit_start').val();

            if (!startValue) {
                $('#labour_affidavit_expriry').val('');
                return;
            }

            const startDate = new Date(startValue);
            const cidDuration = 180; // 180 วัน (รวมเสาร์อาทิตย์)

            const expiryDate = new Date(startDate);
            expiryDate.setDate(expiryDate.getDate() + cidDuration); // เพิ่มไปอีก 180 วันรวมวันหยุด

            const yyyy = expiryDate.getFullYear();
            const mm = String(expiryDate.getMonth() + 1).padStart(2, '0');
            const dd = String(expiryDate.getDate()).padStart(2, '0');
            const formattedDate = `${yyyy}-${mm}-${dd}`;

            $('#labour_affidavit_expriry').val(formattedDate);
        }



        function calculateDiseaseExpiry() {
            const startValue = $('#labour_disease_start').val();
            if (!startValue) {
                $('#labour_disease_expriry').val('');
                return;
            }

            const startDate = new Date(startValue);
            const diseaseDuration = 90; // นับรวมทุกวัน

            const expiryDate = new Date(startDate);
            expiryDate.setDate(expiryDate.getDate() + diseaseDuration); // เพิ่มวันต่อเนื่อง 90 วัน

            const yyyy = expiryDate.getFullYear();
            const mm = String(expiryDate.getMonth() + 1).padStart(2, '0');
            const dd = String(expiryDate.getDate()).padStart(2, '0');
            const formattedDate = `${yyyy}-${mm}-${dd}`;

            $('#labour_disease_expriry').val(formattedDate);
        }



        $(document).ready(function() {
            // ฟังก์ชัน passportExpiry() สำหรับคำนวณวันหมดอายุ
            function passportExpiry() {
                // Get the selected expiry date
                var expiryDate = new Date($('#labour_passport_expiry').val());
                var today = new Date(); // Current date

                // Calculate the difference in time
                var timeDiff = expiryDate.getTime() - today.getTime();

                // Calculate the number of days until expiry
                var daysUntilExpiry = Math.ceil(timeDiff / (1000 * 3600 * 24));

                // Update the span with the number of days left
                if ($('#labour_passport_expiry').val() === "") {
                    $('#daysLeft').text('กรุณาเลือกวันที่');
                } else if (daysUntilExpiry > 0) {
                    $('#daysLeft').text('วันหมดอายุ : ' + daysUntilExpiry + ' วัน');
                } else {
                    $('#daysLeft').text('หมดอายุแล้ว');
                }
            }
            // ทำงานเมื่อมีการเปลี่ยนวันที่ใน input[type="date"]
            $('#labour_passport_expiry').on('change', function() {
                passportExpiry();
            });

            passportExpiry()

            $('.delete-file').on('click', function(e) {
                e.preventDefault();
                var path = $(this).attr('data-path');
                var fileId = $(this).attr('data-file-id');
                var labourId = $(this).attr('data-labour-id');
                // ตรวจสอบค่าตัวแปรที่ได้รับ
                console.log('Path:', path);
                console.log('File ID:', fileId);
                console.log('Labour ID:', labourId);

                if (confirm('คุณแน่ใจนะว่าจะลบไฟล์ ' + path)) {
                    $.ajax({
                        url: '{{ route('labourfile.delete') }}',
                        method: 'GET',
                        data: {
                            path: path,
                            fileId: fileId,
                            labourId: labourId,
                        }
                    }).done(function(response) {
                        console.log(response); // แสดงข้อมูลที่ตอบกลับใน console
                        if (response.success) {
                            alert(response.success);
                            location.reload(); // Reload หน้าเว็บเมื่อสำเร็จ
                        } else {
                            alert(response.error);
                        }
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        console.error("Request failed: " + textStatus + ", " + errorThrown);
                        alert('เกิดข้อผิดพลาดในการลบไฟล์');
                    });
                } else {
                    alert('ยกเลิกการลบสำเร็จ!');
                }
            });
        });

        let CombinePDFModal;

        // VISA Status Management
        $(document).ready(function() {
            function toggleVisaFields() {
                const status = $('#visa_status').val();
                const rejectDate = $('#visa_reject_date');
                const visaNote = $('#visa_note');

                if (status === 'rejected') {
                    rejectDate.prop('disabled', false).prop('required', true);
                    visaNote.prop('disabled', false).prop('required', true);
                } else {
                    rejectDate.prop('disabled', true).prop('required', false).val('');
                    visaNote.prop('disabled', true).prop('required', false).val('');
                }
            }

            // Initialize on page load
            toggleVisaFields();

            // Handle status change
            $('#visa_status').on('change', toggleVisaFields);
        });

        $(document).ready(function() {
            $('.create-CombinePDF').on('click', function(e) {
                e.preventDefault();

                const url = $(this).attr('href');
                const modalEl = document.getElementById('add-CombinePDF');

                CombinePDFModal = new bootstrap.Modal(modalEl); // <-- ถูกต้องแล้ว
                $('#add-CombinePDF .modal-body').html('<div class="text-center p-3">กำลังโหลด...</div>');
                $('#add-CombinePDF .modal-body').load(url);
                CombinePDFModal.show();
            });
        });

        // ปุ่มกดปิดจาก .close-modal-btn
        $(document).on('click', '.close-modal-btn', function() {
            if (CombinePDFModal) {
                CombinePDFModal.hide();
            }
        });



        $('.job-group').on('change', function() {
            var jobgroup = $(this).val();
            $.ajax({
                url: '{{ route('jobgroup.ajaxSelectPosition') }}',
                method: 'GET',
                data: {
                    _token: '{{ csrf_token() }}',
                    jobgroup: jobgroup
                },
                success: function(response) {
                    var options = '';
                    options +=
                        '<option value="" disabled selected>Select a position</option>';
                    response.forEach(function(position) {
                        options += '<option value="' + position.position_id + '">' +
                            position.position_name + '</option>';
                    });

                    $('#position').html(options);
                }
            });
        });
    </script>

    <!-- Bootstrap Tab JS -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tabTriggerEls = document.querySelectorAll('#labourTab button[data-bs-toggle="tab"]');
            const tabList = [...tabTriggerEls];

            // เปิด tab ตาม hash ที่อยู่ใน URL
            const hash = window.location.hash;
            if (hash) {
                const someTabTriggerEl = document.querySelector(`button[data-bs-target="${hash}"]`);
                if (someTabTriggerEl) {
                    new bootstrap.Tab(someTabTriggerEl).show();
                }
            }

            // เมื่อเปลี่ยน tab → บันทึก hash ลง URL
            tabList.forEach(tabEl => {
                tabEl.addEventListener("shown.bs.tab", function(event) {
                    const target = event.target.getAttribute("data-bs-target");
                    history.replaceState(null, null, target); // เปลี่ยน hash
                });
            });
            
            // Initialize Select2
            $('.select2-customer').select2({
                placeholder: 'Select a Customer',
                allowClear: true,
                width: '100%'
            });
            
            $('.select2-examination').select2({
                placeholder: 'Select a Examination round',
                allowClear: true,
                width: '100%'
            });
            
            $('.select2-position').select2({
                placeholder: 'Select a Position',
                allowClear: true,
                width: '100%'
            });
            
            $('.select2-staff').select2({
                placeholder: 'Select a Staff',
                allowClear: true,
                width: '100%'
            });
            
            $('.select2-staff-sub').select2({
                placeholder: 'Select สายหาคน',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection
