<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume - {{ $lead->getFullNameAttribute() }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Sarabun:wght@300;400;500;700&display=swap" rel="stylesheet">
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
            font-family: 'Roboto', 'Sarabun', Arial, sans-serif;
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

        .company-logo {
            position: absolute;
            top: 10px;
            right: 30px;
            width: 60px;
            height: auto;
            z-index: 1000;
        }

        /* สีธีมหลัก (อิงจากภาพนบพนนท์: สีน้ำตาล-แดง) */
        :root {
            --theme-blue: #8B4513; 
            --text-dark: #5D4037;
            --text-grey: #6D4C41;
            --light-grey-bg: #f5f5f5; /* Background สำหรับ Job Info */
            --border-grey: #ddd;
        }

        /* VD Logo */
        .logo-vd {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 14px;
            color: #d66428;
            font-weight: bold;
            font-family: Arial, sans-serif;
        }
        
        /* เครดิตผู้ออกแบบ */
        .footer-credit {
            position: absolute;
            bottom: 15px;
            right: 30px;
            font-size: 12px;
            color: #555;
        }

        /* ================= คอลัมน์ซ้าย (ข้อมูลส่วนตัว & Contact) ================= */
        .left-col {
            width: 38%;
            padding: 30px 20px 30px 30px;
            color: var(--text-grey);
            border-right: 1px solid var(--border-grey); /* เส้นแบ่งคอลัมน์ */
        }
        
        /* กรอบรูป */
        .photo-frame {
            width: 100%;
            margin-bottom: 25px;
            padding: 5px;
        }

        .profile-img {
            width: 100%;
            max-width: 200px;
            height: auto;
            border-radius: 50%; /* รูปวงกลม */
            border: 4px solid var(--theme-blue);
            display: block;
            margin: 0 auto;
            background-color: #fff;
            object-fit: cover;
        }

        /* หัวข้อส่วนย่อย (Contact, About Me, Skills) */
        .sidebar-title {
            color: var(--text-dark);
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid var(--text-dark); /* เส้นบางๆ สีดำ */
            width: 80%;
            margin-top: 30px;
        }
        
        /* Contact */
        .contact-list {
            list-style: none;
            font-size: 14px;
        }

        .contact-list li {
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
        }

        .contact-list i {
            color: var(--theme-blue);
            margin-right: 15px;
            font-size: 20px;
            margin-top: 3px;
            width: 20px;
            text-align: center;
        }
        
        .contact-text {
            line-height: 1.4;
            font-weight: 400;
        }
        
        /* About Me List */
        .about-me-list {
            list-style: none;
            padding-left: 0;
            font-size: 13px;
        }

        .about-me-list li {
            margin-bottom: 8px;
            line-height: 1.4;
        }
        
        .about-me-list strong {
            color: var(--text-dark);
            font-weight: 500;
            display: inline-block;
            min-width: 120px;
        }

        /* Skills List */
        .skills-list {
            list-style: none;
            padding-left: 0;
            font-size: 14px;
        }
        
        .skills-list li {
            position: relative;
            padding-left: 15px;
            margin-bottom: 8px;
        }
        
        .skills-list li::before {
            content: '•';
            color: var(--theme-blue);
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        /* ================= คอลัมน์ขวา (ชื่อ & ประสบการณ์) ================= */
        .right-col {
            width: 62%;
            padding: 30px 30px 30px 20px;
            color: var(--text-grey);
        }

        /* ส่วนหัวชื่อ */
        .main-header {
            margin-bottom: 25px;
            padding-top: 80px; /* เว้นที่ให้ Logo */
        }

        .main-name {
            font-family: 'Roboto', sans-serif;
            font-size: 32px;
            color: var(--text-dark); /* ใช้สีดำเข้มตามภาพ */
            text-transform: uppercase;
            font-weight: 700;
            line-height: 1.1;
        }
        
        .supermarket-tag {
            font-size: 16px;
            color: var(--text-grey);
            font-weight: 500;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        
        .summary-text {
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 30px;
            text-align: justify;
        }

        /* หัวข้อหลัก Experience */
        .main-section-title {
            color: var(--text-dark);
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 5px;
            border-bottom: 2px solid var(--theme-blue);
        }
        
        /* Job Item Container */
        .job-item {
            margin-bottom: 30px;
        }

        /* ส่วนหัวข้องาน */
        .job-info {
            margin-bottom: 10px;
            background-color: var(--light-grey-bg);
            padding: 8px 10px;
        }
        
        .job-info strong {
            font-size: 16px;
            color: var(--text-dark);
            font-weight: 500;
            display: block;
            margin-bottom: 3px;
        }

        .job-details-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .job-role {
            color: var(--theme-blue);
            font-weight: 500;
            font-size: 14px;
        }

        .job-date {
            color: var(--text-grey);
            font-size: 13px;
        }

        /* รายละเอียดงาน */
        .job-duties {
            list-style: none;
            padding-left: 0;
            font-size: 14px;
            line-height: 1.5;
        }

        .job-duties li {
            position: relative;
            padding-left: 15px;
            margin-bottom: 5px;
        }

        .job-duties li::before {
            content: '•';
            color: var(--text-grey); /* ใช้สีเทาตามภาพ */
            position: absolute;
            left: 0;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="resume-container">
        <img src="{{ asset('logo/V dragon-02.png') }}" alt="Company Logo" class="company-logo">
       
        
        <div class="left-col">
            <div class="photo-frame">
                @if($lead->lead_photo)
                    <img src="{{ asset('storage/' . $lead->lead_photo) }}" alt="Profile Photo" class="profile-img">
                @else
                    <img src="https://via.placeholder.com/200x200/cccccc/ffffff?text=PHOTO" alt="Profile Photo" class="profile-img">
                @endif
            </div>

            <div class="sidebar-title">Contact</div>
            <ul class="contact-list">
                @if($lead->lead_phone)
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <span class="contact-text">{{ $lead->lead_phone }}</span>
                    </li>
                @endif
                @if($lead->lead_email)
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span class="contact-text">{{ $lead->lead_email }}</span>
                    </li>
                @endif
                @if($lead->lead_address_en || $lead->lead_address)
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="contact-text">
                            {{ $lead->lead_address_en ?? $lead->lead_address }}
                        </span>
                    </li>
                @endif
            </ul>

            <div class="sidebar-title">About Me</div>
            <ul class="about-me-list">
                @if($lead->lead_birthday)
                    <li><strong>Date of Birth :</strong> {{ \Carbon\Carbon::parse($lead->lead_birthday)->format('d M Y') }}</li>
                    <li><strong>AGE :</strong> {{ \Carbon\Carbon::parse($lead->lead_birthday)->age }} Years</li>
                @endif
                <li><strong>STATUS :</strong> {{ ucfirst($lead->lead_status ?? 'N/A') }}</li>
                @if($lead->lead_height)
                    <li><strong>Height :</strong> {{ $lead->lead_height }} cm</li>
                @endif
                @if($lead->lead_weight)
                    <li><strong>Weight :</strong> {{ $lead->lead_weight }} kg</li>
                @endif
                @if($lead->lead_height && $lead->lead_weight)
                    <li><strong>BMI :</strong> {{ number_format($lead->lead_weight / (($lead->lead_height / 100) ** 2), 2) }}</li>
                @endif
                @if($lead->country)
                    <li><strong>Nationality :</strong> {{ $lead->country->country_name_en }}</li>
                @endif
                @if($lead->lead_emergency_contact_name)
                    <li>
                        <strong>EMERGENCY CONTACT NAME :</strong>
                        <span>{{ $lead->lead_emergency_contact_name }}</span>
                    </li>
                @endif
                @if($lead->lead_emergency_contact_phone)
                    <li><strong>TEL :</strong> {{ $lead->lead_emergency_contact_phone }}</li>
                @endif
            </ul>

            <div class="sidebar-title">Skills</div>
            <ul class="skills-list">
                <li>Machine operation and production line work</li>
                <li>Product packing and quality inspection</li>
                <li>Forklift and warehouse handling</li>
                <li>Basic maintenance and equipment cleaning</li>
            </ul>
        </div>

        <div class="right-col">
            <header class="main-header">
                <h1 class="main-name">{{ strtoupper($lead->getFullNameAttribute()) }}</h1>
                <div class="supermarket-tag">{{ $lead->position->position_name_en ?? $lead->jobGroup->jobgroup_name_en ?? 'General' }}</div>
                <p class="summary-text">
                    {{ $lead->lead_summary ?? 'Hardworking and responsible person with experience in factory and warehouse operations. Can operate machines, handle materials, and work well with others to complete tasks on time.' }}
                </p>
            </header>

            <div class="main-section-title">Experience</div>
            
            @if($lead->jobHistory && $lead->jobHistory->count() > 0)
                @foreach($lead->jobHistory->sortByDesc('start_date') as $job)
                    <div class="job-item">
                        <div class="job-info">
                            <strong>{{ $job->company_name ?? 'N/A' }}</strong>
                            <div class="job-details-line">
                                @if($job->position)
                                    <span class="job-role">{{ $job->position }}</span>
                                @endif
                                @if($job->start_date && $job->end_date)
                                    <span class="job-date">{{ \Carbon\Carbon::parse($job->start_date)->format('Y') }} - {{ \Carbon\Carbon::parse($job->end_date)->format('Y') }}</span>
                                @elseif($job->start_date)
                                    <span class="job-date">{{ \Carbon\Carbon::parse($job->start_date)->format('Y') }} - Present</span>
                                @endif
                            </div>
                        </div>
                        @if($job->description)
                            <ul class="job-duties">
                                @foreach(explode("\n", $job->description) as $line)
                                    @if(trim($line))
                                        <li>{{ trim($line) }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            @else
                <p>No work experience available</p>
            @endif
        </div>
        
        <div class="footer-credit">{{ $lead->staff->staff_name ?? 'VD Labour' }}/ {{ $lead->recommenderStaff->staff_sub_name ?? 'N/A' }}</div>
    </div>

    <!-- Template Selector & Print Button -->
    <div style="position: fixed; top: 20px; right: 20px; z-index: 2000; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
        <label for="templateSelect" style="font-weight: 600; margin-right: 10px;">Template:</label>
        <select id="templateSelect" onchange="changeTemplate(this.value)" style="padding: 5px 10px; border-radius: 4px; border: 1px solid #ccc;">
            @for($i = 1; $i <= 14; $i++)
                <option value="{{ $i }}" {{ request('template', '1') == $i ? 'selected' : '' }}>Template {{ $i }}</option>
            @endfor
        </select>
        <button onclick="window.print()" style="margin-left: 10px; padding: 5px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Print</button>
    </div>

    <script>
        function changeTemplate(templateNumber) {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('template', templateNumber);
            window.location.href = currentUrl.toString();
        }
    </script>

    <style>
        @media print {
            body {
                padding: 0;
                background: white;
            }
            .resume-container {
                box-shadow: none;
                margin: 0;
            }
            div[style*="position: fixed"] {
                display: none !important;
            }
        }
    </style>

</body>
</html>