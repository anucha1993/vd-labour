<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CV Form Complete</title>
    <style>
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
                <div style="color: #F16731; font-size: 18px; font-weight: bold;">V.DRAGON RECRUITMENT CO., LTD.</div>
                <div style="color: #2E7D32; font-size: 14px; font-weight: bold;">บริษัท จัดหางาน วี ดรากอน จำกัด</div>
            </td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
    <!-- Header Row -->
    <tr>
        <td colspan="24" style="background-color: #FF9933; color: black; text-align: center; font-weight: bold; font-size: 14px; padding: 8px; border: 2px solid black; letter-spacing: 3px;">
           <u> APPLICATION FORM</u>
        </td>
    </tr>
        <tr>
            <td  colspan="4" style="border-left: 1px solid black; padding: 8px; text-align: left; font-weight: bold;">
                Male <span class="checkbox">✓</span>
            </td>
            <td  colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                Female <span class="checkbox">✓</span>
            </td>
            <td  colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                Single <span class="checkbox">✓</span>
            </td>
            <td  colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                Married <span class="checkbox">✓</span>
            </td>
            <td  colspan="4" style=" padding: 8px; text-align: left; font-weight: bold;">
                Divorced <span class="checkbox">✓</span>
            </td>
            <td  colspan="4" rowspan="6" style="border: 1px solid black; text-align: center; font-weight: bold; font-size: 24px; vertical-align: middle; width: 120px; background-color: #f9f9f9;">
                PHOTO
            </td>
        </tr>
    
    <!-- PERSONAL INFORMATION Header -->
    <tr>
        <td colspan="20" style=" color: #FF9933; font-weight: bold; padding: 6px 10px; border-left: 1px solid black; border-right: 1px solid black;">
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
    <td colspan="20" style="border: 1px solid black; padding: 4px; font-weight:bold">Permanent Address :</td>
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
    <td colspan="8" style="border: 1px solid black; padding: 4px; font-weight: bold;">Passport/ID Card No :</td>
    <td colspan="8" style="border: 1px solid black; padding: 4px; font-weight: bold;">Date of Issue :</td>
    <td colspan="8" style="border: 1px solid black; padding: 4px; font-weight: bold;">Date of Expiry :</td>
</tr>

<!-- Size Row -->
{{--<tr>
    <td colspan="7" style="border: 1px solid black; padding: 4px; font-weight: bold; ">Shirt size :</td>
    <td colspan="7" style="border: 1px solid black; padding: 4px; font-weight: bold; ">Pant size:</td>
    <td colspan="6" style="border: 1px solid black; padding: 4px; font-weight: bold;">Shoes size :</td>
</tr> --}}
    
</table>



</div>
</body>
</html>