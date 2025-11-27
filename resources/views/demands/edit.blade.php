@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">แก้ไข Demand #{{ $demand->dm_id }}</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('demands.update', $demand->dm_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6  mb-3">
                                <div class="form-group">
                                    <label for="dm_issue_date">ISSUE DATE (วันที่)  <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('dm_issue_date') is-invalid @enderror" 
                                           name="dm_issue_date" value="{{ old('dm_issue_date', $demand->dm_issue_date->format('Y-m-d')) }}" required>
                                    @error('dm_issue_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="dm_let_no">DEMAND LETTER NO. (เลขที่ใบแจ้งรายละเอียดงาน) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('dm_let_no') is-invalid @enderror" 
                                           name="dm_let_no" value="{{ old('dm_let_no', $demand->dm_let_no) }}" required>
                                    @error('dm_let_no')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="dm_com_name">COMPANY NAME (ชื่อบริษัท) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('dm_com_name') is-invalid @enderror" 
                                           name="dm_com_name" value="{{ old('dm_com_name', $demand->dm_com_name) }}" required>
                                    @error('dm_com_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6  mb-3">
                                <div class="form-group">
                                    <label for="dm_reg_no">REGISTRATION NO. (หมายเลขทะเบียน) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('dm_reg_no') is-invalid @enderror" 
                                           name="dm_reg_no" value="{{ old('dm_reg_no', $demand->dm_reg_no) }}" required>
                                    @error('dm_reg_no')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="dm_com_addr">COMPANY ADDRESS (ที่อยู่บริษัท) <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('dm_com_addr') is-invalid @enderror" 
                                              name="dm_com_addr" rows="3" required>{{ old('dm_com_addr', $demand->dm_com_addr) }}</textarea>
                                    @error('dm_com_addr')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="dm_indust_type">INDUSTRY TYPE (ประเภทอุตสาหกรรม) <span class="text-danger">*</span></label>
                                    <select class="form-control @error('dm_indust_type') is-invalid @enderror" 
                                            name="dm_indust_type" required>
                                        <option value="">เลือกประเภทอุตสาหกรรม</option>
                                        @foreach($industryTypes as $industryType)
                                            <option value="{{ $industryType->inducstry_type_id }}" 
                                                {{ old('dm_indust_type', $demand->dm_indust_type) == $industryType->inducstry_type_id ? 'selected' : '' }}>
                                                {{ $industryType->industry_type_name_th ?? $industryType->industry_type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dm_indust_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                             <!-- Additional Information -->
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label for="dm_bmi">BMI</label>
                                    <input type="text" class="form-control @error('dm_bmi') is-invalid @enderror" 
                                           name="dm_bmi" value="{{ old('dm_bmi', $demand->dm_bmi) }}">
                                    @error('dm_bmi')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label for="dm_time_work">INDUSTRY TYPE (ประเภทงาน) </label>
                                    <input type="text" class="form-control @error('dm_time_work') is-invalid @enderror" 
                                           name="dm_time_work" value="{{ old('dm_time_work', $demand->dm_time_work) }}">
                                    @error('dm_time_work')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label>EXPENSE AND BENEFIT (ค่าใช้จ่ายและสวัสดิการ)</label>
                                    <div class="form-check-list">
                                        @php
                                            $selectedExp = old('dm_exp', is_array($demand->dm_exp) ? $demand->dm_exp : ($demand->dm_exp ? [$demand->dm_exp] : []));
                                        @endphp
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="dm_exp[]" value="accomm" id="edit_accomm" {{ (is_array($selectedExp) && in_array('accomm', $selectedExp)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_accomm">
                                               พักอาศัย นายจ้างจะจัดหาที่พักให้โดยจะหักจากเงินเดือนตามที่กฎหมายกำหนด 
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="dm_exp[]" value="food" id="edit_food" {{ (is_array($selectedExp) && in_array('food', $selectedExp)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_food">
                                                Food provided by workers อาหารจัดเตรียมโดยคนงานเอง
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="dm_exp[]" value="med" id="edit_med" {{ (is_array($selectedExp) && in_array('med', $selectedExp)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_med">
                                               ประกันสุขภาพ ตามกฎหมายอิสราเอล นายจ้างจะจัดให้มีประกันสุขภาพสำหรับลูกจ้างตั้งแต่วันแรกที่เริ่ม ทำงาน โดยจะมีการหักค่าใช้จ่ายจากเงินเดือนของลูกจ้าง 
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="dm_exp[]" value="shuttle" id="edit_shuttle" {{ (is_array($selectedExp) && in_array('shuttle', $selectedExp)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_shuttle">
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
                                           name="location" id="location" value="{{ old('location', $demand->location) }}" 
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
                                           name="date" id="date" value="{{ old('date', $demand->date) }}" 
                                           placeholder="Date (วันที่)">
                                    @error('date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="dm_sa">เงินเดือน/สวัสดิการ</label>
                                    <textarea class="form-control @error('dm_sa') is-invalid @enderror" 
                                              name="dm_sa" rows="3">{{ old('dm_sa', $demand->dm_sa) }}</textarea>
                                    @error('dm_sa')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="dm_job">รายละเอียดงาน</label>
                                    <textarea class="form-control @error('dm_job') is-invalid @enderror" 
                                              name="dm_job" rows="3">{{ old('dm_job', $demand->dm_job) }}</textarea>
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
                                    @foreach($demand->positions as $index => $position)
                                    <div class="position-row mb-3 p-3 border rounded">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Position (ตำแหน่ง) <span class="text-danger">*</span></label>
                                                <select class="form-control" name="positions[{{ $index }}][position_id]" required>
                                                    <option value="">เลือกตำแหน่ง</option>
                                                    @foreach($positions as $pos)
                                                        <option value="{{ $pos->id }}" 
                                                            {{ $position->position_id == $pos->id ? 'selected' : '' }}>
                                                            {{ $pos->position_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Amount (จำนวน) <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="positions[{{ $index }}][amount]" 
                                                       min="1" step="0.01" value="{{ $position->position_dm_amount }}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Employment Period (สัญญาจ้าง) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="positions[{{ $index }}][period]" 
                                                       placeholder="เช่น 2 ปี" value="{{ $position->position_dm_period }}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Age (อายุ) Sex (เพศ) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="positions[{{ $index }}][age]" 
                                                       placeholder="เช่น 25-35 ปี" value="{{ $position->position_dm_age }}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-block remove-position" 
                                                    {{ $loop->first && $loop->count == 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash"></i> ลบ
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <button type="button" class="btn btn-success" id="add-position">
                                    <i class="fas fa-plus"></i> เพิ่มตำแหน่ง
                                </button>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> อัพเดท
                                </button>
                                <a href="{{ route('demands.show', $demand->dm_id) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i> ดูรายละเอียด
                                </a>
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
let positionIndex = {{ count($demand->positions) }};

// Store positions data for JavaScript use
const positionsData = {!! json_encode($positions->map(function($position) {
    return [
        'id' => $position->id,
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
                            <label>ตำแหน่ง <span class="text-danger">*</span></label>
                            <select class="form-control" name="positions[${positionIndex}][position_id]" required>
                                ${optionsHtml}
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>จำนวน <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="positions[${positionIndex}][amount]" 
                                   min="1" step="0.01" required>
                        </div>
                        <div class="col-md-2">
                            <label>ระยะเวลา <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="positions[${positionIndex}][period]" 
                                   placeholder="เช่น 2 ปี" required>
                        </div>
                        <div class="col-md-3">
                            <label>อายุ <span class="text-danger">*</span></label>
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