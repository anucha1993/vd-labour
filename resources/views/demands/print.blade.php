<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEMAND LETTER - {{ $demand->dm_let_no }}</title>
    <style>
        body {
            font-family: 'Sarabun', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            background: white;
        }
        
        .print-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 20px;
            padding: 10px;
            border: 2px solid #000;
            background-color: #f8f9fa;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        
        .info-table td, .info-table th {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        
        .info-table .label {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 45%;
            vertical-align: top;
        }

        .work-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        
        .work-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        
        .work-table .label {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 25%;
            vertical-align: top;
        }
        
        .qualification-header {
            text-align: center;
            font-weight: bold;
            background-color: #f8f9fa;
            padding: 10px;
        }
        
        .positions-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .positions-table th, .positions-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        
        .positions-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .positions-table .position-name {
            text-align: left;
        }
        
        .bmi-note {
            text-align: center;
            font-weight: bold;
            margin: 10px 0;
            padding: 5px;
            vertical-align: middle;
        }
        

        
        .checkbox-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .checkbox-list li {
            margin-bottom: 8px;
            padding-left: 25px;
            position: relative;
        }
        
        .checkbox {
            position: absolute;
            left: 0;
            top: 2px;
            width: 15px;
            height: 15px;
            border: 1px solid #000;
            display: inline-block;
        }
        
        .checkbox.checked::after {
            content: "✓";
            position: absolute;
            left: 2px;
            top: -2px;
            font-size: 12px;
            font-weight: bold;
        }
        
        @media print {
            body { 
                margin: 0; 
                padding: 0; 
                font-size: 12px;
            }
            .print-container { 
                box-shadow: none; 
                padding: 0;
                max-width: none;
            }
            .no-print { 
                display: none; 
            }
        }
        
        .print-btn {
            margin-bottom: 20px;
            text-align: center;
        }
        
        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
        }
        
        .btn:hover {
            background-color: #0056b3;
        }
        
        .btn-secondary {
            background-color: #6c757d;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
        }
    </style>
