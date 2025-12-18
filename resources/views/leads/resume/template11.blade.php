<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume - {{ $lead->fullName }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @page {
            size: A4;
            margin: 20px;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .container {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                box-shadow: none;
            }
            .template-selector {
                display: none !important;
            }
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .container {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            padding-bottom: 30px;
            overflow: hidden;
        }

        /* Define the primary color for this template (Yellow/Orange) */
        :root {
            --primary-color: #FFB700; /* Yellow-Orange */
            --secondary-color: #333; /* Dark text */
            --background-gray: #f0f0f0;
        }

        /* HEADER & PHOTO LAYOUT */
        .top-section {
            display: flex;
            align-items: flex-start;
            padding: 0;
            background-color: white;
        }
        .photo-side {
            width: 30%;
            padding: 15px 15px;
            margin: 20px;
            position: relative;
            background-color: var(--primary-color);
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }
        .profile-pic {
            width: 100%;
            max-width: 200px;
            height: auto;
            display: block;
            position: relative;
            z-index: 2;
            border: 3px solid white;
        }

        .header-side {
            width: 70%;
            padding: 20px 20px 10px 20px;
        }
        .header-side h1, .header-side h2 {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            line-height: 1.1;
        }
        .header-side h1 {
            background-color: var(--primary-color);
            padding: 12px 20px;
            border-radius: 20px;
            display: block;
            margin: 0 0 10px 0;
            color: var(--secondary-color);
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
        }
        .header-side h2 {
            color: var(--secondary-color);
            background-color: transparent;
            padding: 0;
            display: block;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 2em;
            font-weight: bold;
            text-transform: uppercase;
        }
        .profile-summary {
            margin-top: 15px;
            font-size: 0.85em;
            line-height: 1.6;
            text-align: justify;
        }
        .profile-summary p {
            margin: 5px 0;
        }

        /* MAIN CONTENT - 3 COLUMNS layout */
        .main-content {
            display: flex;
            padding: 20px 20px 20px 20px;
            gap: 30px; /* Space between columns */
        }
        
        /* EXPENSE SECTION (LEFT, WIDER) */
        .experience-column {
            width: 65%; 
        }
        /* CONTACT/PROFILE SECTION (RIGHT, NARROWER) */
        .info-column {
            width: 35%;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* SECTION TITLES */
        .section-title {
            background-color: var(--primary-color);
            color: var(--secondary-color);
            font-size: 1.1em;
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 15px;
            padding: 8px 20px;
            border-radius: 10px;
            display: inline-block;
        }
        
        /* CONTACT & PROFILE BOXES */
        .info-box {
            margin-bottom: 25px;
        }
        .info-box h3 {
            background-color: var(--primary-color);
            color: black;
            font-size: 1em;
            margin: 0 0 15px 0;
            padding: 10px 20px;
            text-align: center;
            font-weight: bold;
            border-radius: 30px;
            display: block;
            box-sizing: border-box;
        }

        .contact-details {
            font-size: 0.9em;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .contact-details div {
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
        }
        .contact-details i {
            color: black;
            margin-right: 10px;
            font-size: 1.2em;
            line-height: 1.5;
            min-width: 20px;
        }
        .profile-details ul {
            list-style-type: disc;
            padding-left: 20px;
            margin: 0;
            font-size: 0.85em;
        }
        .profile-details ul li {
            margin-bottom: 2px;
            line-height: 1.5;
        }
        .profile-details strong {
            display: inline-block;
            width: 95px; /* Aligning labels */
        }


        /* EXPERIENCE SECTION */
        .experience-item {
            margin-bottom: 25px;
        }
        .experience-item h4 {
            margin: 0;
            font-size: 1.1em;
            color: var(--secondary-color);
            font-weight: bold;
        }
        .experience-item p.duration-role {
            margin: 3px 0 5px 0;
            color: #555;
            font-weight: bold;
            font-size: 0.9em;
        }
        .experience-item ul {
            list-style-type: disc;
            padding-left: 20px;
            margin-top: 5px;
            font-size: 0.9em;
            color: #333;
        }
        .footer-note {
            position: absolute;
            bottom: 5px;
            right: 40px;
            font-size: 0.8em;
            color: #777;
            z-index: 3;
        }
        
        .company-logo {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 80px;
            height: auto;
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
            border: 2px solid #FFB700;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            background-color: white;
            color: #333;
        }
        .template-selector select:focus {
            outline: none;
            border-color: #FFA500;
        }
        .template-selector .print-btn {
            padding: 8px 16px;
            background-color: #FFB700;
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
            background-color: #FFA500;
        }
         /* Footer credit - แสดงทุกหน้า */
            .footer-note {
                position: fixed;
                bottom: 10px;
                left: 30px;
                font-size: 12px;
                color: #333 !important;
                font-weight: 500;
            }


        @media print { 
            .template-selector {
                display: none !important;
            }
            .container {
                box-shadow: none !important;
                page-break-after: auto;
            }
            .footer-note {
                position: fixed;
                bottom: 10px;
                right: 40px;
                font-size: 0.8em;
                color: #333 !important;
                z-index: 3;
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
        
        <!-- Company Logo -->
        <img src="{{ asset('logo/V dragon-02.png') }}" alt="Company Logo" class="company-logo">
        
        <div class="top-section">
            <div class="photo-side">
                @if($lead->lead_photo)
                    <img src="{{ asset('storage/' . $lead->lead_photo) }}" alt="Profile Picture" class="profile-pic">
                @else
                    <div style="width: 100%; height: 100%; background-color: #ddd; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user" style="font-size: 80px; color: #999;"></i>
                    </div>
                @endif
            </div>

            <div class="header-side">
                <h1 style="font-size: 35px"><b>{!! strtoupper($lead->lead_prefix.$lead->lead_firstname.'<br>'.$lead->lead_lastname ?? 'N/A') !!}</b></h1><br>
      
                <h2>{{ strtoupper($lead->position->position_name ?? 'N/A') }}</h2>
                <div class="profile-summary">
                    <p>{{ $lead->lead_summary ?? 'No summary available.' }}</p>
                </div>
            </div>
        </div>

        <div class="main-content">
            
            <div class="experience-column">
                <h3 class="section-title">EXPERIENCE</h3>

                @if($lead->jobHistory && $lead->jobHistory->count() > 0)
                    @foreach($lead->jobHistory->sortByDesc('start_date') as $job)
                        <div class="experience-item">
                            <h4>{{ $job->company_name }} - {{ $job->start_date ? \Carbon\Carbon::parse($job->start_date)->format('Y') : 'N/A' }} - {{ $job->end_date ? \Carbon\Carbon::parse($job->end_date)->format('Y') : 'Present' }}</h4>
                            <p class="duration-role">{{ $job->position ?? '' }}</p>
                            @if($job->description)
                                <ul>
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
                    <p>No work experience available.</p>
                @endif
            </div>

            <div class="info-column">
                
                <div class="info-box">
                    <h3>CONTACT</h3>
                    <div class="contact-details">
                        <div><i class="fas fa-phone"></i> {{ $lead->lead_phone ?? 'N/A' }}</div>
                        @if($lead->lead_email)
                        <div><i class="fas fa-envelope"></i> {{ $lead->lead_email }}</div>
                        @endif
                        <div><i class="fas fa-map-marker-alt"></i> {{ $lead->lead_address ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="info-box">
                    <h3>PROFILE</h3>
                    <div class="profile-details">
                        <ul>
                            <li><strong>Date of Birth:</strong> {{ $lead->lead_birthday ? $lead->lead_birthday->format('d M Y') : 'N/A' }}</li>
                            <li><strong>AGE:</strong> {{ $lead->lead_age ?? 'N/A' }} Years</li>
                            <li><strong>STATUS:</strong> {{ $lead->lead_marital_status ?? 'N/A' }}</li>
                            <li><strong>Height:</strong> {{ $lead->lead_height ?? 'N/A' }} cm</li>
                            <li><strong>Weight:</strong> {{ $lead->lead_weight ?? 'N/A' }} kg</li>
                            <li><strong>Shirt size:</strong> {{ $lead->lead_shirt_size ?? 'N/A' }}</li>
                            <li><strong>Pants size:</strong> {{ $lead->lead_pant_size ?? 'N/A' }}</li>
                            <li><strong>Shoe size:</strong> {{ $lead->lead_shoes_size ?? 'N/A' }}</li>
                        </ul>
                    </div>
                </div>

                @if($lead->lead_emergency_contact_name || $lead->lead_emergency_contact_phone)
                <div class="info-box">
                    <h3>EMERGENCY CONTACT</h3>
                    <div class="profile-details">
                        <ul>
                            @if($lead->lead_emergency_contact_name)
                                <li><strong>Name:</strong> {{ $lead->lead_emergency_contact_name }}</li>
                            @endif
                            @if($lead->lead_emergency_contact_phone)
                                <li><strong>Phone:</strong> {{ $lead->lead_emergency_contact_phone }}</li>
                            @endif
                            @if($lead->lead_emergency_contact_relation)
                                <li><strong>Relation:</strong> {{ $lead->lead_emergency_contact_relation }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <footer class="footer-note">
            {{ $lead->staff->staff_nickname ?? 'N/A' }} /  {{ $lead->recommenderStaff->staff_sub_name ?? '' }}
        </footer>
    </div>
</body>
</html>