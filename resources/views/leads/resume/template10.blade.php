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
                min-height: 297mm;
                margin: 0;
                box-shadow: none;
                page-break-after: auto;
            }
        }
        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .container {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            padding: 25px 30px;
            box-sizing: border-box;
        }
        
        /* Background - Cream color on top half only */
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 20%;
            background: #E8E4D9;
            z-index: 0;
        }

        /* Define colors */
        :root {
            --primary-color: #6B7552;
            --brown-color: #654321;
            --text-color: #333;
            --light-bg: #E8E4D9;
        }

        /* PROFILE IMAGE with decorative oval frame */
        .profile-wrapper {
            position: absolute;
            top: 40px;
            left: 30px;
            z-index: 10;
        }
        .profile-oval {
            width: 150px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            background-color: white;
            border: 3px solid var(--brown-color);
            box-shadow: 0 0 0 6px var(--light-bg), 0 0 0 9px var(--brown-color);
            position: relative;
        }
        .profile-oval::before,
        .profile-oval::after {
            content: '';
            position: absolute;
            width: 15px;
            height: 15px;
            background-color: var(--brown-color);
            border-radius: 50%;
        }
        .profile-oval::before {
            top: 50%;
            left: -25px;
            transform: translateY(-50%);
        }
        .profile-oval::after {
            top: 50%;
            right: -25px;
            transform: translateY(-50%);
        }
        .profile-oval img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* HEADER SECTION (Name and Position) */
        .header {
            position: absolute;
            top: 30px;
            right: 30px;
            text-align: center;
            z-index: 10;
            width: 65%;
        }
        .header .position-subtitle {
            font-family: 'Brush Script MT', cursive;
            font-size: 24px;
            color: #999;
            font-style: italic;
            margin: 0 0 3px 0;
        }
        .header h1 {
            font-family: Georgia, serif;
            margin: 0;
            line-height: 1;
            color: var(--primary-color);
            font-size: 30px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* MAIN CONTENT (2 COLUMNS) */
        .main-content {
            display: flex;
            margin-top: 230px;
            gap: 20px;
            position: relative;
            z-index: 10;
        }
        .left-column {
            width: 35%;
            box-sizing: border-box;
        }
        .right-column {
            width: 65%;
            box-sizing: border-box;
        }

        /* SECTION TITLES */
        .section-title {
            color: var(--primary-color);
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0 6px 0;
            font-family: Georgia, serif;
        }
        
        /* Contact Section */
        .contact-section {
            margin-bottom: 10px;
        }
        .contact-section .phone {
            font-size: 13px;
            font-weight: bold;
            color: var(--text-color);
            margin: 3px 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .contact-section .address {
            font-size: 11px;
            color: var(--text-color);
            line-height: 1.3;
            display: flex;
            align-items: flex-start;
            gap: 6px;
        }
        .contact-section i {
            color: var(--primary-color);
        }

        /* Profile Section */
        .profile-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 11px;
            color: var(--text-color);
        }
        .profile-section ul li {
            margin-bottom: 2px;
        }

        /* Summary Section */
        .summary-section p {
            font-size: 11px;
            color: var(--text-color);
            margin: 3px 0;
            text-align: justify;
        }

        /* Work Experience Section */
        .work-experience-title {
            color: var(--primary-color);
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0 8px 0;
            font-family: Georgia, serif;
        }
        .experience-item {
            margin-bottom: 10px;
        }
        .experience-item .job-title {
            font-size: 12px;
            font-weight: bold;
            color: var(--text-color);
            margin: 0 0 2px 0;
        }
        .experience-item .job-duration {
            font-size: 11px;
            font-style: italic;
            color: #666;
            margin: 0 0 3px 0;
        }
        .experience-item .job-department {
            font-size: 11px;
            font-style: italic;
            color: var(--text-color);
            margin: 0 0 3px 0;
        }
        .experience-item ul {
            list-style-type: disc;
            padding-left: 15px;
            margin: 3px 0;
            font-size: 11px;
            color: var(--text-color);
        }
        .experience-item ul li {
            margin-bottom: 1px;
        }
        
        .footer-note {
            position: absolute;
            bottom: 12px;
            left: 30px;
            font-size: 12px;
            color: var(--text-color);
            z-index: 10;
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
            border: 2px solid #6B7552;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            background-color: white;
            color: #333;
        }
        .template-selector select:focus {
            outline: none;
            border-color: #8B4513;
        }
        .template-selector .print-btn {
            padding: 8px 16px;
            background-color: #6B7552;
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
            background-color: #8B4513;
        }
         
        
        /* Print Styles */
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
            
            .main-content {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
            }
            
            .left-column {
                width: 35% !important;
                flex: 0 0 35% !important;
                max-width: 35% !important;
            }
            
            .right-column {
                width: 65% !important;
                flex: 0 0 65% !important;
                max-width: 65% !important;
            }
            
            .container::before {
                background: #E8E4D9 !important;
            }
            
            .footer-note {
                position: absolute;
                bottom: 10px;
                left: 30px;
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
        
        <div class="profile-wrapper">
            <div class="profile-oval">
                @if($lead->lead_photo)
                    <img src="{{ asset('storage/' . $lead->lead_photo) }}" alt="Profile Picture">
                @else
                    <img src="{{ asset('logo/user.png') }}" alt="Profile Picture">
                @endif
            </div>
        </div>

        <div class="header">
            <p class="position-subtitle">{{ $lead->position->position_name ?? 'Position' }}</p>
            <h1>{{ strtoupper($lead->fullName ?? 'NAME') }}</h1>
        </div>

        <div class="main-content">
            
            <div class="left-column">
                
                <div class="contact-section">
                    <h3 class="section-title">Contact</h3>
                    @if($lead->lead_phone)
                        <div class="phone">
                            <i class="fas fa-phone"></i>
                            {{ $lead->lead_phone }}
                        </div>
                    @endif
                    @if($lead->lead_address)
                        <div class="address">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $lead->lead_address }}
                        </div>
                    @endif
                </div>

                <div class="profile-section">
                    <h3 class="section-title">Profile</h3>
                    <ul>
                        @if($lead->lead_birthday)
                        <li>• <strong>Date of Birth :</strong> {{ $lead->lead_birthday->format('d M Y') }}</li>
                        @endif
                        @if($lead->lead_age)
                        <li>• <strong>AGE :</strong> {{ $lead->lead_age }} Years</li>
                        @endif
                        @if($lead->lead_marital_status)
                        <li>• <strong>STATUS :</strong> {{ ucfirst($lead->lead_marital_status) }}</li>
                        @endif
                        @if($lead->lead_height)
                        <li>• <strong>Hight :</strong> {{ $lead->lead_height }}</li>
                        @endif
                        @if($lead->lead_weight)
                        <li>• <strong>Weight :</strong> {{ $lead->lead_weight }}</li>
                        @endif
                        @if($lead->lead_shirt_size)
                        <li>• <strong>Shirt size :</strong> {{ $lead->lead_shirt_size }}</li>
                        @endif
                        @if($lead->lead_pant_size)
                        <li>• <strong>Pant size :</strong> {{ $lead->lead_pant_size }}</li>
                        @endif
                        @if($lead->lead_shoes_size)
                        <li>• <strong>Shoes size :</strong> {{ $lead->lead_shoes_size }}</li>
                        @endif
                        @if($lead->lead_emergency_name)
                        <li>• <strong>EMERGENCY CONTACT NAME :</strong><br>{{ $lead->lead_emergency_name }}</li>
                        @endif
                        @if($lead->lead_emergency_phone)
                        <li>• <strong>TEL :</strong> {{ $lead->lead_emergency_phone }}</li>
                        @endif
                        @if($lead->lead_car_type)
                        <li>• <strong>TYPE OF CAR :</strong> {{ $lead->lead_car_type }}</li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="right-column">
                
                <div class="summary-section">
                    <h3 class="section-title">Summary</h3>
                    @if($lead->lead_summary)
                        <p>{{ $lead->lead_summary }}</p>
                    @else
                        <p>I am a hardworking and responsible person with several years of experience in factory and warehouse operations. I have experience in packing products, operating basic machinery, and maintaining a clean and safe working environment.</p>
                        <p>I am a quick learner, able to work well in a team, and always follow safety and quality standards. I am ready to contribute my skills and effort to support the success of your company.</p>
                    @endif
                </div>

                <h3 class="work-experience-title">Work experience</h3>

                @if($lead->jobHistory && $lead->jobHistory->count() > 0)
                    @foreach($lead->jobHistory->sortByDesc('start_date') as $job)
                        <div class="experience-item">
                            <div class="job-title">
                                {{ $job->company_name ?? 'Company Name' }} - 
                                @php
                                    $startDate = $job->start_date ? \Carbon\Carbon::parse($job->start_date) : null;
                                    $endDate = $job->end_date ? \Carbon\Carbon::parse($job->end_date) : null;
                                @endphp
                                @if($startDate && $endDate)
                                    {{ $startDate->format('Y') }} - {{ $endDate->format('Y') }}
                                @elseif($startDate)
                                    {{ $startDate->format('Y') }} - Present
                                @else
                                    {{ $job->experience_years ? $job->experience_years . ' Years' : '' }}
                                @endif
                            </div>
                            <div class="job-department">{{ $job->position ?? 'Position' }}</div>
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
                    <div class="experience-item">
                        <div class="job-title">7-Eleven Convenience Store - 2013-2025</div>
                        <div class="job-department">Product arrangement / Product packaging department</div>
                        <ul>
                            <li>Greet and assist customers in a friendly manner.</li>
                            <li>Operate the cash register and handle transactions accurately.</li>
                            <li>Stock shelves, organize products, and check expiration dates.</li>
                            <li>Maintain cleanliness and orderliness of the store.</li>
                            <li>Follow company policies and safety standards.</li>
                            <li>Assist in inventory checking and report shortages or damages.</li>
                        </ul>
                    </div>

                    <div class="experience-item">
                        <div class="job-title">Paper Box Manufacturing Factory - 2007-2013</div>
                        <div class="job-department">Production department</div>
                        <ul>
                            <li>Operated machinery for cutting, folding, and assembling paper boxes.</li>
                            <li>Inspected product quality and removed defective items.</li>
                            <li>Packed and organized finished boxes for shipment.</li>
                            <li>Maintained cleanliness and followed safety regulations.</li>
                            <li>Assisted in material handling and production line support.</li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        
        <footer class="footer-note">
            @if($lead->staff)
                {{ $lead->staff->staff_nickname }}/{{ $lead->recommenderStaff->staff_sub_name ?? '' }}
            @endif
        </footer>
    </div>
    </div><!-- end resume-wrapper -->
</body>
</html>