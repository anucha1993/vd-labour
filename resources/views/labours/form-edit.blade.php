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
                        {{-- labour info  --}}
                        <h4> ข้อมูลคนงาน <a class="text-danger" target="_blink"
                                href="{{ route('labour.print', $labourModel->labour_id) }}"> <i class="fa fa-print"></i>
                                พิมพ์ข้อมูล</a></h4>
                        <span>ตำแหน่งไฟล์ : <a
                                href="#">{{ env('LOCATION_DRIVE') }}{{ '\\' . $labourModel->labour_path }}</a></span>

                        <hr>
                        < <div class="col-md-1">
                            <label>Prefix</label>
                            <select name="labour_prefix" class="form-select" required
                                @cannot('update labour') disabled @endcannot>
                                <option @if ($labourModel->labour_prefix === 'MR') selected @endif value="MR">MR.</option>
                                <option @if ($labourModel->labour_prefix === 'MS') selected @endif value="MS">MS.</option>
                                <option @if ($labourModel->labour_prefix === 'MRS') selected @endif value="MRS">MRS.</option>
                            </select>
                    </div>


                    <div class="col-md-2">
                        <label>Name</label>
                        <input type="text" class="form-control" name="labour_firstname" placeholder="Firstname"
                            @cannot('update labour') disabled @endcannot value="{{ $labourModel->labour_firstname }}"
                            required>
                    </div>

                    <div class="col-md-2">
                        <label>Lastname</label>
                        <input type="text" class="form-control" name="labour_lastname" placeholder="Lastname"
                            @cannot('update labour') disabled @endcannot value="{{ $labourModel->labour_lastname }}"
                            required>
                    </div>

                    <div class="col-md-2">
                        <label>Birthday <span id="age_result"></span></label>
                        <input type="date" class="form-control" name="labour_birthday" placeholder="birthday"
                            @cannot('update labour') disabled @endcannot id="labour_birthday"
                            value="{{ $labourModel->labour_birthday }}" required>
                    </div>



                    <div class="col-md-2">
                        <label>Phone.</label>
                        <input type="text" class="form-control" name="labour_phone" placeholder="+66"
                            @cannot('update labour') disabled @endcannot value="{{ $labourModel->labour_phone }}">
                    </div>


            </div>
            <input type="hidden" name="labour_customer_old" value="{{ $labourModel->labour_customer }}">
            <div class="row mt-3">
                <div class="col-md-3 mt-3">
                    <label>Customer (นายจ้าง)</label>
                    <select name="labour_customer" class="form-select" @cannot('update labour') disabled @endcannot>
                        <option value="">Select a Customer</option>

                        @forelse ($customers as $item)
                            <option @if ($item->customer_id === $labourModel->labour_customer) selected @endif value="{{ $item->customer_id }}">
                                {{ $item->customer_name }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>

                <div class="col-md-2 mt-3">
                    <label>Passport No.</label>
                    <input type="text" name="labour_passport_number" class="form-control"
                        @cannot('update labour') disabled @endcannot placeholder="Passport Number"
                        value="{{ $labourModel->labour_passport_number }}">
                </div>
                <div class="col-md-2 mt-3">
                    <label>Data Issue</label>
                    <input type="date" name="labour_passport_issue" class="form-control" placeholder="Date Issue"
                        @cannot('update labour') disabled @endcannot value="{{ $labourModel->labour_passport_issue }}">
                </div>

                <div class="col-md-2 mt-3">
                    <label>Data Expiry</label> <span id="daysLeft" class="text-info">จำนวนวันหมดอายุ : </span>
                    <input type="date" name="labour_passport_expiry" id="labour_passport_expiry"
                        @cannot('update labour') disabled @endcannot class="form-control" placeholder="Date Expiry"
                        value="{{ $labourModel->labour_passport_expiry }}">
                </div>

                <div class="col-md-3 mt-3">
                    <label>Register Number.</label>
                    <input type="text" name="labour_register_number" class="form-control"
                        @cannot('update labour') disabled @endcannot placeholder="Register Number"
                        value="{{ $labourModel->labour_register_number }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mt-3">
                    <label>Disease Start (วันออกผลโรค) </label>
                    <input type="date" name="labour_disease_start" id="labour_disease_start" class="form-control"
                        @cannot('update labour') disabled @endcannot placeholder="Disease Start"
                        value="{{ $labourModel->labour_disease_start }}">
                </div>
                <div class="col-md-3 mt-3">
                    <label>Disease Expiry (ผลโรคหมดอายุ) คำนวน 90 วัน </label>
                    <input type="date" name="labour_disease_expriry" id="labour_disease_expriry"
                        @cannot('update labour') disabled @endcannot class="form-control">
                </div>


                <div class="col-md-3 mt-3">
                    <label>date disease results (วันรับผลโรค) </label>
                    <input type="date" name="labour_disease_results_date" id="labour_disease_expriry"
                        @cannot('update labour') disabled @endcannot
                        value="{{ $labourModel->labour_disease_results_date }}" class="form-control">
                </div>
                <div class="col-md-3 mt-3">
                    <label>Status Disease (สถานะผลโรค)</label>
                    <select name="labour_disease_status" class="form-select">
                        <option value="5">--ไม่ระบุ---</option>
                        <option @if ($labourModel->labour_disease_status == 0) selected @endif value="0">รอตรวจผลโรค</option>
                        <option @if ($labourModel->labour_disease_status == 1) selected @endif value="1">ผลโรคผ่าน</option>
                        <option @if ($labourModel->labour_disease_status == 2) selected @endif value="2">รอตรวจผลโรคซ้ำ</option>
                    </select>
                </div>
                <div class="col-md-3 mt-3">
                    <label>วันที่ยื่น CID</label>
                    <input type="date" name="labour_cid_stand_date" class="form-control"
                        @cannot('update labour') disabled @endcannot value="{{ $labourModel->labour_cid_stand_date }}">
                </div>
                  <div class="col-md-3 mt-3">
                    <label>Affidavit issues Date :</label>
                    <input type="date" name="labour_affidavit_start" class="form-control" id="labour_affidavit_start"
                        @cannot('update labour') disabled @endcannot value="{{ $labourModel->labour_affidavit_start }}">
                </div>

                 <div class="col-md-3 mt-3">
                    <label>Affidavit Date Expriry :</label>
                    <input type="date" name="labour_affidavit_expriry" class="form-control" id="labour_affidavit_expriry"
                        @cannot('update labour') disabled @endcannot value="{{ $labourModel->labour_affidavit_expriry }}">
                </div>



            </div>

            <div class="row">
                <div class="col-md-3 mt-3">
                    <label>CID Start</label>
                    <input type="date" name="labour_cid_start" class="form-control" id="labour_cid_start"
                        @cannot('update labour') disabled @endcannot placeholder="Register Number"
                        value="{{ $labourModel->labour_cid_start }}">
                </div>
                <div class="col-md-3 mt-3">
                    <label>CID Expiry</label>
                    <input type="date" name="labour_cid_expriry" class="form-control" id="labour_cid_expriry"
                        @cannot('update labour') disabled @endcannot placeholder="CID Expiry" value="">
                </div>
                <div class="col-md-3 mt-3">
                    <label>CID Results</label>
                    <select name="labour_cid_results" class="form-select" @cannot('update labour') disabled @endcannot>
                        <option value="">ไม่ระบุ</option>
                        @forelse ($CidResults as $item)
                            <option @if ($item->cid_results_id === $labourModel->labour_cid_results) selected @endif
                                value="{{ $item->cid_results_id }}">{{ $item->cid_results_name }}</option>
                        @empty
                            ไม่มีข้อมูล
                        @endforelse
                    </select>
                </div>
                <div class="col-md-3 mt-3">
                    <label>CID File</label><br>
                    @if ($labourModel->labour_cid_results_file)
                        <div class="border p-2 rounded">
                            <p>📄 <strong>{{ $labourModel->labour_cid_results_file }}</strong></p>

                            <a href="{{ asset('storage/LABOURS/' . $labourModel->labour_path . '/' . $labourModel->labour_cid_results_file) }}"
                                target="_blank" class="btn btn-sm btn-primary">
                                ดูไฟล์
                            </a>

                            <a href="{{ route('labour.cidfile.delete', $labourModel->labour_id) }}"
                                @cannot('delete labour') disabled @endcannot
                                onclick="return confirm('คุณแน่ใจว่าต้องการลบไฟล์นี้?')" class="btn btn-sm btn-danger">
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
            {{-- labour Group  --}}
            <hr>
            <h4>ข้อมูลกลุ่มงาน</h4>
            <div class="row">
                <div class="col-md-3 mt-3">
                    <label>Examination round (รอบสอบ)</label>
                    <select name="labour_examination" class="form-select" @cannot('update labour') disabled @endcannot>
                        <option selected value="{{ $labourModel->labour_examination }}">
                            {{ date('d-m-Y', strtotime($labourModel->labour_examination)) }} </option>
                        @forelse ($examinationRound as $item)
                            <option value="{{ $item->examination_round_name }}">
                                {{ date('d-m-Y', strtotime($item->examination_round_name)) }}
                            </option>
                        @empty
                        @endforelse
                    </select>
                </div>

                <div class="col-md-3 mt-3">
                    <label>Country</label>
                    <select name="labour_country" class="form-select country" required
                        @cannot('update labour') disabled @endcannot>
                        <option value="">Select a Country</option>
                        @forelse ($country as $item)
                            <option @if ($item->country_id === $labourModel->labour_country) selected @endif value="{{ $item->country_id }}">
                                {{ $item->country_name_en }}</option>
                        @empty
                            No date
                        @endforelse

                    </select>
                </div>
                <div class="col-md-3 mt-3">
                    <label>Job Group</label>
                    <select name="labour_job_group" class="form-select job-group"
                        @cannot('update labour') disabled @endcannot required>
                        <option value="">Select a Job Group</option>

                        @forelse ($jobGroup as $item)
                            <option @if ($item->job_group_id === $labourModel->labour_job_group) selected @endif value="{{ $item->job_group_id }}">
                                {{ $item->job_group_name }}</option>
                        @empty
                        @endforelse

                    </select>
                </div>
                <div class="col-md-3 mt-3">
                    <label>Position</label>
                    <select name="labour_position" class="form-select" id="position" required
                        @cannot('update labour') disabled @endcannot>
                        <option selected value="{{ $labourModel->labour_position }}">
                            {{ $position->position_name }}</option>

                    </select>
                </div>

                <div class="col-md-3 mt-3">
                    <label>lacation Test</label>
                    <select name="labour_location_test" class="form-select" required
                        @cannot('update labour') disabled @endcannot>
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
                        @cannot('update labour') disabled @endcannot>
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
            {{-- labour Status  --}}
            <h4>ข้อมูลสถานะ</h4>
            <div class="row">

                <div class="col-md-3">
                    <label>สายหาคน</label>
                    <select name="labour_staff_sub" class="form-select" required
                        @cannot('update labour') disabled @endcannot>
                        <option @if ($labourModel->labour_staff_sub === 'no-sub') selected @endif value="no-sub">ไม่ระบุ</option>

                        @forelse ($staffSub as $item)
                            <option @if ($item->staff_sub_id == $labourModel->labour_staff_sub) selected @endif value="{{ $item->staff_sub_id }}">
                                {{ $item->staff_sub_name }}
                                {{ $item->staff_sub_phone ? '(' . $item->staff_sub_phone . ')' : '' }}
                            </option>

                        @empty
                            <option disabled>ไม่มีข้อมูลพนักงาน</option>
                        @endforelse
                    </select>

                </div>

                <div class="col-md-3">
                    <label>Staff</label>
                    <select name="labour_staff" class="form-select" @cannot('update labour') disabled @endcannot>
                        <option value="">Select a Staff</option>
                        @forelse ($staffs as $item)
                            <option @if ($item->staff_id === $labourModel->labour_staff) selected @endif value="{{ $item->staff_id }}">
                                {{ $item->staff_name }}({{ $item->staff_nickname }}) {{ $item->staff_phone }}
                            </option>
                        @empty
                        @endforelse
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Status</label>
                    <select name="labour_status" class="form-select" @cannot('update labour') disabled @endcannot>
                        <option @if ($labourModel->labour_status === 'wait') selected @endif value="wait">กำลังดำเนินการ
                        </option>
                        <option @if ($labourModel->labour_status === 'success') selected @endif value="success">บินแล้ว
                        </option>
                        <option @if ($labourModel->labour_status === 'cancel') selected @endif value="cancel">ยกเลิก</option>
                    </select>
                </div>
                <br>
                <br>

                <div class="row mt-3">
                    <hr>
                    <h4>ข้อมูลบัญชี</h4>


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
                            value="{{ $labourModel->labour_cid_deposit_total }}" step="0.01" placeholder="0.00">
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
                            <option @if ($labourModel->labour_cid_deposit_status === 'ยกเลิก-คืนเงินประกัน') selected @endif value="ยกเลิก-คืนเงินประกัน">
                                ยกเลิก-คืนเงินประกัน</option>
                            <option @if ($labourModel->labour_cid_deposit_status === 'ยกเลิก-ไม่คืนเงินประกัน') selected @endif value="ยกเลิก-ไม่คืนเงินประกัน">
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
                            value="{{ $labourModel->labour_refund_deposit_total }}" step="0.01" placeholder="0.00">
                    </div>




                </div>

                <div class="col-md-6">
                    <label for="">บันทึกเพิ่มเติม</label>
                    <textarea name="labour_note" class="form-control" cols="30" rows="3" placeholder="บันทึกเพิ่มเติม">{{ $labourModel->labour_note }}</textarea>
                </div>

            </div>
            <hr>
            {{-- labour Status  --}}
            {{-- labour File  --}}
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
                                        @php
                                            $filePath = asset(
                                                'storage/LABOURS/' .
                                                    $labourModel->labour_path .
                                                    '/' .
                                                    $item->labour_file_path,
                                            );
                                            $timestamp = $item->updated_at ? $item->updated_at->timestamp : time();
                                        @endphp
                                        <a href="{{ $filePath . '?v=' . $timestamp }}"
                                            onclick="openPdfPopup(this.href); return false;">
                                            <i class="fas fa-file-pdf text-danger"></i>
                                            {{ $item->labour_file_path }}
                                        </a>
                                    @else
                                        <input type="hidden" name="labour_file_name[]"
                                            value="{{ $item->labour_file_name }}">
                                        <input type="hidden" name="labour_file_id[]"
                                            value="{{ $item->labour_file_id }}">
                                        <input type="file" name="files[]">
                                    @endif
                                </td>
                                <td>
                                    @can('delete labour')
                                        <a href="" data-file-id="{{ $item->labour_file_id }}"
                                            data-labour-id="{{ $labourModel->labour_id }}"
                                            data-path="{{ $labourModel->labour_path . '/' . $item->labour_file_path }}"
                                            class="delete-file text-danger"> <i class="fa fa-trash"></i> Delete</a>
                                    @endcan
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
            {{-- labour File  --}}
            <br>
            <br>


            {{-- @endcan
                     --}}
            <button type="submit" class="btn btn-sm float-end btn-success" form="form-create"
                @cannot('update labour') readonly @endcannot><i class="fa fa-save"></i>
                อัทเดพข้อมูล</button>


        </div>
    </div>
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
@endsection
