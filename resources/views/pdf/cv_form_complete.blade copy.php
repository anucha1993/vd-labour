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
                    style="background-color: #FF9933; color: black; text-align: center; font-weight: bold; font-size: 14px; padding: 8px; border: 2px solid black; letter-spacing: 3px;">
                    <u> APPLICATION FORM</u>
                </td>
            </tr>
            <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 8px; text-align: left; font-weight: bold;">
                    Male <span class="checkbox">✓</span>
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    Female <span class="checkbox">✓</span>
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    Single <span class="checkbox">✓</span>
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    Married <span class="checkbox">✓</span>
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    Divorced <span class="checkbox">✓</span>
                </td>
                <td colspan="4" rowspan="6"
                    style="border: 1px solid black; text-align: center; font-weight: bold; font-size: 24px; vertical-align: middle; width: 120px; background-color: #f9f9f9;">
                    PHOTO
                </td>
            </tr>

            <!-- PERSONAL INFORMATION Header -->
            <tr>
                <td colspan="20"
                    style=" color: #FF9933; font-weight: bold; padding: 6px 10px; border-left: 1px solid black; border-right: 1px solid black;">
                    PERSONAL INFORMATION:
                </td>
            </tr>
            <tr>
                <td colspan="7" style="border: 1px solid black; padding: 4px; font-weight: bold;">Position 1 :</td>
                <td colspan="7" style="border: 1px solid black; padding: 4px; font-weight: bold;">Position 2 :</td>
                <td colspan="6" style="border: 1px solid black; padding: 4px; font-weight: bold;">Position 3 :</td>
            </tr>

            <!-- Full Name Row -->
            <tr>
                <td colspan="10" style="border: 1px solid black; padding: 4px; font-weight: bold;">Full Name :</td>
                <td colspan="5" style="border: 1px solid black; padding: 4px; font-weight: bold;">TEL:</td>
                <td colspan="5" style="border: 1px solid black; padding: 4px; font-weight: bold;">TEL:</td>
            </tr>

            <!-- Address Row -->
            <tr>
                <td colspan="20" style="border: 1px solid black; padding: 4px; font-weight:bold">Permanent Address :
                </td>
            </tr>

            <!-- Birth Info Row -->
            <tr>
                <td colspan="4" style="border: 1px solid black; padding: 4px; font-weight:bold">Date of Birth :</td>
                <td colspan="4" style="border: 1px solid black; padding: 4px; font-weight:bold">AGE :</td>
                <td colspan="4" style="border: 1px solid black; padding: 4px; font-weight:bold">Height :</td>
                <td colspan="4" style="border: 1px solid black; padding: 4px; font-weight:bold">Weight :</td>
                <td colspan="4" style="border: 1px solid black; padding: 4px; font-weight:bold">BMI :</td>
            </tr>

            <!-- Passport Row -->
            <tr>
                <td colspan="8" style="border: 1px solid black; padding: 4px; font-weight: bold;">Passport/ID Card No
                    :</td>
                <td colspan="8" style="border: 1px solid black; padding: 4px; font-weight: bold;">Date of Issue :
                </td>
                <td colspan="8" style="border: 1px solid black; padding: 4px; font-weight: bold;">Date of Expiry :
                </td>
            </tr>
            <tr>
                <td colspan="8" style="border: 1px solid black; padding: 4px; font-weight: bold;">Shirt size :</td>
                <td colspan="8" style="border: 1px solid black; padding: 4px; font-weight: bold;">Pant size:</td>
                <td colspan="8" style="border: 1px solid black; padding: 4px; font-weight: bold;">Shoes size :</td>
            </tr>
            <tr>
                <td colspan="24"
                    style=" color: #FF9933; font-weight: bold; padding: 6px 10px 0px ; border-left: 1px solid black; border-right: 1px solid black;">
                    EDUCATION :
                </td> 
            </tr>
            <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> ELEMENTARY <br>SCHOOL
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> JUNIOR  <br>HIGH SCHOOL
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> HIGH SCHOOL
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span>VOC. CERT
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span>HIGH VOC.CERT
                </td>
                <td colspan="4"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border-right: 1px solid black;">
                    <span class="checkbox">✓</span> BACHELOR <br>DEGREES
                </td>
            </tr>

             <tr>
                <td colspan="24"
                    style=" color: #FF9933; font-weight: bold; padding: 0px 10px 0px ; border-left: 1px solid black; border-right: 1px solid black;">
                    LANGUAGES SKILLS:
                </td>
            </tr>

             <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 8px; text-align: left; font-weight: bold;">
                    CHINESE SPEAKING :
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> NO
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span>  BEGINNER
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> INTERMEDIATE
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> ADVANCE
                </td>
                <td colspan="4"
                    style=" padding: 8px 8px 0px; text-align: left; font-weight: bold; border-right: 1px solid black;">
                    
                </td>
            </tr>


             <tr>
                <td colspan="24"
                    style=" color: #FF9933; font-weight: bold; padding: 0px 10px 0px ; border-left: 1px solid black; border-right: 1px solid black;">
                    LANGUAGES SKILLS:
                </td>
            </tr>

             <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 8px; text-align: left; font-weight: bold;">
                    ENGLISH SPEAKING :
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> NO
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span>  BEGINNER
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> INTERMEDIATE
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> ADVANCE
                </td>
                <td colspan="4"
                    style=" padding: 8px 8px 0px; text-align: left; font-weight: bold; border-right: 1px solid black;">
                    
                </td>
            </tr>
            <tr>
                <td colspan="24"
                    style=" color: #000000; font-weight: bold; padding: 0px 10px 0px ; border-left: 1px solid black; border-right: 1px solid black;">
                   OTHER LANGUAGES : <u>Japanese</u>
                </td>
            </tr>

            <tr>
                <td colspan="24"
                    style=" color: #FF9933; font-weight: bold; padding: 6px 10px 0px ; border-left: 1px solid black; border-right: 1px solid black;">
                    ADDITIONAL INFORMATION:
                </td> 
            </tr>
             <tr>
                <td colspan="24"
                    style=" color: #020202; font-weight: bold; padding: 6px 10px 0px ; border-left: 1px solid black; border-right: 1px solid black;">
                   Have you ever work in Israel?  
                   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                   Criminal history?
                </td> 
            </tr>
            <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> NO
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> YES
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    Details : <u>Old Text</u>
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> NO
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> YES
                </td>
                <td colspan="4"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border-right: 1px solid black;">
                     Details : <u>Old Text</u>
                </td>
            </tr>

             <tr>
                <td colspan="24"
                    style=" color: #020202; font-weight: bold; padding: 6px 10px 0px ; border-left: 1px solid black; border-right: 1px solid black;">
                   Eyesight?
                   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; 
                  Color blindness?
                </td> 
            </tr>
            <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> Normal
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> Abnormal
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                   
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> NO
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> YES
                </td>
                <td colspan="4"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border-right: 1px solid black;">
                    
                </td>
            </tr>
             <tr>
                <td colspan="24"
                    style="background-color: #FF9933; color: FF9933; text-align: left; font-weight: bold; font-size: 14px; padding: 4px; border: 1px solid black; letter-spacing: 3px;">
                    <u> POSITION AND SKILL</u>
                </td>
            </tr>
            <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span>  ELECTRICIAN
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span>  PAINTER
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span>  WELDER
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> TILE
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span> AGRICULTURE
                </td>
                <td colspan="4"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border-right: 1px solid black;">
                    <span class="checkbox">✓</span>  FACTORY
                </td>
            </tr>
             <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span>   CARPENTER
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span>   PLASTERER
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span>   IRON
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span>  BRICK
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span>  PLUMBER
                </td>
                <td colspan="4"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border-right: 1px solid black;">
                    <span class="checkbox">✓</span>   ROLLER
                </td>
            </tr>

             <tr>
                <td colspan="4"
                    style="border-left: 1px solid black; padding: 8px; text-align: left; font-weight: bold; border-bottom: 1px solid black;">
                    <span class="checkbox">✓</span>    LOADER
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold; border-bottom: 1px solid black;">
                    <span class="checkbox">✓</span>    Excavators
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold; border-bottom: 1px solid black;">
                    <span class="checkbox">✓</span>     JCB
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold; border-bottom: 1px solid black;">
                    <span class="checkbox">✓</span>    Telescopic
                </td>
                <td colspan="4" style=" padding: 8px; text-align: left; font-weight: bold; border-bottom: 1px solid black;">
                    <span class="checkbox">✓</span>    Driving a Forklift
                </td>
                <td colspan="4"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border-right: 1px solid black; border-bottom: 1px solid black;" >
                    <span class="checkbox">✓</span>   Etc.
                </td>
            </tr>
            <tr>
                
            </tr>
             

            
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-top: 0px;">
            <tr>
                <td colspan="26"
                    style="background-color: #FF9933; color: FF9933; text-align: left; font-weight: bold; font-size: 14px; padding: 4px; border: 1px solid black; letter-spacing: 3px;">
                    <u> JOB HISTORY</u>
                </td>
            </tr>
        
             <tr>
                <th colspan="1"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border: 1px solid black;">
                    NO.
                </th>
                 <th colspan="12"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border: 1px solid black;">
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
            @endphp
           @for ($i = 0; $i < $limit ; $i++)
            <tr>
               <td colspan="1"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border: 1px solid black;">
                   {{ $i+1}}
                </td>
                 <td colspan="12"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border: 1px solid black;">
                 
                </td>
                <td colspan="5"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border: 1px solid black;">
                 
                </td>
                <td colspan="4"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border: 1px solid black;">
                  
                </td>
                 <td colspan="4"
                    style=" padding: 8px ; text-align: left; font-weight: bold; border: 1px solid black;">
                 
                </td>
            </tr>
             @endfor

             <tr>
                <td  colspan="26" style=" padding: 8px ; text-align: left; font-weight: bold; border: 1px solid black;">
                    EMERGENCY CONTACT NAME <small>(ผู้ติดต่อฉุกเฉิน)</small>: นายอนุชา โยธานันท์ 
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;   
                    STATUS <small>(ความสัมพันธ์)</small> : แฟน 
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                    TEL: 081-2345678

                </td>
             </tr>
             <tr>
                <td  colspan="26" style=" padding: 8px ; text-align: left; font-weight: bold; border: 1px solid black;">
                    DRIVING LICENSE : <small>(ใบขับขี่)</small>: <span class="checkbox">✓</span> NO  <span class="checkbox">✓</span> YES
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                    TYPE OF CAR : <small>(ประเภทรถ)</small> : PUBLIC VEHICLE CLASS2
                    &nbsp; <br><br>
                    Valid Until date : <small>(วันหมดอายุใบขับขี่)</small> : 03/12/2028
                </td>
             </tr>
            
             

        </table>
