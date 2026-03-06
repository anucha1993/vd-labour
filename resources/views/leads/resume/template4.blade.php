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
            padding: 20px 15px 20px 20px;
            color: var(--text-grey);
            border-right: 1px solid var(--border-grey);
        }
        
        /* กรอบรูป */
        .photo-frame {
            width: 100%;
            margin-bottom: 12px;
            padding: 3px;
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
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid var(--text-dark);
            width: 80%;
            margin-top: 15px;
        }
        
        /* Contact */
        .contact-list {
            list-style: none;
            font-size: 11px;
        }

        .contact-list li {
            margin-bottom: 6px;
            display: flex;
            align-items: flex-start;
        }

        .contact-list i {
            color: var(--theme-blue);
            margin-right: 10px;
            font-size: 14px;
            margin-top: 2px;
            width: 16px;
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
            font-size: 11px;
        }

        .about-me-list li {
            margin-bottom: 4px;
            line-height: 1.3;
        }
        
        .about-me-list strong {
            color: var(--text-dark);
            font-weight: 500;
            display: inline-block;
            min-width: 95px;
        }

        /* Skills List */
        .skills-list {
            list-style: none;
            padding-left: 0;
            font-size: 11px;
        }
        
        .skills-list li {
            position: relative;
            padding-left: 12px;
            margin-bottom: 4px;
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
            padding: 20px 20px 20px 15px;
            color: var(--text-grey);
        }

        /* ส่วนหัวชื่อ */
        .main-header {
            margin-bottom: 12px;
            padding-top: 50px;
        }

        .main-name {
            font-family: 'Roboto', sans-serif;
            font-size: 26px;
            color: var(--text-dark);
            text-transform: uppercase;
            font-weight: 700;
            line-height: 1.1;
        }
        
        .supermarket-tag {
            font-size: 13px;
            color: var(--text-grey);
            font-weight: 500;
            margin-top: 3px;
            margin-bottom: 6px;
        }
        
        .summary-text {
            font-size: 11px;
            line-height: 1.4;
            margin-bottom: 15px;
            text-align: justify;
        }

        /* หัวข้อหลัก Experience */
        .main-section-title {
            color: var(--text-dark);
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 10px;
            padding-bottom: 4px;
            border-bottom: 2px solid var(--theme-blue);
        }
        
        /* Job Item Container */
        .job-item {
            margin-bottom: 12px;
        }

        /* ส่วนหัวข้องาน */
        .job-info {
            margin-bottom: 5px;
            background-color: var(--light-grey-bg);
            padding: 5px 8px;
        }
        
        .job-info strong {
            font-size: 12px;
            color: var(--text-dark);
            font-weight: 500;
            display: block;
            margin-bottom: 2px;
        }

        .job-details-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .job-role {
            color: var(--theme-blue);
            font-weight: 500;
            font-size: 11px;
        }

        .job-date {
            color: var(--text-grey);
            font-size: 11px;
        }

        /* รายละเอียดงาน */
        .job-duties {
            list-style: none;
            padding-left: 0;
            font-size: 11px;
            line-height: 1.3;
        }

        .job-duties li {
            position: relative;
            padding-left: 12px;
            margin-bottom: 3px;
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

    <div id="resume-wrapper">
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
        
        <footer class="footer-credit">{{ $lead->staff->staff_nickname ?? 'VD Labour' }}/ {{ $lead->recommenderStaff->staff_sub_name ?? '' }}</footer>
    </div>
    </div><!-- end resume-wrapper -->

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

        function fitToOnePage() {
            const container = document.querySelector('.resume-container');
            const wrapper = document.getElementById('resume-wrapper');
            if (!container || !wrapper) return;

            wrapper.style.width = '210mm';
            wrapper.style.height = '297mm';
            wrapper.style.overflow = 'hidden';
            wrapper.style.margin = '0 auto';
            const a4Height = wrapper.offsetHeight;

            container.style.zoom = '1';
            container.style.width = '210mm';

            const contentHeight = container.scrollHeight;

            let zoomLevel = 1;
            if (contentHeight > a4Height) {
                zoomLevel = Math.floor((a4Height / contentHeight) * 1000) / 1000;
            }

            let dynamicStyle = document.getElementById('dynamic-zoom-style');
            if (!dynamicStyle) {
                dynamicStyle = document.createElement('style');
                dynamicStyle.id = 'dynamic-zoom-style';
                document.head.appendChild(dynamicStyle);
            }
            dynamicStyle.textContent = 
                '.resume-container { zoom: ' + zoomLevel + ' !important; }' +
                '#resume-wrapper { width: 210mm !important; height: 297mm !important; overflow: hidden !important; margin: 0 auto !important; }' +
                '@media print { ' +
                '  .resume-container { zoom: ' + zoomLevel + ' !important; }' +
                '  #resume-wrapper { width: 210mm !important; height: 297mm !important; overflow: hidden !important; margin: 0 !important; }' +
                '}';
        }

        window.addEventListener('load', fitToOnePage);
    </script>

    <style>
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
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                position: relative;
            }
            
            /* คงค่าโลโก้และตกแต่ง */
            .company-logo {
                position: absolute;
                top: 10px;
                right: 30px;
                width: 60px;
                height: auto;
                z-index: 1000;
            }
            
            .logo-vd {
                position: absolute;
                top: 10px;
                right: 15px;
                font-size: 14px;
                color: #d66428 !important;
                font-weight: bold;
            }
            
            /* คอลัมน์ซ้าย */
            .left-col {
                width: 38% !important;
                flex: 0 0 38% !important;
                max-width: 38% !important;
                padding: 20px 15px 20px 20px;
                border-right: 1px solid #ddd !important;
            }
            
            /* คอลัมน์ขวา */
            .right-col {
                width: 62% !important;
                flex: 0 0 62% !important;
                max-width: 62% !important;
                padding: 20px 20px 20px 15px;
            }
            
            /* รูปโปรไฟล์ */
            .photo-frame {
                width: 100%;
                margin-bottom: 25px;
            }
            
            .profile-img {
                width: 100%;
                max-width: 200px;
                height: auto;
                border-radius: 50%;
                border: 4px solid #8B4513 !important;
                object-fit: cover;
            }
            
            /* หัวข้อ section */
            .section-title {
                background-color: #8B4513 !important;
                color: white !important;
                padding: 10px 15px;
                font-weight: 700;
                font-size: 16px;
                text-transform: uppercase;
                margin-bottom: 15px;
                margin-top: 25px;
            }
            
            .section-title i {
                color: white !important;
                margin-right: 10px;
            }
            
            /* ข้อมูล Profile */
            .info-row {
                font-size: 13px;
                line-height: 1.8;
                margin-bottom: 8px;
            }
            
            .info-label {
                font-weight: 700;
                color: #5D4037 !important;
                min-width: 120px;
            }
            
            /* ส่วนหัวชื่อขวา */
            .name-header h1 {
                font-size: 42px;
                color: #8B4513 !important;
                font-weight: 700;
                text-transform: uppercase;
                line-height: 1.1;
                margin-bottom: 10px;
            }
            
            .name-header .subtitle {
                color: #6D4C41 !important;
                font-size: 16px;
                font-weight: 400;
                margin-bottom: 30px;
            }
            
            /* Summary box */
            .summary-box {
                background-color: #f5f5f5 !important;
                border-left: 4px solid #8B4513 !important;
                padding: 15px 20px;
                margin-bottom: 30px;
                font-size: 14px;
                line-height: 1.6;
                color: #6D4C41 !important;
            }
            
            /* Work Experience */
            .job-item {
                background-color: #f5f5f5 !important;
                padding: 15px 20px;
                margin-bottom: 15px;
                border-left: 4px solid #8B4513 !important;
                page-break-inside: avoid;
            }
            
            .job-header {
                display: flex;
                justify-content: space-between;
                align-items: baseline;
                margin-bottom: 8px;
            }
            
            .job-title {
                font-weight: 700;
                color: #8B4513 !important;
                font-size: 16px;
            }
            
            .job-period {
                font-size: 13px;
                color: #6D4C41 !important;
                font-style: italic;
            }
            
            .company-name {
                color: #6D4C41 !important;
                font-weight: 500;
                margin-bottom: 8px;
                font-size: 14px;
            }
            
            .job-desc {
                font-size: 13px;
                color: #6D4C41 !important;
                line-height: 1.6;
                padding-left: 15px;
            }
            
            /* Contact items */
            .contact-item {
                font-size: 13px;
                margin-bottom: 10px;
                page-break-inside: avoid;
            }
            
            .contact-item i {
                color: #8B4513 !important;
                margin-right: 10px;
                width: 20px;
            }
            
            /* ป้องกันการแบ่งหน้า */
            .info-row, .contact-item {
                page-break-inside: avoid;
            }
            
            /* Footer credit */
            .footer-credit {
                position: fixed;
                bottom: 15px;
                right: 30px;
                font-size: 12px;
                color: #555 !important;
            }
        }
    </style>

</body>
</html>