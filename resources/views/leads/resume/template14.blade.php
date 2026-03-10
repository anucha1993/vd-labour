<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume - {{ $lead->fullName }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
            display: flex;
            flex-direction: column;
        }

        /* Define the primary color for this template (Dark Blue/Grey) */
        :root {
            --primary-color: #011E48; /* Dark Navy Blue */
            --secondary-color: #A9A9A9; /* Mid Grey */
            --tertiary-color: #F8DA1D; /* Yellow/Gold for highlight */
        }

        /* HEADER & PHOTO LAYOUT */
        .header-section {
            display: flex;
            background-color: white;
            padding-bottom: 0;
            flex: 1;
        }

        /* Left Column: Photo and Profile Text */
        .left-column {
            width: 35%;
            background-color: var(--primary-color);
            padding: 20px 15px 15px 15px;
            box-sizing: border-box;
            color: white;
            position: relative;
            z-index: 2;
        }
        
        .profile-pic-wrapper {
            width: 100%;
            max-width: 160px;
            height: auto;
            margin: 0 auto 15px auto;
            background-color: white;
            border: 3px solid white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        .profile-pic-wrapper img {
            width: 100%;
            height: 100%;
            display: block;
        }

        /* Right Column: Name, Title, Experience */
        .right-column {
            width: 65%;
            background-color: white;
            padding: 20px 25px 20px 25px;
            box-sizing: border-box;
            position: relative;
            z-index: 2;
        }
        
        .name-header {
            background-color: var(--secondary-color);
            padding: 12px;
            margin: -20px -25px 15px -25px;
        }
        
        .name-header h1 {
            color: white;
            font-family: 'Georgia', serif;
            font-size: 1.8em;
            font-weight: bold;
            margin: 0;
            line-height: 1.1;
            text-transform: uppercase;
        }
        .name-header h2 {
            color: white;
            font-size: 0.9em;
            font-weight: normal;
            margin: 3px 0 10px 0;
            text-transform: uppercase;
        }
        
        /* Logos/Top Icons */
        .logo-vd {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 50px; /* Placeholder size */
            height: 50px;
            background-color: var(--tertiary-color); /* Yellow background */
            border-radius: 5px;
            z-index: 3;
            text-align: center;
            line-height: 50px;
            font-size: 0.8em;
            color: var(--primary-color);
            font-weight: bold;
        }

        /* Contact Bar (Bottom of Page) */
        .contact-bar {
            background-color: var(--tertiary-color);
            padding: 8px 25px;
            color: var(--primary-color);
            font-size: 0.85em;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 4;
        }
        .contact-bar span {
            display: flex;
            align-items: center;
        }
        .contact-bar i {
            margin-right: 8px;
            font-size: 1em;
        }
        /* Using a simple icon placeholder for display purposes */
        .icon-phone:before { content: "📞"; }
        .icon-map:before { content: "🏠"; }


        /* PROFILE SECTION (Left Column) */
        .section-title {
            font-size: 1.1em;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 6px;
            border-bottom: 2px solid white;
            padding-bottom: 3px;
            color: white;
        }
        .profile-text p {
            font-size: 0.8em;
            line-height: 1.4;
        }
        .profile-text ul {
            list-style-type: disc;
            padding-left: 15px;
            margin-top: 6px;
            font-size: 0.75em;
        }
        .profile-text ul li {
            margin-bottom: 5px;
            list-style-type: none; /* Removing disc for the list */
            text-indent: -0.5em;
        }
        .profile-text ul li:before {
            content: '• ';
            color: white;
        }
        .footer-note {
            font-size: 0.7em;
            color: white;
            text-align: center;
            margin-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            padding-top: 6px;
        }

        /* EXPERIENCE SECTION (Right Column) */
        .experience-title {
            font-size: 1.1em;
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 10px;
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 3px;
        }
        .experience-item {
            margin-bottom: 12px;
        }
        .experience-item h4 {
            margin: 0;
            font-size: 0.9em;
            color: var(--primary-color);
            font-weight: bold;
        }
        .experience-item p.duration {
            margin: 2px 0 3px 0;
            color: #555;
            font-weight: bold;
            font-size: 0.8em;
        }
        .experience-item ul {
            list-style-type: disc;
            padding-left: 15px;
            margin-top: 3px;
            font-size: 0.8em;
            color: #333;
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
            border: 2px solid #011E48;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            background-color: white;
            color: #333;
        }
        .template-selector select:focus {
            outline: none;
            border-color: #A9A9A9;
        }
        .template-selector .print-btn {
            padding: 8px 16px;
            background-color: #011E48;
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
            background-color: #A9A9A9;
        }

        @media print {
            .template-selector {
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
                background-color: white;
                padding: 0;
                margin: 0;
            }

            .container {
                box-shadow: none;
                margin: 0;
                width: 210mm !important;
                min-height: auto;
            }

            .header-section {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
            }

            .left-column {
                width: 35% !important;
                flex: 0 0 35% !important;
                max-width: 35% !important;
                background-color: var(--primary-color) !important;
            }

            .right-column {
                width: 65% !important;
                flex: 0 0 65% !important;
                max-width: 65% !important;
            }

            .name-header {
                background-color: var(--secondary-color) !important;
            }

            .contact-bar {
                background-color: var(--tertiary-color) !important;
            }

            .footer-note {
                color: white !important;
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

        function fitToOnePage() {
            const container = document.querySelector('.container');
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
                '.container { zoom: ' + zoomLevel + ' !important; }' +
                '#resume-wrapper { width: 210mm !important; height: 297mm !important; overflow: hidden !important; margin: 0 auto !important; }' +
                '@media print { ' +
                '  .container { zoom: ' + zoomLevel + ' !important; }' +
                '  #resume-wrapper { width: 210mm !important; height: 297mm !important; overflow: hidden !important; margin: 0 !important; }' +
                '}';
        }

        window.addEventListener('load', fitToOnePage);
    </script>
    <div id="resume-wrapper">
    <div class="container">
        
        <!-- Company Logo -->
        <img src="{{ asset('logo/V dragon-02.png') }}" alt="Company Logo" class="company-logo">
        
        <div class="header-section">
            
            <div class="left-column">
                <div class="profile-pic-wrapper">
                    @if($lead->lead_photo)
                        <img src="{{ asset('storage/' . $lead->lead_photo) }}" alt="Profile Picture">
                    @else
                        <div style="width: 100%; height: 100%; background-color: #ddd; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user" style="font-size: 60px; color: #999;"></i>
                        </div>
                    @endif
                </div>

                <div class="profile-text">
                    <h3 class="section-title">PROFILE</h3>
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
                
                <footer class="footer-note">
                    {{ $lead->staff->staff_nickname ?? 'N/A' }} / {{ $lead->recommenderStaff->staff_sub_name ?? '' }}
                </footer>
            </div>

            <div class="right-column">
               
                <div class="name-header">
                    <h1 style="color:white; font-family:serif;">{{ strtoupper($lead->lead_prefix ?? '') }}.{{ strtoupper($lead->lead_firstname ?? 'N/A') }}</h1>
                    <h1 style="color:white; font-family:serif;">{{ strtoupper($lead->lead_lastname ?? '') }}</h1>
                    <h2>{{ strtoupper($lead->position->position_name ?? 'N/A') }}</h2>
                </div>

                <h3 class="experience-title">WORK EXPERIENCE</h3>

                

                @if($lead->jobHistory && $lead->jobHistory->count() > 0)
                    @foreach($lead->jobHistory->sortByDesc('start_date') as $job)
                        <div class="experience-item">
                            <h4>{{ $job->company_name }}</h4>
                            <p class="duration">{{ $job->position ?? '' }} | {{ $job->start_date ? \Carbon\Carbon::parse($job->start_date)->format('Y') : 'N/A' }} - {{ $job->end_date ? \Carbon\Carbon::parse($job->end_date)->format('Y') : 'Present' }}</p>
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
                    <p style="display:none;">No work experience available.</p>
                @endif
            </div>
        </div>
        
        <div class="contact-bar">
            <span>
                <i class="fas fa-phone"></i> {{ $lead->lead_phone ?? 'N/A' }}
            </span>
            <span>
                <i class="fas fa-map-marker-alt"></i> {{ $lead->lead_address ?? 'N/A' }}
            </span>
        </div>
    </div>
    </div><!-- end resume-wrapper -->
</body>
</html>