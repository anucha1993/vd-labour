@extends('layouts.main')
@section('content')
    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>เพิ่มข้อมูลผู้สนใจ (Lead)</h4>
                <a href="{{ route('leads.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> กลับ
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('leads.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- 1. PERSONAL INFORMATION -->
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-person-fill me-2"></i>1. PERSONAL INFORMATION: ข้อมูลผู้สมัคร</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                  <div class="col-md-3">
                                <label class="form-label">รูปถ่าย</label>
                                <div class="text-center">
                                    <img id="photo_preview" src="https://via.placeholder.com/200x250" class="img-thumbnail mb-2" style="width: 100%; max-height: 280px; object-fit: cover;">
                                    <input type="file" class="form-control" name="lead_photo" accept="image/*" onchange="previewPhoto(event)">
                                    <small class="text-muted">รูปถ่ายหน้าตรง สวมเสื้อเป็นทางการ</small>
                                </div>
                            </div>
                            <br>

                                <div class="row mb-3">
                                    <div class="col-md-1">
                                        <label class="form-label">คำนำหน้า</label>
                                        <select class="form-select @error('lead_prefix') is-invalid @enderror" name="lead_prefix">
                                            <option value="Mr." {{ old('lead_prefix') == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                            <option value="Mrs." {{ old('lead_prefix') == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                            <option value="Ms." {{ old('lead_prefix') == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                        </select>
                                        @error('lead_prefix')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">ชื่อ <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('lead_firstname') is-invalid @enderror" 
                                               name="lead_firstname" value="{{ old('lead_firstname') }}" required>
                                        @error('lead_firstname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">นามสกุล <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('lead_lastname') is-invalid @enderror" 
                                               name="lead_lastname" value="{{ old('lead_lastname') }}" required>
                                        @error('lead_lastname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-1">
                                        <label class="form-label">เพศ</label>
                                        <select class="form-select" name="lead_gender">
                                            <option value="male" {{ old('lead_gender', 'male') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('lead_gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">สถานะ</label>
                                        <select class="form-select" name="lead_marital_status">
                                            <option value="single" {{ old('lead_marital_status', 'single') == 'single' ? 'selected' : '' }}>Single</option>
                                            <option value="married" {{ old('lead_marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                                            <option value="divorced" {{ old('lead_marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">วันเกิด</label>
                                        <input type="date" class="form-control" name="lead_birthday" id="lead_birthday" value="{{ old('lead_birthday') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">อายุ</label>
                                        <input type="number" class="form-control bg-light" name="lead_age" id="lead_age" value="{{ old('lead_age') }}" readonly>
                                    </div>
                               
                                    <div class="col-md-3">
                                        <label class="form-label">โทรศัพท์ (หลัก)</label>
                                        <input type="text" class="form-control @error('lead_phone') is-invalid @enderror" 
                                               name="lead_phone" value="{{ old('lead_phone') }}">
                                        @error('lead_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">โทรศัพท์ (รอง)</label>
                                        <input type="text" class="form-control" name="lead_phone_2" value="{{ old('lead_phone_2') }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label">ที่อยู่ (English)</label>
                                        <textarea class="form-control" name="lead_address" rows="2">{{ old('lead_address') }}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label">ส่วนสูง (cm)</label>
                                        <input type="number" step="0.01" class="form-control" name="lead_height" id="lead_height" value="{{ old('lead_height') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">น้ำหนัก (kg)</label>
                                        <input type="number" step="0.01" class="form-control" name="lead_weight" id="lead_weight" value="{{ old('lead_weight') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">BMI</label>
                                        <input type="text" class="form-control bg-light" name="lead_bmi" id="lead_bmi" value="{{ old('lead_bmi') }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">อยู่ในเกณฑ์</label>
                                        <input type="text" class="form-control bg-light" id="bmi_category" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Passport/ID Card No.</label>
                                        <input type="text" class="form-control @error('lead_passport_number') is-invalid @enderror" 
                                               name="lead_passport_number" id="lead_passport_number" 
                                               maxlength="6" pattern="[0-9]{6}" 
                                               value="{{ old('lead_passport_number') }}"
                                               placeholder="กรอกตัวเลข 6 หลักเท่านั้น">
                                        <div class="invalid-feedback" id="passport_error">
                                            กรุณากรอกตัวเลข 6 หลักเท่านั้น
                                        </div>
                                        @error('lead_passport_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Date of Issue</label>
                                        <input type="date" class="form-control" name="lead_passport_issue_date" value="{{ old('lead_passport_issue_date') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Date of Expiry</label>
                                        <input type="date" class="form-control" name="lead_passport_expiry_date" value="{{ old('lead_passport_expiry_date') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Shirt size (เสื้อ)</label>
                                        <div class="d-flex gap-3 flex-wrap">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lead_shirt_size" id="shirt_s" value="S" {{ old('lead_shirt_size') == 'S' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="shirt_s">S</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lead_shirt_size" id="shirt_m" value="M" {{ old('lead_shirt_size') == 'M' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="shirt_m">M</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lead_shirt_size" id="shirt_l" value="L" {{ old('lead_shirt_size') == 'L' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="shirt_l">L</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lead_shirt_size" id="shirt_xl" value="XL" {{ old('lead_shirt_size') == 'XL' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="shirt_xl">XL</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lead_shirt_size" id="shirt_2xl" value="2XL" {{ old('lead_shirt_size') == '2XL' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="shirt_2xl">2XL</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lead_shirt_size" id="shirt_3xl" value="3XL" {{ old('lead_shirt_size') == '3XL' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="shirt_3xl">3XL</label>
                                            </div>
                                        </div>
                                    </div>
                               
                                    <div class="col-md-3">
                                        <label class="form-label">Pant size (กางเกง)</label>
                                        <input type="text" class="form-control" name="lead_pant_size" value="{{ old('lead_pant_size') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Shoes size (รองเท้า)</label>
                                        <input type="text" class="form-control" name="lead_shoes_size" value="{{ old('lead_shoes_size') }}">
                                    </div>
                                </div>
                            </div>
                          
                        </div>
                    </div>
                </div>

                <!-- 2. EDUCATION & LANGUAGES -->
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-book-fill me-2"></i>2. EDUCATION / LANGUAGES SKILLS: การศึกษา และ ความสามารถทางภาษา</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">EDUCATION (การศึกษา)</label>
                                <select class="form-select" name="lead_education">
                                    <option value="">-- เลือกระดับการศึกษา --</option>
                                    <option value="elementary" {{ old('lead_education') == 'elementary' ? 'selected' : '' }}>ELEMENTARY SCHOOL (ประถมศึกษา)</option>
                                    <option value="junior_high" {{ old('lead_education') == 'junior_high' ? 'selected' : '' }}>JUNIOR HIGH SCHOOL (มัธยมต้น)</option>
                                    <option value="high_school" {{ old('lead_education') == 'high_school' ? 'selected' : '' }}>HIGH SCHOOL (มัธยมปลาย)</option>
                                    <option value="voc_cert" {{ old('lead_education') == 'voc_cert' ? 'selected' : '' }}>VOC. CERT (ปวช.)</option>
                                    <option value="high_voc_cert" {{ old('lead_education') == 'high_voc_cert' ? 'selected' : '' }}>HIGH VOC. CERT (ปวส.)</option>
                                    <option value="bachelor" {{ old('lead_education') == 'bachelor' ? 'selected' : '' }}>BACHELOR DEGREES (ปริญญาตรี)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">CHINESE SPEAKING (ภาษาจีน)</label>
                                <select class="form-select" name="lead_chinese_speaking">
                                    <option value="no" {{ old('lead_chinese_speaking', 'no') == 'no' ? 'selected' : '' }}>NO</option>
                                    <option value="beginner" {{ old('lead_chinese_speaking') == 'beginner' ? 'selected' : '' }}>BEGINNER</option>
                                    <option value="intermediate" {{ old('lead_chinese_speaking') == 'intermediate' ? 'selected' : '' }}>INTERMEDIATE</option>
                                    <option value="advance" {{ old('lead_chinese_speaking') == 'advance' ? 'selected' : '' }}>ADVANCE</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ENGLISH SPEAKING (ภาษาอังกฤษ)</label>
                                <select class="form-select" name="lead_english_speaking">
                                    <option value="no" {{ old('lead_english_speaking', 'no') == 'no' ? 'selected' : '' }}>NO</option>
                                    <option value="beginner" {{ old('lead_english_speaking') == 'beginner' ? 'selected' : '' }}>BEGINNER</option>
                                    <option value="intermediate" {{ old('lead_english_speaking') == 'intermediate' ? 'selected' : '' }}>INTERMEDIATE</option>
                                    <option value="advance" {{ old('lead_english_speaking') == 'advance' ? 'selected' : '' }}>ADVANCE</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">OTHER LANGUAGES (ภาษาอื่นๆ เช่น Korean, Japanese)</label>
                                <input type="text" class="form-control" name="lead_other_language" 
                                       placeholder="ระบุภาษาอื่นๆที่สามารถสื่อสารได้" value="{{ old('lead_other_language') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. ADDITIONAL INFORMATION -->
                <div class="card mb-3">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-info-circle-fill me-2"></i>3. ADDITIONAL INFORMATION: ข้อมูลอื่นๆเพิ่มเติม</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Have you ever work in Israel?</label>
                                <select class="form-select" name="lead_work_israel">
                                    <option value="no" {{ old('lead_work_israel', 'no') == 'no' ? 'selected' : '' }}>NO</option>
                                    <option value="yes" {{ old('lead_work_israel') == 'yes' ? 'selected' : '' }}>YES</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Criminal history? (ประวัติอาชญากรรม)</label>
                                <select class="form-select" name="lead_criminal_history">
                                    <option value="no" {{ old('lead_criminal_history', 'no') == 'no' ? 'selected' : '' }}>NO</option>
                                    <option value="yes" {{ old('lead_criminal_history') == 'yes' ? 'selected' : '' }}>YES</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Eyesight? (การมองเห็น)</label>
                                <select class="form-select" name="lead_eyesight">
                                    <option value="normal" {{ old('lead_eyesight', 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="abnormal" {{ old('lead_eyesight') == 'abnormal' ? 'selected' : '' }}>Abnormal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Color blindness (ตาบอดสี)</label>
                                <select class="form-select" name="lead_color_blindness">
                                    <option value="no" {{ old('lead_color_blindness', 'no') == 'no' ? 'selected' : '' }}>NO</option>
                                    <option value="yes" {{ old('lead_color_blindness') == 'yes' ? 'selected' : '' }}>YES</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. POSITION AND SKILL -->
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-briefcase-fill me-2"></i>4. POSITION AND SKILL: ตำแหน่งและทักษะงาน</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Position 1:</label>
                                <select class="form-select" name="position_id">
                                    <option value="">-- เลือกตำแหน่ง --</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->position_id }}" {{ old('position_id') == $position->position_id ? 'selected' : '' }}>
                                            {{ $position->position_name }} ({{ $position->position_name_th }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Position 2:</label>
                                <select class="form-select" name="position_id_2">
                                    <option value="">-- เลือกตำแหน่ง --</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->position_id }}" {{ old('position_id_2') == $position->position_id ? 'selected' : '' }}>
                                            {{ $position->position_name }} ({{ $position->position_name_th }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Position 3:</label>
                                <select class="form-select" name="position_id_3">
                                    <option value="">-- เลือกตำแหน่ง --</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->position_id }}" {{ old('position_id_3') == $position->position_id ? 'selected' : '' }}>
                                            {{ $position->position_name }} ({{ $position->position_name_th }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">ประเทศที่สนใจ</label>
                                <select class="form-select" name="country_id">
                                    <option value="">-- เลือกประเทศ --</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->country_id }}" {{ old('country_id') == $country->country_id ? 'selected' : '' }}>
                                            {{ $country->country_name_th ?? $country->country_name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">กลุ่มงาน</label>
                                <select class="form-select" name="job_group_id">
                                    <option value="">-- เลือกกลุ่มงาน --</option>
                                    @foreach($jobGroups as $jobGroup)
                                        <option value="{{ $jobGroup->job_group_id }}" {{ old('job_group_id') == $jobGroup->job_group_id ? 'selected' : '' }}>
                                            {{ $jobGroup->job_group_name_th ?? $jobGroup->job_group_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. EMERGENCY CONTACT & DRIVING -->
                <div class="card mb-3">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-telephone-fill me-2"></i>5. ข้อมูลเพิ่มเติม</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">EMERGENCY CONTACT NAME (ผู้ติดต่อฉุกเฉิน)</label>
                                <input type="text" class="form-control" name="lead_emergency_name" 
                                       placeholder="ชื่อผู้ติดต่อฉุกเฉิน" value="{{ old('lead_emergency_name') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">STATUS (ความสัมพันธ์)</label>
                                <input type="text" class="form-control" name="lead_emergency_status" 
                                       placeholder="เช่น พ่อ, แม่, พี่, น้อง" value="{{ old('lead_emergency_status') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">TEL (เบอร์โทรศัพท์)</label>
                                <input type="text" class="form-control" name="lead_emergency_phone" 
                                       placeholder="เบอร์โทรผู้ติดต่อฉุกเฉิน" value="{{ old('lead_emergency_phone') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">DRIVING LICENSE (ใบขับขี่)</label>
                                <select class="form-select" name="lead_driving_license">
                                    <option value="no" {{ old('lead_driving_license', 'no') == 'no' ? 'selected' : '' }}>NO (ไม่มี)</option>
                                    <option value="yes" {{ old('lead_driving_license') == 'yes' ? 'selected' : '' }}>YES (มี)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">TYPE OF CAR (ประเภทรถ)</label>
                                <input type="text" class="form-control" name="lead_car_type" 
                                       placeholder="เช่น Private Car, Motorcycle" value="{{ old('lead_car_type') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Valid Until date (วันหมดอายุ)</label>
                                <input type="date" class="form-control" name="lead_license_valid_until" value="{{ old('lead_license_valid_until') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">รอบสอบ (Examination Round)</label>
                                <select class="form-select" name="examination_round_id">
                                    <option value="">-- เลือกรอบสอบ --</option>
                                    @foreach($examinationRounds as $round)
                                        <option value="{{ $round->examination_round_id }}" {{ old('examination_round_id') == $round->examination_round_id ? 'selected' : '' }}>
                                            {{ date('d/m/Y', strtotime($round->examination_round_name)) }} - {{ $round->examination_round_note }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Recommender (ผู้แนะนำ)</label>
                                <input type="text" class="form-control" name="lead_recommender" 
                                       placeholder="ชื่อผู้แนะนำหรือชื่อบริษัท" value="{{ old('lead_recommender') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">สถานะ Lead</label>
                                <select class="form-select" name="lead_status">
                                    <option value="new" {{ old('lead_status', 'new') == 'new' ? 'selected' : '' }}>ใหม่</option>
                                    <option value="contacted" {{ old('lead_status') == 'contacted' ? 'selected' : '' }}>ติดต่อแล้ว</option>
                                    <option value="interview" {{ old('lead_status') == 'interview' ? 'selected' : '' }}>นัดสัมภาษณ์</option>
                                    <option value="qualified" {{ old('lead_status') == 'qualified' ? 'selected' : '' }}>ผ่านคุณสมบัติ</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">เจ้าหน้าที่ผู้รับผิดชอบ</label>
                                <select class="form-select" name="staff_id">
                                    <option value="">-- เลือกเจ้าหน้าที่ --</option>
                                    @foreach($staffs as $staff)
                                        <option value="{{ $staff->staff_id }}" {{ old('staff_id') == $staff->staff_id ? 'selected' : '' }}>
                                            {{ $staff->staff_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">หมายเหตุ</label>
                                <textarea class="form-control" name="lead_note" rows="3" 
                                          placeholder="บันทึกข้อมูลเพิ่มเติม...">{{ old('lead_note') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 6. DOCUMENT CHECKLIST -->
                <div class="card mb-3">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-file-earmark-check-fill me-2"></i>6. เอกสารคุณสมบัติ (Document Checklist)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="รูปถ่ายขนาด_2_นิ้ว" id="doc1" {{ in_array('รูปถ่ายขนาด_2_นิ้ว', old('documents', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc1">1. รูปถ่ายขนาด 2 นิ้ว</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="หนังสือเดินทาง_สำเนา" id="doc8" {{ in_array('หนังสือเดินทาง_สำเนา', old('documents', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc8">8. หนังสือเดินทาง (สำเนา)</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="Passport" id="doc2" {{ in_array('Passport', old('documents', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc2">2. Passport</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="ใบ_สผท" id="doc9" {{ in_array('ใบ_สผท', old('documents', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc9">9. ใบ สผท.</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="บัตรประชาชน_สำเนา" id="doc3" {{ in_array('บัตรประชาชน_สำเนา', old('documents', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc3">3. บัตรประชาชน (สำเนา)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="ใบสุคันธ์_สำเนาจริงในเค" id="doc10" {{ in_array('ใบสุคันธ์_สำเนาจริงในเค', old('documents', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc10">10. ใบสุคันธ์ (สำเนาจริงในเค)</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="ผลโรค" id="doc4" {{ in_array('ผลโรค', old('documents', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc4">4. ผลโรค</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="CV" id="doc11" {{ in_array('CV', old('documents', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc11">11. CV</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="ใบผ่านงาน" id="doc5" {{ in_array('ใบผ่านงาน', old('documents', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc5">5. ใบผ่านงาน</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="หนังสือสมรส_กรณีสมรส" id="doc12" {{ in_array('หนังสือสมรส_กรณีสมรส', old('documents', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc12">12. หนังสือสมรส (กรณีสมรส)</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="ใบอนุญาต_No" id="doc6" {{ in_array('ใบอนุญาต_No', old('documents', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc6">6. ใบอนุญาต No. <input type="text" class="form-control form-control-sm d-inline-block" style="width: 120px; margin-left: 10px;" name="license_number" value="{{ old('license_number') }}" placeholder="เลขที่"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="สำเนาบัตรประชาชนคู่สมรส_กรณีสมรส" id="doc13" {{ in_array('สำเนาบัตรประชาชนคู่สมรส_กรณีสมรส', old('documents', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc13">13. สำเนาบัตรประชาชนคู่สมรส(กรณีสมรส)</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="CID" id="doc7" {{ in_array('CID', old('documents', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc7">7. CID</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="สำเนาบัตรอัยพ่อแม่สกุล_กรณีเดคลแปลปธิ" id="doc14" {{ in_array('สำเนาบัตรอัยพ่อแม่สกุล_กรณีเดคลแปลปธิ', old('documents', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc14">14. สำเนาบัตรอัยพ่อแม่สกุล(กรณีเดคลแปลปธิ)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 7. JOB HISTORY -->
                <div class="card mb-3">
                    <div class="card-header bg-secondary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>7. JOB HISTORY: ประวัติการทำงาน</h5>
                            <button type="button" class="btn btn-light btn-sm" onclick="addJobHistory()">
                                <i class="bi bi-plus-circle"></i> เพิ่มประวัติ
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="job-history-container">
                            <!-- Job history rows will be added here -->
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                คลิกปุ่ม "เพิ่มประวัติ" เพื่อเพิ่มประวัติการทำงาน
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('leads.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> ยกเลิก
                    </a>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-save"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let jobHistoryCount = 0;

        function previewPhoto(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('photo_preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // Calculate BMI
        document.getElementById('lead_height').addEventListener('input', calculateBMI);
        document.getElementById('lead_weight').addEventListener('input', calculateBMI);

        function calculateBMI() {
            const height = parseFloat(document.getElementById('lead_height').value);
            const weight = parseFloat(document.getElementById('lead_weight').value);
            
            if (height && weight && height > 0) {
                const heightM = height / 100;
                const bmi = (weight / (heightM * heightM)).toFixed(2);
                document.getElementById('lead_bmi').value = bmi;
                
                // แสดงเกณฑ์ BMI
                let category = '';
                let categoryColor = '';
                
                if (bmi < 18.50) {
                    category = 'น้ำหนักน้อย / ผอม';
                    categoryColor = 'text-primary';
                } else if (bmi >= 18.50 && bmi <= 22.90) {
                    category = 'ปกติ (สุขภาพดี)';
                    categoryColor = 'text-success fw-bold';
                } else if (bmi >= 23 && bmi <= 24.90) {
                    category = 'ท้วม / โรคอ้วนระดับ 1';
                    categoryColor = 'text-warning';
                } else if (bmi >= 25 && bmi <= 29.90) {
                    category = 'อ้วน / โรคอ้วนระดับ 2';
                    categoryColor = 'text-danger';
                } else if (bmi >= 30) {
                    category = 'อ้วนมาก / โรคอ้วนระดับ 3';
                    categoryColor = 'text-danger fw-bold';
                }
                
                const categoryField = document.getElementById('bmi_category');
                categoryField.value = category;
                categoryField.className = `form-control bg-light ${categoryColor}`;
            } else {
                document.getElementById('lead_bmi').value = '';
                document.getElementById('bmi_category').value = '';
                document.getElementById('bmi_category').className = 'form-control bg-light';
            }
        }

        // Calculate Age from Birthday
        document.getElementById('lead_birthday').addEventListener('change', calculateAge);

        function calculateAge() {
            const birthday = document.getElementById('lead_birthday').value;
            if (birthday) {
                const birthDate = new Date(birthday);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                
                // ถ้ายังไม่ถึงวันเกิดในปีนี้ ให้ลบอายุออก 1
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                document.getElementById('lead_age').value = age;
            } else {
                document.getElementById('lead_age').value = '';
            }
        }

        // Validate Passport Number (must be exactly 6 digits)
        document.getElementById('lead_passport_number').addEventListener('input', function(e) {
            const value = e.target.value;
            const passportField = e.target;
            
            // Allow only numbers
            e.target.value = value.replace(/[^0-9]/g, '');
            
            // Check length
            if (e.target.value.length > 0 && e.target.value.length !== 6) {
                passportField.classList.add('is-invalid');
                passportField.classList.remove('is-valid');
            } else if (e.target.value.length === 6) {
                passportField.classList.remove('is-invalid');
                passportField.classList.add('is-valid');
            } else {
                passportField.classList.remove('is-invalid', 'is-valid');
            }
        });

        // Validate on form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const passportField = document.getElementById('lead_passport_number');
            const passportValue = passportField.value;
            
            if (passportValue.length > 0 && passportValue.length !== 6) {
                e.preventDefault();
                passportField.classList.add('is-invalid');
                passportField.focus();
                alert('กรุณากรอก Passport/ID Card No. ให้ครบ 6 หลัก');
                return false;
            }
        });

        function addJobHistory() {
            jobHistoryCount++;
            const container = document.getElementById('job-history-container');
            
            // Remove info alert if it exists
            const alert = container.querySelector('.alert-info');
            if (alert) {
                alert.remove();
            }
            
            const row = document.createElement('div');
            row.className = 'job-history-row mb-4 p-3 border rounded';
            row.style.backgroundColor = '#f8f9fa';
            row.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0"><i class="bi bi-briefcase me-2"></i>ประวัติการทำงาน #${jobHistoryCount}</h6>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeJobHistory(this)">
                        <i class="bi bi-trash"></i> ลบ
                    </button>
                </div>
                
                <div class="row g-3 mb-3">

                     <div class="col-md-3">
                        <label class="form-label">Start Date (วันที่เริ่มงาน)</label>
                        <input type="month" class="form-control" name="job_history[${jobHistoryCount}][start_date]" 
                               placeholder="YYYY-MM">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date (วันที่สิ้นสุด)</label>
                        <input type="month" class="form-control" name="job_history[${jobHistoryCount}][end_date]" 
                               placeholder="YYYY-MM หรือ ปัจจุบัน">
                    </div>

                      <div class="col-md-2">
                        <label class="form-label">Position (ตำแหน่ง) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="job_history[${jobHistoryCount}][position]" 
                               placeholder="เช่น PLASTERER, CARPENTER, TILE" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Country (ประเทศ)</label>
                        <select class="form-select" name="job_history[${jobHistoryCount}][country]">
                            <option value="THAI">THAI</option>
                            <option value="ISRAEL">ISRAEL</option>
                            <option value="OTHER">OTHER</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Experience Years (จำนวนปี)</label>
                        <input type="number" step="0.5" class="form-control" name="job_history[${jobHistoryCount}][experience_years]" 
                               placeholder="0" min="0" value="0">
                    </div>

                    </div>

                     <div class="row g-3">


                    <div class="col-md-6">
                        <label class="form-label">Type of Work And Company Name (ประเภทงาน/บริษัท) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="job_history[${jobHistoryCount}][company_type]" 
                               placeholder="เช่น General Construction, Construction Contractor" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company Name (ชื่อบริษัท - ถ้ามี)</label>
                        <input type="text" class="form-control" name="job_history[${jobHistoryCount}][company_name]" 
                               placeholder="ชื่อบริษัท (ไม่บังคับ)">
                    </div>
                    
                  
                    
                   
                    
                    <div class="col-md-12">
                        <label class="form-label">Job Description / Responsibilities (รายละเอียดงานที่ทำ)</label>
                        <textarea class="form-control" name="job_history[${jobHistoryCount}][description]" rows="4" 
                                  placeholder="- ระบุหน้าที่ความรับผิดชอบ&#10;- ผลงานที่สำคัญ&#10;- ทักษะที่ได้รับ"></textarea>
                    </div>
                </div>
            `;
            
            container.appendChild(row);
            
            // เพิ่ม event listeners สำหรับการตรวจสอบ overlap
            const startInput = row.querySelector(`input[name="job_history[${jobHistoryCount}][start_date]"]`);
            const endInput = row.querySelector(`input[name="job_history[${jobHistoryCount}][end_date]"]`);
            
            startInput.addEventListener('change', () => validateJobHistoryOverlap());
            endInput.addEventListener('change', () => validateJobHistoryOverlap());
        }

        // ฟังก์ชันตรวจสอบการซ้ำซ้อนของช่วงเวลาทำงาน
        function validateJobHistoryOverlap() {
            const jobHistoryRows = document.querySelectorAll('.job-history-row');
            const periods = [];
            let hasError = false;
            
            // รวบรวมข้อมูลช่วงเวลาทำงานทั้งหมด
            jobHistoryRows.forEach((row, index) => {
                const startInput = row.querySelector('input[name*="[start_date]"]');
                const endInput = row.querySelector('input[name*="[end_date]"]');
                
                if (startInput && endInput && startInput.value) {
                    const startDate = startInput.value; // YYYY-MM format
                    const endDate = endInput.value || '9999-12'; // ถ้าไม่มี end date ให้ถือว่าเป็นปัจจุบัน
                    
                    periods.push({
                        index: index,
                        start: startDate,
                        end: endDate,
                        startInput: startInput,
                        endInput: endInput,
                        row: row
                    });
                }
            });
            
            // ตรวจสอบการซ้ำซ้อน
            for (let i = 0; i < periods.length; i++) {
                for (let j = i + 1; j < periods.length; j++) {
                    const period1 = periods[i];
                    const period2 = periods[j];
                    
                    // ตรวจสอบว่าช่วงเวลาทับซ้อนกันหรือไม่
                    if (isPeriodsOverlapping(period1.start, period1.end, period2.start, period2.end)) {
                        // ไม่อนุญาตให้ทับซ้อน ยกเว้นกรณีที่สิ้นสุดและเริ่มต้นเป็นเดือนเดียวกัน
                        if (!(period1.end === period2.start || period1.start === period2.end)) {
                            showOverlapError(period1, period2);
                            hasError = true;
                        }
                    }
                }
            }
            
            // ลบ error message ถ้าไม่มี error
            if (!hasError) {
                clearOverlapErrors();
            }
            
            return !hasError;
        }
        
        // ตรวจสอบว่าช่วงเวลา 2 ช่วงทับซ้อนกันหรือไม่
        function isPeriodsOverlapping(start1, end1, start2, end2) {
            // แปลงเป็น Date object สำหรับเปรียบเทียบ
            const s1 = new Date(start1 + '-01');
            const e1 = new Date(end1 + '-01');
            const s2 = new Date(start2 + '-01');
            const e2 = new Date(end2 + '-01');
            
            // ตรวจสอบว่าทับซ้อนกันหรือไม่
            return s1 <= e2 && s2 <= e1;
        }
        
        // แสดง error message สำหรับการทับซ้อน
        function showOverlapError(period1, period2) {
            // ลบ error เก่าก่อน
            clearOverlapErrors();
            
            // เพิ่ม class error และ message
            [period1.startInput, period1.endInput, period2.startInput, period2.endInput].forEach(input => {
                if (input) {
                    input.classList.add('is-invalid');
                }
            });
            
            // แสดง alert message
            const errorAlert = document.createElement('div');
            errorAlert.className = 'alert alert-danger mt-2 overlap-error';
            errorAlert.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i><strong>คำเตือน:</strong> ช่วงเวลาทำงานซ้ำซ้อนกัน กรุณาตรวจสอบวันที่เริ่มงานและสิ้นสุดงาน';
            
            const container = document.getElementById('job-history-container');
            container.insertBefore(errorAlert, container.firstChild);
        }
        
        // ลบ error messages
        function clearOverlapErrors() {
            // ลบ class error
            document.querySelectorAll('.job-history-row input[type="month"]').forEach(input => {
                input.classList.remove('is-invalid');
            });
            
            // ลบ error alerts
            document.querySelectorAll('.overlap-error').forEach(alert => {
                alert.remove();
            });
        }

        function removeJobHistory(button) {
            const row = button.closest('.job-history-row');
            row.remove();
            
            // Show info alert if no job history remains
            const container = document.getElementById('job-history-container');
            if (container.querySelectorAll('.job-history-row').length === 0) {
                container.innerHTML = `
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        คลิกปุ่ม "เพิ่มประวัติ" เพื่อเพิ่มประวัติการทำงาน
                    </div>
                `;
            } else {
                // ตรวจสอบ overlap หลังจากลบแถว
                setTimeout(() => validateJobHistoryOverlap(), 100);
            }
        }
        
        // เพิ่มการตรวจสอบก่อน submit form
        document.querySelector('form').addEventListener('submit', function(e) {
            // ตรวจสอบ passport ก่อน
            const passportField = document.getElementById('lead_passport_number');
            const passportValue = passportField.value;
            
            if (passportValue.length > 0 && passportValue.length !== 6) {
                e.preventDefault();
                passportField.classList.add('is-invalid');
                passportField.focus();
                alert('กรุณากรอก Passport/ID Card No. ให้ครบ 6 หลัก');
                return false;
            }
            
            // ตรวจสอบ job history overlap
            if (!validateJobHistoryOverlap()) {
                e.preventDefault();
                alert('กรุณาแก้ไขช่วงเวลาทำงานที่ซ้ำซ้อนกันก่อนบันทึกข้อมูล');
                return false;
            }
        });
    </script>
@endsection
