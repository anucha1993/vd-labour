@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
           <form action="{{route('labour.store')}}" method="post" id="form-create">
            @csrf
                <div class="row mt-3">
                    <h4> ข้อมูลคนงาน</h4>

                    <hr>
                    <div class="col-md-1">
                        <label>Prefix</label>
                        <select name="labour_prefix" class="form-select" required>
                            <option value="MR">MR.</option>
                            <option value="MS">MS.</option>
                            <option value="MRS">MRS.</option>
                        </select>
                    </div>


                    <div class="col-md-4">
                        <label>Name</label>
                        <input type="text" class="form-control" name="labour_firstname" placeholder="Firstname" required>
                    </div>

                    <div class="col-md-4">
                        <label>Lastname</label>
                        <input type="text" class="form-control" name="labour_lastname" placeholder="Lastname " required>
                    </div>
                    <div class="col-md-3">
                        <label>Phone.</label>
                        <input type="text" class="form-control" name="labour_phone" placeholder="+66" >
                    </div>


                </div>
                <div class="row mt-3">
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
                        <label>Passport No.</label>
                        <input type="text" name="labour_passport_number" class="form-control" 
                            placeholder="Passport Number">
                    </div>
                    <div class="col-md-2">
                        <label>Data Issue</label>
                        <input type="date" name="labour_passport_issue" class="form-control" placeholder="Date Issue" >
                    </div>
                    <div class="col-md-2 mt-3">
                        <label>Data Expiry</label> <span id="daysLeft" class="text-info">จำนวนวันหมดอายุ : </span>
                        <input type="date" name="labour_passport_expiry" id="labour_passport_expiry" class="form-control"
                            placeholder="Date Expiry">
                    </div>
                    <div class="col-md-2">
                        <label>Register Number.</label>
                        <input type="text" name="labour_register_number" class="form-control" placeholder="Register Number" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mt-3">
                        <label>Disease Start (วันออกผลโรค) </label>
                        <input type="date" name="labour_disease_start" id="labour_disease_start" class="form-control"
                            placeholder="Disease Start">
                    </div>
                    <div class="col-md-3 mt-3">
                        <label>Disease Expiry (ผลโรคหมดอายุ) คำนวน 30 วัน </label>
                        <input type="date" name="labour_disease_expiry" id="labour_disease_expiry" class="form-control" >
                    </div>
                    
                    
                    <div class="col-md-3 mt-3">
                        <label>CID Start</label>
                        <input type="date" name="labour_cid_start" class="form-control" id="labour_cid_start"
                            placeholder="Register Number" >
                    </div>
                    <div class="col-md-3 mt-3">
                        <label>CID Expiry</label>
                        <input type="date" name="labour_cid_expiry" class="form-control" id="labour_cid_expiry"
                            placeholder="CID Expiry" value="" >
                    </div>
                    
                    
                </div>

                <hr>
                <h4>ข้อมูลกลุ่มงาน</h4>
                <div class="row">
                    <div class="col-md-3 mt-3">
                        <label>Examination round (รอบสอบ)</label>
                        <select name="labour_examination" class="form-select" required>
                            <option value="">Select a Examination round</option>

                             @forelse ($examinationRound as $item)
                             <option value="{{$item->examination_round_name}}">{{date('d-m-Y',strtotime($item->examination_round_name))}}</option>
                             @empty
                                 
                             @endforelse
                        </select>
                    </div>

                    <div class="col-md-3 mt-3">
                        <label>Country</label>
                        <select name="labour_country" class="form-select country" required>
                            <option value="">Select a Country</option>
                            @forelse ($country as $item)
                                <option value="{{ $item->country_id }}">{{ $item->country_name_en }}</option>
                            @empty
                                No date
                            @endforelse

                        </select>
                    </div>
                    <div class="col-md-3 mt-3">
                        <label>Job Group</label>
                        <select name="labour_job_group" class="form-select job-group" required>
                            <option value="">Select a Job Group</option>

                            @forelse ($jobGroup as $item)
                                <option value="{{ $item->job_group_id }}">{{ $item->job_group_name }}</option>
                            @empty
                            @endforelse

                        </select>
                    </div>
                    <div class="col-md-3 mt-3">
                        <label>Position</label>
                        <select name="labour_position" class="form-select" id="position" required>
                            <option value="">Select a Position</option>

                        </select>
                    </div>

                    <div class="col-md-3 mt-3">
                        <label>lacation Test</label>
                        <select name="labour_location_test" class="form-select" required>
                            <option value="">Select a Localtion Test</option>
                            @forelse ($locationtest as $item)
                            <option value="{{$item->location_test_id}}">{{$item->location_test_name}}</option>
                            @empty
                                
                            @endforelse
                        </select>
                    </div>

                    <div class="col-md-3 mt-3">
                        <label>Docs. Type. (Path จัดเก็บเอกสาร)</label>
                        <select name="labour_location_doc" class="form-select" required>
                            <option value="">Select a File Manage</option>
                            @forelse ($fileManage as $item)
                            <option value="{{$item->file_manage_id}}">{{$item->file_manage_name}}</option>
                            @empty
                                
                            @endforelse
                        </select>
                    </div>

                </div>
                <hr>
                <h4>ข้อมูลสถานะ</h4>
                <div class="row">
                    <div class="col-md-3">
                        <label>Staff</label>
                        <select name="labour_staff" class="form-select" required>
                            <option value="">Select a Staff</option>
                            @forelse ($staffs as $item)
                            <option value="{{$item->staff_id}}">{{$item->staff_name}}({{$item->staff_nickname}})</option>
                            @empty
                                
                            @endforelse
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>สายหาคน</label>
                        <select name="labour_staff_sub" class="form-select" required>
                            <option value="no-sub">Null</option>
                            @forelse ($staffSub as $item)
                            <option value="{{$item->staff_sub_id}}">{{$item->staff_sub_name}}</option>
                            @empty
                                
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Status</label>
                        <select name="labour_status" class="form-select" required>
                            <option value="wait">กำลังดำเนินการ</option>

                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="">บันทึกเพิ่มเติม</label>
                        <textarea name="labour_note" class="form-control" cols="30" rows="3" placeholder="บันทึกเพิ่มเติม"></textarea>
                    </div>

                </div>
                <hr>
                ไฟล์เอกสาร
                <div class="row">
                    No Data File
                </div>
                <button type="submit" class="btn btn-sm float-end text-success"><i class="fa fa-save"></i> บันทึกข้อมูล</button>
            </div>
        </div>
    </div>

    <script>

