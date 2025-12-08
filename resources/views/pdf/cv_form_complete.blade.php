<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CV Form Complete Page 1</title>
    <style>
        @page {
            footer: html_myFooter;
            margin-footer: 5mm;
        }

        body {
            font-family: 'Calibri', 'Segoe UI', 'Arial', 'Helvetica', sans-serif;
            font-size: 11px;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        .form-header {
            font-family: 'Arial Black', 'Arial', sans-serif;
            font-weight: bold;
        }

        .field-label {
            font-family: 'Calibri', 'Arial', sans-serif;
            font-weight: 600;
        }

        .company-name {
            font-family: 'Arial Black', 'Arial', sans-serif;
        }

        .checkbox {
            border: 2px solid black;
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-left: 10px;
            vertical-align: middle;
            background-color: white;
            text-align: center;
            font-size: 14px;
            line-height: 20px;
            box-sizing: border-box;
            font-weight: bold;
        }
        .checkbox-empty {
            color: white;
        }
    </style>
</head>

<body>
    <div style="text-align: center; margin-bottom: 10px; margin-top: -10px;">
        <table style="margin: 0 auto; border-collapse: collapse;">
            <tr>
                <td style="width: 100px; text-align: right; vertical-align: middle;">
                    <img src="{{ public_path('logo/V dragon-02.png') }}" height="60" alt="VD Logo">
                </td>
                <td style="text-align: left; vertical-align: middle; padding-left: 15px;">
                    <div style="color: #F16731; font-size: 18px; font-weight: bold;">V.DRAGON RECRUITMENT CO., LTD.
                    </div>
                    <div style="color: #2E7D32; font-size: 14px; font-weight: bold;">บริษัท จัดหางาน วี ดรากอน จำกัด
                    </div>
                </td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-top: -3px;">
            <!-- Header Row -->
            <tr>
                <td colspan="24"
                    style="background-color: #FF9933; color: black; text-align: center; font-weight: bold; font-size: 13px; padding: 4px; border: 2px solid black; letter-spacing: 2px;">
                    <u> APPLICATION FORM</u>
                </td>
            </tr>
            <!-- Application Number Row -->
            <tr>
                <td colspan="24"
                    style="border-left: 2px solid black; border-right: 2px solid black; padding: 4px; text-align: right; font-weight: bold; font-size: 11px;">
                    @php
                        $jobLeadNumber = 'ไม่มีเลขที่ใบสมัคร';
                        if($lead && $lead->lead_id) {
                            $jobLead = \App\Models\jobs\JobLeadModel::where('lead_id', $lead->lead_id)
                                ->whereNotIn('job_lead_status', ['ถอน', 'ปฏิเสธ'])
                                ->orderBy('created_at', 'desc')
                                ->first();
                            if($jobLead && $jobLead->job_lead_number) {
                                $jobLeadNumber = 'เลขที่ใบสมัคร: ' . $jobLead->job_lead_number;
                            }
                        }
                    @endphp
                    {{ $jobLeadNumber }}
                </td>
            </tr>
            <tr>
                <td colspan="4"
                    style="border-left: 2px solid black; padding: 4px; text-align: left; font-weight: bold;">
                    Male <span class="checkbox">{!! $lead && $lead->lead_gender == 'male' ? '✓' : '<span style="color:white;">✓</span>' !!}</span>
                </td>
                <td colspan="4" style=" padding: 4px; text-align: left; font-weight: bold;">
                    Female <span class="checkbox">{!! $lead && $lead->lead_gender == 'female' ? '✓' : '<span style="color:white;">✓</span>' !!}</span>
                </td>
                <td colspan="4" style=" padding: 4px; text-align: left; font-weight: bold;">
                    Single <span class="checkbox">{!! $lead && $lead->lead_marital_status == 'single' ? '✓' : '<span style="color:white;">✓</span>' !!}</span>
                </td>
                <td colspan="4" style=" padding: 4px; text-align: left; font-weight: bold;">
                    Married <span class="checkbox">{!! $lead && $lead->lead_marital_status == 'married' ? '✓' : '<span style="color:white;">✓</span>' !!}</span>
                </td>
                <td colspan="4" style=" padding: 4px; text-align: left; font-weight: bold;">
                    Divorced <span class="checkbox">{!! $lead && $lead->lead_marital_status == 'divorced' ? '✓' : '<span style="color:white;">✓</span>' !!}</span>
                </td>
                <td colspan="4" rowspan="6"
                    style="border: 1px solid black; text-align: center; font-weight: bold; font-size: 24px; vertical-align: middle; width: 120px; background-color: #f9f9f9;">
                    PHOTO
                </td>
            </tr>

            <!-- PERSONAL INFORMATION Header -->
            <tr>
                <td colspan="20"
                    style=" color: #FF9933; font-weight: bold; padding: 2px 8px; border-left: 1px solid black; border-right: 1px solid black;">
                    PERSONAL INFORMATION:
                </td>
            </tr>
            <tr>
                <td colspan="7" style="border: 1px solid black; padding: 4px; font-weight: bold;">Position 1 : {{ $lead && $lead->position ? $lead->position->position_name : '' }}</td>
                <td colspan="7" style="border: 1px solid black; padding: 4px; font-weight: bold;">Position 2 : {{ $lead && $lead->position2 ? $lead->position2->position_name : '' }}</td>
                <td colspan="6" style="border: 1px solid black; padding: 4px; font-weight: bold;">Position 3 : {{ $lead && $lead->position3 ? $lead->position3->position_name : '' }}</td>
            </tr>
            <!-- Full Name Row -->
            <tr>
                <td colspan="10" style="border: 1px solid black; padding: 4px; font-weight: bold;">Full Name : {{ $lead ? ($lead->lead_prefix ? $lead->lead_prefix . ' ' : '') . $lead->lead_firstname . ' ' . $lead->lead_lastname : '' }}</td>
                <td colspan="5" style="border: 1px solid black; padding: 4px; font-weight: bold;">TEL: {{ $lead ? $lead->lead_phone : '' }}</td>
                <td colspan="5" style="border: 1px solid black; padding: 4px; font-weight: bold;">TEL: {{ $lead ? $lead->lead_phone_2 : '' }}</td>
            </tr>

            <!-- Address Row -->
            <tr>
                <td colspan="20" style="border: 1px solid black; padding: 4px; font-weight:bold">Permanent Address : {{ $lead ? $lead->lead_address : '' }}
                </td>
            </tr>

            <!-- Birth Info Row -->
            <tr>
                <td colspan="4" style="border: 1px solid black; padding: 4px; font-weight:bold">Date of Birth : {{ $lead && $lead->lead_birthday ? $lead->lead_birthday->format('d/m/Y') : '' }}</td>
                <td colspan="4" style="border: 1px solid black; padding: 4px; font-weight:bold">AGE : {{ $lead ? $lead->lead_age : '' }}</td>
                <td colspan="4" style="border: 1px solid black; padding: 4px; font-weight:bold">Height : {{ $lead ? $lead->lead_height : '' }}</td>
                <td colspan="4" style="border: 1px solid black; padding: 4px; font-weight:bold">Weight : {{ $lead ? $lead->lead_weight : '' }}</td>
                <td colspan="4" style="border: 1px solid black; padding: 4px; font-weight:bold">BMI : {{ $lead ? $lead->lead_bmi : '' }}</td>
            </tr>

            <!-- Passport Row -->
            <tr>
                <td colspan="8" style="border: 1px solid black; padding: 4px; font-weight: bold;">Passport/ID Card No
                    : {{ $lead ? $lead->lead_passport_number : '' }}</td>
                <td colspan="8" style="border: 1px solid black; padding: 4px; font-weight: bold;">Date of Issue :
                {{ $lead && $lead->lead_passport_issue_date ? $lead->lead_passport_issue_date->format('d/m/Y') : '' }}</td>
                <td colspan="8" style="border: 1px solid black; padding: 4px; font-weight: bold;">Date of Expiry :
                {{ $lead && $lead->lead_passport_expiry_date ? $lead->lead_passport_expiry_date->format('d/m/Y') : '' }}</td>
            </tr>
            <tr>
                <td colspan="8" style="border: 1px solid black; padding: 4px; font-weight: bold;">Shirt size : {{ $lead ? $lead->lead_shirt_size : '' }}</td>
                <td colspan="8" style="border: 1px solid black; padding: 4px; font-weight: bold;">Pant size: {{ $lead ? $lead->lead_pant_size : '' }}</td>
                <td colspan="8" style="border: 1px solid black; padding: 4px; font-weight: bold;">Shoes size : {{ $lead ? $lead->lead_shoes_size : '' }}</td>
            </tr>
            <tr>
                <td colspan="24"
                    style=" color: #FF9933; font-weight: bold; padding: 2px 8px; border-left: 1px solid black; border-right: 1px solid black;">
                    EDUCATION :
                </td> 
            </tr>
            <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 4px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_education == 'elementary' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> ELEMENTARY <br>SCHOOL
                </td>
                <td colspan="4" style=" padding: 4px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_education == 'junior_high' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> JUNIOR  <br>HIGH SCHOOL
                </td>
                <td colspan="4" style=" padding: 4px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_education == 'high_school' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> HIGH SCHOOL
                </td>
                <td colspan="4" style=" padding: 4px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_education == 'voc_cert' ? '✓' : '<span style="color:white;">✓</span>' !!}</span>VOC. CERT
                </td>
                <td colspan="4" style=" padding: 4px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_education == 'high_voc_cert' ? '✓' : '<span style="color:white;">✓</span>' !!}</span>HIGH VOC.CERT
                </td>
                <td colspan="4"
                    style=" padding: 4px ; text-align: left; font-weight: bold; border-right: 1px solid black;">
                    <span class="checkbox">{!! $lead && $lead->lead_education == 'bachelor' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> BACHELOR <br>DEGREES
                </td>
            </tr>

             <tr>
                <td colspan="24"
                    style=" color: #FF9933; font-weight: bold; padding: 2px 8px; border-left: 1px solid black; border-right: 1px solid black;">
                    LANGUAGES SKILLS
                </td>
            </tr>

             <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 3px; text-align: left; font-weight: bold;">
                    CHINESE SPEAKING
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_chinese_speaking == 'no' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> NO
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_chinese_speaking == 'beginner' ? '✓' : '<span style="color:white;">✓</span>' !!}</span>  BEGINNER
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_chinese_speaking == 'intermediate' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> INTERMEDIATE
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_chinese_speaking == 'advance' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> ADVANCE
                </td>
                <td colspan="4"
                    style=" padding: 3px; text-align: left; font-weight: bold; border-right: 1px solid black;">
                    
                </td>
            </tr>


             <tr>
                <td colspan="24"
                    style=" color: #FF9933; font-weight: bold; padding: 2px 8px; border-left: 1px solid black; border-right: 1px solid black;">
                    LANGUAGES SKILLS
                </td>
            </tr>

             <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 3px; text-align: left; font-weight: bold;">
                    ENGLISH SPEAKING
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_english_speaking == 'no' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> NO
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_english_speaking == 'beginner' ? '✓' : '<span style="color:white;">✓</span>' !!}</span>  BEGINNER
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_english_speaking == 'intermediate' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> INTERMEDIATE
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_english_speaking == 'advance' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> ADVANCE
                </td>
                <td colspan="4"
                    style=" padding: 3px; text-align: left; font-weight: bold; border-right: 1px solid black;">
                    
                </td>
            </tr>
            <tr>
                <td colspan="24"
                    style=" color: #000000; font-weight: bold; padding: 2px 8px; border-left: 1px solid black; border-right: 1px solid black;">
                   OTHER LANGUAGES : <u>{{ $lead ? $lead->lead_other_language : '' }}</u>
                </td>
            </tr>

            <tr>
                <td colspan="24"
                    style=" color: #FF9933; font-weight: bold; padding: 2px 8px; border-left: 1px solid black; border-right: 1px solid black;">
                    ADDITIONAL INFORMATION:
                </td> 
            </tr>
             <tr>
                <td colspan="24"
                    style=" color: #020202; font-weight: bold; padding: 2px 8px; border-left: 1px solid black; border-right: 1px solid black;">
                   Have you ever work in Israel?  
                   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                   Criminal history?
                </td> 
            </tr>
            <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_work_israel == 'no' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> NO
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_work_israel == 'yes' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> YES
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    Details : <u>{{ $lead && $lead->lead_work_israel_details ? $lead->lead_work_israel_details : '' }}</u>
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_criminal_history == 'no' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> NO
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_criminal_history == 'yes' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> YES
                </td>
                <td colspan="4"
                    style=" padding: 3px ; text-align: left; font-weight: bold; border-right: 1px solid black;">
                     Details : <u>{{ $lead && $lead->lead_criminal_details ? $lead->lead_criminal_details : '' }}</u>
                </td>
            </tr>

             <tr>
                <td colspan="24"
                    style=" color: #020202; font-weight: bold; padding: 2px 8px; border-left: 1px solid black; border-right: 1px solid black;">
                   Eyesight?
                   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; 
                  Color blindness?
                </td> 
            </tr>
            <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_eyesight == 'normal' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> Normal
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_eyesight == 'abnormal' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> Abnormal
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                   
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_color_blindness == 'no' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> NO
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_color_blindness == 'yes' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> YES
                </td>
                <td colspan="4"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border-right: 1px solid black;">
                    
                </td>
            </tr>
             <tr>
                <td colspan="24"
                    style="background-color: #FF9933; color: FF9933; text-align: left; font-weight: bold; font-size: 13px; padding: 3px; border: 1px solid black; letter-spacing: 2px;">
                    <u> POSITION AND SKILL</u>
                </td>
            </tr>
            <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('electrician', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>  ELECTRICIAN
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('painter', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>  PAINTER
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('welder', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>  WELDER
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('tile', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span> TILE
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('agriculture', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span> AGRICULTURE
                </td>
                <td colspan="4"
                    style=" padding: 3px ; text-align: left; font-weight: bold; border-right: 1px solid black;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('factory', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>  FACTORY
                </td>
            </tr>
             <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('carpenter', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>   CARPENTER
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('plasterer', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>   PLASTERER
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('iron', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>   IRON
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('brick', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>  BRICK
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('plumber', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>  PLUMBER
                </td>
                <td colspan="4"
                    style=" padding: 3px ; text-align: left; font-weight: bold; border-right: 1px solid black;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('roller', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>   ROLLER
                </td>
            </tr>

             <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 3px; text-align: left; font-weight: bold; border-bottom: 1px solid black;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('loader', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>    LOADER
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold; border-bottom: 1px solid black;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('excavators', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>    Excavators
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold; border-bottom: 1px solid black;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('jcb', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>     JCB
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold; border-bottom: 1px solid black;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('telescopic', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>    Telescopic
                </td>
                <td colspan="4" style=" padding: 3px; text-align: left; font-weight: bold; border-bottom: 1px solid black;">
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('forklift', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>    Driving a Forklift
                </td>
                <td colspan="4"
                    style=" padding: 3px ; text-align: left; font-weight: bold; border-right: 1px solid black; border-bottom: 1px solid black;" >
                    <span class="checkbox">{!! $lead && $lead->lead_skills && in_array('etc', $lead->lead_skills) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>   Etc.
                </td>
            </tr>
            <tr>
                
            </tr>
             

            
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-top: -3px;">
            <tr>
                <td colspan="26"
                    style="background-color: #FF9933; color: FF9933; text-align: left; font-weight: bold; font-size: 13px; padding: 3px; border: 1px solid black; letter-spacing: 2px;">
                    <u> JOB HISTORY</u>
                </td>
            </tr>
        
             <tr>
                <th colspan="1"
                    style=" padding: 3px ; text-align: left; font-weight: bold; border: 1px solid black;">
                    NO.
                </th>
                 <th colspan="12"
                    style=" padding: 3px ; text-align: left; font-weight: bold; border: 1px solid black;">
                  <u>Type of Work And Company Name</u>
                </th>
                <th colspan="5"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border: 1px solid black;">
                  <u>Position</u>
                </th>
                <th colspan="4"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border: 1px solid black;">
                  <u>Country </u>
                </th>
                 <th colspan="4"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border: 1px solid black;">
                  <u>Experience Years</u>
                </th>
            </tr>
            @php
             $limit = 5;
             $jobHistories = $lead && $lead->jobHistory ? $lead->jobHistory : collect();
            @endphp
           @for ($i = 0; $i < $limit ; $i++)
            @php
                $history = $jobHistories->get($i);
            @endphp
            <tr>
               <td colspan="1"
                    style=" padding: 3px ; text-align: left; font-weight: bold; border: 1px solid black;">
                   {{ $i+1}}
                </td>
                 <td colspan="12"
                    style=" padding: 3px ; text-align: left; font-weight: bold; border: 1px solid black;">
                  {{ $history ? ($history->company_type . ($history->company_name ? ' - ' . $history->company_name : '')) : '' }}
                </td>
                <td colspan="5"
                    style=" padding: 3px ; text-align: left; font-weight: bold; border: 1px solid black;">
                  {{ $history ? $history->position : '' }}
                </td>
                <td colspan="4"
                    style=" padding: 3px ; text-align: left; font-weight: bold; border: 1px solid black;">
                  {{ $history ? $history->country : '' }}
                </td>
                 <td colspan="4"
                    style=" padding: 3px ; text-align: left; font-weight: bold; border: 1px solid black;">
                  {{ $history ? $history->experience_years : '' }}
                </td>
            </tr>
             @endfor

             <tr>
                <td  colspan="26" style=" padding: 4px ; text-align: left; font-weight: bold; border: 1px solid black;">
                    EMERGENCY CONTACT NAME <small>(ผู้ติดต่อฉุกเฉิน)</small>: {{ $lead ? $lead->lead_emergency_name : '' }} 
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;   
                    STATUS <small>(ความสัมพันธ์)</small> : {{ $lead ? $lead->lead_emergency_status : '' }} 
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                    TEL: {{ $lead ? $lead->lead_emergency_phone : '' }}

                </td>
             </tr>
             <tr>
                <td  colspan="26" style=" padding: 4px ; text-align: left; font-weight: bold; border: 1px solid black;">
                    DRIVING LICENSE : <small>(ใบขับขี่)</small>: <span class="checkbox">{!! $lead && $lead->lead_driving_license == 'no' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> NO  <span class="checkbox">{!! $lead && $lead->lead_driving_license == 'yes' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> YES
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                    TYPE OF CAR : <small>(ประเภทรถ)</small> : {{ $lead && $lead->lead_driving_license == 'yes' ? $lead->lead_car_type : '' }}
                    &nbsp; <br><br>
                    Valid Until date : <small>(วันหมดอายุใบขับขี่)</small> : {{ $lead && $lead->lead_license_valid_until ? $lead->lead_license_valid_until->format('d/m/Y') : '' }}
                </td>
             </tr>
            
             

        </table>

<!-- Spacer to push signature section to bottom -->
<div style="height: 50px;"></div>

<table style="width: 100%; border-collapse: collapse; position: fixed; bottom: 30px; left: 0; right: 0;">
    <tr>
        <td style="width: 40%; padding-top: 8px; text-align: center; font-weight: bold;">
            ____________________________________________ <br>
            <small>DATE & LOCATION <br>วันที่ทดสอบ + สถานท้ี่ทดสอบ<br>{{ $lead ? $lead->lead_date_location : '' }}</small>
        </td>

        <td style="width: 20%"></td>

        <td style="width: 40%; padding-top: 8px; font-weight: bold; text-align: left;">
            SIGN ____________________________________________ Job Applicants<br>
            Recommender: {{ $lead && $lead->recommenderStaff ? $lead->recommenderStaff->staff_sub_name : '' }}<br>
          
        </td>
        
    </tr>
    
     
</table>

    </div>

    <!-- Page Break -->
    <div style="page-break-after: always;"></div>

    <!-- PAGE 2 START -->
     <div style="text-align: center; margin-bottom: 10px; margin-top: -10px;">
        <table style="margin: 0 auto; border-collapse: collapse;">
            <tr>
                <td style="width: 100px; text-align: right; vertical-align: middle;">
                    <img src="{{ public_path('logo/V dragon-02.png') }}" height="60" alt="VD Logo">
                </td>
                <td style="text-align: left; vertical-align: middle; padding-left: 15px;">
                    <div style="color: #F16731; font-size: 18px; font-weight: bold;">V.DRAGON RECRUITMENT CO., LTD.
                    </div>
                    <div style="color: #2E7D32; font-size: 14px; font-weight: bold;">บริษัท จัดหางาน วี ดรากอน จำกัด
                    </div>
                </td>
            </tr>
        </table>

     </div>
     
      <br>
     
        <table style="width: 100%; border-collapse: collapse; margin-top: -3px;">
        <tr>
            <td colspan="50"></td>
        </tr>
         <tr >
         <td style="padding: 8px" colspan="10"><b>ชื่อบิดา:</b> {{ $lead ? $lead->lead_father_name : '' }}</td>
         <td colspan="10"></td>
         <td colspan="10"><b>ชื่อมารดา:</b> {{ $lead ? $lead->lead_mother_name : '' }}</td>
         </tr>
         
          <tr>
         <td style="padding: 8px" colspan="10"><b>Email:</b> {{ $lead ? $lead->lead_email : '' }}</td>

     
         <td colspan="10"></td>
         <td colspan="10"></td>
         </tr>
         <br>
           <tr>
         <td style="padding: 8px" colspan="10"><b>เลขที่บัญชี :</b> {{ $lead ? $lead->lead_bank_account_number : '' }}</td>
      
         <td colspan="10"></td>
         <td colspan="10"><b>ธนาคาร:</b> {{ $lead ? $lead->lead_bank_name : '' }}</td>
         </tr>
          <tr>
         <td style="padding: 8px" colspan="50"><b>ไซส์เสื้อ :</b> {{ $lead ? $lead->lead_shirt_size : '' }} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <b>ไซส์กางเกง :</b> {{ $lead ? $lead->lead_pant_size : '' }}  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b>ไซส์รองเท้า :</b> {{ $lead ? $lead->lead_shoes_size : '' }}</td>
       
         </tr>
         <br>

           <tr>
                <td colspan="50"
                    style="background-color: #FF9933; color: FF9933; text-align: center; font-weight: bold; font-size: 14px; padding: 4px; border: 1px solid black;">
                   CHECK LIST เอกสารผู้สมัคร
                </td>
            </tr>
              <tr >
         <td style="padding: 8px" colspan="10"><b>งาน:</b> {{ $lead && $lead->jobGroup ? $lead->jobGroup->job_group_name : '' }}</td>
         <td colspan="10"></td>
         <td colspan="10"><b>ตำแหน่ง:</b> {{ $lead && $lead->position ? $lead->position->position_name : '' }}</td>
         </tr>
           <tr >
         <td style="padding: 8px" colspan="10"><b>ชื่อ - นามสกุล :</b> {{ $lead ? ($lead->lead_prefix ? $lead->lead_prefix . ' ' : '') . $lead->lead_firstname . ' ' . $lead->lead_lastname : '' }}</td>
         <td colspan="10"></td>
         <td colspan="10"><b>เบอร์โทร :</b> {{ $lead ? $lead->lead_phone : '' }}</td>
         </tr>

    </table>
    <br>
     <table style="width: 100%; border-collapse: collapse; margin-top: -3px;">
          <tr>
                <td colspan="50"
                    style="text-align: left; font-weight: bold; font-size: 14px; padding: 4px;">
                 เอกสารผู้สมัคร
                </td>
            </tr>

          @php
             $documentsList = [
                'รูปถ่ายขนาด_2_นิ้ว' => '1. รูปถ่ายขนาด 2 นิ้ว',
                'Passport' => '2. Passport',
                'บัตรประชาชน_สำเนา' => '3. บัตรประชาชน (สำเนา)',
                'ผลโรค' => '4. ผลโรค',
                'ใบผ่านงาน' => '5. ใบผ่านงาน',
                'ใบอนุญาต_No' => '6. ใบอนุญาต No.',
                'CID' => '7. CID',
                'หนังสือเดินทาง_สำเนา' => '8. หนังสือเดินทาง (สำเนา)',
                'ใบ_สผท' => '9. ใบ สผท.',
                'ใบสุคันธ์_สำเนาจริงในเค' => '10. ใบสุคันธ์ (สำเนาจริงในเค)',
                'CV' => '11. CV',
                'หนังสือสมรส_กรณีสมรส' => '12. หนังสือสมรส (กรณีสมรส)',
                'สำเนาบัตรประชาชนคู่สมรส_กรณีสมรส' => '13. สำเนาบัตรประชาชนคู่สมรส(กรณีสมรส)',
                'สำเนาบัตรอัยพ่อแม่สกุล_กรณีเดคลแปลปธิ' => '14. สำเนาบัตรอัยพ่อแม่สกุล(กรณีเดคลแปลปธิ)'
             ];
             
             $leadDocuments = $lead && $lead->documents ? $lead->documents : [];
             $docsArray = array_values($documentsList);
             $docsKeys = array_keys($documentsList);
             $leftColumn = array_slice($docsArray, 0, 7);
             $rightColumn = array_slice($docsArray, 7, 7);
             $leftKeys = array_slice($docsKeys, 0, 7);
             $rightKeys = array_slice($docsKeys, 7, 7);
            @endphp
           @for ($i = 0; $i < 7; $i++)
         <tr>
                <td colspan="2"
                    style= "padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! isset($leftKeys[$i]) && in_array($leftKeys[$i], $leadDocuments) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>
                </td>
                <td colspan="23" style="padding: 8px; text-align: left; font-weight: bold;">
                    {{ $leftColumn[$i] ?? '' }}
                </td>
              
                <td colspan="2"
                    style=" padding: 8px ; text-align: left; font-weight: bold;">
                    <span class="checkbox">{!! isset($rightKeys[$i]) && in_array($rightKeys[$i], $leadDocuments) ? '✓' : '<span style="color:white;">✓</span>' !!}</span>
                </td>
                <td colspan="23" style="padding: 8px; text-align: left; font-weight: bold;">
                    {{ $rightColumn[$i] ?? '' }}
                </td>
            </tr>
            @endfor
     </table>
     <br>
     <br>

     <div style="padding: 10px; text-align: left; font-weight: bold;">การยืนยันสถานะทางกฎหมายและความบริสุทธิ์ทางคดี</div>
     <div style="padding: 10px; text-align: left; font-weight: bold;"> 
        <span class="checkbox">{!! $lead && $lead->lead_criminal_history == 'no' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> ไม่มีคดี &nbsp; &nbsp; &nbsp; 
        <span class="checkbox">{!! $lead && $lead->lead_criminal_history == 'yes' ? '✓' : '<span style="color:white;">✓</span>' !!}</span> มีคดี ระบุรายละเอียด: {{ $lead && $lead->lead_criminal_details ? $lead->lead_criminal_details : '' }}
     </div>
     <div style="padding: 10px; text-align: left; font-weight: bold;">
        <span class="checkbox"><span style="color:white;">✓</span></span> ข้าพเจ้ายืนยันว่าข้าพเจ้าไม่เคยมีประวัติอาชญากรรม ไม่เคยถูกดำเนินคดีอาญาใด ๆ  ทั้งในอดีตและ																									
ปัจจุบัน และไม่มีคดีอาญาที่อยู่ในกระบวนการพิจารณาของศาล หากมีการเปลี่ยนแปลงเกี่ยวกับสถานะทาง																									
กฎหมายในอนาคต ข้าพเจ้าจะแจ้งให้บริษัททราบโดยทันที 
     </div>
</div>
<br>
<br>
<div style="padding: 10px; text-align: left; font-weight: bold;">ลงชื่อ___________________________________________</div>
<div style="padding: 10px; text-align: left; font-weight: bold;">วันที่____________________________________________</div>


<htmlpagefooter name="myFooter">
<table width="100%" style="font-size: 7pt;">
    <tr>
        <td width="50%" style="text-align: left;"></td>
        <td width="50%" style="text-align: right; font-weight: bold; color: #333;">FM-RCM-01</td>
    </tr>
</table>
</htmlpagefooter>


</body>

</html>


