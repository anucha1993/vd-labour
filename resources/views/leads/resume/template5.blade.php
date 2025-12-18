<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume - {{ $lead->fullName }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400;500;700&family=Sarabun:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* ตั้งค่า Reset พื้นฐาน */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f5f5f5;
            font-family: 'Sarabun', Arial, sans-serif;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        /* จำลองกระดาษ A4 */
        .resume-container {
            width: 210mm;
            min-height: 297mm;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: flex;
            position: relative;
        }

        /* สีธีมหลัก (อิงจากภาพนบพนนท์: น้ำตาลเข้ม/ส้ม) */
        :root {
            --theme-brown: #33201a; 
            --theme-orange: #d66428; 
            --text-dark: #333;
            --text-grey: #555;
            --light-bg: #f5f5f5;
        }

        /* VD Logo */
        .logo-vd {
            position: absolute;
            top: 20px;
            right: 30px;
            z-index: 100;
        }
        
        .logo-vd img {
            width: 60px;
            height: auto;
        }
        
        /* เครดิตผู้ออกแบบ */
        .footer-credit {
            position: absolute;
            bottom: 10px;
            left: 30px;
            font-size: 12px;
            color: var(--text-dark);
            font-weight: 500;
        }

        /* ================= คอลัมน์ซ้าย (ข้อมูลส่วนตัว & Contact) ================= */
        .left-col {
            width: 50%;
            padding: 20px 25px;
            color: var(--text-grey);
            position: relative;
        }

        /* ชื่อหลัก */
        .main-name-container {
            margin-bottom: 15px;
        }

        .main-name {
            font-family: 'Oswald', sans-serif;
            font-size: 28px;
            color: var(--theme-brown);
            text-transform: uppercase;
            font-weight: 700;
            line-height: 1.1;
        }

        /* Supermarket Badge */
        .supermarket-badge {
            display: inline-block;
            padding: 5px 20px;
            border: 2px solid var(--theme-brown);
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            color: var(--theme-brown);
            margin-top: 8px;
            margin-bottom: 15px;
        }

        /* About Me Header */
        .about-header {
            font-family: 'Oswald', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--theme-brown);
            margin-bottom: 10px;
        }

        /* About Me Text */
        .about-text {
            font-size: 13px;
            line-height: 1.4;
            margin-bottom: 15px;
            text-align: justify;
        }

        /* Profile List */
        .profile-list {
            list-style: none;
            padding-left: 0;
            font-size: 12px;
            line-height: 1.5;
        }

        .profile-list li {
            position: relative;
            padding-left: 10px;
            margin-bottom: 3px;
        }

        .profile-list li::before {
            content: '•';
            color: var(--theme-orange);
            position: absolute;
            left: 0;
            font-weight: bold;
        }
        
        .profile-list strong {
            color: var(--text-dark);
            font-weight: 500;
            display: inline-block;
            min-width: 100px;
        }
        
        /* Contact Header */
        .contact-header {
            font-family: 'Oswald', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--theme-brown);
            margin-top: 20px;
            margin-bottom: 10px;
        }

        /* Contact List */
        .contact-list {
            list-style: none;
            font-size: 12px;
        }

        .contact-list li {
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
        }

        .contact-list i {
            color: var(--theme-orange);
            margin-right: 15px;
            font-size: 18px;
            width: 20px;
            text-align: center;
            margin-top: 3px;
        }
        
        .contact-text {
            line-height: 1.4;
            font-weight: 400;
        }

        /* ================= คอลัมน์ขวา (รูปภาพ & ประสบการณ์) ================= */
        .right-col {
            width: 50%;
            padding: 20px 25px 20px 10px;
            color: var(--text-grey);
            position: relative;
        }
        
        /* กรอบรูป */
        .photo-frame {
            position: absolute;
            top: 10px;
            left: -30px; /* ให้รูปทับขอบซ้ายเข้ามา แต่ไม่มากเกินไป */
            width: 170px; /* ควบคุมขนาด */
            height: 170px;
            overflow: hidden;
            z-index: 10;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%; /* ทำให้รูปเป็นวงกลม */
            border: 5px solid white; /* ขอบขาว */
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        /* Header Experience */
        .experience-header {
            font-family: 'Oswald', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--theme-brown);
            text-transform: uppercase;
            margin-top: 200px; /* เว้นที่ให้รูปภาพด้านบน (เพิ่มขึ้นเพราะรูปเลื่อนลง) */
            margin-bottom: 20px;
        }
        
        /* Timeline Layout */
        .timeline-item {
            display: flex;
            margin-bottom: 18px;
            position: relative;
        }

        /* เส้น Timeline แนวตั้ง */
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20px;
            bottom: -30px;
            width: 1px;
            background-color: var(--theme-orange);
        }

        /* วงกลมบนเส้น Timeline */
        .timeline-item:first-child::before {
            top: 5px;
        }
        
        /* ซ่อนเส้นของรายการสุดท้าย */
        .timeline-item:last-child::before {
            display: none;
        }

        .job-details-container {
            padding-left: 20px;
            flex-grow: 1;
        }

        .job-details-container::before {
            content: '';
            position: absolute;
            left: -3px;
            top: 7px;
            width: 8px;
            height: 8px;
            background-color: var(--theme-orange);
            border-radius: 50%;
            z-index: 2;
        }

        /* Job Title Header */
        .job-info {
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }
        
        .job-title {
            font-size: 15px;
            color: var(--theme-brown);
            font-weight: 500;
        }
        
        .job-date {
            font-size: 13px;
            color: var(--theme-orange);
            font-weight: 500;
            white-space: nowrap;
        }
        
        .company-name {
            font-size: 14px;
            color: var(--text-dark);
            font-weight: 400;
            display: block;
            margin-bottom: 10px;
        }

        /* รายละเอียดงาน */
        .job-duties {
            list-style: none;
            padding-left: 0;
            font-size: 13px;
            line-height: 1.5;
        }

        .job-duties li {
            position: relative;
            padding-left: 15px;
            margin-bottom: 5px;
        }

        .job-duties li::before {
            content: '•';
            color: var(--text-dark); /* ใช้สีดำตามภาพ */
            position: absolute;
            left: 0;
            font-weight: bold;
        }
        
        /* Template Selector */
        .template-selector {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .template-selector select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
            font-size: 14px;
        }
        
        .template-selector button {
            padding: 8px 16px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .template-selector button:hover {
            background: #0056b3;
        }
        
        @media print {
            /* ซ่อนปุ่มและ selector */
            .template-selector, .no-print, div[style*="position: fixed"] {
                display: none !important;
            }
            
            @page {
                size: A4;
                margin: 0;
            }
            
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            body {
                padding: 0;
                margin: 0;
                background: white;
                width: 210mm;
                height: 297mm;
            }
            
            .resume-container {
                width: 210mm;
                min-height: 297mm;
                box-shadow: none;
                margin: 0;
                page-break-after: auto;
                display: flex;
                position: relative;
            }
            
            /* คงค่าโลโก้ */
            .logo-vd {
                position: absolute;
                top: 20px;
                right: 30px;
                z-index: 100;
            }
            
            .logo-vd img {
                width: 60px;
                height: auto;
            }
            
            /* คอลัมน์ซ้าย */
            .left-col {
                width: 50%;
                padding: 20px 25px;
            }
            
            /* คอลัมน์ขวา */
            .right-col {
                width: 50%;
                padding: 20px 25px 20px 10px;
            }
            
            /* รูปโปรไฟล์ */
            .photo-frame {
                position: absolute;
                top: 10px;
                left: -30px;
                width: 170px;
                height: 170px;
            }
            
            .profile-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 50%;
                border: 5px solid white !important;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            
            /* ชื่อหลัก */
            .main-name {
                font-family: 'Oswald', sans-serif;
                font-size: 32px;
                color: #33201a !important;
                text-transform: uppercase;
                font-weight: 700;
                line-height: 1.1;
            }
            
            /* Badge */
            .supermarket-badge {
                display: inline-block;
                padding: 5px 20px;
                border: 2px solid #33201a !important;
                border-radius: 20px;
                color: #33201a !important;
            }
            
            /* Section Headers */
            .about-header, .contact-header, .experience-header {
                font-family: 'Oswald', sans-serif;
                color: #33201a !important;
                font-weight: 700;
            }
            
            /* Timeline */
            .timeline-item::before {
                content: '';
                position: absolute;
                left: 0;
                top: 20px;
                bottom: -30px;
                width: 1px;
                background-color: #d66428 !important;
            }
            
            .timeline-item:first-child::before {
                top: 5px;
            }
            
            .timeline-item:last-child::before {
                display: none;
            }
            
            .job-details-container::before {
                content: '';
                position: absolute;
                left: -3px;
                top: 7px;
                width: 8px;
                height: 8px;
                background-color: #d66428 !important;
                border-radius: 50%;
                z-index: 2;
            }
            
            /* Job Info */
            .job-title {
                color: #33201a !important;
                font-weight: 500;
            }
            
            .job-date {
                color: #d66428 !important;
                font-weight: 500;
            }
            
            /* Bullets */
            .profile-list li::before,
            .contact-list i {
                color: #d66428 !important;
            }
            
            /* Work Experience */
            .job-item {
                page-break-inside: avoid;
            }
            
            .timeline-item {
                page-break-inside: avoid;
            }
            
            /* Footer credit - แสดงทุกหน้า */
            .footer-credit {
                position: fixed;
                bottom: 10px;
                left: 30px;
                font-size: 12px;
                color: #333 !important;
                font-weight: 500;
            }
        }
    </style>
</head>
<body>

    <!-- Template Selector -->
    <div class="template-selector">
        <select id="templateSelector" onchange="changeTemplate(this.value)">
            @for($i = 1; $i <= 14; $i++)
                <option value="{{ $i }}" {{ $template == $i ? 'selected' : '' }}>Template {{ $i }}</option>
            @endfor
        </select>
        <button onclick="window.print()">
            <i class="fas fa-print"></i> Print
        </button>
    </div>

    <div class="resume-container">
        <div class="logo-vd">
            <img src="{{ asset('logo/V dragon-02.png') }}" alt="VD Logo" style="width: 60px; height: auto;">
        </div>
        
        <div class="left-col">
            <div class="main-name-container">
                <h1 class="main-name">{{ strtoupper($lead->lead_prefix . ' ' . $lead->lead_firstname) }}<br>{{ strtoupper($lead->lead_lastname) }}</h1>
            </div>
            
            <div class="supermarket-badge">{{ strtoupper($lead->position->position_name ?? 'POSITION') }}</div>

            <div class="about-header">ABOUT ME</div>
            <p class="about-text">
                {{ $lead->lead_summary ?? 'Hello, my name is ' . $lead->fullName . '. I am a responsible and hardworking person who enjoys working with people and providing good customer service.' }}
            </p>

            <ul class="profile-list">
                @if($lead->lead_birthday)
                <li><strong>Date of Birth :</strong> {{ \Carbon\Carbon::parse($lead->lead_birthday)->format('d M Y') }}</li>
                @endif
                @if($lead->lead_age)
                <li><strong>AGE :</strong> {{ $lead->lead_age }} Years</li>
                @endif
                @if($lead->lead_marital_status)
                <li><strong>STATUS :</strong> {{ ucfirst($lead->lead_marital_status) }}</li>
                @endif
                @if($lead->lead_height)
                <li><strong>Height :</strong> {{ $lead->lead_height }} cm</li>
                @endif
                @if($lead->lead_weight)
                <li><strong>Weight :</strong> {{ $lead->lead_weight }} kg</li>
                @endif
                @if($lead->lead_shirt_size)
                <li><strong>Shirt size :</strong> {{ $lead->lead_shirt_size }}</li>
                @endif
                @if($lead->lead_pant_size)
                <li><strong>Pant size :</strong> {{ $lead->lead_pant_size }}</li>
                @endif
                @if($lead->lead_shoes_size)
                <li><strong>Shoes size :</strong> {{ $lead->lead_shoes_size }}</li>
                @endif
                @if($lead->lead_emergency_name)
                <li><strong>EMERGENCY CONTACT NAME :</strong> {{ $lead->lead_emergency_name }}</li>
                @endif
                @if($lead->lead_driving_license === 'yes')
                <li><strong>DRIVING LICENSE :</strong> {{ $lead->lead_car_type ?? 'Yes' }}</li>
                @endif
            </ul>

            <div class="contact-header">CONTACT ME</div>
            <ul class="contact-list">
                @if($lead->lead_phone)
                <li>
                    <i class="fas fa-phone-alt"></i>
                    <span class="contact-text">{{ $lead->lead_phone }}</span>
                </li>
                @endif
                @if($lead->lead_address)
                <li>
                    <i class="fas fa-map-marker-alt"></i>
                    <span class="contact-text">{{ $lead->lead_address }}</span>
                </li>
                @endif
                @if($lead->lead_email)
                <li>
                    <i class="fas fa-envelope"></i>
                    <span class="contact-text">{{ $lead->lead_email }}</span>
                </li>
                @endif
            </ul>
        </div>

        <div class="right-col mt-5">
            <div class="photo-frame">
                @if($lead->lead_photo)
                    <img src="{{ asset('storage/' . $lead->lead_photo) }}" alt="Profile Photo" class="profile-img">
                @else
                    <img src="https://via.placeholder.com/200x200/cccccc/ffffff?text=PHOTO" alt="Profile Photo" class="profile-img">
                @endif
            </div>

            <div class="experience-header">EXPERIENCE</div>
            
            @if($lead->jobHistory && count($lead->jobHistory) > 0)
                @foreach($lead->jobHistory->sortByDesc('start_date') as $job)
                <div class="timeline-item">
                    <div class="job-details-container">
                        <div class="job-info">
                            <span class="job-title">{{ $job->position ?? 'Position' }}</span>
                            <span class="job-date">
                                @if($job->start_date && $job->end_date)
                                    {{ \Carbon\Carbon::parse($job->start_date)->format('Y') }} - {{ \Carbon\Carbon::parse($job->end_date)->format('Y') }}
                                @elseif($job->start_date)
                                    {{ \Carbon\Carbon::parse($job->start_date)->format('Y') }} - Present
                                @else
                                    {{ $job->experience_years ? $job->experience_years . ' Years' : 'Duration' }}
                                @endif
                            </span>
                        </div>
                        <span class="company-name">{{ $job->company_name ?? '' }}</span>
                        <ul class="job-duties">
                            @if($job->description)
                                @foreach(explode("\n", $job->description) as $duty)
                                    @if(trim($duty))
                                        <li>{{ trim($duty) }}</li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                @endforeach
            @else
                <div class="timeline-item">
                    <div class="job-details-container">
                        <div class="job-info">
                            <span class="job-title">No Experience Listed</span>
                            <span class="job-date">-</span>
                        </div>
                        <span class="company-name"></span>
                        <ul class="job-duties">
                            <li>Please update work experience information</li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
        
        <footer class="footer-credit">{{ $lead->staff->staff_nickname ?? 'VD Labour' }}/ {{ $lead->recommenderStaff->staff_sub_name ?? '' }}</footer>
    </div>

    <script>
        function changeTemplate(templateNumber) {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('template', templateNumber);
            window.location.href = currentUrl.toString();
        }
    </script>

</body>
</html>