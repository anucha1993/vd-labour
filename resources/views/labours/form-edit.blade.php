@extends('layouts.main')
@section('content')
    <div class="row">
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

        <div class="card">
            <div class="card-body">
                <form action="{{ route('labour.update', $labourModel->labour_id) }}" enctype="multipart/form-data"
                    method="post" id="form-create">
                    @csrf
                    @method('put')
                    <div class="row mt-3">
                        <h4> ข้อมูลคนงาน </h4>
                        <span>ตำแหน่งไฟล์ : <a
                                href="#">{{ env('LOCATION_PATH') }}{{ '\\' . $labourModel->labour_path }}
                            </a></span>

                        <hr>
                      

                        <div class="col-md-1">
                            <label>Prefix</label>
                            <select name="labour_prefix" class="form-select" required>
                                <option @if ($labourModel->labour_prefix === 'MR') selected @endif value="MR">MR.</option>
                                <option @if ($labourModel->labour_prefix === 'MS') selected @endif value="MS">MS.</option>
                                <option @if ($labourModel->labour_prefix === 'MRS') selected @endif value="MRS">MRS.</option>
                            </select>
                        </div>


                        <div class="col-md-4">
                            <label>Name</label>
                            <input type="text" class="form-control" name="labour_firstname" placeholder="Firstname"
                                value="{{ $labourModel->labour_firstname }}" required>
                        </div>

                        <div class="col-md-4">
                            <label>Lastname</label>
                            <input type="text" class="form-control" name="labour_lastname" placeholder="Lastname"
                                value="{{ $labourModel->labour_lastname }}" required>
                        </div>
                        <div class="col-md-3">
                            <label>Phone.</label>
                            <input type="text" class="form-control" name="labour_phone" placeholder="+66"
                                value="{{ $labourModel->labour_phone }}">
                        </div>


                    </div>
                    <input type="hidden" name="labour_customer_old" value="{{ $labourModel->labour_customer }}">
                    <div class="row mt-3">
                        <div class="col-md-3 mt-3">
                            <label>Customer (นายจ้าง)</label>
                            <select name="labour_customer" class="form-select"  >
                                <option value="">Select a Customer</option>

                                @forelse ($customers as $item)
                                    <option @if ($item->customer_id === $labourModel->labour_customer) selected @endif
                                        value="{{ $item->customer_id }}">{{ $item->customer_name }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>

                        <div class="col-md-2 mt-3">
                            <label>Passport No.</label>
                            <input type="text" name="labour_passport_number" class="form-control"
                                placeholder="Passport Number" value="{{ $labourModel->labour_passport_number }}">
                        </div>
                        <div class="col-md-2 mt-3">
                            <label>Data Issue</label>
                            <input type="date" name="labour_passport_issue" class="form-control" placeholder="Date Issue"
                                value="{{ $labourModel->labour_passport_issue }}">
                        </div>

                        <div class="col-md-2 mt-3">
                            <label>Data Expiry</label> <span id="daysLeft" class="text-info">จำนวนวันหมดอายุ : </span>
                            <input type="date" name="labour_passport_expiry" id="labour_passport_expiry" class="form-control"
                                placeholder="Date Expiry" value="{{ $labourModel->labour_passport_expiry }}">
                        </div>

                        <div class="col-md-3 mt-3">
                            <label>Register Number.</label>
                            <input type="text" name="labour_register_number" class="form-control"
                                placeholder="Register Number" value="{{ $labourModel->labour_register_number }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mt-3">
                            <label>Disease Start (วันออกผลโรค) </label>
                            <input type="date" name="labour_disease_start" id="labour_disease_start" class="form-control"
                                placeholder="Disease Start" value="{{ $labourModel->labour_disease_start }}">
                        </div>
                        <div class="col-md-3 mt-3">
                            <label>Disease Expiry (ผลโรคหมดอายุ) คำนวน 30 วัน </label>
                            <input type="date" name="labour_disease_expiry" id="labour_disease_expiry" class="form-control" >
                        </div>
                        
                        
                        <div class="col-md-3 mt-3">
                            <label>CID Start</label>
                            <input type="date" name="labour_cid_start" class="form-control" id="labour_cid_start"
                                placeholder="Register Number" value="{{ $labourModel->labour_cid_start }}">
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
                            <select name="labour_examination" class="form-select" required
                               >
                                <option selected value="{{$labourModel->labour_examination}}">{{ date('d-m-Y',strtotime($labourModel->labour_examination)) }} </option>

                                @forelse ($examinationRound as $item)
                                    <option 
                                        value="{{ $item->examination_round_name }}">{{ date('d-m-Y',strtotime($item->examination_round_name)) }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                        </div>

                        <div class="col-md-3 mt-3">
                            <label>Country</label>
                            <select name="labour_country" class="form-select country" required
                               >
                                <option value="">Select a Country</option>
                                @forelse ($country as $item)
                                    <option @if ($item->country_id === $labourModel->labour_country) selected @endif
                                        value="{{ $item->country_id }}">{{ $item->country_name_en }}</option>
                                @empty
                                    No date
                                @endforelse

                            </select>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label>Job Group</label>
                            <select name="labour_job_group" class="form-select job-group" required
                               >
                                <option value="">Select a Job Group</option>

                                @forelse ($jobGroup as $item)
                                    <option @if ($item->job_group_id === $labourModel->labour_job_group) selected @endif
                                        value="{{ $item->job_group_id }}">{{ $item->job_group_name }}</option>
                                @empty
                                @endforelse

                            </select>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label>Position</label>
                            <select name="labour_position" class="form-select" id="position" required
                               >
                                <option selected value="{{ $labourModel->labour_position }}">
                                    {{ $position->position_name }}</option>

                            </select>
                        </div>

                        <div class="col-md-3 mt-3">
                            <label>lacation Test</label>
                            <select name="labour_location_test" class="form-select" required
                               >
                                <option value="">Select a Localtion Test</option>
                                @forelse ($locationtest as $item)
                                    <option @if ($item->location_test_id === $labourModel->labour_location_test) selected @endif
                                        value="{{ $item->location_test_id }}">{{ $item->location_test_name }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>

                        <div class="col-md-3 mt-3">
                            <label>Docs. Type. (Path จัดเก็บเอกสาร)</label>
                            <select name="labour_location_doc" class="form-select" required
                               >
                                <option value="">Select a File Manage</option>
                                @forelse ($fileManage as $item)
                                    <option @if ($item->file_manage_id === $labourModel->labour_location_doc) selected @endif
                                        value="{{ $item->file_manage_id }}">{{ $item->file_manage_name }}</option>
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
                            <select name="labour_staff" class="form-select" required
                               >
                                <option value="">Select a Staff</option>
                                @forelse ($staffs as $item)
                                    <option @if ($item->staff_id === $labourModel->labour_staff) selected @endif
                                        value="{{ $item->staff_id }}">
                                        {{ $item->staff_name }}({{ $item->staff_nickname }})</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>สายหาคน</label>
                            <select name="labour_staff_sub" class="form-select" required>
                                <option value="no-sub">Null</option>
                               >
                               
                                @forelse ($staffSub as $item)
                                    <option @if ($item->staff_sub_id === $labourModel->labour_staff_sub) selected @endif
                                        value="{{ $item->staff_sub_id }}">
                                        {{ $item->staff_sub_name }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label>Status</label>
                            <select name="labour_status" class="form-select" required>
                                <option @if ($labourModel->labour_status === 'wait') selected @endif value="wait">กำลังดำเนินการ
                                </option>
                                <option @if ($labourModel->labour_status === 'success') selected @endif value="success">บินแล้ว
                                </option>
                                <option @if ($labourModel->labour_status === 'cancel') selected @endif value="cancel">ยกเลิก</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">บันทึกเพิ่มเติม</label>
                            <textarea name="labour_note" class="form-control" cols="30" rows="3" placeholder="บันทึกเพิ่มเติม">{{ $labourModel->labour_note }}</textarea>
                        </div>

                    </div>
                    <hr>

                    <a href="{{ route('labour.CombinePDF', $labourModel->labour_id) }}"
                        class="create-CombinePDF btn btn-primary"><i class="fas fa-file-pdf"></i> CombinePDF</a>
                    <br>
                    <br>
                    <div class="row">
                        <table>
                            <tbody>
                                @foreach ($labourfiles as $key => $item)
                                    <tr>
                                        <td> {{ $key + 1 }}.{{ $item->labour_file_note }}-<b>({{ $item->labour_file_name }})</b>
                                        </td>
                                        <td>
                                            @if ($item->labour_file_path)
                                                <a href="{{ asset('storage/' . $labourModel->labour_path . '/' . $item->labour_file_path) }}"
                                                    target="_blank"><i class="fas fa-file-pdf text-danger"></i>
                                                    {{ $item->labour_file_path }}</a>
                                            @else
                                                <input type="hidden" name="labour_file_name[]"
                                                    value="{{ $item->labour_file_name }}">
                                                <input type="hidden" name="labour_file_id[]"
                                                    value="{{ $item->labour_file_id }}">
                                                <input type="file" name="files[]">
                                            @endif
                                        </td>
                                        <td> <a href="" data-file-id="{{ $item->labour_file_id }}"
                                                data-labour-id="{{ $labourModel->labour_id }}"
                                                data-path="{{ $labourModel->labour_path . '/' . $item->labour_file_path }}"
                                                class="delete-file text-danger"> <i class="fa fa-trash"></i> Delete</a>
                                        </td>
                                    </tr>
                                @endforeach

                                @foreach ($listFiles as $itemNew)
                                    <tr>
                                        <td> {{ $key }}.{{ $itemNew->list_file_note }}-<b>({{ $itemNew->list_file_name }})</b>
                                        </td>
                                        <td>
                                            <input type="hidden" name="labour_file_name[]"
                                                value="{{ $itemNew->labour_file_name }}">
                                            <input type="hidden" name="labour_file_id[]"
                                                value="{{ $itemNew->labour_file_id }}">
                                            <input type="file" name="filesNew[]">
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>


                    </div>
                    <button type="submit" class="btn btn-sm float-end text-success" form="form-create"><i
                            class="fa fa-save"></i>
                        อัทเดพข้อมูล</button>
            </div>
        </div>
    </div>



    <style>
        .modal {
            width: 1000px;
            height: 1000px;
            left: 40%;
            top: 30%;
            margin-left: -150px;
            margin-top: -150px;
        }
    </style>



    <div class="modal fade bd-example-modal-sm modal-lg" id="add-CombinePDF" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                ...
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

    //         $('#labour_passport_expiry').on('change', function() {
    //     // Get the selected expiry date
    //     var expiryDate = new Date($(this).val());
    //     var today = new Date(); // Current date

    //     // Calculate the difference in time
    //     var timeDiff = expiryDate.getTime() - today.getTime();

    //     // Calculate the number of days until expiry
    //     var daysUntilExpiry = Math.ceil(timeDiff / (1000 * 3600 * 24));

    //     // Update the span with the number of days left
    //     if (daysUntilExpiry > 0) {
    //         $('#daysLeft').text('วันหมดอายุ : ' + daysUntilExpiry + ' วัน');
    //     } else {
    //         $('#daysLeft').text('หมดอายุแล้ว');
    //     }
    // });

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


        $(document).ready(function() {

            // modal add user
            $(".create-CombinePDF").click("click", function(e) {
                e.preventDefault();
                $("#add-CombinePDF")
                    .modal("show")
                    .addClass("modal-lg")
                    .find(".modal-content")
                    .load($(this).attr("href"));
            });
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
@endsection
