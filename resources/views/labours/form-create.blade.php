@extends('layouts.main')
@section('content')

<div class="row">
    <div class="card">
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h6 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>กรุณาแก้ไขข้อผิดพลาดต่อไปนี้:</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <form action="{{ route('labour.store') }}" method="post" id="form-create" novalidate>
                @csrf

                  <div class="row float-end mb-4">

                <div class="col-md-12 text-success">
                    <label>สถานะคนงาน (Status)</label>
                    <select name="labour_status" class="form-select">
                        <option value="wait">กำลังดำเนินการ
                        </option>
                    </select>
                </div>
            </div>

              <br>
            <br>

                <!-- Nav Tabs -->
                <ul class="nav nav-tabs mb-4" id="labourTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-basic" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                            <i class="bi bi-person-badge-fill me-1"></i> ข้อมูลคนงาน
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-passport" data-bs-toggle="tab" data-bs-target="#passport" type="button" role="tab">
                            <i class="bi bi-journal-medical me-1"></i> หนังสือเดินทาง & สุขภาพ
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-group" data-bs-toggle="tab" data-bs-target="#group" type="button" role="tab">
                            <i class="bi bi-people-fill me-1"></i> กลุ่มงาน
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-docs" data-bs-toggle="tab" data-bs-target="#docs" type="button" role="tab">
                            <i class="bi bi-folder2-open me-1"></i> เอกสาร
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-status" data-bs-toggle="tab" data-bs-target="#status" type="button" role="tab">
                            <i class="bi bi-wallet2 me-1"></i> การเงิน บัญชี
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-visa" data-bs-toggle="tab" data-bs-target="#visa" type="button" role="tab">
                            <i class="bi bi-passport me-1"></i> VISA
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="labourTabContent">
                    <!-- Tab 1: ข้อมูลคนงาน -->
                    <div class="tab-pane fade show active" id="basic" role="tabpanel">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-1">
                                <label>Prefix <span class="text-danger">*</span></label>
                                <select name="labour_prefix" class="form-select" required>
                                    <option value="MR">MR.</option>
                                    <option value="MS">MS.</option>
                                    <option value="MRS">MRS.</option>
                                </select>
                                <div class="invalid-feedback">กรุณาเลือก Prefix</div>
                            </div>
                            <div class="col-md-2">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="labour_firstname" placeholder="Firstname" required>
                                <div class="invalid-feedback">กรุณากรอกชื่อ</div>
                            </div>
                            <div class="col-md-2">
                                <label>Lastname <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="labour_lastname" placeholder="Lastname " required>
                                <div class="invalid-feedback">กรุณากรอกนามสกุล</div>
                            </div>
                            <div class="col-md-2">
                                <label>Birthday <span class="text-danger">*</span> <span id="age_result"></span></label>
                                <input type="date" class="form-control" name="labour_birthday" placeholder="birthday" id="labour_birthday" required>
                                <div class="invalid-feedback">กรุณาเลือกวันเกิด</div>
                            </div>
                            <div class="col-md-2">
                                <label>Phone.</label>
                                <input type="text" class="form-control" name="labour_phone" placeholder="+66" >
                            </div>
                        </div>
                    </div>
                    <!-- Tab 2: Passport & สุขภาพ -->
                    <div class="tab-pane fade" id="passport" role="tabpanel">
                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <label>Passport No.</label>
                                <input type="text" name="labour_passport_number" class="form-control" placeholder="Passport Number">
                            </div>
                            <div class="col-md-2">
                                <label>Data Issue</label>
                                <input type="date" name="labour_passport_issue" class="form-control" placeholder="Date Issue" >
                            </div>
                            <div class="col-md-2">
                                <label>Data Expiry <span id="daysLeft" class="text-info">จำนวนวันหมดอายุ : </span></label>
                                <input type="date" name="labour_passport_expiry" id="labour_passport_expiry" class="form-control" placeholder="Date Expiry">
                            </div>
                            <div class="col-md-2">
                                <label>Register Number.</label>
                                <input type="text" name="labour_register_number" class="form-control" placeholder="Register Number" >
                            </div>
                            <div class="col-md-3">
                                <label>Disease Start (วันออกผลโรค) </label>
                                <input type="date" name="labour_disease_start" id="labour_disease_start" class="form-control" placeholder="Disease Start">
                            </div>
                            <div class="col-md-3">
                                <label>Disease Expiry (ผลโรคหมดอายุ) คำนวน 90 วัน </label>
                                <input type="date" name="labour_disease_expriry" id="labour_disease_expriry" class="form-control" >
                            </div>
                            <div class="col-md-3">
                                <label>date disease results (วันรับผลโรค) </label>
                                <input type="date" name="labour_disease_results_date" id="labour_disease_expriry"  class="form-control" >
                            </div>
                            <div class="col-md-3">
                                <label>Status Disease (สถานะผลโรค)</label>
                                <select name="labour_disease_status" class="form-select">
                                    <option value="5">--ไม่ระบุ---</option>
                                    <option value="0">รอตรวจผลโรค</option>
                                    <option value="1">ผลโรคผ่าน</option>
                                    <option value="2">รอตรวจผลโรคซ้ำ</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>วันที่ยื่น CID</label>
                                <input type="date" name="labour_cid_stand_date" class="form-control"  >
                            </div>
                            <div class="col-md-3">
                                <label>CID Start</label>
                                <input type="date" name="labour_cid_start" class="form-control" id="labour_cid_start" placeholder="Register Number" >
                            </div>
                            <div class="col-md-3">
                                <label>CID Expiry</label>
                                <input type="date" name="labour_cid_expriry" class="form-control" id="labour_cid_expriry" placeholder="CID Expiry" value="" >
                            </div>
                            <div class="col-md-3">
                                <label>CID Results</label>
                                <select name="labour_cid_results" class="form-select">
                                    <option value="">ไม่ระบุ</option>
                                    @forelse ($CidResults as $item)
                                        <option  value="{{$item->cid_results_id}}">{{$item->cid_results_name}}</option>
                                    @empty
                                        ไม่มีข้อมูล
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Affidavit issues Date :</label>
                                <input type="date" name="labour_affidavit_start" class="form-control" id="labour_affidavit_start">
                            </div>
                            <div class="col-md-3">
                                <label>Affidavit Date Expriry :</label>
                                <input type="date" name="labour_affidavit_expriry" class="form-control" id="labour_affidavit_expriry">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>CID File</label>
                                <input type="file" name="" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                    <!-- Tab 3: กลุ่มงาน -->
                    <div class="tab-pane fade" id="group" role="tabpanel">
                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <label>Customer (นายจ้าง)</label>
                                <select name="labour_customer" class="form-select">
                                    <option value="">Select a Customer</option>
                                    @forelse ($customers as $item)
                                        <option value="{{$item->customer_id}}">{{$item->customer_name}}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Examination round (รอบสอบ)</label>
                                <select name="labour_examination" class="form-select" >
                                    <option value="">Select a Examination round</option>
                                    @forelse ($examinationRound as $item)
                                        <option value="{{$item->examination_round_name}}">{{date('d-m-Y',strtotime($item->examination_round_name))}}-{{$item->examination_round_note}}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Country <span class="text-danger">*</span></label>
                                <select name="labour_country" class="form-select country" required >
                                    <option value="">Select a Country</option>
                                    @forelse ($country as $item)
                                        <option value="{{ $item->country_id }}">{{ $item->country_name_en }}</option>
                                    @empty
                                        No date
                                    @endforelse
                                </select>
                                <div class="invalid-feedback">กรุณาเลือกประเทศ</div>
                            </div>
                            <div class="col-md-3">
                                <label>Job Group <span class="text-danger">*</span></label>
                                <select name="labour_job_group" class="form-select job-group" required>
                                    <option value="">Select a Job Group</option>
                                    @forelse ($jobGroup as $item)
                                        <option value="{{ $item->job_group_id }}">{{ $item->job_group_name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                <div class="invalid-feedback">กรุณาเลือก Job Group</div>
                            </div>
                            <div class="col-md-3">
                                <label>Position <span class="text-danger">*</span></label>
                                <select name="labour_position" class="form-select" id="position" required>
                                    <option value="">Select a Position</option>
                                    @forelse ($positions as $pos)
                                        <option value="{{ $pos->position_id }}">{{ $pos->position_name }}</option>
                                    @empty
                                        <!-- ไม่มี position จะโหลดจาก AJAX เมื่อเลือก job group -->
                                    @endforelse
                                </select>
                                <div class="invalid-feedback">กรุณาเลือก Position</div>
                            </div>

                            <div class="col-md-3">
                                <label>Staff <span class="text-danger">*</span></label>
                                <select name="labour_staff" class="form-select" required>
                                    <option value="">Select a Staff</option>
                                    @forelse ($staffs as $item)
                                        <option value="{{$item->staff_id}}">{{$item->staff_name}}({{$item->staff_nickname}}) {{$item->staff_phone }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                <div class="invalid-feedback">กรุณาเลือก Staff</div>
                            </div>
                            <div class="col-md-3">
                                <label>สายหาคน <span class="text-danger">*</span></label>
                                <select name="labour_staff_sub" class="form-select" required>
                                    <option value="no-sub">Null</option>
                                    @forelse ($staffSub as $item)
                                        <option value="{{$item->staff_sub_id}}">{{$item->staff_sub_name}} {{ $item->staff_sub_phone }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                <div class="invalid-feedback">กรุณาเลือกสายหาคน</div>
                            </div>
                        </div>
                     
                    </div>
                    <!-- Tab 4: เอกสาร -->
                    <div class="tab-pane fade" id="docs" role="tabpanel">
                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <label>Docs. Type. (Path จัดเก็บเอกสาร) <span class="text-danger">*</span></label>
                                <select name="labour_location_doc" class="form-select" required>
                                    <option value="">Select a File Manage</option>
                                    @forelse ($fileManage as $item)
                                        <option value="{{$item->file_manage_id}}">{{$item->file_manage_name}}</option>
                                    @empty
                                    @endforelse
                                </select>
                                <div class="invalid-feedback">กรุณาเลือก Docs Type</div>
                            </div>
                            <div class="col-md-12">
                                <label>ไฟล์เอกสาร</label>
                                <div>No Data File</div>
                            </div>
                        </div>
                    </div>
                    <!-- Tab 5: การเงิน/บัญชี -->
                    <div class="tab-pane fade" id="status" role="tabpanel">
                        
                        <hr class="my-4">
                        <h5 class="mb-3">ข้อมูลการเงิน</h5>
                        <div class="row g-3 mt-2">
                            <div class="col-md-3 mt-3">
                                <label>วันที่ วางเงินประกัน </label>
                                <input type="date" name="labour_cid_deposit_date" class="form-control" >
                            </div>
                            <div class="col-md-3 mt-3">
                                <label>จำนวนเงิน วางเงินประกัน</label>
                                <input type="number" name="labour_cid_deposit_total" class="form-control"  step="0.01" placeholder="0.00">
                            </div>
                            <div class="col-md-3 mt-3">
                                <label>Date CID-P</label>
                                <input type="date" name="labour_cidp_date" class="form-control">
                            </div>
                            <div class="col-md-3 mt-3">
                                <label>CID-P Total</label>
                                <input type="number" name="labour_cidp_total" class="form-control" step="0.01" placeholder="0.00">
                            </div>
                            <div class="col-md-3 mt-3">
                                <label>วันที่รับ Date CID-P</label>
                                <input type="date" name="labour_cidp_in_date" class="form-control" >
                            </div>
                            <div class="col-md-3 mt-3">
                                <label>จำนวนเงิน รับ CID-P Total</label>
                                <input type="number" name="labour_cidp_in_total" class="form-control"  step="0.01" placeholder="0.00">
                            </div>
                            <div class="col-md-3 mt-3">
                                <label>สถานะการคืนเงินประกัน</label>
                                <select name="labour_cid_deposit_status" id="" class="form-select">
                                    <option value="None">None</option>
                                    <option value="ยกเลิก-คืนเงินประกัน">ยกเลิก-คืนเงินประกัน</option>
                                    <option value="ยกเลิก-ไม่คืนเงินประกัน">ยกเลิก-ไม่คืนเงินประกัน</option>
                                </select>
                            </div>
                            <div class="col-md-3 mt-3">
                                <label>วันที่คืนเงินวางประกัน</label>
                                <input type="date" name="labour_refund_deposit_date" class="form-control" >
                            </div>
                            <div class="col-md-3 mt-3">
                                <label>จำนวนเงินคืนวางเงินประกัน</label>
                                <input type="number" name="labour_refund_deposit_total" class="form-control" step="0.01" placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <!-- Tab VISA -->
                    <div class="tab-pane fade" id="visa" role="tabpanel">
                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <label class="form-label">วันที่ยืนวีซ่า</label>
                                <input type="date" name="labour_visa_submit_date" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">วันที่ Approved VISA</label>
                                <input type="date" name="labour_visa_approved_date" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status VISA</label>
                                <select name="labour_visa_status" class="form-select" id="visa_status_create">
                                    <option value="none" selected>None</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">วันที่ Reject VISA</label>
                                <input type="date" name="labour_visa_reject_date" class="form-control" id="visa_reject_date_create" disabled>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">วันที่ออก VISA หรือ วันที่เริ่มต้น VISA</label>
                                <input type="date" name="labour_visa_start_date" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Visa Note (กรณี VISA มี Status rejected)</label>
                                <textarea name="labour_visa_note" class="form-control" rows="3" id="visa_note_create" 
                                    placeholder="กรอกหมายเหตุกรณี VISA ถูกปฏิเสธ" disabled></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ไฟล์เอกสาร VISA</label>
                                <input type="file" name="visa_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                <small class="text-muted">รองรับไฟล์: PDF, JPG, PNG, DOC, DOCX</small>
                            </div>
                        </div>
                    </div>

                       <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">บันทึกเพิ่มเติม</label>
                                <textarea name="labour_note" class="form-control" cols="30" rows="3" placeholder="บันทึกเพิ่มเติม"></textarea>
                            </div>
                        </div>
                </div>
                <button type="submit" class="btn btn-sm float-end text-success mt-4"><i class="fa fa-save"></i> บันทึกข้อมูล</button>
            </form>
        </div>
    </div>
</div>


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

        // VISA Status Management for Create Form
        $(document).ready(function() {
            function toggleVisaFieldsCreate() {
                const status = $('#visa_status_create').val();
                const rejectDate = $('#visa_reject_date_create');
                const visaNote = $('#visa_note_create');
                
                if (status === 'rejected') {
                    rejectDate.prop('disabled', false).prop('required', true);
                    visaNote.prop('disabled', false).prop('required', true);
                } else {
                    rejectDate.prop('disabled', true).prop('required', false).val('');
                    visaNote.prop('disabled', true).prop('required', false).val('');
                }
            }

            // Initialize on page load
            toggleVisaFieldsCreate();
            
            // Handle status change
            $('#visa_status_create').on('change', toggleVisaFieldsCreate);
        });

        $(document).ready(function() {
$('#labour_birthday').on('change', function() {
const birthDate = new Date($(this).val());
const today = new Date();

let years = today.getFullYear() - birthDate.getFullYear();
let months = today.getMonth() - birthDate.getMonth();
let days = today.getDate() - birthDate.getDate();

// ปรับเดือนและวันกรณีที่คำนวณเป็นลบ
if (days < 0) {
months--;
days += new Date(today.getFullYear(), today.getMonth(), 0).getDate(); // วันสุดท้ายของเดือนก่อนหน้า
}
if (months < 0) {
years--;
months += 12;
}

// แสดงผลลัพธ์
$('#age_result').text(`${years} ปี ${months} เดือน ${days} วัน`);
});
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




// ฟังก์ชันสำหรับคำนวณวันหมดอายุของโรค โดยไม่นับวันเสาร์และอาทิตย์
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
                options += '<option value="" disabled selected>Select a position</option>';
                response.forEach(function(position) {
                    options += '<option value="' + position.position_id + '">' + position.position_name + '</option>';
                });
                
                $('#position').html(options);
            }
        });
    });
});

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
    </script>
@endsection
