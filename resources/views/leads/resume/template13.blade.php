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
            margin: -10px;
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

        /* Define the primary color for this template (Dark Blue/Teal) */
        :root {
            --primary-color: #001F4D; /* Dark Blue */
            --secondary-color: #4A90E2; /* Light Blue */
            --background-gray: #f0f0f0;
        }

        /* HEADER & PHOTO LAYOUT */
        .top-section {
            display: flex;
            align-items: flex-start;
            padding: 0; /* Remove padding here, move it to sub-elements */
            position: relative;
        }

        /* Styling for the large name/title header background */
        .name-header-bg {
            background-color: var(--primary-color);
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 200px; /* Cover the name section height */
            z-index: 1;
        }
        .name-header {
            width: 70%;
            padding: 30px 40px 10px 0;
            text-align: right;
            position: relative;
            z-index: 3;
        }
        .name-header h1 {
            color: white; /* Name text is white */
            font-size: 3em;
            font-weight: 900;
            margin: 0;
            line-height: 1.1;
            text-transform: uppercase;
        }
        .name-header h2 {
            color: var(--secondary-color); /* Subtitle in light blue */
            font-size: 1.5em;
            font-style: italic;
            font-weight: normal;
            margin: 5px 0 20px 0;
        }

        /* Image and Contact Info Column (Left Side) */
        .left-info-column {
            width: 35%;
            padding: 20px 40px 20px 40px;
            position: relative;
            z-index: 3;
            box-sizing: border-box;
        }
        
        /* The blue background shape behind the image */
        .image-bg-shape {
            position: absolute;
            top: 0;
            left: 0;
            margin: 130px 30px 80px -120px;
  
            width: 130%;
            height: 200px; 
            background-color: #003D82;
            border-radius: 0 100px 100px 0;
            transform: rotate(-30deg);
            transform-origin: top left;
            z-index: 1;
        }

        .profile-pic-wrapper {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            background-color: white; /* White border effect */
            border: 5px solid white;
            margin: 0 auto 20px auto;
            position: relative;
            z-index: 3;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .profile-pic-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* MAIN CONTENT - 2 COLUMNS layout */
        .main-content {
            display: flex;
        }
        
        /* LEFT COLUMN (NARROWER) - Contact, Profile */
        .left-column {
            width: 35%;
            padding: 0 40px 20px 40px;
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
            color: var(--secondary-color);
            font-size: 1.3em;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .contact-details {
            font-size: 0.9em;
            margin-bottom: 20px;
        }
        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        .contact-item i {
            color: var(--primary-color);
            margin-right: 10px;
            font-size: 1.2em;
        }
        
        .profile-text {
            font-size: 0.9em;
        }
        .profile-text ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            font-size: 0.85em;
        }
        .profile-text ul li {
            margin-bottom: 2px;
        }
        .profile-text strong {
             font-weight: bold;
        }

        /* EXPERIENCE SECTION */
        .experience-item {
            margin-bottom: 25px;
            position: relative;
            padding-left: 30px;
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
        .experience-item p.company {
            margin: 0 0 5px 0;
            font-size: 1em;
            color: var(--primary-color);
            font-weight: bold;
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
            left: 40px;
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
            border: 2px solid #003D82;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            background-color: white;
            color: #333;
        }
        .template-selector select:focus {
            outline: none;
            border-color: #4A90E2;
        }
        .template-selector .print-btn {
            padding: 8px 16px;
            background-color: #003D82;
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
            background-color: #4A90E2;
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
        
        <div class="name-header-bg"></div>

        <div class="top-section">
            
            <div class="left-info-column">
                <div class="image-bg-shape"></div>
                <div class="profile-pic-wrapper">
                    @if($lead->lead_photo)
                        <img src="{{ asset('storage/' . $lead->lead_photo) }}" alt="Profile Picture">
                    @else
                        <div style="width: 100%; height: 100%; background-color: #ddd; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user" style="font-size: 60px; color: #999;"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="name-header">
                <h1>{{ strtoupper($lead->lead_prefix ?? '') }} {{ strtoupper($lead->lead_firstname ?? 'N/A') }}</h1>
                <h1>{{ strtoupper($lead->lead_lastname ?? '') }}</h1>
                <h2>{{ $lead->position->position_name ?? 'N/A' }}</h2>
            </div>
        </div>

        <div class="main-content">
            
            <div class="left-column">
                <br>
                
                <div class="section contact-details">
                    <h3 class="section-title" style="color:#333; font-size:1.5em; border-bottom: none;">CONTACT</h3>
                    
                    <div class="contact-item">
                        <i class="fas fa-phone"></i> <span>{{ $lead->lead_phone ?? 'N/A' }}</span>
                    </div>
                    @if($lead->lead_email)
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i> <span>{{ $lead->lead_email }}</span>
                    </div>
                    @endif
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i> <span>{{ $lead->lead_address ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="section profile-text">
                    <h3 class="section-title" style="color:#333; font-size:1.5em; border-bottom: none;">Profile</h3>
                    <p>{{ $lead->lead_summary ?? 'No summary available.' }}</p>
                    
                    <ul>
                        <li>• Date of Birth: {{ $lead->lead_birthday ? $lead->lead_birthday->format('d M Y') : 'N/A' }}</li>
                        <li>• AGE: {{ $lead->lead_birthday ? $lead->lead_birthday->age : 'N/A' }} Years</li>
                        <li>• STATUS: {{ $lead->lead_marital_status ?? 'N/A' }}</li>
                        <li>• Height: {{ $lead->lead_height ?? 'N/A' }} cm</li>
                        <li>• Weight: {{ $lead->lead_weight ?? 'N/A' }} kg</li>
                        <li>• Shirt size: {{ $lead->lead_shirt_size ?? 'N/A' }}</li>
                        <li>• Pant size: {{ $lead->lead_pant_size ?? 'N/A' }}</li>
                        <li>• Shoes size: {{ $lead->lead_shoes_size ?? 'N/A' }}</li>
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
                <h3 class="section-title" style="color:#333; font-size:1.5em; border-bottom: none;">Work Experience</h3>

                @if($lead->jobHistory && $lead->jobHistory->count() > 0)
                    @foreach($lead->jobHistory->sortByDesc('start_date') as $job)
                        <div class="experience-item">
                            <p class="duration-role">{{ $job->start_date ? \Carbon\Carbon::parse($job->start_date)->format('Y') : 'N/A' }} - {{ $job->end_date ? \Carbon\Carbon::parse($job->end_date)->format('Y') : 'Present' }}</p>
                            <p class="company">{{ $job->company_name }}</p>
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