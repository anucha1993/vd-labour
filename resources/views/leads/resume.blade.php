<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติคนงาน</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Sarabun', 'THSarabunNew', sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            background-color: #fff;
        }
        
        .cv-container {
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            display: flex;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* Left Column */
        .cv-left-column {
            width: 30%;
            background-color: #2a3b4c;
            color: #fff;
            padding: 40px 20px;
        }
        
        .cv-photo-container {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .cv-photo {
            width: 150px;
            height: 180px;
            border-radius: 8px;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        
        .cv-section-left {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        
        .cv-section-left:last-child {
            border-bottom: none;
        }
        
        .cv-section-left h2 {
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            border-bottom: 2px solid #4a90e2;
            padding-bottom: 8px;
        }
        
        .cv-contact-item {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }
        
        .cv-contact-icon {
            width: 30px;
            text-align: center;
            margin-right: 10px;
        }
        
        .cv-skills-list {
            list-style-type: none;
        }
        
        .cv-skills-list li {
            margin-bottom: 8px;
            position: relative;
            padding-left: 20px;
        }
        
        .cv-skills-list li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: #4a90e2;
            font-weight: bold;
        }
        
        /* Right Column */
        .cv-right-column {
            width: 70%;
            padding: 40px;
            display: flex;
            flex-direction: column;
        }
        
        .cv-content {
            flex: 1;
        }
        
        .cv-header {
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 20px;
        }
        
        .cv-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #2a3b4c;
            margin-bottom: 5px;
        }
        
        .cv-role {
            font-size: 18px;
            color: #4a90e2;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .cv-section-right {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .cv-section-right:last-of-type {
            border-bottom: none;
        }
        
        .cv-section-right h2 {
            color: #2a3b4c;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            position: relative;
        }
        
        .cv-section-right h2::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 50px;
            height: 3px;
            background-color: #4a90e2;
        }
        
        .cv-work-item {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .cv-work-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .cv-work-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .cv-company-name {
            font-weight: 700;
            font-size: 14px;
            color: #2a3b4c;
        }
        
        .cv-work-date {
            color: #4a90e2;
            font-weight: 600;
            font-size: 14px;
        }
        
        .cv-job-title {
            font-style: italic;
            color: #555;
            margin-bottom: 8px;
        }
        
        .cv-work-description {
            margin-top: 10px;
        }
        
        .cv-work-description ul {
            padding-left: 20px;
        }
        
        .cv-work-description li {
            margin-bottom: 5px;
        }
        
        .cv-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .cv-grid-item {
            margin-bottom: 5px;
        }
        
        .cv-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 3px;
        }
        
        .cv-value {
            font-weight: 400;
        }
        
        .cv-note {
            background-color: #f9f9f9;
            border-left: 3px solid #4a90e2;
            padding: 10px 15px;
            margin-top: 10px;
        }
        
        .cv-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .cv-table th {
            background-color: #f0f0f0;
            text-align: left;
            padding: 10px;
            font-weight: 600;
            border-bottom: 2px solid #ddd;
        }
        
        .cv-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .cv-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .cv-signature-section {
            margin-top: 30px;
            border-top: 1px solid #e0e0e0;
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        
        .cv-signature {
            width: 200px;
        }
        
        .cv-signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
        }
        
        .cv-signature-name {
            text-align: center;
            margin-top: 10px;
        }
        
        .cv-date {
            align-self: flex-end;
            color: #777;
        }
        
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.3);
            transition: all 0.3s ease;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(74, 144, 226, 0.4);
        }
        
        .print-btn:active {
            transform: translateY(0);
        }
        
        /* Print Specific Styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .cv-container {
                box-shadow: none;
                margin: 0;
            }
            
            .print-btn {
                display: none !important;
            }
        }
    </style>
</head>
<body>

@php
    use Carbon\Carbon;
    $thai = fn($d)=> $d ? Carbon::parse($d)->format('d/m/Y') : '–';
    $left = fn($d)=> $d ? now()->diffInDays(Carbon::parse($d),false).' days' : '–';
@endphp

<div class="cv-container">
    <!-- Left Column -->
    <div class="cv-left-column">
        <div class="cv-photo-container">
            <img src="{{ $lead->lead_photo
                        ? asset('storage/'.$lead->lead_photo)
                        : asset('/template/dist/assets/images/user/avatar-1.jpg') }}"
                class="cv-photo">
        </div>
        
        <div class="cv-section-left">
            <h2>Contact</h2>
            
            @if($lead->lead_phone)
            <div class="cv-contact-item">
                <div class="cv-contact-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div>{{ $lead->lead_phone }}</div>
            </div>
            @endif
            
            @if($lead->lead_email)
            <div class="cv-contact-item">
                <div class="cv-contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div>{{ $lead->lead_email }}</div>
            </div>
            @endif
            
            @if($lead->lead_address)
            <div class="cv-contact-item">
                <div class="cv-contact-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div>{{ $lead->lead_address }}</div>
            </div>
            @endif
        </div>
         <div class="cv-section-left">
            <h2>Education</h2>
            
            @if($lead->lead_education)
            <div class="cv-contact-item">
                <div class="cv-contact-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    @php
                        $educationLabels = [
                            'elementary' => 'ELEMENTARY SCHOOL',
                            'junior_high' => 'JUNIOR HIGH SCHOOL',
                            'high_school' => 'HIGH SCHOOL',
                            'voc_cert' => 'VOC. CERT',
                            'high_voc_cert' => 'HIGH VOC. CERT',
                            'bachelor' => 'BACHELOR DEGREES'
                        ];
                    @endphp
                    {{ $educationLabels[$lead->lead_education] ?? $lead->lead_education }}
                </div>
            </div>
            @endif
            
           
        </div>
        
        <div class="cv-section-left">
            <h2>PERSONAL INFORMATION</h2>
            
            @if($lead->lead_birthday)
            <div class="cv-contact-item">
                <div class="cv-contact-icon">
                    <i class="fas fa-birthday-cake"></i>
                </div>
                <div>Age {{ Carbon::parse($lead->lead_birthday)->age }} years</div>
            </div>
            @endif
            
            @if($lead->lead_height)
            <div class="cv-contact-item">
                <div class="cv-contact-icon">
                    <i class="fas fa-ruler-vertical"></i>
                </div>
                <div>{{ $lead->lead_height }} cm</div>
            </div>
            @endif
            
            @if($lead->lead_weight)
            <div class="cv-contact-item">
                <div class="cv-contact-icon">
                    <i class="fas fa-weight"></i>
                </div>
                <div>{{ $lead->lead_weight }} kg</div>
            </div>
            @endif
        </div>
        
       
        
        @if($lead->lead_bmi)
        <div class="cv-section-left">
            <h2>Health Metrics</h2>
            <div class="cv-contact-item">
                <div class="cv-contact-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div>BMI: {{ number_format($lead->lead_bmi, 2) }}</div>
            </div>
            @if($lead->lead_shirt_size)
            <div class="cv-contact-item">
                <div class="cv-contact-icon">
                    <i class="fas fa-tshirt"></i>
                </div>
                <div>Shirt: {{ $lead->lead_shirt_size }}</div>
            </div>
            @endif
            @if($lead->lead_pant_size)
            <div class="cv-contact-item">
                <div class="cv-contact-icon">
                    <i class="fas fa-vest"></i>
                </div>
                <div>Pant: {{ $lead->lead_pant_size }}</div>
            </div>
            @endif
        </div>
        @endif
    </div>
    
    <!-- Right Column -->
    <div class="cv-right-column">
        <div class="cv-content">
        <div class="cv-header">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px;">
                <div>
                    <h1>{{ strtoupper($lead->getFullNameAttribute()) }}</h1>
                    <div class="cv-role">{{ $lead->position->position_name_en ?? 'Professional' }}</div>
                </div>
                <div style="flex-shrink: 0; text-align: center;">
                    <img src="{{ asset('logo/V dragon-02.png') }}" alt="VD Logo" style="width: 150px; height: 150px; object-fit: contain;">
                </div>
            </div>
        </div>
        
       
    
        @if($lead->jobHistory && $lead->jobHistory->count() > 0)
        <div class="cv-section-right">
            <h2>Work Experience</h2>
            @foreach($lead->jobHistory as $index => $job)
            <div class="cv-work-item">
                <div class="cv-work-header">
                    <span class="cv-company-name">{{ $job->company_name ?? 'Company' }}</span>
                    @if($job->start_date || $job->end_date)
                    <span class="cv-work-date">
                        {{ $job->start_date ? Carbon::parse($job->start_date)->format('Y') : 'N/A' }} 
                        - 
                        {{ $job->end_date ? Carbon::parse($job->end_date)->format('Y') : 'Present' }}
                    </span>
                    @endif
                </div>
                
                @if($job->position)
                <div class="cv-job-title">Position :{{ $job->position }}</div>
                @endif
                
                {{-- <div class="cv-grid" style="grid-template-columns: repeat(3, 1fr); font-size: 12px; margin: 10px 0;">
                    @if($job->company_type)
                    <div class="cv-grid-item">
                        <div class="cv-label">Company Type</div>
                        <div class="cv-value">{{ $job->company_type }}</div>
                    </div>
                    @endif
                    @if($job->country)
                    <div class="cv-grid-item">
                        <div class="cv-label">Country</div>
                        <div class="cv-value">{{ $job->country }}</div>
                    </div>
                    @endif
                    @if($job->experience_years)
                    <div class="cv-grid-item">
                        <div class="cv-label">Experience</div>
                        <div class="cv-value">{{ $job->experience_years }} years</div>
                    </div>
                    @endif
                </div> --}}
                
                @if($job->description)
                <div class="cv-work-description">
                    <strong>Responsibilities:</strong>
                    <ul>
                        @foreach(explode("\n", $job->description) as $desc)
                            @if(trim($desc))
                            <li>{!! nl2br(e(trim($desc))) !!}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

         @php
            // Get latest job history by start_date for company_about
            $latestJob = $lead->jobHistory->sortByDesc('start_date')->first();
        @endphp
        
        @if($latestJob && $latestJob->company_about)
        <div class="cv-section-right">
            <h2>About The Company</h2>
            <div style="text-align: justify; line-height: 1.6;">
                {!! nl2br(e($latestJob->company_about)) !!}
            </div>
        </div>
        @endif
        </div>
        
        <div class="cv-signature-section">
            <div class="cv-signature">
                <div class="cv-signature-line"></div>
                <div class="cv-signature-name">Applicant's Signature</div>
            </div>
            <div class="cv-date">
                Print Date: {{ $thai(now()) }}
            </div>
        </div>
    </div>
</div>

<button class="print-btn" onclick="handlePrint()">
    <i class="fas fa-print"></i> Print Resume
</button>

<script>
    function handlePrint() {
        window.print();
    }
    
    window.addEventListener('beforeprint', () => {
        const btn = document.querySelector('.print-btn');
        if (btn) btn.style.display = 'none';
    });
    
    window.addEventListener('afterprint', () => {
        const btn = document.querySelector('.print-btn');
        if (btn) btn.style.display = 'flex';
    });
</script>
</body>
</html>
