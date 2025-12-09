<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume - {{ $lead->getFullNameAttribute() }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Playfair+Display:wght@700&family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* ตั้งค่าพื้นฐาน */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #e0e0e0;
            font-family: 'Sarabun', sans-serif;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        /* จำลองกระดาษ A4 */
        .resume-container {
            width: 210mm;
            min-height: 297mm;
            background-color: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            display: flex;
            position: relative;
            overflow: hidden;
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
            --theme-yellow: #fcd55a;
            --theme-dark: #3b2a24;
            --text-dark: #333;
            --text-brown: #5d4037;
        }

        /* ================= คอลัมน์ซ้าย ================= */
        .left-col {
            width: 35%;
            background-color: var(--theme-yellow);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: var(--theme-dark);
        }

        /* กรอบรูป */
        .photo-frame {
            background-color: var(--theme-dark);
            padding: 10px;
            border-radius: 20px;
            width: 100%;
            margin-bottom: 0;
            position: relative;
            z-index: 2;
        }

        .profile-img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            display: block;
            background-color: #fff; /* Placeholder สีขาวถ้าไม่มีรูป */
        }

        /* ป้ายชื่อใต้รูป */
        .sub-name-badge {
            background-color: var(--theme-dark);
            color: white;
            width: 100%;
            text-align: center;
            padding: 10px;
            border-radius: 0 0 20px 20px;
            margin-top: -10px; /* ดึงขึ้นไปติดกรอบรูป */
            margin-bottom: 30px;
            font-family: 'Oswald', sans-serif;
            font-size: 18px;
            letter-spacing: 1px;
            z-index: 1;
        }

        /* หัวข้อ Sidebar (Contact Me, Profile) */
        .sidebar-header {
            background-color: var(--theme-dark);
            color: white;
            width: 100%;
            text-align: center;
            padding: 8px 0;
            border-radius: 20px;
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        /* จุดสีเหลืองในหัวข้อ */
        .sidebar-header::before,
        .sidebar-header::after {
            content: '';
            width: 10px;
            height: 10px;
            background-color: var(--theme-yellow);
            border-radius: 50%;
            display: inline-block;
        }

        /* เนื้อหาใน Sidebar */
        .sidebar-content {
            width: 100%;
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
            line-height: 1.5;
        }

        .contact-text {
            margin-bottom: 10px;
            font-weight: 600;
        }

        /* รายการ Profile */
        .profile-list {
            list-style: none;
            text-align: left;
            padding-left: 10px;
            font-size: 14px;
        }

        .profile-list li {
            margin-bottom: 6px;
            line-height: 1.4;
        }

        .footer-credit {
            margin-top: auto;
            font-size: 16px;
            font-weight: bold;
            opacity: 0.7;
            align-self: flex-start;
        }

        /* ================= คอลัมน์ขวา ================= */
        .right-col {
            width: 65%;
            background-color: white;
            display: flex;
            flex-direction: column;
        }

        /* ส่วนหัวด้านขวา (พื้นหลังสีเหลือง) */
        .top-section {
            background-color: var(--theme-yellow);
            padding: 30px 40px 10px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .supermarket-badge {
            background-color: var(--theme-dark);
            color: white;
            padding: 8px 40px;
            border-radius: 20px;
            font-family: 'Oswald', sans-serif;
            font-size: 14px;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .main-name {
            font-family: 'Playfair Display', serif; /* หรือใช้ Oswald ตามภาพก็ได้ */
            font-size: 48px;
            color: var(--theme-dark);
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1;
            text-align: center;
        }

        /* About Me Box */
        .about-box {
            background-color: var(--theme-dark);
            color: white;
            border-radius: 30px;
            padding: 20px 30px;
            width: 100%;
            margin-top: 10px;
            margin-bottom: -30px; /* ให้มันกินพื้นที่ลงไปในส่วนสีขาว */
            position: relative;
            z-index: 10;
            text-align: center;
        }

        .about-header {
            color: white;
            font-family: 'Oswald', sans-serif;
            font-size: 20px;
            margin-bottom: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }
        
        .about-header::before,
        .about-header::after {
            content: '';
            width: 12px;
            height: 12px;
            background-color: var(--theme-yellow);
            border-radius: 50%;
        }

        .about-text {
            font-size: 14px;
            line-height: 1.4;
            font-weight: 300;
        }

        /* ส่วนเนื้อหาล่าง (พื้นหลังขาว) */
        .bottom-section {
            padding: 60px 40px 40px 40px;
            flex-grow: 1;
        }

        .section-header-pill {
            background-color: var(--theme-dark);
            color: white;
            padding: 8px 0;
            border-radius: 20px;
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 18px;
            text-align: center;
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }

        .section-header-pill::before,
        .section-header-pill::after {
            content: '';
            width: 10px;
            height: 10px;
            background-color: var(--theme-yellow);
            border-radius: 50%;
        }

        /* Timeline Layout */
        .timeline-item {
            display: flex;
            margin-bottom: 30px;
            position: relative;
        }

        .timeline-left {
            width: 140px;
            flex-shrink: 0;
            padding-right: 20px;
            text-align: center;
            position: relative;
        }

        /* เส้นตั้ง Timeline */
        .timeline-left::after {
            content: '';
            position: absolute;
            top: 35px;
            right: 0;
            bottom: -50px;
            width: 2px;
            background-color: #333;
        }
        /* ซ่อนเส้นของตัวสุดท้าย */
        .timeline-item:last-child .timeline-left::after {
            display: none;
        }

        .year-badge {
            background-color: var(--theme-yellow); /* สีเหลืองอ่อนลง */
            color: var(--theme-dark);
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: 700;
            font-size: 14px;
            display: inline-block;
            margin-bottom: 5px;
            white-space: nowrap;
        }

        .duration-text {
            font-size: 12px;
            color: #666;
            display: block;
        }

        .timeline-right {
            padding-left: 20px;
            flex-grow: 1;
        }

        .job-title-header {
            background-color: var(--theme-yellow); /* สีเหลือง */
            color: var(--theme-dark);
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: 700;
            font-size: 15px;
            display: inline-block;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .job-details {
            list-style: none;
            color: #d88d2a; /* สีส้มทองๆ ตาม bullet */
        }

        .job-details li {
            position: relative;
            padding-left: 15px;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
            line-height: 1.4;
        }

        .job-details li::before {
            content: '•';
            color: #d88d2a;
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        @media screen and (max-width: 768px) {
            .resume-container {
                width: 100%;
                flex-direction: column;
                height: auto;
            }
            .left-col, .right-col {
                width: 100%;
            }
            .timeline-item {
                flex-direction: column;
            }
            .timeline-left::after {
                display: none;
            }
            .timeline-left {
                width: 100%;
                text-align: left;
                margin-bottom: 10px;
            }
            .timeline-right {
                padding-left: 0;
            }
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
                    <img src="https://via.placeholder.com/300x380/cccccc/ffffff?text=PHOTO" alt="Profile Photo" class="profile-img">
                @endif
            </div>
            <div class="sub-name-badge">{{ strtoupper($lead->getFullNameAttribute()) }}</div>

            <div class="sidebar-header">CONTACT ME</div>
            <div class="sidebar-content">
                <div class="contact-text" style="font-size: 20px;">{{ $lead->lead_phone ?? 'N/A' }}</div>
                @if($lead->lead_email)
                    <div style="margin-bottom: 10px;">{{ $lead->lead_email }}</div>
                @endif
                <div>{{ $lead->lead_address_en ?? $lead->lead_address ?? 'N/A' }}</div>
            </div>

            <div class="sidebar-header">PROFILE</div>
            <ul class="profile-list">
                @if($lead->lead_birthday)
                    <li><strong>DATE OF BIRTH :</strong><br> {{ \Carbon\Carbon::parse($lead->lead_birthday)->format('d M Y') }}</li>
                    <li><strong>AGE :</strong> {{ \Carbon\Carbon::parse($lead->lead_birthday)->age }} YEARS</li>
                @endif
                <li><strong>STATUS :</strong> {{ strtoupper($lead->lead_status ?? 'N/A') }}</li>
                @if($lead->lead_height)
                    <li><strong>HEIGHT :</strong> {{ $lead->lead_height }} cm</li>
                @endif
                @if($lead->lead_weight)
                    <li><strong>WEIGHT :</strong> {{ $lead->lead_weight }} kg</li>
                @endif
                @if($lead->lead_height && $lead->lead_weight)
                    <li><strong>BMI :</strong> {{ number_format($lead->lead_weight / (($lead->lead_height / 100) ** 2), 2) }}</li>
                @endif
                @if($lead->country)
                    <li><strong>NATIONALITY :</strong> {{ strtoupper($lead->country->country_name_en) }}</li>
                @endif
                @if($lead->lead_emergency_contact_name)
                    <li><strong>EMERGENCY CONTACT :</strong><br> {{ strtoupper($lead->lead_emergency_contact_name) }}</li>
                @endif
                @if($lead->lead_emergency_contact_phone)
                    <li><strong>TEL :</strong> {{ $lead->lead_emergency_contact_phone }}</li>
                @endif
            </ul>

            <div class="footer-credit">{{ $lead->staff->staff_name ?? 'VD Labour' }}</div>
        </div>

        <div class="right-col">
            <div class="top-section">
                <div class="supermarket-badge">{{ strtoupper($lead->position->position_name_en ?? $lead->jobGroup->jobgroup_name_en ?? 'GENERAL') }}</div>
                <h1 class="main-name">{{ strtoupper($lead->getFullNameAttribute()) }}</h1>

                <div class="about-box">
                    <div class="about-header">ABOUT ME</div>
                    <p class="about-text">
                        {{ $lead->lead_summary ?? 'Responsible, hardworking, and fast-learning person who can work well both independently and as part of a team.' }}
                    </p>
                </div>
            </div>

            <div class="bottom-section">
                <div class="section-header-pill">WORK EXPERIENCE</div>

                @if($lead->jobHistory && $lead->jobHistory->count() > 0)
                    @foreach($lead->jobHistory as $job)
                        <div class="timeline-item">
                            <div class="timeline-left">
                                @if($job->start_date && $job->end_date)
                                    <span class="year-badge">{{ \Carbon\Carbon::parse($job->start_date)->format('Y') }} – {{ \Carbon\Carbon::parse($job->end_date)->format('Y') }}</span>
                                    <span class="duration-text">({{ \Carbon\Carbon::parse($job->start_date)->diffInYears(\Carbon\Carbon::parse($job->end_date)) }} YEARS)</span>
                                @elseif($job->start_date)
                                    <span class="year-badge">{{ \Carbon\Carbon::parse($job->start_date)->format('Y') }} – Present</span>
                                @else
                                    <span class="year-badge">N/A</span>
                                @endif
                            </div>
                            <div class="timeline-right">
                                <div class="job-title-header">{{ strtoupper($job->company_name ?? 'N/A') }}</div>
                                @if($job->position)
                                    <div style="font-weight: 600; margin-bottom: 8px; color: var(--theme-dark);">{{ $job->position }}</div>
                                @endif
                                @if($job->description)
                                    <ul class="job-details">
                                        @foreach(explode("\n", $job->description) as $line)
                                            @if(trim($line))
                                                <li>{{ trim($line) }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>No work experience available</p>
                @endif

            </div>
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