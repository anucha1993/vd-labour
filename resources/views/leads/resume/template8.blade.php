<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume - {{ $lead->fullName }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            padding: 20px;
            font-size: 14px;
        }

        /* A4 Size Container */
        .container {
            width: 210mm;
            min-height: 297mm;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Decorative Background Shapes */
        .top-left-shape {
            position: absolute;
            top: 0;
            left: 0;
            width: 450px;
            height: 200px;
            background: linear-gradient(135deg, #8b95b8 0%, #a8b0c9 50%, transparent 100%);
            clip-path: polygon(0 0, 100% 0, 50% 100%, 0 80%);
            z-index: 1;
        }

        .top-right-shape {
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 150px;
            background: linear-gradient(225deg, #8b95b8 0%, #a8b0c9 50%, transparent 100%);
            clip-path: polygon(50% 0, 100% 0, 100% 100%, 70% 100%);
            z-index: 1;
        }

        .bottom-left-shape {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200px;
            height: 150px;
            background: linear-gradient(45deg, #8b95b8 0%, #a8b0c9 50%, transparent 100%);
            clip-path: polygon(0 100%, 100% 100%, 50% 0, 0 30%);
            z-index: 1;
        }

        .bottom-right-shape {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 350px;
            height: 120px;
            background: linear-gradient(315deg, #8b95b8 0%, #a8b0c9 50%, transparent 100%);
            clip-path: polygon(50% 0, 100% 30%, 100% 100%, 0 100%);
            z-index: 1;
        }

        /* Profile Image */
        .profile-wrapper {
            position: absolute;
            top: 30px;
            left: 40px;
            z-index: 10;
        }

        .profile-circle {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            overflow: hidden;
            background-color: #4A90E2;
            border: 8px solid white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .profile-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Logo */
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

        /* Header Section */
        .header {
            position: relative;
            padding: 40px 40px 20px 240px;
            z-index: 2;
        }

        .header h1 {
            font-family: 'Roboto', sans-serif;
            color: #4A90E2;
            font-size: 48px;
            font-weight: 900;
            text-transform: uppercase;
            line-height: 1.1;
            margin: 0;
            letter-spacing: 2px;
        }

        .header h2 {
            color: #000;
            font-size: 22px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 10px 0 0 0;
            letter-spacing: 1px;
        }

        /* Main Content (2 Columns) */
        .main-content {
            display: flex;
            position: relative;
            z-index: 2;
            padding: 20px 40px 60px 40px;
            gap: 30px;
        }

        .left-column {
            width: 38%;
            color: #333;
        }

        .right-column {
            width: 62%;
        }

        /* Section Titles */
        .section-title {
            color: #4A90E2;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
            margin-top: 0;
        }

        /* Contact Section */
        .contact-details {
            margin-bottom: 30px;
        }

        .contact-details .phone {
            color: #4A90E2;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .contact-details p {
            font-size: 13px;
            line-height: 1.6;
            color: #333;
            margin: 0;
        }

        /* About Me / Profile Section */
        .about-me {
            margin-bottom: 30px;
        }

        .about-me p {
            font-size: 13px;
            line-height: 1.6;
            text-align: justify;
            margin-bottom: 15px;
        }

        .about-me ul {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 12px;
        }

        .about-me ul li {
            margin-bottom: 6px;
            line-height: 1.4;
            position: relative;
            padding-left: 15px;
        }

        .about-me ul li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: #4A90E2;
            font-size: 16px;
            font-weight: bold;
        }

        .about-me ul li strong {
            font-weight: 500;
        }

        /* Summary Box */
        .summary-box {
            background-color: #e0e7ff;
            padding: 20px;
            margin-bottom: 30px;
            font-size: 14px;
            line-height: 1.5;
            text-align: justify;
            border-radius: 20px;
            border: 1px solid #c7d2fe;
        }

        /* Experience Section */
        .experience-title {
            color: #4A90E2;
            font-size: 20px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .experience-item {
        
            margin-bottom: 25px;
            position: relative;
            padding-left: 25px;
        }

        .experience-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: px;
            width: 12px;
            height: 12px;
            background-color: #000;
            border-radius: 50%;
            border: 2px solid #000;
        }

        .experience-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
            margin-top: 8px;
        }

        .experience-item h4 {
            margin: 0;
            font-size: 15px;
            color: #000;
            font-weight: 700;
        }

        .experience-item .company-location {
            font-size: 13px;
            color: #000;
            font-weight: 500;
            margin-bottom: 8px;
       
        }

        .experience-item .duration {
            font-size: 14px;
            color: #000;
            font-weight: 700;
            white-space: nowrap;
             margin-bottom: 8px;
            
        }

        .experience-item ul {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 12px;
            line-height: 1.6;
        }

        .experience-item ul li {
            margin-bottom: 4px;
            position: relative;
            padding-left: 15px;
            color: #4A90E2;
        }

        .experience-item ul li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: #4A90E2;
            font-weight: bold;
        }

        /* Footer Note */
        .footer-note {
            position: absolute;
            bottom: 20px;
            left: 40px;
            font-size: 14px;
            color: #4A90E2;
            font-weight: 700;
            z-index: 10;
        }

        /* Template Selector Styles */
        .template-selector {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .template-selector select {
            padding: 8px 12px;
            border: 2px solid #4A90E2;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            background-color: white;
            color: #333;
        }
        .template-selector select:focus {
            outline: none;
            border-color: #357ABD;
        }
        .template-selector .print-btn {
            padding: 8px 16px;
            background-color: #4A90E2;
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
            background-color: #357ABD;
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

        /* Print Styles */
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .template-selector {
                display: none !important;
            }
            .container {
                box-shadow: none;
                margin: 0;
                width: 100%;
                min-height: auto;
            }
            .top-left-shape,
            .top-right-shape,
            .bottom-left-shape,
            .bottom-right-shape {
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

    <div class="container">
        <!-- Logo -->
        <div class="logo-vd">
            <img src="{{ asset('logo/V dragon-02.png') }}" alt="VD Logo">
        </div>

        <!-- Decorative Shapes -->
        <div class="top-left-shape"></div>
        <div class="top-right-shape"></div>
        <div class="bottom-left-shape"></div>
        <div class="bottom-right-shape"></div>

        <!-- Profile Image -->
        <div class="profile-wrapper">
            <div class="profile-circle">
                @if($lead->lead_photo)
                    <img src="{{ asset('storage/' . $lead->lead_photo) }}" alt="Profile Photo">
                @else
                    <img src="https://via.placeholder.com/350x450/cccccc/ffffff?text=PHOTO" alt="Profile Photo">
                @endif
            </div>
        </div>

        <!-- Header with Name -->
        <div class="header">
            <h1>{{ strtoupper($lead->fullName ?? 'NAME') }}</h1>
            @if($lead->position)
                <h2>{{ strtoupper($lead->position->position_name) }}</h2>
            @endif
        </div>

        <!-- Main Content -->
        <div class="main-content">
            
            <!-- Left Column -->
            <div class="left-column">
                
                <!-- Contact Section -->
                <h3 class="section-title">Contact</h3>
                <div class="contact-details">
                    @if($lead->lead_phone)
                        <div class="phone">{{ $lead->lead_phone }}</div>
                    @endif
                    @if($lead->lead_address)
                        <p>{{ $lead->lead_address }}</p>
                    @endif
                </div>

                <!-- About Me Section -->
                <h3 class="section-title">About me</h3>
                <div class="about-me">
                    @if($lead->lead_summary)
                        <p>{{ $lead->lead_summary }}</p>
                    @endif
                    
                    <ul>
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
                        <li><strong>Hight :</strong> {{ $lead->lead_height }}</li>
                        @endif
                        @if($lead->lead_weight)
                        <li><strong>Weight :</strong> {{ $lead->lead_weight }}</li>
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
                </div>
            </div>

            <!-- Right Column -->
            <div class="right-column">
                
                <h3 class="experience-title">Experience</h3>

                @if($lead->jobHistory && $lead->jobHistory->count() > 0)
                    @foreach($lead->jobHistory->sortByDesc('start_date') as $job)
                    <div class="experience-item">
                        <div class="experience-header">
                            <div>
                                @if($job->company_name)
                                <h4>{{ $job->company_name }}</h4>
                                @endif
                                @if($job->position)
                                <div class="company-location">{{ $job->position }}@if($lead->country) / {{ $job->position }} - {{ $lead->country->country_name }}@endif</div>
                                @endif
                            </div>
                            @php
                                $startDate = $job->start_date ? \Carbon\Carbon::parse($job->start_date) : null;
                                $endDate = $job->end_date ? \Carbon\Carbon::parse($job->end_date) : null;
                            @endphp
                            @if($startDate || $endDate)
                                <div class="duration">
                                    @if($startDate && $endDate)
                                        {{ $startDate->format('Y') }} - {{ $endDate->format('Y') }}
                                    @elseif($startDate)
                                        {{ $startDate->format('Y') }} - Present
                                    @else
                                        {{ $job->experience_years ? $job->experience_years . ' Years' : '' }}
                                    @endif
                                </div>
                            @endif
                        </div>
                        @if($job->description)
                        <ul>
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
        
        <!-- Footer -->
        <div class="footer-note">
            @if($lead->staff)
                {{ $lead->staff->staff_nickname }}/{{ $lead->recommenderStaff->staff_sub_name ?? '' }}
            @endif
        </div>
    </div>

</body>
</html>