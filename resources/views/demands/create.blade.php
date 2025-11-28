@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">เพิ่ม Demand ใหม่</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('demands.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="dm_issue_date">ISSUE DATE (วันที่) <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('dm_issue_date') is-invalid @enderror" 
                                           name="dm_issue_date" value="{{ old('dm_issue_date') }}" required>
                                    @error('dm_issue_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="dm_let_no">DEMAND LETTER NO. (เลขที่ใบแจ้งรายละเอียดงาน) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('dm_let_no') is-invalid @enderror" placeholder="Enter demand letter number"
                                           name="dm_let_no" value="{{ old('dm_let_no') }}" required>
                                    @error('dm_let_no')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="dm_com_name">COMPANY NAME (ชื่อบริษัท)  <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('dm_com_name') is-invalid @enderror"  placeholder="Enter company name"
                                           name="dm_com_name" value="{{ old('dm_com_name') }}" required>
                                    @error('dm_com_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                             <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="dm_reg_no">REGISTRATION NO. (เลขทะเบียนบริษัท) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('dm_reg_no') is-invalid @enderror" placeholder="Enter registration number"
                                           name="dm_reg_no" value="{{ old('dm_reg_no') }}" required>
                                    @error('dm_reg_no')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                              <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="dm_com_addr">COMPANY ADDRESS (ที่อยู่บริษัท)  <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('dm_com_addr') is-invalid @enderror"  placeholder="Enter company address"
                                              name="dm_com_addr" rows="3" required>{{ old('dm_com_addr') }}</textarea>
                                    @error('dm_com_addr')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                           
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="dm_indust_type">INDUSTRY TYPE (ประเภทงาน) <span class="text-danger">*</span></label>
                                    <select class="form-control @error('dm_indust_type') is-invalid @enderror" 
                                            name="dm_indust_type" required>
                                        <option value="">เลือกประเภทอุตสาหกรรม</option>
                                        @foreach($industryTypes as $industryType)
                                            <option value="{{ $industryType->inducstry_type_id }}" 
                                                {{ old('dm_indust_type') == $industryType->inducstry_type_id ? 'selected' : '' }}>
                                                {{ $industryType->inducstry_type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dm_indust_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Country -->
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="country_id">ประเทศ (Country) <span class="text-danger">*</span></label>
                                    <select class="form-control @error('country_id') is-invalid @enderror" 
                                            name="country_id" required>
                                        <option value="">เลือกประเทศ</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->country_id }}" 
                                                {{ old('country_id') == $country->country_id ? 'selected' : '' }}>
                                                {{ $country->country_name_th }} ({{ $country->country_name_en }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                              <!-- Additional Information -->
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="dm_bmi">BMI</label>
                                    <input type="text" class="form-control @error('dm_bmi') is-invalid @enderror" value="**ค่า BMI ต้องไม่ต่ำกว่า 18 และไม่เกิน 30**"
                                           name="dm_bmi" value="{{ old('dm_bmi') }}"> 
                                    @error('dm_bmi')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="dm_time_work">TIME TO WORK (เวลาทำงาน) </label>
                                    <input type="text" class="form-control @error('dm_time_work') is-invalid @enderror" value="ทำงาน 6 วัน/สัปดาห์ 10 ชั่วโมง/วัน"
                                           name="dm_time_work" value="{{ old('dm_time_work') }}"> 
                                    @error('dm_time_work')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label>EXPENSE AND BENEFIT (ค่าใช้จ่ายและสวัสดิการ)</label>
                                    <div class="form-check-list">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="dm_exp[]" value="accomm" id="accomm" {{ (is_array(old('dm_exp')) && in_array('accomm', old('dm_exp'))) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="accomm">
                                               พักอาศัย นายจ้างจะจัดหาที่พักให้โดยจะหักจากเงินเดือนตามที่กฎหมายกำหนด 
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="dm_exp[]" value="food" id="food" {{ (is_array(old('dm_exp')) && in_array('food', old('dm_exp'))) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="food">
                                                Food provided by workers อาหารจัดเตรียมโดยคนงานเอง
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="dm_exp[]" value="med" id="med" {{ (is_array(old('dm_exp')) && in_array('med', old('dm_exp'))) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="med">
                                               ประกันสุขภาพ ตามกฎหมายอิสราเอล นายจ้างจะจัดให้มีประกันสุขภาพสำหรับลูกจ้างตั้งแต่วันแรกที่เริ่ม ทำงาน โดยจะมีการหักค่าใช้จ่ายจากเงินเดือนของลูกจ้าง 

                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="dm_exp[]" value="shuttle" id="shuttle" {{ (is_array(old('dm_exp')) && in_array('shuttle', old('dm_exp'))) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="shuttle">
                                               รถรับส่งฟรี
                                            </label>
                                        </div>
                                    </div>
                                    @error('dm_exp')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="location">LOCATION (สถานที่)</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           name="location" id="location" value="{{ old('location') }}" 
                                           placeholder="Location (สถานที่)">
                                    @error('location')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="date">DATE (วันที่)</label>
                                    <input type="text" class="form-control @error('date') is-invalid @enderror" 
                                           name="date" id="date" value="{{ old('date') }}" 
                                           placeholder="Date (วันที่)">
                                    @error('date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="dm_sa">SALARY (เงินเดือน)</label>
                                    <textarea class="form-control @error('dm_sa') is-invalid @enderror"  placeholder="SALARY (เงินเดือน)"
                                              name="dm_sa" rows="3">{{ old('dm_sa') }}</textarea>
                                    @error('dm_sa')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="dm_job">JOB DESCRIPTION (รายละเอียดงาน)</label>
                                    <textarea class="form-control @error('dm_job') is-invalid @enderror"  placeholder="JOB DESCRIPTION (รายละเอียดงาน)"
                                              name="dm_job" rows="3">{{ old('dm_job') }}</textarea>
                                    @error('dm_job')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Positions Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">QUALIFICATION (คุณสมบัติของผู้สมัครและรายละเอียดในการจ้างงาน) <span class="text-danger">*</span></h5>
                            </div>
                            <div class="card-body">
                                <div id="positions-container">
                                    <div class="position-row mb-3 p-3 border rounded">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Position (ตำแหน่ง) <span class="text-danger">*</span></label>
                                                <select class="form-control" name="positions[0][position_id]" required>
                                                    <option value="">เลือกตำแหน่ง</option>
                                                    @foreach($positions as $position)
                                                        <option value="{{ $position->position_id }}">{{ $position->position_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Amount (จำนวน) <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="positions[0][amount]" value="5"
                                                       min="1" step="0.01" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Period (ระยะเวลา) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="positions[0][period]"  value="5 ปี 3 เดือน"
                                                       placeholder="เช่น 2 ปี" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Age (อายุ) Sex (เพศ) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="positions[0][age]" 
                                                       placeholder="เช่น 25-35 ปี" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-block remove-position" disabled>
                                                    <i class="fas fa-trash"></i> ลบ
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="button" class="btn btn-success" id="add-position">
                                    <i class="fas fa-plus"></i> เพิ่มตำแหน่ง
                                </button>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> บันทึก
                                </button>
                                <a href="{{ route('demands.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> ย้อนกลับ
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
let positionIndex = 1;

// Store positions data for JavaScript use
const positionsData = {!! json_encode($positions->map(function($position) {
    return [
        'id' => $position->position_id,
        'name' => $position->position_name
    ];
})) !!};

console.log('Positions data loaded:', positionsData.length, 'positions');

// Wait for DOM to be fully loaded
$(document).ready(function() {
    console.log('Document ready');
    
    // Check if elements exist
    const addButton = $('#add-position');
    const container = $('#positions-container');
    
    console.log('Add button found:', addButton.length > 0);
    console.log('Container found:', container.length > 0);
    
    if (addButton.length > 0) {
        addButton.on('click', function(e) {
            e.preventDefault();
            console.log('Add button clicked');
            
            if (container.length === 0) {
                console.error('Container not found');
                return;
            }
            
            // Create select options
            let optionsHtml = '<option value="">เลือกตำแหน่ง</option>';
            positionsData.forEach(position => {
                optionsHtml += `<option value="${position.id}">${position.name}</option>`;
            });
            
            const newPositionHtml = `
                <div class="position-row mb-3 p-3 border rounded">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Position (ตำแหน่ง) <span class="text-danger">*</span></label>
                            <select class="form-control" name="positions[${positionIndex}][position_id]" required>
                                ${optionsHtml}
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Amount (จำนวน) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="positions[${positionIndex}][amount]" value="5"
                                   min="1" step="0.01" required>
                        </div>
                        <div class="col-md-2">
                            <label>Period (ระยะเวลา) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="positions[${positionIndex}][period]"  value="5 ปี 3 เดือน"
                                   placeholder="เช่น 2 ปี" required>
                        </div>
                        <div class="col-md-3">
                            <label>Age (อายุ) Sex (เพศ) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="positions[${positionIndex}][age]" 
                                   placeholder="เช่น 25-35 ปี" required>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-block remove-position">
                                <i class="fas fa-trash"></i> ลบ
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            container.append(newPositionHtml);
            positionIndex++;
            updateRemoveButtons();
            
            console.log('New position added, index:', positionIndex);
            console.log('Total positions now:', $('.position-row').length);
        });
    } else {
        console.error('Add button not found');
    }
    
    // Handle remove button clicks
    $(document).on('click', '.remove-position', function(e) {
        e.preventDefault();
        $(this).closest('.position-row').remove();
        updateRemoveButtons();
        console.log('Position removed');
    });
    
    // Initial setup
    updateRemoveButtons();
});

function updateRemoveButtons() {
    const positions = $('.position-row');
    const removeButtons = $('.remove-position');
    
    removeButtons.each(function() {
        $(this).prop('disabled', positions.length === 1);
    });
}
</script>
@endsection