<table style="width: 100%; border-collapse: collapse;">
    <tr>
        <td style="width: 40%; padding-top: 20px; text-align: center; font-weight: bold;">
            ____________________________________________ <br><br>
            <small>DATE & LOCATION <br>วันที่ทดสอบ + สถานที่ทดสอบ</small>
        </td>

        <td style="width: 20%"></td>

        <td style="width: 40%; padding-top: 20px; font-weight: bold; text-align: left;">
            SIGN ____________________________________________ Job Applicants<br><br>
            Recommender: __________________________________________<br>
          
        </td>
        
    </tr>
    
     
</table>

    </div>

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
         <td style="padding: 8px" colspan="10"><b>ชื่อบิดา:</b></td>
         <td colspan="10"></td>
         <td colspan="10"><b>ชื่อบิดา:</b></td>
         </tr>
         
          <tr>
         <td style="padding: 8px" colspan="10"><b>Email:</b></td>

     
         <td colspan="10"></td>
         <td colspan="10"></td>
         </tr>
         <br>
           <tr>
         <td style="padding: 8px" colspan="10"><b>เลขที่บัญชี :</b></td>
      
         <td colspan="10"></td>
         <td colspan="10"><b>ธนาคาร</b></td>
         </tr>
          <tr>
         <td style="padding: 8px" colspan="50"><b>ไซส์เสื้อ :</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <b>ไซส์กางเกง :</b>  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b>ไซส์รองเท้า :</b></td>
       
         </tr>
         <br>

           <tr>
                <td colspan="50"
                    style="background-color: #FF9933; color: FF9933; text-align: center; font-weight: bold; font-size: 14px; padding: 4px; border: 1px solid black;">
                   CHECK LIST เอกสารผู้สมัคร
                </td>
            </tr>
              <tr >
         <td style="padding: 8px" colspan="10"><b>งาน:</b></td>
         <td colspan="10"></td>
         <td colspan="10"><b>ตำแหน่ง:</b></td>
         </tr>
           <tr >
         <td style="padding: 8px" colspan="10"><b>ชื่อ - นามสกุล :</b></td>
         <td colspan="10"></td>
         <td colspan="10"><b>เบอร์โทร :</b></td>
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
             $limit = 7;
            @endphp
           @for ($i = 0; $i < $limit ; $i++)
           
        

         <tr>
                <td colspan="4"
                    style= "padding: 8px; text-align: left; font-weight: bold;">
                    <span class="checkbox">✓</span>   CARPENTER
                </td>
                 <td colspan="40"></td>
              
                <td colspan="4"
                    style=" padding: 8px ; text-align: left; font-weight: bold; ">
                    <span class="checkbox">✓</span>   ROLLER
                </td>
            </tr>

            @endfor
     </table>
     <br>
     <br>

     <div style="padding: 10px; text-align: left; font-weight: bold;">การยืนยันสถานะทางกฎหมายและความบริสุทธิ์ทางคดี</div>
     <div style="padding: 10px; text-align: left; font-weight: bold;"> <span class="checkbox">✓</span>   ไม่มีคดี &nbsp; &nbsp; &nbsp; <span class="checkbox">✓</span>   มีคดี ระบุรายละเอียด.</div>
     <div style="padding: 10px; text-align: left; font-weight: bold;">[ ] ข้าพเจ้ายืนยันว่าข้าพเจ้าไม่เคยมีประวัติอาชญากรรม ไม่เคยถูกดำเนินคดีอาญาใด ๆ  ทั้งในอดีตและ																									
ปัจจุบัน และไม่มีคดีอาญาที่อยู่ในกระบวนการพิจารณาของศาล หากมีการเปลี่ยนแปลงเกี่ยวกับสถานะทาง																									
กฎหมายในอนาคต ข้าพเจ้าจะแจ้งให้บริษัททราบโดยทันที </div>
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


