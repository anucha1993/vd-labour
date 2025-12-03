@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">เพิ่มงานใหม่</h4>
                    <a href="{{ route('jobs.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> กลับ
                    </a>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('jobs.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="job_name" class="form-label">ชื่องาน <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('job_name') is-invalid @enderror" 
                                           id="job_name" name="job_name" value="{{ old('job_name') }}" required>
                                    @error('job_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                             <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_id" class="form-label">บริษัทนายจ้าง <span class="text-danger">*</span></label>
                                    <select class="form-select @error('customer_id') is-invalid @enderror" 
                                            id="customer_id" name="customer_id" required>
                                        <option value="">-- เลือกบริษัทนายจ้าง --</option>
                                        @foreach($customer as $cust)
                                            <option value="{{ $cust->customer_id }}" 
                                                    {{ old('customer_id') == $cust->customer_id ? 'selected' : '' }}>
                                                {{ $cust->customer_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="country_id" class="form-label">ประเทศ <span class="text-danger">*</span></label>
                                    <select class="form-select @error('country_id') is-invalid @enderror" 
                                            id="country_id" name="country_id" required>
                                        <option value="">-- เลือกประเทศ --</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->country_id }}" 
                                                    {{ old('country_id') == $country->country_id ? 'selected' : '' }}>
                                                {{ $country->country_name_th }} ({{ $country->country_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="dm_id" class="form-label">Demand (หนังสือขอคนงาน) <span class="text-danger">*</span></label>
                                    <select class="form-select @error('dm_id') is-invalid @enderror" 
                                            id="dm_id" name="dm_id" required>
                                        <option value="">-- เลือก Demand --</option>
                                        @foreach($demands as $demand)
                                            <option value="{{ $demand->dm_id }}" 
                                                    {{ old('dm_id') == $demand->dm_id ? 'selected' : '' }}
                                                    data-country="{{ $demand->country_id }}">
                                                {{ $demand->dm_let_no }} - {{ $demand->dm_com_name }}
                                                @if($demand->country)
                                                    ({{ $demand->country->country_name_th }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dm_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">เลือกหนังสือขอคนงานที่ต้องการเปิดรับสมัคร</div>
                                </div>
                            </div>
                      
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="job_group_id" class="form-label">ประเภทงาน (Job Group) <span class="text-danger">*</span></label>
                                    <select class="form-select @error('job_group_id') is-invalid @enderror" id="job_group_id" name="job_group_id" required>
                                        <option value="">-- เลือกประเภทงาน --</option>
                                        @foreach($jobGroups as $jg)
                                            <option value="{{ $jg->job_group_id }}" {{ old('job_group_id') == $jg->job_group_id ? 'selected' : '' }}>
                                                {{ $jg->job_group_name }} ({{ $jg->job_group_name_th }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('job_group_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="position_id" class="form-label">ตำแหน่ง (Position) <span class="text-danger">*</span></label>
                                    <select class="form-select @error('position_id') is-invalid @enderror" id="position_id" name="position_id" required> 
                                        <option value="">-- เลือกตำแหน่ง --</option>
                                        @foreach($positions as $pos)
                                            <option value="{{ $pos->position_id }}" data-jobgroup="{{ $pos->job_group_id }}" {{ old('position_id') == $pos->position_id ? 'selected' : '' }}>
                                                {{ $pos->position_name }} ({{ $pos->position_name_th }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('position_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                           
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="job_start_date" class="form-label">วันเริ่มรับสมัคร <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('job_start_date') is-invalid @enderror" 
                                           id="job_start_date" name="job_start_date" 
                                           value="{{ old('job_start_date', date('Y-m-d')) }}" required>
                                    @error('job_start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="job_end_date" class="form-label">วันปิดรับสมัคร</label>
                                    <input type="date" class="form-control @error('job_end_date') is-invalid @enderror" 
                                           id="job_end_date" name="job_end_date" 
                                           value="{{ old('job_end_date') }}">
                                    @error('job_end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">หากไม่ระบุ = รับสมัครต่อเนื่อง</div>
                                </div>
                            </div>

                             <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="job_total" class="form-label">จำนวนเปิดรับ <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('job_total') is-invalid @enderror" 
                                           id="job_total" name="job_total" value="{{ old('job_total') }}" 
                                           min="1" required>
                                    @error('job_total')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="job_status" class="form-label">สถานะงาน <span class="text-danger">*</span></label>
                                    <select class="form-select @error('job_status') is-invalid @enderror" 
                                            id="job_status" name="job_status" required>
                                        <option value="เปิดรับสมัคร" {{ old('job_status') == 'เปิดรับสมัคร' ? 'selected' : '' }}>เปิดรับสมัคร</option>
                                        <option value="ปิดรับสมัคร" {{ old('job_status') == 'ปิดรับสมัคร' ? 'selected' : '' }}>ปิดรับสมัคร</option>
                                    </select>
                                    @error('job_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('jobs.index') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-x-circle"></i> ยกเลิก
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> บันทึก
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const countrySelect = document.getElementById('country_id');
    const demandSelect = document.getElementById('dm_id');
    const startDateInput = document.getElementById('job_start_date');
    const endDateInput = document.getElementById('job_end_date');
    
    // Filter demands based on selected country
    countrySelect.addEventListener('change', function() {
        const selectedCountry = this.value;
        const demandOptions = demandSelect.querySelectorAll('option');
        
        demandOptions.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }
            
            const optionCountry = option.dataset.country;
            if (!selectedCountry || optionCountry === selectedCountry) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
                if (option.selected) {
                    option.selected = false;
                }
            }
        });
        
        // Reset demand selection if hidden
        if (demandSelect.value && demandSelect.querySelector(`option[value="${demandSelect.value}"]`).style.display === 'none') {
            demandSelect.value = '';
        }
    });
    
    // Validate end date is after start date
    function validateDates() {
        if (startDateInput.value && endDateInput.value) {
            if (new Date(endDateInput.value) <= new Date(startDateInput.value)) {
                endDateInput.setCustomValidity('วันปิดรับสมัครต้องหลังวันเริ่มรับสมัคร');
            } else {
                endDateInput.setCustomValidity('');
            }
        } else {
            endDateInput.setCustomValidity('');
        }
    }
    
    startDateInput.addEventListener('change', validateDates);
    endDateInput.addEventListener('change', validateDates);
    
    // Generate job number preview
    const jobNameInput = document.getElementById('job_name');
    
    function generateJobNumberPreview() {
        if (jobNameInput.value) {
            const year = new Date().getFullYear();
            // This is just a preview, actual number will be generated server-side
            console.log(`Preview: JOB${year}-XXXXX`);
        }
    }
    
    jobNameInput.addEventListener('blur', generateJobNumberPreview);

    // Job Group -> Positions dynamic loading
    const jobGroupSelect = document.getElementById('job_group_id');
    const positionSelect = document.getElementById('position_id');

    if (jobGroupSelect) {
        jobGroupSelect.addEventListener('change', function() {
            const jobGroupId = this.value;
            // Clear current positions
            positionSelect.innerHTML = '<option value="">-- เลือกตำแหน่ง --</option>';

            if (!jobGroupId) return;

            const url = '{{ route("jobgroup.ajaxSelectPosition") }}?jobgroup=' + encodeURIComponent(jobGroupId);
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.json())
                .then(data => {
                    data.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.position_id;
                        opt.textContent = p.position_name + ' (' + p.position_name_th + ')';
                        positionSelect.appendChild(opt);
                    });
                })
                .catch(err => {
                    console.error('Could not load positions:', err);
                });
        });
    }
});
</script>
@endsection