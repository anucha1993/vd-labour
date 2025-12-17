<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume - {{ $lead->getFullNameAttribute() }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* ตั้งค่าพื้นฐาน */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f0f0f0;
            font-family: 'Roboto', Arial, sans-serif;
            color: #444;
            line-height: 1.6;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        /* จำลองขนาดกระดาษ A4 */
        .resume-container {
            background-color: white;
            width: 210mm;
            min-height: 297mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 40px 30px;
            display: flex;
            flex-wrap: wrap;
            position: relative;
        }

        /* เส้นขอบบนและล่าง */
        .resume-container::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 30px;
            right: 30px;
            height: 2px;
            background-color: #888;
        }
        
        .resume-container::after {
            content: '';
            position: absolute;
            bottom: 20px;
            left: 30px;
            right: 30px;
            height: 2px;
            background-color: #888;
        }

        /* คอลัมน์ซ้าย */
        .left-col {
            width: 35%;
            padding-right: 20px;
        }

        /* คอลัมน์ขวา */
        .right-col {
            width: 65%;
            padding-left: 10px;
        }

        /* รูปโปรไฟล์ */
        .profile-img-container {
            margin-bottom: 30px;
            margin-top: 40px; /* เว้นที่ให้เส้นบน */
        }
        
        .profile-img {
            width: 100%;
            height: auto;
            border-radius: 4px; /* มุมมนเล็กน้อยตามภาพ */
            object-fit: cover;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* สไตล์หัวข้อ Section (แถบสีน้ำเงิน) */
        .section-header {
            background-color: #4e607b;
            color: white;
            padding: 8px 15px;
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 15px;
            margin-top: 20px;
        }

        /* รายละเอียดใน Profile */
        .profile-list {
            list-style: none;
            font-size: 14px;
        }

        .profile-list li {
            margin-bottom: 10px;
            display: flex;
        }

        .profile-list strong {
            margin-right: 5px;
            min-width: 100px; /* จัดให้ Colon ตรงกัน */
            color: #333;
        }

        /* ส่วน Contact */
        .contact-list {
            list-style: none;
            font-size: 14px;
        }

        .contact-list li {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }

        .contact-list i {
            color: #4e607b;
            margin-right: 15px;
            font-size: 18px;
            margin-top: 3px;
            width: 20px;
            text-align: center;
        }

        /* ส่วนหัวชื่อ (ขวา) */
        .main-header {
            margin-top: 40px;
            margin-bottom: 40px;
        }

        h1 {
            font-size: 42px;
            color: #333;
            text-transform: uppercase;
            line-height: 1.1;
            font-weight: 700;
        }

        /* กล่องสีเทาจางๆ ใต้ชื่อ (ในภาพมีแถบว่างๆ) */
        .grey-bar {
            background-color: #f2f2f2;
            height: 30px;
            width: 60%;
            margin-top: 20px;
            margin-bottom: 40px;
        }

        /* เนื้อหา Summary */
        .summary-text {
            font-size: 14px;
            text-align: justify;
            margin-bottom: 30px;
            color: #555;
            padding: 0 5px;
        }

        /* ส่วน Work Experience */
        .job-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 5px;
        }

        .job-title {
            font-weight: 700;
            color: #333;
            font-size: 16px;
        }

        .job-date {
            color: #6a9bd6; /* สีฟ้าอ่อนตามภาพ */
            font-size: 14px;
            font-weight: 500;
        }

        .company-name {
            font-style: italic;
            color: #6a9bd6;
            margin-bottom: 10px;
            display: block;
            font-size: 14px;
        }

        .job-tasks {
            list-style-type: disc;
            padding-left: 20px;
            font-size: 14px;
            color: #555;
        }

        .job-tasks li {
            margin-bottom: 8px;
        }

        /* ลายเซ็น/Footer มุมขวาล่าง */
        .footer-sign {
            position: absolute;
            bottom: 30px;
            right: 30px;
            font-size: 12px;
            color: #333;
        }

        /* Media Query สำหรับหน้าจอมือถือ */
        @media (max-width: 768px) {
            .resume-container {
                width: 100%;
                flex-direction: column;
                height: auto;
                padding: 20px;
            }
            .left-col, .right-col {
                width: 100%;
                padding: 0;
            }
            .resume-container::before, 
            .resume-container::after {
                display: none;
            }
        }

        /* Template Selector */
        .template-selector {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        /* Company Logo */
        .company-logo {
            position: absolute;
            top: 30px;
            right: 30px;
            width: 80px;
            height: auto;
            z-index: 10;
        }
        
        @media print {
            .template-selector, .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Template Selector -->
    <div class="template-selector no-print">
        <label class="form-label"><strong>เลือกฟอร์ม Resume:</strong></label>
        <select class="form-select" id="templateSelector" onchange="changeTemplate(this.value)">
            @for($i = 1; $i <= 14; $i++)
                <option value="{{ $i }}" {{ $template == $i ? 'selected' : '' }}>
                    ฟอร์มที่ {{ $i }}
                </option>
            @endfor
        </select>
        <button onclick="window.print()" class="btn btn-primary btn-sm mt-2 w-100">
            <i class="fas fa-print"></i> พิมพ์
        </button>
    </div>

    <div class="resume-container">
        <!-- Company Logo -->
        <img src="{{ asset('logo/V dragon-02.png') }}" alt="Company Logo" class="company-logo">
        <div class="left-col">
            <div class="profile-img-container">
                @if($lead->lead_photo)
                    <img src="{{ asset('storage/' . $lead->lead_photo) }}" alt="Profile Photo" class="profile-img">
                @else
                    <img src="https://via.placeholder.com/300x350/cccccc/ffffff?text=PHOTO" alt="Profile Photo" class="profile-img">
                @endif
            </div>

            <div class="section-header">Profile</div>
            <ul class="profile-list">
                <li><strong>Date of Birth :</strong> {{ $lead->lead_birthday ? $lead->lead_birthday->format('d M Y') : '-' }}</li>
                <li><strong>AGE :</strong> {{ $lead->lead_age ?? '-' }} Years</li>
                <li><strong>STATUS :</strong> {{ ucfirst(str_replace(['single', 'married', 'divorced'], ['Single', 'Married', 'Divorced'], $lead->lead_marital_status ?? '-')) }}</li>
                <li><strong>Height :</strong> {{ $lead->lead_height ?? '-' }} cm</li>
                <li><strong>Weight :</strong> {{ $lead->lead_weight ?? '-' }} kg</li>
                <li><strong>BMI :</strong> {{ $lead->lead_bmi ?? '-' }}</li>
                <li><strong>Nationality :</strong> {{ $lead->country->country_name_en ?? 'Thai' }}</li>
                <li><strong>Religion :</strong> Buddhism</li>
                @if($lead->lead_emergency_name)
                <li style="display:block; margin-top:10px;">
                    <strong>EMERGENCY CONTACT :</strong><br>
                    <span style="display:block; margin-top:5px;">{{ $lead->lead_emergency_name }}</span>
                </li>
                @endif
                @if($lead->lead_emergency_phone)
                <li><strong>EMERGENCY TEL :</strong> {{ $lead->lead_emergency_phone }}</li>
                @endif
            </ul>

            <div class="section-header">Contact</div>
            <ul class="contact-list">
                @if($lead->lead_phone)
                <li>
                    <i class="fas fa-phone-alt"></i>
                    <span>{{ $lead->lead_phone }}</span>
                </li>
                @endif
                @if($lead->lead_email)
                <li>
                    <i class="fas fa-envelope"></i>
                    <span>{{ $lead->lead_email }}</span>
                </li>
                @endif
                @if($lead->lead_address)
                <li>
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $lead->lead_address }}</span>
                </li>
                @endif
            </ul>
        </div>

        <div class="right-col">
            <header class="main-header">
                <h1>{{ strtoupper($lead->lead_prefix ?? '') }} {{ strtoupper($lead->lead_firstname) }}<br>{{ strtoupper($lead->lead_lastname) }}</h1>
                <div class="grey-bar"></div>
            </header>

            @if($lead->lead_summary)
            <div class="section-header">Summary</div>
            <p class="summary-text">
                {{ $lead->lead_summary }}
            </p>
            @endif

            @if($lead->jobHistory && $lead->jobHistory->count() > 0)
            <div class="section-header">Work Experience</div>
            
            @foreach($lead->jobHistory->sortByDesc('start_date') as $job)
            <div class="job-item">
                <div class="job-header">
                    <span class="job-title">{{ $job->position ?? 'Position' }}</span>
                    <span class="job-date">
                        @if($job->start_date)
                            {{ \Carbon\Carbon::parse($job->start_date)->format('Y') }} - 
                            {{ $job->end_date ? \Carbon\Carbon::parse($job->end_date)->format('Y') : 'Present' }}
                        @else
                            -
                        @endif
                    </span>
                </div>
                <span class="company-name">{{ $job->company_name ?? ($job->company_type ?? 'Company') }}</span>
                
                @if($job->description)
                <ul class="job-tasks">
                    @foreach(explode("\n", $job->description) as $desc)
                        @if(trim($desc))
                        <li>{{ trim($desc) }}</li>
                        @endif
                    @endforeach
                </ul>
                @endif
            </div>
            @endforeach
            @else
            <div class="section-header">Work Experience</div>
            <div class="job-item">
                <p style="color: #999; font-style: italic;">No work experience information available</p>
            </div>
            @endif
        </div>
        
        <div class="footer-sign">{{ $lead->staff->staff_nickname ?? 'VD Labour' }} / {{ $lead->recommenderStaff->staff_sub_name ?? ' ' }}</div>
    </div>

    <script>
        function changeTemplate(templateNumber) {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('template', templateNumber);
            window.location.href = currentUrl.toString();
        }
    </script>