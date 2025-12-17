<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume - {{ $lead->fullName }}</title>
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

        /* สีธีมหลัก (ม่วง/น้ำตาลเข้ม) */
        :root {
            --theme-purple: #5c3b6d; /* สีม่วงเข้ม Header */
            --light-purple: #f5f0f9; /* สีม่วงอ่อน Background Experience */
            --text-dark: #333;
            --text-grey: #555;
            --header-red: #d66428;
            --border-grey: #ddd;
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
            color: var(--text-dark);
            font-weight: 500;
        }

        /* ================= คอลัมน์ซ้าย (รูปภาพ & Profile & Contact) ================= */
        .left-col {
            width: 38%;
            padding: 0 0 30px 0; /* เอา padding ด้านบนออก เพราะรูปภาพจะอยู่ติดขอบบน */
            color: var(--text-grey);
            position: relative;
        }
        
        /* รูปภาพ */
        .photo-frame {
            width: 100%;
            height: 250px; /* กำหนดความสูงเพื่อให้เท่ากับ Header ม่วงด้านขวา */
            overflow: hidden;
            background-color: #fff;
            border-bottom: 5px solid white; /* สร้างช่องว่างระหว่างรูปกับ Profile */
            position: relative;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: contain;
            object-position: center top;
        }
        
        /* Profile & Contact Content */
        .left-content {
            padding: 20px 20px 0 30px;
        }

        /* Profile Header */
        .profile-header {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 15px;
        }

        /* Profile Text / Summary */
        .profile-text {
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 20px;
            text-align: justify;
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
            margin-bottom: 3px;
        }

        .profile-list li::before {
            content: '•';
            color: var(--theme-purple);
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
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            margin-top: 30px;
            margin-bottom: 15px;
        }

        /* Contact List */
        .contact-list {
            list-style: none;
            font-size: 13px;
        }

        .contact-list li {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }

        .contact-list i {
            color: var(--theme-purple);
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

        /* ================= คอลัมน์ขวา (Header & Experience) ================= */
        .right-col {
            width: 62%;
            padding-bottom: 30px;
            color: var(--text-grey);
            position: relative;
        }

        /* Header ม่วง */
        .top-header-purple {
            background-color: var(--theme-purple);
            padding: 50px 30px 30px 30px;
            color: white;
            height: 250px; /* ให้ความสูงเท่ากับรูปภาพด้านซ้าย */
            border-radius: 0 0 50px 0; /* มุมล่างขวาโค้งมน */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .main-name {
            font-family: 'Roboto', sans-serif;
            font-size: 42px;
            color: white;
            text-transform: uppercase;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 0;
            padding-bottom: 20px;
            border-bottom: 3px solid white;
        }

        .supermarket-tag {
            font-size: 20px;
            color: white;
            font-weight: 500;
            padding-top: 20px;
            text-align: center;
        }
        
        /* Content ด้านขวา (Experience) */
        .right-content {
            padding: 30px 30px 0 30px;
            background-color: var(--light-purple); /* พื้นหลังสีม่วงอ่อน */
            min-height: calc(297mm - 250px); /* ความสูงที่เหลือของ A4 */
            border-radius: 50px 0 0 0; /* มุมบนซ้ายโค้งมน */
            margin-top: 20px; /* เว้นระยะห่างจาก Header ม่วง */
        }

        /* Experience Header */
        .experience-header {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            text-transform: uppercase;
            margin-bottom: 25px;
        }
        
        /* Job Item Container */
        .job-item {
            margin-bottom: 30px;
        }

        /* Job Dates */
        .job-dates {
            font-size: 16px;
            color: var(--theme-purple);
            font-weight: 700;
            margin-bottom: 5px;
        }

        /* Job Title */
        .job-title {
            font-size: 16px;
            color: var(--theme-purple);
            font-weight: 70s;
            display: block;
            margin-bottom: 3px;
        }

        .company-name {
            font-size: 14px;
            color: var(--text-dark);
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
            color: var(--theme-purple);
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        /* Template Selector Dropdown */
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
            border: 2px solid var(--theme-purple);
            border-radius: 5px;
            background-color: white;
            color: var(--text-dark);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            outline: none;
        }

        .template-selector select:hover {
            background-color: var(--light-purple);
        }

        .template-selector .print-btn {
            padding: 8px 16px;
            background-color: var(--theme-purple);
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
            background-color: #4a2d5a;
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
            .top-header-purple {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            .right-content {
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
                    <img src="https://via.placeholder.com/350x400/cccccc/ffffff?text=PHOTO" alt="Profile Photo" class="profile-img">
                @endif
            </div>

            <div class="left-content">
                <div class="profile-header">Profile</div>
                @if($lead->lead_summary)
                <p class="profile-text">
                    {{ $lead->lead_summary }}
                </p>
                @endif

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
                    <li><strong>EMERGENCY CONTACT NAME :</strong> {{ $lead->lead_emergency_name }}</li>
                    @endif
                    @if($lead->lead_emergency_phone)
                    <li><strong>TEL :</strong> {{ $lead->lead_emergency_phone }}</li>
                    @endif
                    @if($lead->lead_car_type)
                    <li><strong>TYPE OF CAR :</strong> {{ $lead->lead_car_type }}</li>
                    @endif
                </ul>

                <div class="contact-header">Contact </div>
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
                    @if($lead->lead_address)
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="contact-text">
                            {{ $lead->lead_address }}
                        </span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="right-col">
            <div class="top-header-purple">
                <h1 class="main-name">{{ strtoupper($lead->fullName) }}</h1>
                @if($lead->position)
                <div class="supermarket-tag">{{ $lead->position->position_name }}</div>
                @endif
            </div>

            <div class="right-content">
                <div class="experience-header">Experience </div>
                
                @if($lead->jobHistory && $lead->jobHistory->count() > 0)
                    @foreach($lead->jobHistory->sortByDesc('start_date') as $job)
                    <div class="job-item">
                        <div class="job-dates">
                            @if($job->start_date && $job->end_date)
                                {{ \Carbon\Carbon::parse($job->start_date)->format('Y') }} - {{ \Carbon\Carbon::parse($job->end_date)->format('Y') }}
                            @elseif($job->start_date)
                                {{ \Carbon\Carbon::parse($job->start_date)->format('Y') }} - Present
                            @else
                                {{ $job->experience_years ? $job->experience_years . ' Years' : '' }}
                            @endif
                        </div>
                        @if($job->position)
                        <span class="job-title">{{ $job->position }}</span>
                        @endif
                        @if($job->company_name)
                        <span class="company-name">{{ strtoupper($job->company_name) }}</span>
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
                {{ $lead->staff->staff_nickname }}/{{ $lead->recommenderStaff->staff_sub_name ?? '' }}
            @endif
        </div>
    </div>

</body>
</html>