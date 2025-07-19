<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ประวัติข้อมูลคนงาน</title>
  <style>
    body {
      font-family: 'Sarabun', sans-serif;
      margin: 0;
      background: #f9f9f9;
      color: #333;
    }
    .container {
      max-width: 1000px;
      margin: 20px auto;
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }
    h1, h2 {
      text-align: center;
      color: #005a9c;
    }
    .section {
      margin-bottom: 25px;
    }
    .section h3 {
      border-bottom: 2px solid #005a9c;
      padding-bottom: 5px;
      color: #005a9c;
    }
    .field {
      margin-bottom: 8px;
    }
    .label {
      font-weight: bold;
      display: inline-block;
      width: 240px;
    }
    .value {
      display: inline-block;
    }
  </style>
</head>
<body>
  <div class="container">
    
    <div class="text-center mt-4">
        {!! QrCode::size(150)->generate(route('labour.print', $labour->labour_id)) !!}
    </div>

    <h1>ประวัติข้อมูลคนงาน</h1>
    <div class="section">
      <h3>ข้อมูลทั่วไป</h3>
      <div class="field"><span class="label">Full Name:</span>{{ $labour->labour_prefix }} {{ $labour->labour_firstname }} {{ $labour->labour_lastname }}</div>
      <div class="field"><span class="label">Birthday:</span>{{ \Carbon\Carbon::parse($labour->labour_birthday)->format('d/m/Y') }}</div>
      <div class="field"><span class="label">Phone:</span>{{ $labour->labour_phone }}</div>
      <div class="field"><span class="label">Customer (นายจ้าง):</span>{{ $labour->customer?->customer_name ?? '-' }}</div>
    </div>

    <div class="section">
      <h3>ข้อมูลหนังสือเดินทาง</h3>
      <div class="field"><span class="label">Passport No.:</span>{{ $labour->labour_passport_number ?? '-' }}</div>
      <div class="field"><span class="label">Data Issue:</span>{{ $labour->labour_passport_issue ?? '-' }}</div>
      <div class="field"><span class="label">Data Expiry:</span>{{ $labour->labour_passport_expiry ?? '-' }}</div>
      <div class="field"><span class="label">Register Number:</span>{{ $labour->labour_register_number ?? '-' }}</div>
    </div>

    <div class="section">
      <h3>ข้อมูลผลโรค</h3>
      <div class="field"><span class="label">Disease Start:</span>{{ $labour->labour_disease_start ?? '-' }}</div>
      <div class="field"><span class="label">Disease Expiry:</span>{{ $labour->labour_disease_expriry ?? '-' }}</div>
      <div class="field"><span class="label">วันที่รับผลโรค:</span>{{ $labour->labour_disease_results_date ?? '-' }}</div>
    </div>

    <div class="section">
      <h3>ข้อมูล CID</h3>
      <div class="field"><span class="label">วันที่ยื่น CID:</span>{{ $labour->labour_cid_stand_date ?? '-' }}</div>
      <div class="field"><span class="label">CID Start:</span>{{ $labour->labour_cid_start ?? '-' }}</div>
      <div class="field"><span class="label">CID Expiry:</span>{{ $labour->labour_cid_expriry ?? '-' }}</div>
      <div class="field"><span class="label">CID Results:</span>{{ $labour->cid?->cid_results_text ?? '-' }}</div>
      <div class="field"><span class="label">CID File:</span>{{ $labour->labour_cid_results_file ?? '-' }}</div>
    </div>

    <div class="section">
      <h3>ข้อมูลกลุ่มงาน</h3>
      <div class="field"><span class="label">Examination round:</span>{{ $labour->labour_examination ?? '-' }}</div>
      <div class="field"><span class="label">Country:</span>{{ $labour->country?->country_name ?? '-' }}</div>
      <div class="field"><span class="label">Job Group:</span>{{ $labour->jobGroup?->job_group_name ?? '-' }}</div>
      <div class="field"><span class="label">Position:</span>{{ $labour->position?->position_name ?? '-' }}</div>
      <div class="field"><span class="label">Location Test:</span>{{ $labour->locationTest?->location_test_name ?? '-' }}</div>
      <div class="field"><span class="label">Docs. Type:</span>{{ $labour->labour_location_doc ?? '-' }}</div>
    </div>

    <div class="section">
      <h3>ข้อมูลสถานะ</h3>
      <div class="field"><span class="label">สายหาคน:</span>{{ $labour->staffSub?->staff_sub_name ?? '-' }}</div>
      <div class="field"><span class="label">Staff:</span>{{ $labour->staff?->staff_name ?? '-' }}</div>
      <div class="field"><span class="label">Status:</span>{{ $labour->labour_status ?? '-' }}</div>
    </div>

    <div class="section">
      <h3>ข้อมูลบัญชี</h3>
      <div class="field"><span class="label">วันที่วางเงินประกัน:</span>{{ $labour->labour_cid_deposit_date ?? '-' }}</div>
      <div class="field"><span class="label">จำนวนเงินวางเงินประกัน:</span>{{ number_format($labour->labour_cid_deposit_total ?? 0, 2) }}</div>
      <div class="field"><span class="label">Date CID-P:</span>{{ $labour->labour_cidp_date ?? '-' }}</div>
      <div class="field"><span class="label">CID-P Total:</span>{{ number_format($labour->labour_cidp_total ?? 0, 2) }}</div>
      <div class="field"><span class="label">ประเภทการชำระเงิน:</span>{{ $labour->payment_type ?? '-' }}</div>
      <div class="field"><span class="label">วันที่รับ Date CID-P:</span>{{ $labour->labour_cidp_in_date ?? '-' }}</div>
      <div class="field"><span class="label">จำนวนเงินรับ CID-P Total:</span>{{ number_format($labour->labour_cidp_in_total ?? 0, 2) }}</div>
      <div class="field"><span class="label">สถานะการคืนเงินประกัน:</span>{{ $labour->labour_cid_deposit_status ?? 'None' }}</div>
      <div class="field"><span class="label">วันที่คืนเงินวางประกัน:</span>{{ $labour->labour_refund_deposit_date ?? '-' }}</div>
      <div class="field"><span class="label">จำนวนเงินคืนวางเงินประกัน:</span>{{ number_format($labour->labour_refund_deposit_total ?? 0, 2) }}</div>
    </div>

    <div class="section">
      <h3>บันทึกเพิ่มเติม</h3>
      <div class="field"><span class="label">หมายเหตุ:</span>{{ $labour->labour_note ?? '-' }}</div>
    </div>
  </div>
</body>
</html>