</head>
<body>
    <div class="print-btn no-print">
        <button class="btn" onclick="window.print()">
            <i class="bi bi-printer"></i> พิมพ์เอกสาร
        </button>
        <button class="btn btn-secondary" onclick="window.history.back()">
            <i class="bi bi-arrow-left"></i> กลับ
        </button>
    </div>

    <div class="print-container">
        <!-- Header -->
        <div class="header-title">
            DEMAND LETTER (ใบแปลคุณสมบัติของแรงงาน)
        </div>

        <!-- Main Table - All in one continuous table -->
        <table class="info-table">
            <!-- Basic Information -->
            <tr>
                <td class="label">ISSUE DATE (วันที่)</td>
                <td>{{ $demand->dm_issue_date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">DEMAND LETTER NO. (เลขที่ใบแจ้งรายะเอียดงาน)</td>
                <td>{{ $demand->dm_let_no }}</td>
            </tr>
            <tr>
                <td class="label">COMPANY NAME (ชื่อบริษัท)</td>
                <td>{{ $demand->dm_com_name }}</td>
            </tr>
            <tr>
                <td class="label">COMPANY ADDRESS (ที่อยู่บริษัท)</td>
                <td>{{ $demand->dm_com_addr }}</td>
            </tr>
            <tr>
                <td class="label">REGISTRATION NO. (เลขทะเบียนบริษัท)</td>
                <td>{{ $demand->dm_reg_no }}</td>
            </tr>
            <tr>
                <td class="label">INDUSTRY TYPE (ประเภทกิจ)</td>
                <td>{{ $demand->industryType->industry_type_name_th ?? $demand->industryType->inducstry_type_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">LOCATION AND DATE (สถานที่ และ วัน เดือน ปี)</td>
                <td>{{ $demand->location ?? '' }} {{ $demand->date ?? '' }}</td>
            </tr>

            <!-- Qualification Header -->
            <tr>
                <td colspan="2" class="qualification-header">
                    QUALIFICATION (คุณสมบัติของผู้รับเอียดงานและรายละเอียดในการจ้างงาน)
                </td>
            </tr>

            <!-- Positions Section -->
            <tr>
                <td colspan="2" style="padding: 0;">
                    <table class="positions-table" style="width: 100%; margin: 0;">
                        <thead>
                            <tr>
                                <th rowspan="2">Position (ตำแหน่ง)</th>
                                <th rowspan="2">Amount<br>(จำนวน)</th>
                                <th rowspan="2">Employment<br>Period<br>(ระยะเวลาจ้าง)</th>
                                <th rowspan="2">Age (อายุ)<br>Sex (เพศ)</th>
                            </tr>
                           
                        </thead>
                        <tbody>
                            @forelse($demand->positions as $index => $position)
                            <tr>
                                <td class="position-name">{{ $index + 1 }}. {{ $position->position->position_name ?? 'N/A' }}</td>
                                <td>{{ $position->position_dm_amount }}</td>
                                <td>{{ $position->position_dm_period }}</td>
                                <td>{{ $position->position_dm_age }}</td>
                            </tr>

                            @empty
                            <tr>
                                <td colspan="5" class="text-center">ไม่มีข้อมูลตำแหน่งงาน</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </td>
            </tr>

            <!-- BMI Note -->
            <tr>
                <td colspan="2" class="bmi-note" style="text-align: center; vertical-align: middle;">
                    **ดัชนี BMI ต้องไม่ต่ำกว่า 18 และไม่เกิน 30**
                </td>
            </tr>

        </table>

        <!-- Time to Work Table (separate table with border connected) -->
        <table class="work-table " style="border-top: none;">
            <tr>
                <td class="label" style="text-align: center; vertical-align: middle;">TIME TO WORK<br>(เวลาทำงาน)</td>
                <td>{{ $demand->dm_time_work ?? '' }}</td>
            </tr>
        </table>

        <!-- Other Information Table (continues with connected border) -->
        <table class="work-table" style="border-top: none;">
            <!-- Salary -->
            <tr>
                <td class="label" style="text-align: center; vertical-align: middle;">SALARY (เงินเดือน)</td>
                <td>{{ $demand->dm_sa ?? '' }}</td>
            </tr>

            <!-- Job Description -->
            <tr>
                <td class="label" style="text-align: center; vertical-align: middle;">JOB DESCRIPTION<br>AND<br>REQUIREMENTS<br><br>(รายะเอียดงานและ<br>คุณสมบัติ)</td>
                <td>{{ $demand->dm_job ?? '' }}</td>
            </tr>

            <!-- Expense and Benefit -->
            <tr>
                <td class="label" style="text-align: center; vertical-align: middle;">EXPENSE AND<br>BENEFIT<br><br>(ค่าใช้จ่ายและสวัสดิการ)</td>
                <td>
                    @if($demand->dm_exp && is_array($demand->dm_exp))
                    <ul class="checkbox-list">
                        <li>
                            <span class="checkbox {{ in_array('accomm', $demand->dm_exp) ? 'checked' : '' }}"></span>
                            ที่พักอาศัย นายจ้างจะจัดหาที่พักให้โดยจะหักจากเงินเดือนตามที่กฎหมายกำหนด
                        </li>
                        <li>
                            <span class="checkbox {{ in_array('food', $demand->dm_exp) ? 'checked' : '' }}"></span>
                            Food provided by workers อาหารจัดเตรียมโดยคนงานเอง
                        </li>
                        <li>
                            <span class="checkbox {{ in_array('med', $demand->dm_exp) ? 'checked' : '' }}"></span>
                            ประกันสุขภาพ ตามกฎหมายอิสราเอล นายจ้างจะจัดให้มีประกันสุขภาพสำหรับลูกจ้างตั้งแต่วันแรกที่เริ่ม ทำงาน โดยจะมีการหักค่าใช้จ่ายจากเงินเดือนของลูกจ้าง
                        </li>
                        <li>
                            <span class="checkbox {{ in_array('shuttle', $demand->dm_exp) ? 'checked' : '' }}"></span>
                            รถรับส่งฟรี
                        </li>
                    </ul>
                    @else
                    <p>ไม่มีข้อมูลค่าใช้จ่ายและสวัสดิการ</p>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>