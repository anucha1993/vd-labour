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
            font-size: 16px;
        }

        /* A4 Size Container */
        .container {
            width: 210mm;
            min-height: 297mm;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        /* Orange Header Bar */
        .top-bar {
            background: linear-gradient(135deg, #F57C00 0%, #FF9800 100%);
            height: 30px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        /* Orange Footer Bar */
        .bottom-bar {
            background: linear-gradient(135deg, #F57C00 0%, #FF9800 100%);
            height: 40px;
            width: 100%;
            position: absolute;
            bottom: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        /* Brown Decorative Shapes */
        .left-brown-bar {
            position: absolute;
            top: 30px;
            left: 0;
            width: 25px;
            height: 280px;
            background-color: #8B4513;
            z-index: 2;
        }

        .brown-accent-1 {
            position: absolute;
            top: 210px;
            right: 0;
            width: 250px;
            height: 100px;
            background-color: #D2691E;
            z-index: 1;
        }

        /* Logo */
        .logo-vd {
            position: absolute;
            top: 5px;
            right: 15px;
            width: 50px;
            height: auto;
            z-index: 10;
        }

        .logo-vd img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Profile Image */
        .profile-wrapper {
            position: absolute;
            top: 50px;
            left: 40px;
            z-index: 10;
        }

        .profile-frame {
            width: 180px;
            height: 210px;
            overflow: hidden;
            background-color: #D2691E;
            border: 5px solid #D2691E;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .profile-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        /* Header Section */
        .header {
            position: relative;
            padding: 50px 40px 20px 240px;
            z-index: 2;
        }

        .header h1 {
            font-family: 'Roboto', sans-serif;
            color: #D2691E;
            font-size: 42px;
            font-weight: 900;
            text-transform: uppercase;
            line-height: 1.2;
            margin: 0 0 15px 0;
            letter-spacing: 1px;
        }

        .header .position-badge {
            background: linear-gradient(135deg, #FFB84D 0%, #FFA726 100%);
            color: #000;
            font-size: 16px;
            font-weight: 700;
            padding: 8px 20px;
            display: inline-block;
            text-transform: uppercase;
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
            width: 35%;
            color: #333;
        }

        .right-column {
            width: 65%;
        }

        /* Section Titles */
        .section-title {
            color: #8B4513;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
            margin-top: 20px;
        }

        .section-title:first-child {
            margin-top: 0;
        }

        /* Contact Section */
        .contact-details {
            margin-bottom: 20px;
        }

        .contact-details .phone {
            color: #D2691E;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-details .address {
            font-size: 13px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .contact-details i {
            color: #D2691E;
            margin-top: 2px;
        }

        /* Profile Section */
        .profile-text {
            margin-bottom: 20px;
        }

        .profile-text p {
            font-size: 13px;
            line-height: 1.6;
            text-align: justify;
            margin-bottom: 15px;
            color: #8B4513;
        }

        .profile-text ul {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 12px;
        }

        .profile-text ul li {
            margin-bottom: 5px;
            line-height: 1.4;
            position: relative;
            padding-left: 15px;
            color: #333;
        }

        .profile-text ul li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: #D2691E;
            font-size: 14px;
            font-weight: bold;
        }

        .profile-text ul li strong {
            font-weight: 600;
            color: #000;
        }

        /* Experience Section */
        .experience-title {
            color: #8B4513;
            font-size: 20px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .experience-item {
            margin-bottom: 25px;
            position: relative;
            padding-left: 20px;
            border-left: 2px solid #D2691E;
        }

        .experience-item::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 5px;
            width: 10px;
            height: 10px;
            background-color: #D2691E;
            border-radius: 50%;
        }

        .experience-header {
            margin-bottom: 8px;
        }

        .experience-item h4 {
            margin: 0 0 5px 0;
            font-size: 15px;
            color: #000;
            font-weight: 700;
        }

        .experience-item .company-info {
            font-size: 14px;
            color: #D2691E;
            font-weight: 600;
            font-style: italic;
            margin-bottom: 5px;
        }

        .experience-item .duration {
            font-size: 14px;
            color: #D2691E;
            font-weight: 700;
            float: right;
            margin-top: -35px;
        }

        .experience-item ul {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 12px;
            line-height: 1.6;
            clear: both;
        }

        .experience-item ul li {
            margin-bottom: 4px;
            position: relative;
            padding-left: 15px;
            color: #333;
        }

        .experience-item ul li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: #D2691E;
            font-weight: bold;
        }

        /* Footer Note */
        .footer-note {
            position: absolute;
            bottom: 20px;
            left: 40px;
            font-size: 14px;
            color: #C55621;
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
            border: 2px solid #F57C00;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            background-color: white;
            color: #333;
        }
        .template-selector select:focus {
            outline: none;
            border-color: #FF9800;
        }
        .template-selector .print-btn {
            padding: 8px 16px;
            background-color: #F57C00;
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
            background-color: #E65100;
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
            .top-bar,
            .bottom-bar,
            .left-brown-bar,
            .brown-accent-1 {
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
        <!-- Orange Top Bar -->
        <div class="top-bar"></div>

        <!-- Logo -->
        <div class="logo-vd">
            <img src="{{ asset('logo/V dragon-02.png') }}" alt="VD Logo">
        </div>

        <!-- Brown Decorative Elements -->
        <div class="left-brown-bar"></div>
        <div class="brown-accent-1"></div>

        <!-- Profile Image -->
        <div class="profile-wrapper">
            <div class="profile-frame">
                @if($lead->lead_photo)
                    <img src="{{ asset('storage/' . $lead->lead_photo) }}" alt="Profile Photo">
                @else
                    <img src="https://via.placeholder.com/350x450/cccccc/ffffff?text=PHOTO" alt="Profile Photo">
                @endif
            </div>
        </div>

        <!-- Header with Name -->
        <div class="header">
            <h1>MR.{{ strtoupper($lead->fullName ?? 'NAME') }}</h1>
            @if($lead->position)
                <div class="position-badge">{{ $lead->position->position_name }}</div>
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
                        <div class="phone">
                            <i class="fas fa-phone"></i>
                            {{ $lead->lead_phone }}
                        </div>
                    @endif
                    @if($lead->lead_address)
                        <div class="address">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $lead->lead_address }}</span>
                        </div>
                    @endif
                </div>

                <!-- Profile Section -->
                <h3 class="section-title">Profile</h3>
                <div class="profile-text">
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
                    @foreach($lead->jobHistory as $job)
                    <div class="experience-item">
                        <div class="experience-header">
                            @if($job->company_name || $job->position)
                            <h4>{{ $job->position ?? 'Position' }}</h4>
                            @endif
                            @if($job->company_name)
                            <div class="company-info">{{ $job->company_name }}@if($lead->country) ({{ $lead->country->country_name }})@endif</div>
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
        
        <!-- Orange Bottom Bar with Staff Name -->
        <div class="bottom-bar">
            @if($lead->staff)
                {{ $lead->staff->staff_name }}
            @endif
        </div>
    </div>

</body>
</html>