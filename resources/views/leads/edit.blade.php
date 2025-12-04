@extends('layouts.main')
@section('content')
    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>แก้ไขข้อมูลผู้สนใจ (Lead): {{ $lead->getFullNameAttribute() }}</h4>
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

            <form action="{{ route('leads.update', $lead->lead_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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
                                    @if($lead->lead_photo)
                                        <img id="photo_preview" src="{{ asset('storage/' . $lead->lead_photo) }}" class="img-thumbnail mb-2" style="width: 100%; max-height: 280px; object-fit: cover;">
                                    @else
                                        <img id="photo_preview" src="https://via.placeholder.com/200x250?text=No+Photo" class="img-thumbnail mb-2" style="width: 100%; max-height: 280px; object-fit: cover;">
                                    @endif
                                    <input type="file" class="form-control" name="lead_photo" accept="image/*" onchange="previewPhoto(event)" id="lead_photo_input">
                                    <small class="text-muted">รูปถ่ายหน้าตรง สวมเสื้อเป็นทางการ (เลือกใหม่เพื่อเปลี่ยน)</small>
                                </div>
                            </div>
                            <br>

                                <div class="row mb-3">
                                    <div class="col-md-1">
                                        <label class="form-label">คำนำหน้า</label>
                                        <select class="form-select @error('lead_prefix') is-invalid @enderror" name="lead_prefix">
                                            <option value="Mr." {{ old('lead_prefix', $lead->lead_prefix) == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                            <option value="Mrs." {{ old('lead_prefix', $lead->lead_prefix) == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                            <option value="Ms." {{ old('lead_prefix', $lead->lead_prefix) == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                        </select>
                                        @error('lead_prefix')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">ชื่อ <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('lead_firstname') is-invalid @enderror" 
                                               name="lead_firstname" value="{{ old('lead_firstname', $lead->lead_firstname) }}" required>
                                        @error('lead_firstname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">นามสกุล <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('lead_lastname') is-invalid @enderror" 
                                               name="lead_lastname" value="{{ old('lead_lastname', $lead->lead_lastname) }}" required>
                                        @error('lead_lastname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-1">
                                        <label class="form-label">เพศ</label>
                                        <select class="form-select" name="lead_gender">
                                            <option value="male" {{ old('lead_gender', $lead->lead_gender) == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('lead_gender', $lead->lead_gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">สถานะ</label>
                                        <select class="form-select" name="lead_marital_status">
                                            <option value="single" {{ old('lead_marital_status', $lead->lead_marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                                            <option value="married" {{ old('lead_marital_status', $lead->lead_marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                                            <option value="divorced" {{ old('lead_marital_status', $lead->lead_marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">วันเกิด</label>
                                        <input type="date" class="form-control" name="lead_birthday" id="lead_birthday" value="{{ old('lead_birthday', $lead->lead_birthday ? $lead->lead_birthday->format('Y-m-d') : '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">อายุ</label>
                                        <input type="number" class="form-control bg-light" name="lead_age" id="lead_age" value="{{ old('lead_age', $lead->lead_age) }}" readonly>
                                    </div>
                               
                                    <div class="col-md-3">
                                        <label class="form-label">โทรศัพท์ (หลัก)</label>
                                        <input type="text" class="form-control @error('lead_phone') is-invalid @enderror" 
                                               name="lead_phone" value="{{ old('lead_phone', $lead->lead_phone) }}">
                                        @error('lead_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">โทรศัพท์ (รอง)</label>
                                        <input type="text" class="form-control" name="lead_phone_2" value="{{ old('lead_phone_2', $lead->lead_phone_2) }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label">ที่อยู่ (English) 
                                            <button type="button" class="btn btn-outline-primary btn-sm ms-2" onclick="translateAddress()" title="แปลจากภาษาไทยเป็นอังกฤษ">
                                                <i class="bi bi-translate"></i> แปลภาษา
                                            </button>
                                        </label>
                                        <div class="input-group">
                                            <textarea class="form-control" name="lead_address" id="lead_address" rows="3" placeholder="กรอกที่อยู่ภาษาไทยหรืออังกฤษ">{{ old('lead_address', $lead->lead_address) }}</textarea>
                                            <button class="btn btn-outline-secondary" type="button" onclick="clearAddress()" title="ล้างข้อมูล">
                                                <i class="bi bi-eraser"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle"></i> 
                                            สามารถกรอกที่อยู่เป็นภาษาไทย แล้วกดปุ่ม "แปลภาษา" เพื่อแปลเป็นอังกฤษ
                                        </small>
                                        <div id="translationLoader" class="text-center mt-2" style="display: none;">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">กำลังแปล...</span>
                                            </div>
                                            <span class="ms-2 text-primary">กำลังแปลภาษา...</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label">ส่วนสูง (cm)</label>
                                        <input type="number" step="0.01" class="form-control" name="lead_height" id="lead_height" value="{{ old('lead_height', $lead->lead_height) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">น้ำหนัก (kg)</label>
                                        <input type="number" step="0.01" class="form-control" name="lead_weight" id="lead_weight" value="{{ old('lead_weight', $lead->lead_weight) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">BMI</label>
                                        <input type="text" class="form-control bg-light" name="lead_bmi" id="lead_bmi" value="{{ old('lead_bmi', $lead->lead_bmi) }}" readonly>
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
                                               value="{{ old('lead_passport_number', $lead->lead_passport_number) }}"
                                               placeholder="กรอกตัวเลข 6 หลักเท่านั้น">
                                        <div class="invalid-feedback" id="passport_error">
                                            กรุณากรอกตัวเลข 6 หลักเท่านั้น
                                        </div>
                                        @error('lead_passport_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Date of Issue</label>
                                        <input type="date" class="form-control" name="lead_passport_issue_date" value="{{ old('lead_passport_issue_date', $lead->lead_passport_issue_date ? $lead->lead_passport_issue_date->format('Y-m-d') : '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Date of Expiry</label>
                                        <input type="date" class="form-control" name="lead_passport_expiry_date" value="{{ old('lead_passport_expiry_date', $lead->lead_passport_expiry_date ? $lead->lead_passport_expiry_date->format('Y-m-d') : '') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Shirt size (เสื้อ)</label>
                                        <div class="d-flex gap-3 flex-wrap">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lead_shirt_size" id="shirt_s" value="S" {{ old('lead_shirt_size', $lead->lead_shirt_size) == 'S' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="shirt_s">S</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lead_shirt_size" id="shirt_m" value="M" {{ old('lead_shirt_size', $lead->lead_shirt_size) == 'M' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="shirt_m">M</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lead_shirt_size" id="shirt_l" value="L" {{ old('lead_shirt_size', $lead->lead_shirt_size) == 'L' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="shirt_l">L</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lead_shirt_size" id="shirt_xl" value="XL" {{ old('lead_shirt_size', $lead->lead_shirt_size) == 'XL' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="shirt_xl">XL</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lead_shirt_size" id="shirt_2xl" value="2XL" {{ old('lead_shirt_size', $lead->lead_shirt_size) == '2XL' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="shirt_2xl">2XL</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lead_shirt_size" id="shirt_3xl" value="3XL" {{ old('lead_shirt_size', $lead->lead_shirt_size) == '3XL' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="shirt_3xl">3XL</label>
                                            </div>
                                        </div>
                                    </div>
                               
                                    <div class="col-md-3">
                                        <label class="form-label">Pant size (กางเกง)</label>
                                        <input type="text" class="form-control" name="lead_pant_size" value="{{ old('lead_pant_size', $lead->lead_pant_size) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Shoes size (รองเท้า)</label>
                                        <input type="text" class="form-control" name="lead_shoes_size" value="{{ old('lead_shoes_size', $lead->lead_shoes_size) }}">
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
                                    <option value="elementary" {{ old('lead_education', $lead->lead_education) == 'elementary' ? 'selected' : '' }}>ELEMENTARY SCHOOL (ประถมศึกษา)</option>
                                    <option value="junior_high" {{ old('lead_education', $lead->lead_education) == 'junior_high' ? 'selected' : '' }}>JUNIOR HIGH SCHOOL (มัธยมต้น)</option>
                                    <option value="high_school" {{ old('lead_education', $lead->lead_education) == 'high_school' ? 'selected' : '' }}>HIGH SCHOOL (มัธยมปลาย)</option>
                                    <option value="voc_cert" {{ old('lead_education', $lead->lead_education) == 'voc_cert' ? 'selected' : '' }}>VOC. CERT (ปวช.)</option>
                                    <option value="high_voc_cert" {{ old('lead_education', $lead->lead_education) == 'high_voc_cert' ? 'selected' : '' }}>HIGH VOC. CERT (ปวส.)</option>
                                    <option value="bachelor" {{ old('lead_education', $lead->lead_education) == 'bachelor' ? 'selected' : '' }}>BACHELOR DEGREES (ปริญญาตรี)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">CHINESE SPEAKING (ภาษาจีน)</label>
                                <select class="form-select" name="lead_chinese_speaking">
                                    <option value="no" {{ old('lead_chinese_speaking', $lead->lead_chinese_speaking ?? 'no') == 'no' ? 'selected' : '' }}>NO</option>
                                    <option value="beginner" {{ old('lead_chinese_speaking', $lead->lead_chinese_speaking) == 'beginner' ? 'selected' : '' }}>BEGINNER</option>
                                    <option value="intermediate" {{ old('lead_chinese_speaking', $lead->lead_chinese_speaking) == 'intermediate' ? 'selected' : '' }}>INTERMEDIATE</option>
                                    <option value="advance" {{ old('lead_chinese_speaking', $lead->lead_chinese_speaking) == 'advance' ? 'selected' : '' }}>ADVANCE</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ENGLISH SPEAKING (ภาษาอังกฤษ)</label>
                                <select class="form-select" name="lead_english_speaking">
                                    <option value="no" {{ old('lead_english_speaking', $lead->lead_english_speaking ?? 'no') == 'no' ? 'selected' : '' }}>NO</option>
                                    <option value="beginner" {{ old('lead_english_speaking', $lead->lead_english_speaking) == 'beginner' ? 'selected' : '' }}>BEGINNER</option>
                                    <option value="intermediate" {{ old('lead_english_speaking', $lead->lead_english_speaking) == 'intermediate' ? 'selected' : '' }}>INTERMEDIATE</option>
                                    <option value="advance" {{ old('lead_english_speaking', $lead->lead_english_speaking) == 'advance' ? 'selected' : '' }}>ADVANCE</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">OTHER LANGUAGES (ภาษาอื่นๆ เช่น Korean, Japanese)</label>
                                <input type="text" class="form-control" name="lead_other_language" 
                                       placeholder="ระบุภาษาอื่นๆที่สามารถสื่อสารได้" value="{{ old('lead_other_language', $lead->lead_other_language) }}">
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
                                    <option value="no" {{ old('lead_work_israel', $lead->lead_work_israel ?? 'no') == 'no' ? 'selected' : '' }}>NO</option>
                                    <option value="yes" {{ old('lead_work_israel', $lead->lead_work_israel) == 'yes' ? 'selected' : '' }}>YES</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Criminal history? (ประวัติอาชญากรรม)</label>
                                <select class="form-select" name="lead_criminal_history">
                                    <option value="no" {{ old('lead_criminal_history', $lead->lead_criminal_history ?? 'no') == 'no' ? 'selected' : '' }}>NO</option>
                                    <option value="yes" {{ old('lead_criminal_history', $lead->lead_criminal_history) == 'yes' ? 'selected' : '' }}>YES</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Eyesight? (การมองเห็น)</label>
                                <select class="form-select" name="lead_eyesight">
                                    <option value="normal" {{ old('lead_eyesight', $lead->lead_eyesight ?? 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="abnormal" {{ old('lead_eyesight', $lead->lead_eyesight) == 'abnormal' ? 'selected' : '' }}>Abnormal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Color blindness (ตาบอดสี)</label>
                                <select class="form-select" name="lead_color_blindness">
                                    <option value="no" {{ old('lead_color_blindness', $lead->lead_color_blindness ?? 'no') == 'no' ? 'selected' : '' }}>NO</option>
                                    <option value="yes" {{ old('lead_color_blindness', $lead->lead_color_blindness) == 'yes' ? 'selected' : '' }}>YES</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Details (รายละเอียดเพิ่มเติม)
                                    <button type="button" class="btn btn-outline-primary btn-sm ms-2" onclick="translateAdditionalDetails()" title="แปลจากภาษาไทยเป็นอังกฤษ">
                                        <i class="bi bi-translate"></i> แปลภาษา
                                    </button>
                                </label>
                                <textarea class="form-control" name="lead_additional_details" id="lead_additional_details" rows="4" 
                                          placeholder="อื่นๆ ระบุ">{{ old('lead_additional_details', $lead->lead_additional_details) }}</textarea>
                               
                                <div id="additionalDetailsLoader" class="text-center mt-2" style="display: none;">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">กำลังแปล...</span>
                                    </div>
                                    <span class="ms-2 text-primary">กำลังแปลรายละเอียดเพิ่มเติม...</span>
                                </div>
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
                                        <option value="{{ $position->position_id }}" {{ old('position_id', $lead->position_id) == $position->position_id ? 'selected' : '' }}>
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
                                        <option value="{{ $position->position_id }}" {{ old('position_id_2', $lead->position_id_2) == $position->position_id ? 'selected' : '' }}>
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
                                        <option value="{{ $position->position_id }}" {{ old('position_id_3', $lead->position_id_3) == $position->position_id ? 'selected' : '' }}>
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
                                        <option value="{{ $country->country_id }}" {{ old('country_id', $lead->country_id) == $country->country_id ? 'selected' : '' }}>
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
                                        <option value="{{ $jobGroup->job_group_id }}" {{ old('job_group_id', $lead->job_group_id) == $jobGroup->job_group_id ? 'selected' : '' }}>
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
                            <div class="col-md-4">
                                <label class="form-label">EMERGENCY CONTACT NAME (ผู้ติดต่อฉุกเฉิน)</label>
                                <input type="text" class="form-control" name="lead_emergency_name" 
                                       placeholder="ชื่อผู้ติดต่อฉุกเฉิน" value="{{ old('lead_emergency_name', $lead->lead_emergency_name) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">STATUS (ความสัมพันธ์)</label>
                                <input type="text" class="form-control" name="lead_emergency_status" 
                                       placeholder="เช่น พ่อ, แม่, พี่, น้อง" value="{{ old('lead_emergency_status', $lead->lead_emergency_status) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">TEL (เบอร์โทรศัพท์)</label>
                                <input type="text" class="form-control" name="lead_emergency_phone" 
                                       placeholder="เบอร์โทรผู้ติดต่อฉุกเฉิน" value="{{ old('lead_emergency_phone', $lead->lead_emergency_phone) }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">DRIVING LICENSE (ใบขับขี่)</label>
                                <select class="form-select" name="lead_driving_license">
                                    <option value="no" {{ old('lead_driving_license', $lead->lead_driving_license ?? 'no') == 'no' ? 'selected' : '' }}>NO (ไม่มี)</option>
                                    <option value="yes" {{ old('lead_driving_license', $lead->lead_driving_license) == 'yes' ? 'selected' : '' }}>YES (มี)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">TYPE OF CAR (ประเภทรถ) 
                                    <i class="bi bi-info-circle text-info ms-1" data-bs-toggle="tooltip" data-bs-placement="top" 
                                       title="เลือกประเภทใบขับขี่ที่มี หากไม่มีให้เลือก 'None'"></i>
                                </label>
                                <select class="form-select" name="lead_car_type" id="lead_car_type">
                                    <option value="">-- เลือกประเภทใบขับขี่ --</option>
                                    <option value="None" {{ old('lead_car_type', $lead->lead_car_type) == 'None' ? 'selected' : '' }}>None (ไม่มี)</option>
                                    
                                    <optgroup label="🚗 ใบขับขี่ส่วนบุคคล (Private License)">
                                        <option value="Temporary Driving License" {{ old('lead_car_type', $lead->lead_car_type) == 'Temporary Driving License' ? 'selected' : '' }}>
                                            Temporary Driving License (ใบอนุญาตขับรถชนิดชั่วคราว)
                                        </option>
                                        <option value="Private Car Driving License" {{ old('lead_car_type', $lead->lead_car_type) == 'Private Car Driving License' ? 'selected' : '' }}>
                                            Private Car Driving License (ใบอนุญาตขับรถยนต์ส่วนบุคคล)
                                        </option>
                                        <option value="Private Three-wheeled Car Driving License" {{ old('lead_car_type', $lead->lead_car_type) == 'Private Three-wheeled Car Driving License' ? 'selected' : '' }}>
                                            Private Three-wheeled Car (ใบอนุญาตขับรถยนต์สามล้อส่วนบุคคล)
                                        </option>
                                        <option value="Motorcycle Driving License" {{ old('lead_car_type', $lead->lead_car_type) == 'Motorcycle Driving License' ? 'selected' : '' }}>
                                            Motorcycle Driving License (ใบอนุญาตขับรถจักรยานยนต์ส่วนบุคคล)
                                        </option>
                                    </optgroup>
                                    
                                    <optgroup label="🚌 ใบขับขี่สาธารณะ (Public License)">
                                        <option value="Public Car Driving License" {{ old('lead_car_type', $lead->lead_car_type) == 'Public Car Driving License' ? 'selected' : '' }}>
                                            Public Car Driving License (ใบอนุญาตขับรถยนต์สาธารณะ)
                                        </option>
                                        <option value="Public Three-wheeled Car Driving License" {{ old('lead_car_type', $lead->lead_car_type) == 'Public Three-wheeled Car Driving License' ? 'selected' : '' }}>
                                            Public Three-wheeled Car (ใบอนุญาตขับรถยนต์สามล้อสาธารณะ)
                                        </option>
                                        <option value="Public Motorcycle Driving License" {{ old('lead_car_type', $lead->lead_car_type) == 'Public Motorcycle Driving License' ? 'selected' : '' }}>
                                            Public Motorcycle License (ใบอนุญาตขับรถจักรยานยนต์สาธารณะ)
                                        </option>
                                    </optgroup>
                                    
                                    <optgroup label="🚚 ใบขับขี่รถขนส่ง (Transport License)">
                                        <option value="Category 2 (บ.2)" {{ old('lead_car_type', $lead->lead_car_type) == 'Category 2 (บ.2)' ? 'selected' : '' }}>
                                            Category 2 (บ.2) - รถขนส่งผู้โดยสาร/รถบรรทุกขนาดเล็ก
                                        </option>
                                        <option value="Category 2 (ท.2)" {{ old('lead_car_type', $lead->lead_car_type) == 'Category 2 (ท.2)' ? 'selected' : '' }}>
                                            Category 2 (ท.2) - รถขนส่งสาธารณะ
                                        </option>
                                        <option value="Category 4 (บ.4)" {{ old('lead_car_type', $lead->lead_car_type) == 'Category 4 (บ.4)' ? 'selected' : '' }}>
                                            Category 4 (บ.4) - รถขนส่งวัตถุอันตราย
                                        </option>
                                        <option value="Category 4 (ท.4)" {{ old('lead_car_type', $lead->lead_car_type) == 'Category 4 (ท.4)' ? 'selected' : '' }}>
                                            Category 4 (ท.4) - รถขนส่งอื่นๆ
                                        </option>
                                    </optgroup>
                                    
                                    <optgroup label="🌍 ใบขับขี่ประเภทอื่นๆ (Other License)">
                                        <option value="International Driving Permit" {{ old('lead_car_type', $lead->lead_car_type) == 'International Driving Permit' ? 'selected' : '' }}>
                                            International Driving Permit (ใบอนุญาตขับรถระหว่างประเทศ)
                                        </option>
                                        <option value="Road Roller Driving License" {{ old('lead_car_type', $lead->lead_car_type) == 'Road Roller Driving License' ? 'selected' : '' }}>
                                            Road Roller Driving License (ใบอนุญาตขับรถบดถนน)
                                        </option>
                                    </optgroup>
                                </select>
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
                                        <option value="{{ $round->examination_round_id }}" {{ old('examination_round_id', $lead->examination_round_id) == $round->examination_round_id ? 'selected' : '' }}>
                                            {{ date('d/m/Y', strtotime($round->examination_round_name)) }} - {{ $round->examination_round_note }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Recommender (ผู้แนะนำ/สาย) *ว่างไว้หากไม่มี </label>
                                <select class="form-select" name="lead_recommender_staff_sub_id">
                                    <option value="">-- เลือกผู้แนะนำ --</option>
                                    @foreach($staffSubs as $staffSub)
                                        <option value="{{ $staffSub->staff_sub_id }}" 
                                                {{ old('lead_recommender_staff_sub_id', $lead->lead_recommender_staff_sub_id) == $staffSub->staff_sub_id ? 'selected' : '' }}>
                                            {{ $staffSub->staff_sub_name }}
                                            @if($staffSub->staff_sub_phone)
                                                ({{ $staffSub->staff_sub_phone }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">สถานะ Lead</label>
                                <select class="form-select" name="lead_status">
                                    <option value="new" {{ old('lead_status', $lead->lead_status ?? 'new') == 'new' ? 'selected' : '' }}>ใหม่</option>
                                    <option value="contacted" {{ old('lead_status', $lead->lead_status) == 'contacted' ? 'selected' : '' }}>ติดต่อแล้ว</option>
                                    <option value="interview" {{ old('lead_status', $lead->lead_status) == 'interview' ? 'selected' : '' }}>นัดสัมภาษณ์</option>
                                    <option value="qualified" {{ old('lead_status', $lead->lead_status) == 'qualified' ? 'selected' : '' }}>ผ่านคุณสมบัติ</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">เจ้าหน้าที่ผู้รับผิดชอบ (พนักงาน วีดี)</label>
                                <select class="form-select" name="staff_id">
                                    <option value="">-- เลือกเจ้าหน้าที่ --</option>
                                    @foreach($staffs as $staff)
                                        <option value="{{ $staff->staff_id }}" {{ old('staff_id', $lead->staff_id) == $staff->staff_id ? 'selected' : '' }}>
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
                                          placeholder="บันทึกข้อมูลเพิ่มเติม...">{{ old('lead_note', $lead->lead_note) }}</textarea>
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
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="รูปถ่ายขนาด_2_นิ้ว" id="doc1" {{ in_array('รูปถ่ายขนาด_2_นิ้ว', old('documents', $lead->documents ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc1">1. รูปถ่ายขนาด 2 นิ้ว</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="หนังสือเดินทาง_สำเนา" id="doc8" {{ in_array('หนังสือเดินทาง_สำเนา', old('documents', $lead->documents ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc8">8. หนังสือเดินทาง (สำเนา)</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="Passport" id="doc2" {{ in_array('Passport', old('documents', $lead->documents ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc2">2. Passport</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="ใบ_สผท" id="doc9" {{ in_array('ใบ_สผท', old('documents', $lead->documents ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc9">9. ใบ สผท.</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="บัตรประชาชน_สำเนา" id="doc3" {{ in_array('บัตรประชาชน_สำเนา', old('documents', $lead->documents ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc3">3. บัตรประชาชน (สำเนา)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="ใบสุคันธ์_สำเนาจริงในเค" id="doc10" {{ in_array('ใบสุคันธ์_สำเนาจริงในเค', old('documents', $lead->documents ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc10">10. ใบสุคันธ์ (สำเนาจริงในเค)</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="ผลโรค" id="doc4" {{ in_array('ผลโรค', old('documents', $lead->documents ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc4">4. ผลโรค</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="CV" id="doc11" {{ in_array('CV', old('documents', $lead->documents ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc11">11. CV</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="ใบผ่านงาน" id="doc5" {{ in_array('ใบผ่านงาน', old('documents', $lead->documents ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc5">5. ใบผ่านงาน</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="หนังสือสมรส_กรณีสมรส" id="doc12" {{ in_array('หนังสือสมรส_กรณีสมรส', old('documents', $lead->documents ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc12">12. หนังสือสมรส (กรณีสมรส)</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="ใบอนุญาต_No" id="doc6" {{ in_array('ใบอนุญาต_No', old('documents', $lead->documents ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc6">6. ใบอนุญาต No. <input type="text" class="form-control form-control-sm d-inline-block" style="width: 120px; margin-left: 10px;" name="license_number" value="{{ old('license_number', $lead->license_number) }}" placeholder="เลขที่"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="สำเนาบัตรประชาชนคู่สมรส_กรณีสมรส" id="doc13" {{ in_array('สำเนาบัตรประชาชนคู่สมรส_กรณีสมรส', old('documents', $lead->documents ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc13">13. สำเนาบัตรประชาชนคู่สมรส(กรณีสมรส)</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="CID" id="doc7" {{ in_array('CID', old('documents', $lead->documents ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="doc7">7. CID</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="documents[]" value="สำเนาบัตรอัยพ่อแม่สกุล_กรณีเดคลแปลปธิ" id="doc14" {{ in_array('สำเนาบัตรอัยพ่อแม่สกุล_กรณีเดคลแปลปธิ', old('documents', $lead->documents ?? [])) ? 'checked' : '' }}>
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
                            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#jobHistoryModal">
                                <i class="bi bi-plus-circle"></i> เพิ่มประวัติ
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="job-history-container">
                            <div class="table-responsive">
                                <table class="table table-striped" id="jobHistoryTable" style="display: none;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>ช่วงเวลา</th>
                                            <th>ตำแหน่ง</th>
                                            <th>บริษัท/ประเภทงาน</th>
                                            <th>ประเทศ</th>
                                            <th>ปีประสบการณ์</th>
                                            <th style="width: 100px;">การจัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jobHistoryTableBody">
                                    </tbody>
                                </table>
                            </div>
                            <div class="alert alert-info" id="noJobHistoryAlert">
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
                        <i class="bi bi-save"></i> บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Job History Modal -->
    <div class="modal fade" id="jobHistoryModal" tabindex="-1" aria-labelledby="jobHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jobHistoryModalLabel">
                        <i class="bi bi-briefcase-fill me-2"></i><span id="modalTitle">เพิ่มประวัติการทำงาน</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="jobHistoryForm">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Start Date (วันที่เริ่มงาน) <span class="text-danger">*</span></label>
                                <input type="month" class="form-control" id="modal_start_date" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date (วันที่สิ้นสุด)</label>
                                <input type="month" class="form-control" id="modal_end_date" placeholder="เว้นว่างถ้าเป็นงานปัจจุบัน">
                            </div>
                        </div>

                           <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Type of Work And Company Name (ประเภทงาน/บริษัท) <span class="text-danger">*</span>
                                    <button type="button" class="btn btn-outline-info btn-sm ms-2" onclick="translateJobField('modal_company_type')" title="แปลจากไทยเป็นอังกฤษ">
                                        <i class="bi bi-translate"></i>
                                    </button>
                                </label>
                                <input type="text" class="form-control" id="modal_company_type" required
                                       placeholder="เช่น งานก่อสร้างทั่วไป หรือ General Construction, Construction Contractor">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Company Name (ชื่อบริษัท - ถ้ามี)
                                    <button type="button" class="btn btn-outline-info btn-sm ms-2" onclick="translateJobField('modal_company_name')" title="แปลจากไทยเป็นอังกฤษ">
                                        <i class="bi bi-translate"></i>
                                    </button>
                                </label>
                                <input type="text" class="form-control" id="modal_company_name" placeholder="ชื่อบริษัท (ไม่บังคับ)">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Position (ตำแหน่ง) <span class="text-danger">*</span>
                                    <button type="button" class="btn btn-outline-info btn-sm ms-2" onclick="translateJobField('modal_position')" title="แปลจากไทยเป็นอังกฤษ">
                                        <i class="bi bi-translate"></i>
                                    </button>
                                </label>
                                <input type="text" class="form-control" id="modal_position" required
                                       placeholder="เช่น ช่างปูน หรือ PLASTERER, CARPENTER, TILE">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Country (ประเทศ)</label>
                                <select class="form-select" id="modal_country">
                                    <option value="THAI">THAI</option>
                                    <option value="ISRAEL">ISRAEL</option>
                                    <option value="OTHER">OTHER</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Experience Years (ปี)</label>
                                <input type="number" step="0.5" class="form-control" id="modal_experience_years" 
                                       min="0" value="0">
                            </div>
                        </div>

                     

                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Job Description / Responsibilities (รายละเอียดงานที่ทำ)
                                    <button type="button" class="btn btn-outline-info btn-sm ms-2" onclick="translateJobField('modal_description')" title="แปลจากไทยเป็นอังกฤษ">
                                        <i class="bi bi-translate"></i>
                                    </button>
                                </label>
                                <textarea class="form-control" id="modal_description" rows="4" 
                                          placeholder="- ระบุหน้าที่ความรับผิดชอบ&#10;- ผลงานที่สำคัญ&#10;- ทักษะที่ได้รับ&#10;หรือกรอกไทยแล้วกดแปล"></textarea>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <label class="form-label">About The Company (เกี่ยวกับบริษัท)
                                    <button type="button" class="btn btn-outline-info btn-sm ms-2" onclick="translateJobField('modal_company_about')" title="แปลจากไทยเป็นอังกฤษ">
                                        <i class="bi bi-translate"></i>
                                    </button>
                                </label>
                                <textarea class="form-control" id="modal_company_about" rows="3" 
                                          placeholder="เล่าสั้น ๆ เกี่ยวกับบริษัท ธรรมชาติของงาน สินค้า/บริการ เป็นต้น"></textarea>
                            </div>
                        </div>

                        <!-- Hidden inputs for job history form submission -->
                        <div id="jobHistoryInputs"></div>

                        <div class="alert alert-danger d-none" id="modalOverlapError">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>คำเตือน:</strong> ช่วงเวลาทำงานซ้ำซ้อนกับประวัติที่มีอยู่แล้ว
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-primary" id="saveJobHistory">
                        <i class="bi bi-save"></i> <span id="saveButtonText">บันทึก</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let jobHistoryCount = 0;

        function previewPhoto(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            // ตรวจสอบประเภทไฟล์
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowedTypes.includes(file.type)) {
                alert('กรุณาเลือกไฟล์รูปภาพ (JPEG, PNG, JPG) เท่านั้น');
                event.target.value = '';
                return;
            }
            
            // ตรวจสอบขนาดไฟล์ (2MB = 2048KB)
            if (file.size > 2048 * 1024) {
                alert('ขนาดไฟล์ต้องไม่เกิน 2MB');
                event.target.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('photo_preview');
                output.src = reader.result;
            };
            reader.onerror = function() {
                alert('เกิดข้อผิดพลาดในการอ่านไฟล์');
                event.target.value = '';
            };
            reader.readAsDataURL(file);
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
            
            // Debug: ตรวจสอบไฟล์รูปภาพ
            const photoInput = document.getElementById('lead_photo_input');
            if (photoInput && photoInput.files.length > 0) {
                console.log('ไฟล์รูปภาพที่เลือก:', photoInput.files[0]);
                console.log('ขนาดไฟล์:', photoInput.files[0].size, 'bytes');
                console.log('ประเภทไฟล์:', photoInput.files[0].type);
            } else {
                console.log('ไม่มีไฟล์รูปภาพใหม่ที่เลือก (จะใช้รูปเดิม)');
            }
        });

        let jobHistoryData = @json($lead->jobHistory->toArray());
        let editingJobHistoryIndex = -1;

        // Debug: Log job history data
        console.log('Job History Data:', jobHistoryData);

        // Initialize job history data transformation immediately
        if (jobHistoryData && jobHistoryData.length > 0) {
            jobHistoryData = jobHistoryData.map(function(history) {
                return {
                    job_history_id: history.job_history_id,
                    start_date: history.start_date,
                    end_date: history.end_date,
                    position: history.position,
                    company_type: history.company_type,
                    company_name: history.company_name,
                    country: history.country,
                    experience_years: history.experience_years,
                    description: history.description,
                    company_about: history.company_about || '',
                    display_order: history.display_order
                };
            });
            
            console.log('Transformed Job History Data:', jobHistoryData);
        }

        // แสดง job history ที่มีอยู่แล้วเมื่อโหลดหน้า
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOMContentLoaded - Job History Count:', jobHistoryData.length);
            console.log('DOMContentLoaded - Table exists:', !!document.getElementById('jobHistoryTable'));
            console.log('DOMContentLoaded - TableBody exists:', !!document.getElementById('jobHistoryTableBody'));
            
            if (jobHistoryData.length > 0) {
                console.log('Calling updateJobHistoryTable from DOMContentLoaded');
                updateJobHistoryTable();
            }
        });

        // Also try to show table immediately if DOM is already ready
        if (document.readyState === 'loading') {
            // Document is still loading, will handle in DOMContentLoaded
        } else {
            // Document already loaded
            if (jobHistoryData.length > 0) {
                console.log('Calling updateJobHistoryTable immediately (DOM ready)');
                setTimeout(() => updateJobHistoryTable(), 100);
            }
        }

        // เปิด modal สำหรับเพิ่มประวัติใหม่
        document.getElementById('jobHistoryModal').addEventListener('show.bs.modal', function (e) {
            if (editingJobHistoryIndex === -1) {
                // เพิ่มใหม่
                document.getElementById('modalTitle').textContent = 'เพิ่มประวัติการทำงาน';
                document.getElementById('saveButtonText').textContent = 'บันทึก';
                clearModalForm();
            }
        });

        // บันทึกข้อมูล job history
        document.getElementById('saveJobHistory').addEventListener('click', async function() {
            if (validateModalForm()) {
                const formData = {
                    start_date: document.getElementById('modal_start_date').value,
                    end_date: document.getElementById('modal_end_date').value,
                    position: document.getElementById('modal_position').value,
                    country: document.getElementById('modal_country').value,
                    experience_years: document.getElementById('modal_experience_years').value,
                    company_type: document.getElementById('modal_company_type').value,
                    company_name: document.getElementById('modal_company_name').value,
                    description: document.getElementById('modal_description').value,
                    company_about: document.getElementById('modal_company_about').value
                };

                // ตรวจสอบการซ้ำซ้อนก่อนบันทึก
                if (checkModalOverlap(formData)) {
                    document.getElementById('modalOverlapError').classList.remove('d-none');
                    return;
                } else {
                    document.getElementById('modalOverlapError').classList.add('d-none');
                }

                // Disable button while saving
                const saveButton = document.getElementById('saveJobHistory');
                const originalText = saveButton.innerHTML;
                saveButton.disabled = true;
                saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>กำลังบันทึก...';

                try {
                    let response;
                    if (editingJobHistoryIndex === -1) {
                        // เพิ่มใหม่ - POST request
                        response = await fetch(`/leads/{{ $lead->lead_id }}/job-history`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(formData)
                        });
                    } else {
                        // แก้ไข - PUT request
                        const jobHistoryId = jobHistoryData[editingJobHistoryIndex].job_history_id;
                        response = await fetch(`/leads/{{ $lead->lead_id }}/job-history/${jobHistoryId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(formData)
                        });
                    }

                    const result = await response.json();
                    
                    if (result.success) {
                        // Show success message and reload page
                        alert(result.message);
                        
                        // Reload the page to refresh all data
                        window.location.reload();
                    } else {
                        alert('เกิดข้อผิดพลาด: ' + result.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการบันทึก กรุณาลองใหม่อีกครั้ง');
                } finally {
                    // Re-enable button
                    saveButton.disabled = false;
                    saveButton.innerHTML = originalText;
                }
            }
        });

        // ตรวจสอบฟอร์มใน modal
        function validateModalForm() {
            const requiredFields = ['modal_start_date', 'modal_position', 'modal_company_type'];
            let isValid = true;

            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            return isValid;
        }

        // ตรวจสอบการซ้ำซ้อนใน modal
        function checkModalOverlap(newData) {
            const newStart = newData.start_date;
            const newEnd = newData.end_date || '9999-12';

            for (let i = 0; i < jobHistoryData.length; i++) {
                if (i === editingJobHistoryIndex) continue; // ข้าม record ที่กำลังแก้ไข

                const existingStart = jobHistoryData[i].start_date;
                const existingEnd = jobHistoryData[i].end_date || '9999-12';

                if (isPeriodsOverlapping(newStart, newEnd, existingStart, existingEnd)) {
                    // ไม่อนุญาตให้ทับซ้อน ยกเว้นกรณีที่สิ้นสุดและเริ่มต้นเป็นเดือนเดียวกัน
                    if (!(newEnd === existingStart || newStart === existingEnd)) {
                        return true; // มีการซ้ำซ้อน
                    }
                }
            }
            return false; // ไม่มีการซ้ำซ้อน
        }

        // อัพเดต table
        function updateJobHistoryTable() {
            const tableBody = document.getElementById('jobHistoryTableBody');
            const table = document.getElementById('jobHistoryTable');
            const noDataAlert = document.getElementById('noJobHistoryAlert');

            if (jobHistoryData.length === 0) {
                table.style.display = 'none';
                noDataAlert.style.display = 'block';
                document.getElementById('jobHistoryInputs').innerHTML = '';
                return;
            }

            table.style.display = 'table';
            noDataAlert.style.display = 'none';
            updateJobHistoryInputs();

            tableBody.innerHTML = '';
            jobHistoryData.forEach((data, index) => {
                const row = document.createElement('tr');
                const periodText = data.end_date ? 
                    `${formatDate(data.start_date)} - ${formatDate(data.end_date)}` : 
                    `${formatDate(data.start_date)} - ปัจจุบัน`;

                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${periodText}</td>
                    <td>${data.position}</td>
                    <td>
                        <div>${data.company_type}</div>
                        ${data.company_name ? `<small class="text-muted">${data.company_name}</small>` : ''}
                    </td>
                    <td>${data.country}</td>
                    <td>${data.experience_years} ปี</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="editJobHistory(${index})" title="แก้ไข">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeJobHistory(${index})" title="ลบ">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;

                tableBody.appendChild(row);
            });
        }

        // แก้ไขประวัติ
        function editJobHistory(index) {
            editingJobHistoryIndex = index;
            const data = jobHistoryData[index];

            document.getElementById('modalTitle').textContent = 'แก้ไขประวัติการทำงาน';
            document.getElementById('saveButtonText').textContent = 'บันทึกการแก้ไข';

            // กรอกข้อมูลเดิมใน modal
            document.getElementById('modal_start_date').value = data.start_date;
            document.getElementById('modal_end_date').value = data.end_date;
            document.getElementById('modal_position').value = data.position;
            document.getElementById('modal_country').value = data.country;
            document.getElementById('modal_experience_years').value = data.experience_years;
            document.getElementById('modal_company_type').value = data.company_type;
            document.getElementById('modal_company_name').value = data.company_name;
            document.getElementById('modal_description').value = data.description;
            document.getElementById('modal_company_about').value = data.company_about || '';

            bootstrap.Modal.getOrCreateInstance(document.getElementById('jobHistoryModal')).show();
        }

        // ลบประวัติ
        async function removeJobHistory(index) {
            if (confirm('ต้องการลบประวัติการทำงานนี้หรือไม่?')) {
                const jobHistoryId = jobHistoryData[index].job_history_id;
                
                try {
                    const response = await fetch(`/leads/{{ $lead->lead_id }}/job-history/${jobHistoryId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        jobHistoryData.splice(index, 1);
                        updateJobHistoryTable();
                        alert(result.message);
                    } else {
                        alert('เกิดข้อผิดพลาด: ' + result.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการลบ กรุณาลองใหม่อีกครั้ง');
                }
            }
        }

        // ล้างฟอร์ม modal
        function clearModalForm() {
            document.getElementById('jobHistoryForm').reset();
            document.getElementById('modal_experience_years').value = '0';
            document.getElementById('modalOverlapError').classList.add('d-none');
            
            // ลบ validation classes
            document.querySelectorAll('#jobHistoryModal .is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
        }

        // อัพเดต hidden inputs สำหรับ form submission
        function updateJobHistoryInputs() {
            const container = document.getElementById('jobHistoryInputs');
            container.innerHTML = '';
            
            jobHistoryData.forEach((data, index) => {
                const inputs = `
                    <input type="hidden" name="job_history[${index}][start_date]" value="${escapeHtml(data.start_date)}">
                    <input type="hidden" name="job_history[${index}][end_date]" value="${escapeHtml(data.end_date)}">
                    <input type="hidden" name="job_history[${index}][position]" value="${escapeHtml(data.position)}">
                    <input type="hidden" name="job_history[${index}][country]" value="${escapeHtml(data.country)}">
                    <input type="hidden" name="job_history[${index}][experience_years]" value="${escapeHtml(data.experience_years)}">
                    <input type="hidden" name="job_history[${index}][company_type]" value="${escapeHtml(data.company_type)}">
                    <input type="hidden" name="job_history[${index}][company_name]" value="${escapeHtml(data.company_name)}">
                    <input type="hidden" name="job_history[${index}][description]" value="${escapeHtml(data.description)}">
                    <input type="hidden" name="job_history[${index}][company_about]" value="${escapeHtml(data.company_about)}">
                `;
                container.innerHTML += inputs;
            });
        }
        
        // Helper function to escape HTML in form values
        function escapeHtml(text) {
            if (!text) return '';
            // Convert to string first to handle numbers and other types
            text = String(text);
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }

        // ฟอร์แมตวันที่
        function formatDate(dateString) {
            if (!dateString) return '';
            const [year, month] = dateString.split('-');
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                              'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return `${monthNames[parseInt(month) - 1]} ${year}`;
        }

        // ฟังก์ชันตรวจสอบการซ้ำซ้อนของช่วงเวลาทำงาน (ใช้กับข้อมูลใน jobHistoryData)
        function validateJobHistoryOverlap() {
            // ตรวจสอบข้อมูลใน jobHistoryData แทน
            for (let i = 0; i < jobHistoryData.length; i++) {
                for (let j = i + 1; j < jobHistoryData.length; j++) {
                    const period1 = jobHistoryData[i];
                    const period2 = jobHistoryData[j];
                    
                    const start1 = period1.start_date;
                    const end1 = period1.end_date || '9999-12';
                    const start2 = period2.start_date;
                    const end2 = period2.end_date || '9999-12';
                    
                    // ตรวจสอบว่าช่วงเวลาทับซ้อนกันหรือไม่
                    if (isPeriodsOverlapping(start1, end1, start2, end2)) {
                        // ไม่อนุญาตให้ทับซ้อน ยกเว้นกรณีที่สิ้นสุดและเริ่มต้นเป็นเดือนเดียวกัน
                        if (!(end1 === start2 || start1 === end2)) {
                            return false; // มีการซ้ำซ้อน
                        }
                    }
                }
            }
            
            return true; // ไม่มีการซ้ำซ้อน
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

        // ฟังก์ชันแปลภาษาที่อยู่ด้วย Google Translate API
        async function translateAddress() {
            const addressField = document.getElementById('lead_address');
            const addressText = addressField.value.trim();
            
            if (!addressText) {
                alert('กรุณากรอกที่อยู่ก่อนแปลภาษา');
                return;
            }

            // แสดง loading
            document.getElementById('translationLoader').style.display = 'block';
            
            try {
                // ตรวจสอบว่าเป็นภาษาไทยหรือไม่
                if (containsThai(addressText)) {
                    // แปลจากไทยเป็นอังกฤษด้วย Google Translate
                    const translatedText = await translateWithGoogle(addressText, 'th', 'en');
                    addressField.value = translatedText;
                    
                    // แสดงข้อความสำเร็จ
                    showTranslationSuccess();
                } else {
                    // ถ้าเป็นอังกฤษอยู่แล้ว ลองแปลกลับเป็นไทย
                    if (confirm('ข้อความนี้ดูเหมือนเป็นภาษาอังกฤษ ต้องการแปลเป็นไทยหรือไม่?')) {
                        const translatedText = await translateWithGoogle(addressText, 'en', 'th');
                        addressField.value = translatedText;
                        showTranslationSuccess();
                    }
                }
            } catch (error) {
                console.error('Translation error:', error);
                alert('เกิดข้อผิดพลาดในการแปลภาษา: ' + (error.message || 'กรุณาลองใหม่อีกครั้ง'));
            } finally {
                // ซ่อน loading
                document.getElementById('translationLoader').style.display = 'none';
            }
        }

        // ตรวจสอบว่ามีตัวอักษรไทยหรือไม่
        function containsThai(text) {
            return /[\u0E00-\u0E7F]/.test(text);
        }

        // ฟังก์ชันแปลภาษาด้วย Google Translate API
        async function translateWithGoogle(text, sourceLang, targetLang) {
            // ลองใช้ Google Translate API ฟรี (Google Translate Website API)
            const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=${sourceLang}&tl=${targetLang}&dt=t&q=${encodeURIComponent(text)}`;
            
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const result = await response.json();
                
                // ตรวจสอบและประมวลผลข้อมูลที่ได้จาก Google Translate
                if (result && result[0] && result[0].length > 0) {
                    let translatedText = '';
                    for (let i = 0; i < result[0].length; i++) {
                        if (result[0][i][0]) {
                            translatedText += result[0][i][0];
                        }
                    }
                    
                    // ปรับแต่งผลลัพธ์สำหรับที่อยู่
                    if (targetLang === 'en') {
                        translatedText = improveEnglishAddress(translatedText);
                    }
                    
                    return translatedText.trim();
                } else {
                    throw new Error('ไม่ได้รับข้อมูลการแปลจาก Google Translate');
                }
                
            } catch (error) {
                console.error('Google Translate API Error:', error);
                // ถ้า Google API ไม่ทำงาน ใช้ fallback dictionary
                if (sourceLang === 'th' && targetLang === 'en') {
                    return await fallbackTranslateThaiToEnglish(text);
                }
                throw new Error('ไม่สามารถเชื่อมต่อ Google Translate API ได้');
            }
        }

        // ฟังก์ชันปรับปรุงที่อยู่ภาษาอังกฤษ
        function improveEnglishAddress(text) {
            return text
                // ปรับแก้คำที่แปลผิดพลาด
                .replace(/\bvillage\s+(\d+)/gi, 'Moo $1')
                .replace(/\bmoo\s+house/gi, 'Moo')
                .replace(/\bsub\s*-?\s*district\b/gi, 'Sub-district')
                .replace(/\bamphoe\b/gi, 'District')
                .replace(/\btambon\b/gi, 'Sub-district')
                .replace(/\bmuang\b/gi, 'Mueang')
                .replace(/\bkrung\s*thep\b/gi, 'Bangkok')
                .replace(/\bbangkok\s+metropolitan\b/gi, 'Bangkok')
                // ปรับรูปแบบตัวเลข
                .replace(/\b(\d+)\s*\/\s*(\d+)\b/g, '$1/$2')
                .replace(/\bno\.\s*(\d+)/gi, 'No. $1')
                // จัดรูปแบบให้เป็นมาตรฐาน
                .replace(/\s+/g, ' ')
                .trim();
        }

        // ฟังก์ชัน fallback ใช้พจนานุกรม (เก็บไว้สำรอง)
        async function fallbackTranslateThaiToEnglish(thaiText) {
            // พจนานุกรมพื้นฐานสำหรับกรณีฉุกเฉิน
            const basicDictionary = {
                'บ้านเลขที่': '',
                'เลขที่': 'No.',
                'หมู่': 'Moo',
                'ซอย': 'Soi',
                'ถนน': 'Road',
                'ตำบล': 'Sub-district',
                'อำเภอ': 'District',
                'จังหวัด': 'Province',
                'กรุงเทพมหานคร': 'Bangkok',
                'กรุงเทพฯ': 'Bangkok'
            };

            let translatedText = thaiText;
            for (const [thai, english] of Object.entries(basicDictionary)) {
                if (thai && english) {
                    const regex = new RegExp(thai, 'g');
                    translatedText = translatedText.replace(regex, english);
                }
            }
            
            return translatedText.trim();
        }

        // ฟังก์ชันแสดงข้อความสำเร็จ
        function showTranslationSuccess() {
            // แสดงข้อความสำเร็จชั่วคราว
            const successMsg = document.createElement('div');
            successMsg.className = 'alert alert-success alert-dismissible fade show mt-2';
            successMsg.innerHTML = `
                <i class="bi bi-check-circle-fill me-2"></i>
                แปลภาษาสำเร็จ!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.getElementById('lead_address').parentNode.appendChild(successMsg);
            
            // ซ่อนข้อความหลัง 3 วินาที
            setTimeout(() => {
                if (successMsg.parentNode) {
                    successMsg.parentNode.removeChild(successMsg);
                }
            }, 3000);
        }

        // ฟังก์ชันแปลภาษาสำหรับฟิลด์ Job History
        async function translateJobField(fieldId) {
            const field = document.getElementById(fieldId);
            const fieldText = field.value.trim();
            
            if (!fieldText) {
                alert('กรุณากรอกข้อมูลก่อนแปลภาษา');
                field.focus();
                return;
            }

            // แสดง loading บนปุ่ม
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>แปล...';
            button.disabled = true;
            
            try {
                // ตรวจสอบว่าเป็นภาษาไทยหรือไม่
                if (containsThai(fieldText)) {
                    // แปลจากไทยเป็นอังกฤษ พร้อมปรับปรุงสำหรับงาน
                    const translatedText = await translateJobText(fieldText, 'th', 'en', fieldId);
                    field.value = translatedText;
                    
                    // แสดงข้อความสำเร็จ
                    showJobTranslationSuccess(field);
                } else {
                    // ถ้าเป็นอังกฤษอยู่แล้ว ลองแปลกลับเป็นไทย
                    if (confirm('ข้อความนี้ดูเหมือนเป็นภาษาอังกฤษ ต้องการแปลเป็นไทยหรือไม่?')) {
                        const translatedText = await translateJobText(fieldText, 'en', 'th', fieldId);
                        field.value = translatedText;
                        showJobTranslationSuccess(field);
                    }
                }
            } catch (error) {
                console.error('Job Translation error:', error);
                alert('เกิดข้อผิดพลาดในการแปลภาษา: ' + (error.message || 'กรุณาลองใหม่อีกครั้ง'));
            } finally {
                // คืนค่าปุ่ม
                button.innerHTML = originalContent;
                button.disabled = false;
            }
        }

        // ฟังก์ชันแปลข้อความที่เกี่ยวกับงาน
        async function translateJobText(text, sourceLang, targetLang, fieldType) {
            try {
                // ใช้ Google Translate API
                const translatedText = await translateWithGoogle(text, sourceLang, targetLang);
                
                // ปรับปรุงผลลัพธ์ตามประเภทฟิลด์
                if (targetLang === 'en') {
                    return improveJobTranslation(translatedText, fieldType);
                }
                
                return translatedText;
            } catch (error) {
                // ถ้า Google API ไม่ทำงาน ใช้ fallback dictionary
                if (sourceLang === 'th' && targetLang === 'en') {
                    return fallbackTranslateJobText(text, fieldType);
                }
                throw error;
            }
        }

        // ฟังก์ชันปรับปรุงการแปลงานให้เหมาะสม
        function improveJobTranslation(text, fieldType) {
            let improvedText = text;

            // พจนานุกรมงานทั่วไป
            const jobDictionary = {
                // ตำแหน่งงาน
                'ช่างปูน': 'Plasterer',
                'ช่างไม้': 'Carpenter',
                'ช่างเหล็ก': 'Steel Worker',
                'ช่างเชื่อม': 'Welder',
                'ช่างไฟ': 'Electrician',
                'ช่างประปา': 'Plumber',
                'ช่างก่อสร้าง': 'Construction Worker',
                'ช่างติดตั้ง': 'Installer',
                'ช่างซ่อม': 'Mechanic',
                'ช่างยนต์': 'Automotive Mechanic',
                'คนงานทั่วไป': 'General Labor',
                'คนงานก่อสร้าง': 'Construction Labor',
                'หัวหน้างาน': 'Supervisor',
                'ผู้จัดการ': 'Manager',
                'ผู้ช่วย': 'Assistant',
                'พนักงานขาย': 'Sales Staff',
                'แม่บ้าน': 'Housekeeper',
                'พ่อครัว': 'Chef',
                'แม่ครัว': 'Cook',
                'พนักงานทำความสะอาด': 'Cleaner',
                'คนขับรถ': 'Driver',
                'คนสวน': 'Gardener',
                'เจ้าหน้าที่': 'Officer',
                
                // ประเภทงาน
                'งานก่อสร้าง': 'Construction Work',
                'งานก่อสร้างทั่วไป': 'General Construction',
                'งานตกแต่ง': 'Interior Work',
                'งานซ่อมแซม': 'Maintenance Work',
                'งานบริการ': 'Service Work',
                'งานขาย': 'Sales Work',
                'งานครัว': 'Kitchen Work',
                'งานทำความสะอาด': 'Cleaning Work',
                'งานสวน': 'Gardening Work',
                'งานขับรถ': 'Driving Work',
                'งานโรงงาน': 'Factory Work',
                'งานเกษตร': 'Agriculture Work',
                'งานปศุสัตว์': 'Livestock Work',
                'งานประมง': 'Fishery Work',
                
                // หน้าที่ความรับผิดชอบ
                'รับผิดชอบ': 'Responsible for',
                'ดูแล': 'Take care of',
                'ควบคุม': 'Control',
                'จัดการ': 'Manage',
                'ติดตั้ง': 'Install',
                'ซ่อมแซม': 'Repair',
                'บำรุงรักษา': 'Maintain',
                'ทำความสะอาด': 'Clean',
                'ตรวจสอบ': 'Inspect',
                'วางแผน': 'Plan',
                'ประสานงาน': 'Coordinate',
                'อบรม': 'Train',
                'สอน': 'Teach',
                'ช่วยเหลือ': 'Assist',
                'สนับสนุน': 'Support',
                'แก้ไขปัญหา': 'Solve problems',
                'ปรับปรุง': 'Improve',
                'พัฒนา': 'Develop',
                
                // คำทั่วไป
                'ทีมงาน': 'Team',
                'โครงการ': 'Project',
                'ลูกค้า': 'Customer',
                'คุณภาพ': 'Quality',
                'ความปลอดภัย': 'Safety',
                'มาตรฐาน': 'Standard',
                'กำหนดเวลา': 'Deadline',
                'เป้าหมาย': 'Target',
                'ประสบการณ์': 'Experience',
                'ทักษะ': 'Skills',
                'ความสามารถ': 'Ability'
            };

            // แทนที่คำในพจนานุกรม
            for (const [thai, english] of Object.entries(jobDictionary)) {
                const regex = new RegExp(thai, 'gi');
                improvedText = improvedText.replace(regex, english);
            }

            // ปรับปรุงตามประเภทฟิลด์
            if (fieldType === 'modal_position') {
                // สำหรับตำแหน่ง - ทำให้เป็น Title Case
                improvedText = improvedText.replace(/\b\w+/g, word => 
                    word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
                );
            } else if (fieldType === 'modal_description') {
                // สำหรับรายละเอียดงาน - จัดรูปแบบให้เป็น bullet points
                const lines = improvedText.split('\n');
                improvedText = lines.map(line => {
                    line = line.trim();
                    if (line && !line.startsWith('-') && !line.startsWith('•')) {
                        return '- ' + line;
                    }
                    return line;
                }).join('\n');
            }

            return improvedText
                .replace(/\s+/g, ' ')
                .trim();
        }

        // ฟังก์ชัน fallback สำหรับแปลงาน
        async function fallbackTranslateJobText(thaiText, fieldType) {
            // ใช้พจนานุกรมพื้นฐานสำหรับกรณีฉุกเฉิน
            const basicJobDict = {
                'ช่างปูน': 'Plasterer',
                'ช่างไม้': 'Carpenter',
                'ช่างเหล็ก': 'Steel Worker',
                'ช่างเชื่อม': 'Welder',
                'คนงานทั่วไป': 'General Labor',
                'งานก่อสร้าง': 'Construction Work',
                'รับผิดชอบ': 'Responsible for'
            };

            let translatedText = thaiText;
            for (const [thai, english] of Object.entries(basicJobDict)) {
                const regex = new RegExp(thai, 'g');
                translatedText = translatedText.replace(regex, english);
            }
            
            return translatedText.trim();
        }

        // ฟังก์ชันแสดงข้อความสำเร็จสำหรับ Job Fields
        function showJobTranslationSuccess(field) {
            // แสดงข้อความสำเร็จชั่วคราว
            const existingMsg = field.parentNode.querySelector('.translation-success');
            if (existingMsg) {
                existingMsg.remove();
            }

            const successMsg = document.createElement('div');
            successMsg.className = 'alert alert-success alert-dismissible fade show mt-1 translation-success';
            successMsg.style.fontSize = '0.875rem';
            successMsg.innerHTML = `
                <i class="bi bi-check-circle-fill me-1"></i>
                แปลเสร็จแล้ว!
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            `;
            
            field.parentNode.appendChild(successMsg);
            
            // ซ่อนข้อความหลัง 3 วินาที
            setTimeout(() => {
                if (successMsg.parentNode) {
                    successMsg.parentNode.removeChild(successMsg);
                }
            }, 3000);
        }

        // ฟังก์ชันล้างข้อมูลที่อยู่
        function clearAddress() {
            if (confirm('ต้องการล้างข้อมูลที่อยู่หรือไม่?')) {
                document.getElementById('lead_address').value = '';
            }
        }

        // ฟังก์ชันแปลภาษาสำหรับฟิลด์ Additional Details
        async function translateAdditionalDetails() {
            const detailsField = document.getElementById('lead_additional_details');
            const detailsText = detailsField.value.trim();
            
            if (!detailsText) {
                alert('กรุณากรอกรายละเอียดก่อนแปลภาษา');
                detailsField.focus();
                return;
            }

            // แสดง loading
            document.getElementById('additionalDetailsLoader').style.display = 'block';
            
            try {
                // ตรวจสอบว่าเป็นภาษาไทยหรือไม่
                if (containsThai(detailsText)) {
                    // แปลจากไทยเป็นอังกฤษ พร้อมปรับปรุงสำหรับรายละเอียดเพิ่มเติม
                    const translatedText = await translateAdditionalDetailsText(detailsText, 'th', 'en');
                    detailsField.value = translatedText;
                    
                    // แสดงข้อความสำเร็จ
                    showAdditionalDetailsTranslationSuccess();
                } else {
                    // ถ้าเป็นอังกฤษอยู่แล้ว ลองแปลกลับเป็นไทย
                    if (confirm('ข้อความนี้ดูเหมือนเป็นภาษาอังกฤษ ต้องการแปลเป็นไทยหรือไม่?')) {
                        const translatedText = await translateAdditionalDetailsText(detailsText, 'en', 'th');
                        detailsField.value = translatedText;
                        showAdditionalDetailsTranslationSuccess();
                    }
                }
            } catch (error) {
                console.error('Additional Details Translation error:', error);
                alert('เกิดข้อผิดพลาดในการแปลภาษา: ' + (error.message || 'กรุณาลองใหม่อีกครั้ง'));
            } finally {
                // ซ่อน loading
                document.getElementById('additionalDetailsLoader').style.display = 'none';
            }
        }

        // ฟังก์ชันแปลข้อความรายละเอียดเพิ่มเติม
        async function translateAdditionalDetailsText(text, sourceLang, targetLang) {
            try {
                // ใช้ Google Translate API
                const translatedText = await translateWithGoogle(text, sourceLang, targetLang);
                
                // ปรับปรุงผลลัพธ์สำหรับรายละเอียดเพิ่มเติม
                if (targetLang === 'en') {
                    return improveAdditionalDetailsTranslation(translatedText);
                }
                
                return translatedText;
            } catch (error) {
                // ถ้า Google API ไม่ทำงาน ใช้ fallback dictionary
                if (sourceLang === 'th' && targetLang === 'en') {
                    return fallbackTranslateAdditionalDetails(text);
                }
                throw error;
            }
        }

        // ฟังก์ชันปรับปรุงการแปลรายละเอียดเพิ่มเติม
        function improveAdditionalDetailsTranslation(text) {
            // พจนานุกรมสำหรับรายละเอียดเพิ่มเติม
            const additionalDetailsDictionary = {
                // สุขภาพและการแพทย์
                'โรคประจำตัว': 'Chronic disease',
                'โรคหัวใจ': 'Heart disease',
                'โรคเบาหวาน': 'Diabetes',
                'โรคความดันสูง': 'Hypertension', 
                'โรคไต': 'Kidney disease',
                'โรคตับ': 'Liver disease',
                'โรคภูมิแพ้': 'Allergy',
                'แพ้ยา': 'Drug allergy',
                'แพ้อาหาร': 'Food allergy',
                'อาการแพ้': 'Allergic reaction',
                'การผ่าตัด': 'Surgery',
                'สุขภาพดี': 'Good health',
                'สุขภาพแข็งแรง': 'Healthy',
                'ไม่มีโรคประจำตัว': 'No chronic disease',
                
                // ทักษะและความสามารถ
                'ทักษะพิเศษ': 'Special skills',
                'ความสามารถพิเศษ': 'Special abilities',
                'ประสบการณ์พิเศษ': 'Special experience',
                'การฝึกอบรม': 'Training',
                'หลักสูตรการฝึกอบรม': 'Training course',
                'ใบประกาศนียบัตร': 'Certificate',
                'รางวัล': 'Award',
                'เกียรติยศ': 'Honor',
                'ความเชี่ยวชาญ': 'Expertise',
                'ผลงาน': 'Achievement',
                
                // ความสามารถทางกาย
                'แข็งแรง': 'Strong',
                'ทนทาน': 'Durable',
                'อดทน': 'Patient',
                'รับน้ำหนักได้': 'Can carry weight',
                'ทำงานหนักได้': 'Can do heavy work',
                'ยืนนานได้': 'Can stand for long time',
                'เดินไกลได้': 'Can walk long distance',
                
                // บุคลิกภาพ
                'มีความรับผิดชอบ': 'Responsible',
                'ตรงต่อเวลา': 'Punctual',
                'ซื่อสัตย์': 'Honest',
                'มีวินัย': 'Disciplined',
                'ทำงานเป็นทีม': 'Team player',
                'เรียนรู้เร็ว': 'Quick learner',
                'กระตือรือร้น': 'Enthusiastic',
                'มีความอดทน': 'Patient',
                
                // ประสบการณ์
                'เคยทำงาน': 'Have worked',
                'ไม่เคยทำงาน': 'Never worked',
                'มีประสบการณ์': 'Have experience',
                'ไม่มีประสบการณ์': 'No experience',
                'เพิ่งจบการศึกษา': 'Recently graduated',
                'พร้อมเรียนรู้': 'Ready to learn',
                
                // อื่นๆ
                'หมายเหตุ': 'Note',
                'ข้อมูลเพิ่มเติม': 'Additional information',
                'รายละเอียด': 'Details',
                'ที่สำคัญ': 'Important',
                'พิเศษ': 'Special',
                'เด่น': 'Outstanding'
            };

            let improvedText = text;

            // แทนที่คำในพจนานุกรม
            for (const [thai, english] of Object.entries(additionalDetailsDictionary)) {
                const regex = new RegExp(thai, 'gi');
                improvedText = improvedText.replace(regex, english);
            }

            // จัดรูปแบบให้เป็น bullet points ถ้ามีหลายบรรทัด
            const lines = improvedText.split('\n');
            if (lines.length > 1) {
                improvedText = lines.map(line => {
                    line = line.trim();
                    if (line && !line.startsWith('-') && !line.startsWith('•') && !line.startsWith('*')) {
                        return '- ' + line;
                    }
                    return line;
                }).join('\n');
            }

            return improvedText
                .replace(/\s+/g, ' ')
                .replace(/\n\s*\n/g, '\n')
                .trim();
        }

        // ฟังก์ชัน fallback สำหรับแปลรายละเอียดเพิ่มเติม
        async function fallbackTranslateAdditionalDetails(thaiText) {
            const basicDict = {
                'สุขภาพดี': 'Good health',
                'ทักษะพิเศษ': 'Special skills',
                'ประสบการณ์': 'Experience',
                'การฝึกอบรม': 'Training',
                'มีความรับผิดชอบ': 'Responsible'
            };

            let translatedText = thaiText;
            for (const [thai, english] of Object.entries(basicDict)) {
                const regex = new RegExp(thai, 'g');
                translatedText = translatedText.replace(regex, english);
            }
            
            return translatedText.trim();
        }

        // ฟังก์ชันแสดงข้อความสำเร็จสำหรับ Additional Details
        function showAdditionalDetailsTranslationSuccess() {
            const detailsField = document.getElementById('lead_additional_details');
            const existingMsg = detailsField.parentNode.querySelector('.translation-success');
            if (existingMsg) {
                existingMsg.remove();
            }

            const successMsg = document.createElement('div');
            successMsg.className = 'alert alert-success alert-dismissible fade show mt-1 translation-success';
            successMsg.innerHTML = `
                <i class="bi bi-check-circle-fill me-2"></i>
                แปลรายละเอียดเพิ่มเติมเสร็จแล้ว!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            detailsField.parentNode.appendChild(successMsg);
            
            // ซ่อนข้อความหลัง 3 วินาที
            setTimeout(() => {
                if (successMsg.parentNode) {
                    successMsg.parentNode.removeChild(successMsg);
                }
            }, 3000);
        }

        // Initialize tooltips และ car type functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Car type select change handler
            const carTypeSelect = document.getElementById('lead_car_type');
            if (carTypeSelect) {
                carTypeSelect.addEventListener('change', function() {
                    const selectedValue = this.value;
                    console.log('Selected car type:', selectedValue);
                    
                    // เพิ่ม visual feedback เมื่อเลือก
                    if (selectedValue && selectedValue !== 'None') {
                        this.classList.add('is-valid');
                        this.classList.remove('is-invalid');
                    } else {
                        this.classList.remove('is-valid', 'is-invalid');
                    }
                });
            }
        });

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
