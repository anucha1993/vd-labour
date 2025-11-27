<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Demand Report #{{ $demand->dm_id }}</title>
    <style>
        body {
            font-family: 'Sarabun', 'THSarabunNew', 'DejaVu Sans', sans-serif;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #F16731;
        }
        .company-name-th {
            font-size: 14px;
            color: #2E7D32;
            font-weight: bold;
        }
        .document-title {
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            color: #333;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 8px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            width: 150px;
        }
        .positions-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .positions-table th,
        .positions-table td {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
        }
        .positions-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .positions-table .text-left {
            text-align: left;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            color: #333;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .description-box {
            border: 1px solid #ccc;
            padding: 15px;
            margin: 10px 0;
            background-color: #f9f9f9;
            min-height: 60px;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
        }
        .signature-area {
            margin-top: 50px;
        }
        .date-info {
            margin-bottom: 10px;
        }
        .total-row {
            background-color: #e8f5e8;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-info">
            <div class="company-name">V.DRAGON RECRUITMENT CO., LTD.</div>
            <div class="company-name-th">บริษัท จัดหางาน วี ดรากอน จำกัด</div>
        </div>
        <div class="document-title">รายงานความต้องการแรงงาน (DEMAND REPORT)</div>
        <div class="date-info">
            <strong>เลขที่เอกสาร:</strong> {{ $demand->dm_let_no }} &nbsp;&nbsp;&nbsp;
            <strong>วันที่ออกหนังสือ:</strong> {{ $demand->dm_issue_date->format('d/m/Y') }}
        </div>
    </div>

    <!-- Company Information -->
    <div class="section-title">ข้อมูลบริษัทนายจ้าง</div>
    <table class="info-table">
        <tr>
            <td class="label">ชื่อบริษัท:</td>
            <td>{{ $demand->dm_com_name }}</td>
        </tr>
        <tr>
            <td class="label">หมายเลขทะเบียน:</td>
            <td>{{ $demand->dm_reg_no }}</td>
        </tr>
        <tr>
            <td class="label">ประเภทอุตสาหกรรม:</td>
            <td>{{ $demand->industryType->industry_type_name_th ?? $demand->industryType->industry_type_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">ที่อยู่:</td>
            <td>{{ $demand->dm_com_addr }}</td>
        </tr>
    </table>

    <!-- Job Requirements -->
    <div class="section-title">ตำแหน่งงานที่ต้องการ</div>
    <table class="positions-table">
        <thead>
            <tr>
                <th width="5%">ลำดับ</th>
                <th width="35%">ตำแหน่งงาน</th>
                <th width="15%">จำนวน (คน)</th>
                <th width="20%">ระยะเวลาทำงาน</th>
                <th width="25%">อายุที่ต้องการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($demand->positions as $index => $position)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left">{{ $position->position->position_name ?? 'N/A' }}</td>
                <td>{{ number_format($position->position_dm_amount, 0) }}</td>
                <td>{{ $position->position_dm_period }}</td>
                <td>{{ $position->position_dm_age }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2"><strong>รวมทั้งหมด</strong></td>
                <td><strong>{{ number_format($demand->positions->sum('position_dm_amount'), 0) }}</strong></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <!-- Work Details -->
    @if($demand->dm_job || $demand->dm_time_work || $demand->dm_bmi)
    <div class="section-title">รายละเอียดงาน</div>
    <table class="info-table">
        @if($demand->dm_job)
        <tr>
            <td class="label">ลักษณะงาน:</td>
            <td>
                <div class="description-box">{{ $demand->dm_job }}</div>
            </td>
        </tr>
        @endif
        
        @if($demand->dm_time_work)
        <tr>
            <td class="label">เวลาทำงาน:</td>
            <td>{{ $demand->dm_time_work }}</td>
        </tr>
        @endif
        
        @if($demand->dm_bmi)
        <tr>
            <td class="label">ความต้องการ BMI:</td>
            <td>{{ $demand->dm_bmi }}</td>
        </tr>
        @endif
        
        @if($demand->dm_exp)
        <tr>
            <td class="label">รายละเอียดงานและคุณสมบัติ:</td>
            <td>
                @php
                    $expOptions = [
                        'accomm' => 'Accommodation (ที่พัก)',
                        'food' => 'Food (อาหาร)', 
                        'med' => 'Medical (การแพทย์)',
                        'shuttle' => 'Shuttle (รถรับส่ง)'
                    ];
                    $selectedExps = is_array($demand->dm_exp) ? $demand->dm_exp : [$demand->dm_exp];
                    $expLabels = [];
                    foreach($selectedExps as $exp) {
                        if(isset($expOptions[$exp])) {
                            $expLabels[] = $expOptions[$exp];
                        }
                    }
                @endphp
                {{ implode(', ', $expLabels) }}
            </td>
        </tr>
        @endif
    </table>
    @endif

    <!-- Salary & Benefits -->
    @if($demand->dm_sa)
    <div class="section-title">เงินเดือนและสวัสดิการ</div>
    <div class="description-box">{{ $demand->dm_sa }}</div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="signature-area">
            <p>จัดทำโดย: บริษัท จัดหางาน วี ดรากอน จำกัด</p>
            <p>วันที่พิมพ์: {{ now()->format('d/m/Y H:i:s') }}</p>
            <br>
            <p>________________________</p>
            <p>ผู้จัดทำเอกสาร</p>
        </div>
    </div>
</body>
</html>