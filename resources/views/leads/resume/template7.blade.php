<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume - {{ $lead->fullName }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&family=Sarabun:wght@300;400;500;700&display=swap" rel="stylesheet">
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
            font-family: 'Sarabun', 'Roboto', Arial, sans-serif;
            display: flex;
            justify-content: center;
            padding: 20px;
            font-size: 14px;
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

        /* สีธีมหลัก (ดำ/เทา/แดงอ่อน) */
        :root {
            --theme-dark: #2a2a2a; /* สีดำเข้มสำหรับชื่อหลัก */
            --theme-red-accent: #000000; /* สีแดงอ่อน/ส้มอิฐสำหรับเส้นแบ่ง */
            --light-grey-bg: #9ea6c0; /* พื้นหลังกล่อง Summary */
            --section-bg: #9ea6c0; /* พื้นหลังส่วน Experience */
            --text-grey: #020202;
            --text-dark-titles: #000000;
            --border-light: #ddd;
        }

        /* VD Logo */
        .logo-vd {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 60px;
            height: auto;
            z-index: 10;
        }
        
        .logo-vd img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        /* เครดิตผู้ออกแบบ */
        .footer-credit {
            position: absolute;
            bottom: 15px;
            left: 30px;
            font-size: 12px;
            color: var(--theme-dark);
            font-weight: 500;
        }

        /* ================= คอลัมน์ซ้าย (รูปภาพ & Profile & Contact) ================= */
        .left-col {
            width: 38%;
            padding: 30px 20px 30px 30px;
            color: var(--text-grey);
            position: relative;
        }
        
        /* รูปภาพ */
        .photo-frame {
            width: 100%;
            margin-bottom: 25px;
            overflow: hidden;
            border-radius: 15px; /* ขอบมน */
            /* ทำให้กรอบรูปดูเป็นบล็อกที่สะอาด */
        }

        .profile-img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
        }

        /* Profile Header */
        .profile-header {
            font-family: 'Roboto', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark-titles);
            margin-bottom: 15px;
            padding-top: 15px; /* เว้นระยะจากรูปภาพ */
            position: relative;
        }
        
        .profile-header i {
            margin-right: 10px;
            color: var(--theme-dark);
        }

        /* Profile List */
        .profile-list {
            list-style: none;
            padding-left: 0;
            font-size: 13px;
            line-height: 1.6;
        }

        .profile-list li {
            position: relative;
            padding-left: 10px;
            margin-bottom: 5px;
        }

        .profile-list li::before {
            content: '•';
            color: var(--theme-dark); /* ใช้สีดำตามภาพ */
            position: absolute;
            left: 0;
            font-weight: bold;
            font-size: 18px; 
            line-height: 0.8;
            top: 5px;
        }
        
        .profile-list strong {
            color: var(--theme-dark);
            font-weight: 500;
            display: inline-block;
            min-width: 110px;
        }

        /* Contact Header */
        .contact-header {
            font-family: 'Roboto', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark-titles);
            margin-top: 30px;
            margin-bottom: 15px;
            position: relative;
        }

        .contact-header i {
            margin-right: 10px;
            color: var(--theme-dark);
        }

        /* Contact List */
        .contact-list {
            list-style: none;
            font-size: 13px;
        }

        .contact-list li {
            margin-bottom: 10px;
            line-height: 1.4;
        }

        /* ================= คอลัมน์ขวา (ชื่อ & Experience) ================= */
        .right-col {
            width: 62%;
            padding: 30px 30px 30px 20px;
            color: var(--text-grey);
            position: relative;
        }

        /* ชื่อหลัก */
        .main-header {
            margin-bottom: 20px;
            padding-bottom: 5px;
            border-bottom: 1px solid var(--border-light);
        }

        .main-name {
            font-family: 'Roboto', sans-serif;
            font-size: 38px;
            color: var(--theme-dark);
            text-transform: uppercase;
            font-weight: 900; /* เน้นให้หนาเป็นพิเศษ */
            line-height: 1.1;
            margin-bottom: 5px;
        }

        .supermarket-tag {
            font-size: 16px;
            color: var(--text-grey);
            font-weight: 500;
            display: block;
            margin-bottom: 10px;
        }
        
        /* Summary Text Box */
        .summary-box {
            background-color: var(--light-grey-bg);
            padding: 20px;
            margin-bottom: 30px;
            font-size: 14px;
            line-height: 1.5;
            text-align: justify;
            border-radius: 20px; /* เพิ่มความโค้งมน */
            border: 1px solid var(--border-light);
        }

        /* Experience Header */
        .experience-header {
            font-family: 'Roboto', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark-titles);
            text-transform: uppercase;
            margin-bottom: 20px;
            position: relative;
        }
        
        /* Experience Wrapper (มีพื้นหลังสีอ่อนและเส้นโค้ง) */
        .experience-wrapper {
            background-color: var(--section-bg);
            padding: 20px 20px 5px 30px;
            border-radius: 20px; /* เพิ่มความโค้งมน */
            position: relative;
        }

        /* Job Item Container */
        .job-item {
            margin-bottom: 30px;
            padding-left: 20px;
            position: relative;
            
        }

        /* เส้นแนวตั้งและจุดนำหน้า (จำลองเส้นขอบโค้งและจุด) */
        .job-item::before {
            content: '';
            position: absolute;
            left: 5px;
            top: 5px;
            width: 8px;
            height: 8px;
            background-color: var(--theme-red-accent);
            border-radius: 50%;
            z-index: 2;
        }
        
        /* เส้นแนวนอนใต้ Experience Header */
        .experience-wrapper::before {
            content: '';
            position: absolute;
            left: 9px;
            top: 40px;
            bottom: 15px;
            width: 1px;
            background-color: var(--border-light);
            z-index: 1;
        }
        
        /* Job Location and Date */
        .job-location-date {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: var(--text-grey);
            margin-bottom: 5px;
        }
        
        .job-date {
            color: var(--theme-red-accent);
            font-weight: 500;
        }

        /* Job Title */
        .job-title {
            font-size: 16px;
            color: var(--theme-dark);
            font-weight: 700;
            display: block;
            margin-bottom: 3px;
        }

        .company-name {
            font-size: 14px;
            color: var(--text-grey);
            font-weight: 500;
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
            color: var(--theme-dark); 
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
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .template-selector select {
            padding: 8px 12px;
            border: 2px solid var(--theme-dark);
            border-radius: 5px;
            background-color: white;
            color: var(--text-dark-titles);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            outline: none;
        }

        .template-selector select:hover {
            background-color: var(--light-grey-bg);
        }

        .template-selector .print-btn {
            padding: 8px 16px;
            background-color: var(--theme-dark);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .template-selector .print-btn:hover {
            background-color: #1a1a1a;
        }

        @media print {
            .template-selector {
                display: none !important;
            }
            
            body {
                background-color: white;
                padding: 0;
                margin: 0;
            }
            
            .resume-container {
                box-shadow: none;
                margin: 0;
                width: 100%;
                min-height: auto;
            }
            
            /* Ensure colors print correctly */
            .summary-box,
            .experience-wrapper {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Template Selector -->
    <div class="template-selector">
        <select id="templateSelect" onchange="changeTemplate(this.value)">
            @for($i = 1; $i <= 14; $i++)
                <option value="{{ $i }}" {{ $template == $i ? 'selected' : '' }}>Template {{ $i }}</option>
            @endfor
        </select>
        <button class="print-btn" onclick="window.print()">
            <i class="fas fa-print"></i> Print
        </button>
    </div>

    <script>
        function changeTemplate(templateNumber) {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('template', templateNumber);
            window.location.href = currentUrl.toString();
        }
    </script>

    <div class="resume-container">
        <div class="logo-vd">
            <img src="{{ asset('logo/V dragon-02.png') }}" alt="VD Logo">
        </div>
        
        <div class="left-col">
            <div class="photo-frame">
                @if($lead->lead_photo)
                    <img src="{{ asset('storage/' . $lead->lead_photo) }}" alt="Profile Photo" class="profile-img">
                @else
                    <img src="https://via.placeholder.com/350x450/cccccc/ffffff?text=PHOTO" alt="Profile Photo" class="profile-img">
                @endif
            </div>

            <div class="profile-header"><i class="fas fa-user"></i>Profile</div>
            
            <ul class="profile-list">
                @if($lead->lead_birthday)
                <li><strong>Date of Birth :</strong> {{ $lead->lead_birthday->format('d M Y') }}</li>
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
                <li><strong>EMERGENCY CONTACT NAME :</strong><br>{{ $lead->lead_emergency_name }}</li>
                @endif
                @if($lead->lead_emergency_phone)
                <li><strong>TEL :</strong> {{ $lead->lead_emergency_phone }}</li>
                @endif
                @if($lead->lead_car_type)
                <li><strong>TYPE OF CAR :</strong> {{ $lead->lead_car_type }}</li>
                @endif
            </ul>

            <div class="contact-header"><i class="fas fa-phone-alt"></i>Contact</div>
            <ul class="contact-list">
                @if($lead->lead_phone)
                <li>{{ $lead->lead_phone }}</li>
                @endif
                @if($lead->lead_email)
                <li>{{ $lead->lead_email }}</li>
                @endif
                @if($lead->lead_address)
                <li>{{ $lead->lead_address }}</li>
                @endif
            </ul>
        </div>

        <div class="right-col">
            <header class="main-header">
                <h1 class="main-name">{{ strtoupper($lead->fullName) }}</h1>
                @if($lead->position)
                <span class="supermarket-tag">{{ $lead->position->position_name }}</span>
                @endif
            </header>
            
            @if($lead->lead_summary)
            <div class="summary-box">
                {{ $lead->lead_summary }}
            </div>
            @endif

            <div class="experience-header">Experience </div>
            
            <div class="experience-wrapper">
                @if($lead->jobHistory && $lead->jobHistory->count() > 0)
                    @foreach($lead->jobHistory as $job)
                    <div class="job-item">
                        <div class="job-location-date">
                             @if($job->company_name)
                        <span class="company-name">{{ $job->company_name }}</span>
                        @endif
                            @if($lead->country)
                            <span class="job-location">{{ $lead->country->country_name }}</span>
                            @endif
                            <span class="job-date">
                                @if($job->start_date && $job->end_date)
                                    {{ \Carbon\Carbon::parse($job->start_date)->format('Y') }} - {{ \Carbon\Carbon::parse($job->end_date)->format('Y') }}
                                @elseif($job->start_date)
                                    {{ \Carbon\Carbon::parse($job->start_date)->format('Y') }} - Present
                                @else
                                    {{ $job->experience_years ? $job->experience_years . ' Years' : '' }}
                                @endif
                            </span>
                        </div>
                        @if($job->position)
                        <span class="job-title">{{ $job->position }}</span>
                        @endif
                       
                        @if($job->description)
                        <ul class="job-duties">
                            @foreach(explode("\n", $job->description) as $duty)
                                @if(trim($duty))
                                <li>{{ trim($duty) }}</li>
                                @endif
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    @endforeach
                @else
                    <p style="color: #999; font-style: italic;">No work experience recorded</p>
                @endif
            </div>
        </div>
        
        <div class="footer-credit">
            @if($lead->staff)
                {{ $lead->staff->staff_name }}/{{ $lead->recommenderStaff->staff_sub_name ?? 'N/A' }}
            @endif
        </div>
    </div>

</body>
</html>