$(document).ready(function() {
    // เมื่อผู้ใช้เปลี่ยนวันที่เริ่มต้นของโรค
    $('#labour_disease_start').on('change', function() {
        calculateDiseaseExpiry();
    });
    $('#labour_cid_start').on('change', function() {
        calculateCIDExpiry();
    });
    calculateDiseaseExpiry();
    calculateCIDExpiry();
});


// ฟังก์ชันสำหรับคำนวณวันหมดอายุของ CID โดยไม่นับวันเสาร์และอาทิตย์
function calculateCIDExpiry() {
    var startDate = new Date($('#labour_cid_start').val());
    var diseaseDuration = 30; // ระยะเวลาหมดอายุใน 30 วัน

    if ($('#labour_cid_start').val() === "") {
        $('#labour_cid_expiry').val('');
        return;
    }

    var currentDate = new Date(startDate);
    var daysAdded = 0;

    // ลูปเพื่อเพิ่มจำนวนวัน โดยไม่นับรวมวันเสาร์และอาทิตย์
    while (daysAdded < diseaseDuration) {
        currentDate.setDate(currentDate.getDate() + 1);

        // ตรวจสอบว่าวันปัจจุบันไม่ใช่วันเสาร์ (6) หรือวันอาทิตย์ (0)
        if (currentDate.getDay() !== 0 && currentDate.getDay() !== 6) {
            daysAdded++;
        }
    }

    // ตั้งค่าใน input ของวันหมดอายุ
    var yyyy = currentDate.getFullYear();
    var mm = String(currentDate.getMonth() + 1).padStart(2, '0'); // เดือนจะต้องบวก 1 เพราะมันนับจาก 0
    var dd = String(currentDate.getDate()).padStart(2, '0');
    var formattedExpiryDate = yyyy + '-' + mm + '-' + dd;

    $('#labour_cid_expiry').val(formattedExpiryDate);
}


// ฟังก์ชันสำหรับคำนวณวันหมดอายุของโรค โดยไม่นับวันเสาร์และอาทิตย์
function calculateDiseaseExpiry() {
    var startDate = new Date($('#labour_disease_start').val());
    var diseaseDuration = 30; // สมมติว่าผลโรคจะหมดอายุใน 14 วัน (กำหนดตามความต้องการ)

    if ($('#labour_disease_start').val() === "") {
        $('#labour_disease_expiry').val('');
        return;
    }

    var currentDate = new Date(startDate);
    var daysAdded = 0;

    // ลูปเพื่อเพิ่มจำนวนวัน โดยไม่นับรวมวันเสาร์และอาทิตย์
    while (daysAdded < diseaseDuration) {
        currentDate.setDate(currentDate.getDate() + 1);

        // ตรวจสอบว่าวันปัจจุบันไม่ใช่วันเสาร์ (6) หรือวันอาทิตย์ (0)
        if (currentDate.getDay() !== 0 && currentDate.getDay() !== 6) {
            daysAdded++;
        }
    }

    // ตั้งค่าใน input ของวันหมดอายุ
    var yyyy = currentDate.getFullYear();
    var mm = String(currentDate.getMonth() + 1).padStart(2, '0'); // เดือนจะต้องบวก 1 เพราะมันนับจาก 0
    var dd = String(currentDate.getDate()).padStart(2, '0');
    var formattedExpiryDate = yyyy + '-' + mm + '-' + dd;

    $('#labour_disease_expiry').val(formattedExpiryDate);
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
