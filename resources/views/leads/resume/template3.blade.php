<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume - {{ $lead->getFullNameAttribute() }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
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
            font-family: 'Roboto', Arial, sans-serif;
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
            padding-top: 50px; /* เว้นที่สำหรับ Header ด้านบน */
        }

        .company-logo {
            position: absolute;
            top: 30px;
            right: 30px;
            width: 80px;
            height: auto;
            z-index: 1000;
        }

        /* สีธีมหลัก */
        :root {
            --theme-blue: #008cba; /* สีฟ้า */
            --text-dark: #333;
            --text-grey: #555;
            --light-grey-bg: #f8f8f8;
        }

        /* โลโก้ VD มุมขวาบน */
        .logo-vd {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 10px;
            color: #d66428;
            font-weight: bold;
        }
        
        /* สัญลักษณ์ลูกศรขาวด้านบน */
        .top-arrows {
            position: absolute;
            top: 50px;
            left: 210px; /* จัดตำแหน่งให้อยู่ข้างรูป */
            font-size: 30px;
            color: #ccc;
            opacity: 0.5;
            line-height: 0.8;
            transform: scaleY(0.5); /* ปรับให้ผอมลง */
        }
        
        /* สัญลักษณ์ลูกศรขาวด้านล่าง */
        .bottom-arrows {
            position: absolute;
            bottom: 30px;
            right: 20px;
            font-size: 30px;
            color: #ccc;
            opacity: 0.5;
            line-height: 0.8;
            transform: scaleY(0.5) rotate(180deg);
        }

        /* ================= คอลัมน์ซ้าย ================= */
        .left-col {
            width: 35%;
            padding: 0 20px 30px 30px;
            color: var(--text-grey);
            border-right: 1px solid #eee;
        }
        
        /* กรอบรูป */
        .photo-frame {
            width: 100%;
            margin-bottom: 20px;
            padding-top: 20px;
        }

        .profile-img {
            width: 100%;
            height: auto;
            border: 1px solid #ddd;
            display: block;
            background-color: #fff;
            object-fit: cover;
        }

        /* หัวข้อส่วนย่อย (Professional Summary, Contact) */
        .sidebar-title {
            color: #555;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 3px solid var(--theme-blue);
            background: linear-gradient(to right, #cce7f0 0%, #cce7f0 60%, transparent 60%);
            padding-left: 10px;
            width: 100%;
            margin-top: 30px;
        }

        /* เนื้อหา Summary */
        .summary-text {
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 25px;
            text-align: justify;
        }

        /* รายละเอียด Profile */
        .profile-list {
            list-style: none;
            padding-left: 0;
            font-size: 13px;
        }

        .profile-list li {
            margin-bottom: 8px;
            line-height: 1.4;
        }
        
        .profile-list strong {
            color: var(--text-dark);
            font-weight: 500;
            display: inline-block;
            min-width: 100px;
        }

        /* ส่วน Contact */
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
            margin-right: 10px;
            font-size: 18px;
            margin-top: 3px;
            width: 20px;
            text-align: center;
        }
        
        .contact-address {
            margin-top: 5px;
            display: block;
            line-height: 1.4;
        }

        /* ================= คอลัมน์ขวา ================= */
        .right-col {
            width: 65%;
            padding: 0 30px 30px 30px;
            color: var(--text-grey);
        }

        /* ส่วนหัวชื่อ */
        .main-header {
            margin-top: -20px; /* ดึงขึ้นไปด้านบน */
            margin-bottom: 30px;
        }

        .main-name {
            font-family: 'Roboto', sans-serif;
            font-size: 38px;
            color: var(--text-dark);
            text-transform: uppercase;
            font-weight: 700;
            line-height: 1.1;
        }

        /* หัวข้อหลัก Work Experience */
        .main-section-title {
            color: #555;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 8px;
            padding-left: 10px;
            border-bottom: 3px solid var(--theme-blue);
            background: linear-gradient(to right, #cce7f0 0%, #cce7f0 60%, transparent 60%);
        }
        
        /* Job Item Container */
        .job-item {
            margin-bottom: 30px;
            padding-left: 10px;
            position: relative;
        }
        
        /* สัญลักษณ์ Bullet (วงกลมสีฟ้า) หน้า Job Item */
        .job-item::before {
            content: '';
            width: 10px;
            height: 10px;
            background-color: var(--theme-blue);
            border-radius: 50%;
            position: absolute;
            left: 0;
            top: 5px;
        }

        /* เส้นแนวตั้งเชื่อม Job Item */
        .job-item::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 15px;
            bottom: -30px; 
            width: 2px;
            background-color: #ddd;
        }
        
        /* ซ่อนเส้นของรายการสุดท้าย */
        .job-item:last-child::after {
            display: none;
        }

        /* ส่วนหัวข้องาน */
        .job-info {
            margin-bottom: 10px;
            padding-left: 15px; /* เว้นให้พ้น bullet */
        }
        
        .job-info strong {
            font-size: 15px;
            color: var(--text-dark);
            font-weight: 500;
            display: block;
            margin-bottom: 5px;
        }

        .company-name {
            color: var(--theme-blue);
            font-weight: 500;
            font-size: 14px;
            display: inline-block;
            margin-right: 15px;
        }

        .job-date {
            color: var(--text-grey);
            font-size: 13px;
        }

        /* รายละเอียดงาน */
        .job-duties {
            list-style-type: none;
            padding-left: 15px;
            font-size: 13px;
            line-height: 1.4;
        }

        .job-duties li {
            position: relative;
            padding-left: 15px;
            margin-bottom: 5px;
        }

        .job-duties li::before {
            content: '•';
            color: #ccc; /* ใช้จุดสีเทาอ่อนตามภาพ */
            position: absolute;
            left: 0;
            font-weight: bold;
        }
        
        /* เครดิตผู้ออกแบบ */
        .footer-credit {
            position: absolute;
            bottom: 10px;
            right: 30px;
            font-size: 12px;
            color: #333;
        }

        @media screen and (max-width: 768px) {
            .resume-container {
                width: 100%;
                flex-direction: column;
                height: auto;
                padding-top: 20px;
            }
            .left-col, .right-col {
                width: 100%;
                padding: 10px 20px;
                border-right: none;
            }
            .main-header {
                margin-top: 20px;
                margin-bottom: 20px;
            }
            .job-item::after, .top-arrows, .bottom-arrows {
                display: none;
            }
            .logo-vd, .footer-credit {
                position: static;
                text-align: center;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>

    <div class="resume-container">
        <img src="{{ asset('logo/V dragon-02.png') }}" alt="Company Logo" class="company-logo">
       
        <div class="top-arrows">
            ^<br>^<br>^
        </div>
        
        <div class="main-header" style="position: absolute; top: 70px; left: 35%; padding-left: 30px;">
            <h1 class="main-name">{{ strtoupper($lead->getFullNameAttribute()) }}</h1>
        </div>
        
        <div class="left-col">
            <div class="photo-frame">
                @if($lead->lead_photo)
                    <img src="{{ asset('storage/' . $lead->lead_photo) }}" alt="Profile Photo" class="profile-img">
                @else
                    <img src="https://via.placeholder.com/300x380/cccccc/ffffff?text=PHOTO" alt="Profile Photo" class="profile-img">
                @endif
            </div>

            <div class="sidebar-title">Professional Summary</div>
            <p class="summary-text">
                {{ $lead->lead_summary ?? 'Hardworking and responsible person with experience in general labor and technical work. Can work well in a team, learn new tasks quickly, and complete duties with care and accuracy.' }}
            </p>

            <ul class="profile-list">
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
                    <li><strong>EMERGENCY CONTACT NAME :</strong><br> {{ $lead->lead_emergency_contact_name }}</li>
                @endif
                @if($lead->lead_emergency_contact_phone)
                    <li><strong>TEL :</strong> {{ $lead->lead_emergency_contact_phone }}</li>
                @endif
            </ul>

            <div class="sidebar-title">Contact</div>
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
                @if($lead->lead_address_en || $lead->lead_address)
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $lead->lead_address_en ?? $lead->lead_address }}</span>
                    </li>
                @endif
            </ul>
        </div>

        <div class="right-col">
            <div style="margin-top: 150px;"></div> <div class="main-section-title">Work Experience</div>
            
            @if($lead->jobHistory && $lead->jobHistory->count() > 0)
                @foreach($lead->jobHistory as $job)
                    <div class="job-item">
                        <div class="job-info">
                            <strong>{{ $job->company_name ?? 'N/A' }}</strong>
                            @if($job->position)
                                <span class="company-name">{{ $job->position }}</span>
                            @endif
                            @if($job->start_date && $job->end_date)
                                <span class="job-date">{{ \Carbon\Carbon::parse($job->start_date)->format('Y') }} - {{ \Carbon\Carbon::parse($job->end_date)->format('Y') }}</span>
                            @elseif($job->start_date)
                                <span class="job-date">{{ \Carbon\Carbon::parse($job->start_date)->format('Y') }} - Present</span>
                            @endif
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
        <div class="bottom-arrows">
            ^<br>^<br>^
        </div>
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