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
            margin: 0;
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
                height: 297mm;
                margin: 0;
                padding: 0;
                box-shadow: none;
                page-break-after: avoid;
                page-break-inside: avoid;
                overflow: hidden;
            }
            .template-selector {
                display: none !important;
            }
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            margin: -9px;
            padding: 0;
            background-color: #f7f7f7;
        }
        .container {
            width: 210mm;
            height: 297mm;
            margin: 20px auto;
            background: #fff;
            box-shadow: none;
            position: relative;
            overflow: hidden;
        }

        /* Define the primary color for this template (Maroon/Reddish-Purple) */
        :root {
            --primary-color: #793345; /* Maroon/Dark Red */
            --secondary-color: #A36B79; /* Muted Red */
            --background-gray: #f0f0f0;
        }

        /* HEADER & PHOTO LAYOUT */
        .top-section {
            display: flex;
            align-items: flex-start;
            padding: 30px 40px 10px 40px;
            background-color: white;
            position: relative;
        }

        /* Styling for the large name/title header */
        .name-header {
            width: 65%;
            padding-left: 20px;
            position: relative;
        }
        .name-header h1 {
            color: var(--primary-color);
            font-size: 3em;
            font-weight: 900;
            margin: 0;
            line-height: 1.1;
            text-transform: uppercase;
        }
        .name-header h2 {
            color: #555;
            font-size: 1.5em;
            font-weight: normal;
            margin: 5px 0 20px 0;
            padding-bottom: 15px;
            position: relative;
        }
        .name-header h1::after {
            content: '';
            margin: 10px;
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 30px;
            background: linear-gradient(to right, var(--primary-color) 0%, var(--secondary-color) 50%, #E8A5A5 100%);
            border-radius: 4px;
        }

        /* Image and Contact Info Column (Left Side) */
        .left-info-column {
            width: 35%;
            padding: 20px;
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        
        /* Background for the image section */
        .image-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%; /* Cover the entire left info column */
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-color) 30%, transparent 30%, transparent 70%, var(--secondary-color) 70%, var(--secondary-color) 100%);
            z-index: 1;
        }

        .profile-pic {
            width: 100%;
            max-width: 180px;
            height: auto;
            display: block;
            position: relative;
            z-index: 2;
            box-sizing: border-box;
        }

        /* MAIN CONTENT - 2 COLUMNS layout */
        .main-content {
            display: flex;
        }
        
        /* LEFT COLUMN (WIDER) - Contact, About Me */
        .left-column {
            width: 35%;
            padding: 20px 20px 20px 40px;
            box-sizing: border-box;
            color: #333;
            position: relative;
            z-index: 3;
        }
        /* RIGHT COLUMN (WIDER) - Experience */
        .right-column {
            width: 65%;
            padding: 0 40px 20px 20px;
            box-sizing: border-box;
            border-left: 1px solid #ddd;
        }

        /* SECTION TITLES */
        .section-title {
            color: var(--primary-color);
            font-size: 1.3em;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .contact-details, .about-me {
            font-size: 0.9em;
            margin-bottom: 20px;
        }
        .contact-details {
            color: #793345;
            position: relative;
            z-index: 2;
        }
        .contact-details strong, .contact-details h3 {
            color: #793345;
        }
        .contact-details p, .contact-details i {
            color: #793345;
        }
        .about-me {
            color: #333; /* Below the background, text should be dark */
        }
        .about-me ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            font-size: 0.85em;
        }
        .about-me ul li {
            margin-bottom: 2px;
        }

        /* EXPERIENCE SECTION */
        .experience-item {
            margin-bottom: 25px;
            position: relative;
            padding-left: 20px;
        }
        .experience-item h4 {
            margin: 0;
            font-size: 1.1em;
            color: #333;
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
        
        /* Styles for the circle markers in the Experience section (Timeline look) */
        .experience-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 5px;
            width: 10px;
            height: 10px;
            background-color: var(--primary-color);
            border-radius: 50%;
            border: 2px solid white; 
            box-shadow: 0 0 0 2px var(--primary-color); 
        }
        
        .footer-note {
            position: absolute;
            bottom: 10px;
            left: 10px;
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
        
        .footer-note::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 100%;
            transform: translateY(-50%);
            width: calc(210mm - 40px - 100%);
            height: 20px;
            background: linear-gradient(to right, #E8A5A5 0%, var(--secondary-color) 50%, var(--primary-color) 100%);
            border-radius: 4px;
            z-index: 1;
            margin-left: 20px;
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
            border: 2px solid #793345;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            background-color: white;
            color: #333;
        }
        .template-selector select:focus {
            outline: none;
            border-color: #A36B79;
        }
        .template-selector .print-btn {
            padding: 8px 16px;
            background-color: #793345;
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
            background-color: #A36B79;
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
            
            <div class="left-info-column">
                <div class="image-bg"></div>
                @if($lead->lead_photo)
                    <img src="{{ asset('storage/' . $lead->lead_photo) }}" alt="Profile Picture" class="profile-pic">
                @else
                    <div style="width: 200px; height: 200px; background-color: #ddd; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user" style="font-size: 80px; color: #999;"></i>
                    </div>
                @endif
            </div>

            <div class="name-header">
                <h1>{{ strtoupper($lead->lead_prefix ?? '') }} {{ strtoupper($lead->lead_firstname ?? 'N/A') }}</h1>
                <h1>{{ strtoupper($lead->lead_lastname ?? '') }}</h1>
                <h2 style="color:var(--primary-color);">{{ strtoupper($lead->position->position_name ?? 'N/A') }}</h2>
            </div>
        </div>

        <div class="main-content">
            
            <div class="left-column">
                
                <div class="section contact-details">
                    <h3 class="section-title" style="color:#793345; border-bottom: 2px solid #793345;">Contact</h3>
                    <p style="margin: 5px 0; color: #793345;"><i class="fas fa-phone"></i> {{ $lead->lead_phone ?? 'N/A' }}</p>
                    @if($lead->lead_email)
                    <p style="margin: 5px 0; color: #793345;"><i class="fas fa-envelope"></i> {{ $lead->lead_email }}</p>
                    @endif
                    <p style="margin-top:5px; color: #793345;"><i class="fas fa-map-marker-alt"></i> {{ $lead->lead_address ?? 'N/A' }}</p>
                </div>

                <div class="section about-me" style="color:#333;">
                    <h3 class="section-title" style="color:var(--primary-color); border-bottom: 2px solid var(--primary-color);">About Me</h3>
                    <p>{{ $lead->lead_summary ?? 'No summary available.' }}</p>
                    
                    <ul>
                        <li>• Date of Birth: {{ $lead->lead_birthday ? $lead->lead_birthday->format('d M Y') : 'N/A' }}</li>
                        <li>• AGE: {{ $lead->lead_birthday ? $lead->lead_birthday->age : 'N/A' }} Years</li>
                        <li>• STATUS: {{ $lead->lead_marital_status ?? 'N/A' }}</li>
                        <li>• Height: {{ $lead->lead_height ?? 'N/A' }} cm</li>
                        <li>• Weight: {{ $lead->lead_weight ?? 'N/A' }} kg</li>
                        <li>• Shirt size: {{ $lead->lead_shirt_size ?? 'N/A' }}</li>
                        <li>• Pants size: {{ $lead->lead_pant_size ?? 'N/A' }}</li>
                        <li>• Shoe size: {{ $lead->lead_shoes_size ?? 'N/A' }}</li>
                        @if($lead->lead_emergency_contact_name)
                        <li>• Emergency Contact: {{ $lead->lead_emergency_contact_name }}</li>
                        @endif
                        @if($lead->lead_emergency_contact_phone)
                        <li>• Emergency Tel: {{ $lead->lead_emergency_contact_phone }}</li>
                        @endif
                        @if($lead->lead_emergency_contact_relation)
                        <li>• Relation: {{ $lead->lead_emergency_contact_relation }}</li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="right-column">
                <h3 class="section-title">Work Experience</h3>

                @if($lead->jobHistory && $lead->jobHistory->count() > 0)
                    @foreach($lead->jobHistory as $job)
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
        </div>
        
        <div class="footer-note">
            {{ $lead->staff->staff_name ?? 'N/A' }} / {{ $lead->recommenderStaff->staff_sub_name ?? 'N/A' }}
        </div>
    </div>
</body>
</html>