@extends('layouts.main')
@section('content')

<style>
  .alert-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 8px 0 rgba(0,0,0,0.06);
    padding: 1.2rem 1.2rem 1rem 1.2rem;
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    margin-bottom: 1.2rem;
    border: none;
    position: relative;
  }
  .alert-card .alert-label {
    color: #6c757d;
    font-size: 1rem;
    margin-bottom: 0.25rem;
    font-weight: 500;
  }
  .alert-card .alert-value {
    font-size: 2.1rem;
    font-weight: bold;
    color: #222;
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  .alert-card .alert-desc {
    color: #a0a0a0;
    font-size: 0.95rem;
    margin-bottom: 0.2rem;
  }
  .alert-icon {
    font-size: 1.7rem;
    border-radius: 50%;
    padding: 0.4rem;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.5rem;
  }
  .alert-icon.bg-blue { background: #2196f3; }
  .alert-icon.bg-green { background: #4caf50; }
  .alert-icon.bg-orange { background: #ff9800; }
  .alert-icon.bg-red { background: #f44336; }
  .alert-icon.bg-cyan { background: #00bcd4; }
  .alert-icon.bg-purple { background: #673ab7; }
  .alert-badge {
    position: absolute;
    top: 1rem;
    right: 1.2rem;
    background: #f44336;
    color: #fff;
    font-size: 0.85rem;
    border-radius: 12px;
    padding: 0.2rem 0.7rem;
    font-weight: 500;
  }
  .alert-sparkline {
    width: 100%;
    height: 30px;
    margin-bottom: 0.3rem;
  }
</style>

<div class="container-fluid py-4" style="background:#f5f7fa;">

 <a href="{{ route('labours.export.alerts') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-file-excel"></i> Export แจ้งเตือนทั้งหมด (Excel)
                    </a>
                 <br>
                 <br>
    <!-- Alert Cards (Multi-style) -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="alert-card">
                <span class="alert-icon bg-blue"><i class="mdi mdi-account"></i></span>
                <span class="alert-badge">รวม</span>
                <div class="alert-label">ข้อมูลคนงานทั้งหมด</div>
                <div class="alert-value">{{ number_format($countAll) }}</div>
                <div class="alert-desc">ทั้งหมดในระบบ</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="alert-card">
                <span class="alert-icon bg-green"><i class="mdi mdi-airplane"></i></span>
                <span class="alert-badge" style="background:#4caf50;">Active</span>
                <div class="alert-label">ไปทำงานแล้ว</div>
                <div class="alert-value">{{ number_format($countSuccess) }}</div>
                <div class="alert-desc">คนงานที่บินแล้วทั้งหมด</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="alert-card">
                <span class="alert-icon bg-orange"><i class="mdi mdi-close-circle"></i></span>
                <span class="alert-badge" style="background:#ff9800;">ยกเลิก</span>
                <div class="alert-label">จำนวนคนงานยกเลิก</div>
                <div class="alert-value">{{ number_format($countCancel) }}</div>
                <div class="alert-desc">ยกเลิกทั้งหมด</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="alert-card">
                <span class="alert-icon bg-red"><i class="mdi mdi-bell-alert"></i></span>
                <span class="alert-badge">แจ้งเตือน</span>
                <div class="alert-label">แจ้งเตือนรวม</div>
                <div class="alert-value">{{ number_format($scopeExpiringPassport + $scopeExpiringCIDConstruct + $scopeExpiringCIDFactory + $scopeExpiringDiseaseConstruct + $scopeExpiringDiseaseFactory + $scopeExpiringCidMoney + $scopeExpiringAffidavit + $visaNotUpdate + $visaApproved + $visaRejected) }}</div>
                <div class="alert-desc">รวมทุกประเภท</div>
            </div>
        </div>
    </div>


    <!-- Alert Cards (แจ้งเตือนแต่ละประเภท) -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'disease-construct']) }}" class="text-decoration-none">
                <div class="alert-card">
                    <span class="alert-icon bg-cyan"><i class="mdi mdi-hospital-box"></i></span>
                    <span class="alert-badge" style="background:#00bcd4;">ก่อสร้าง</span>
                    <div class="alert-label">แจ้งเตือนผลโรค ก่อสร้าง</div>
                    <div class="alert-value">{{ number_format($scopeExpiringDiseaseConstruct) }}</div>
                    <div class="alert-desc">ก่อนหมดอายุ 15 วัน</div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'disease-factory']) }}" class="text-decoration-none">
                <div class="alert-card">
                    <span class="alert-icon bg-purple"><i class="mdi mdi-hospital"></i></span>
                    <span class="alert-badge" style="background:#673ab7;">โรงงาน</span>
                    <div class="alert-label">แจ้งเตือนผลโรค โรงงาน</div>
                    <div class="alert-value">{{ number_format($scopeExpiringDiseaseFactory) }}</div>
                    <div class="alert-desc">ก่อนหมดอายุ 15 วัน</div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'passport']) }}" class="text-decoration-none">
                <div class="alert-card">
                    <span class="alert-icon bg-blue"><i class="mdi mdi-passport"></i></span>
                    <span class="alert-badge" style="background:#2196f3;">Passport</span>
                    <div class="alert-label">พาสปอร์ตหมดอายุ</div>
                    <div class="alert-value">{{ number_format($scopeExpiringPassport) }}</div>
                    <div class="alert-desc">ก่อนหมดอายุ 15 วัน</div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'cid-construct']) }}" class="text-decoration-none">
                <div class="alert-card">
                    <span class="alert-icon bg-orange"><i class="mdi mdi-card-account-details"></i></span>
                    <span class="alert-badge" style="background:#ff9800;">CID ก่อสร้าง</span>
                    <div class="alert-label">CID หมดอายุ (ก่อสร้าง)</div>
                    <div class="alert-value">{{ number_format($scopeExpiringCIDConstruct) }}</div>
                    <div class="alert-desc">ก่อนหมดอายุ 15 วัน</div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'cid-factory']) }}" class="text-decoration-none">
                <div class="alert-card">
                    <span class="alert-icon bg-orange"><i class="mdi mdi-card-account-details-outline"></i></span>
                    <span class="alert-badge" style="background:#ff9800;">CID โรงงาน</span>
                    <div class="alert-label">CID หมดอายุ (โรงงาน)</div>
                    <div class="alert-value">{{ number_format($scopeExpiringCIDFactory) }}</div>
                    <div class="alert-desc">ก่อนหมดอายุ 15 วัน</div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'cid-money']) }}" class="text-decoration-none">
                <div class="alert-card">
                    <span class="alert-icon bg-red"><i class="mdi mdi-cash"></i></span>
                    <span class="alert-badge">เงินประกัน</span>
                    <div class="alert-label">ยังไม่ได้จ่ายเงินประกัน</div>
                    <div class="alert-value">{{ number_format($scopeExpiringCidMoney) }}</div>
                    <div class="alert-desc">ยื่น CID ไปแล้ว 15 วัน</div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'affidavit']) }}" class="text-decoration-none">
                <div class="alert-card">
                    <span class="alert-icon bg-blue"><i class="mdi mdi-file-document"></i></span>
                    <span class="alert-badge" style="background:#2196f3;">Affidavit</span>
                    <div class="alert-label">แจ้งเตือน Affidavit หมดอายุ</div>
                    <div class="alert-value">{{ number_format($scopeExpiringAffidavit) }}</div>
                    <div class="alert-desc">ก่อนหมดอายุ 15 วัน</div>
                </div>
            </a>
        </div>
        
        <!-- VISA Notifications Row -->
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'visa-not-update']) }}" class="text-decoration-none">
                <div class="alert-card">
                    <span class="alert-icon bg-red"><i class="mdi mdi-passport"></i></span>
                    <span class="alert-badge" style="background:#f44336;">VISA</span>
                    <div class="alert-label">VISA ไม่ Update</div>
                    <div class="alert-value">{{ number_format($visaNotUpdate) }}</div>
                    <div class="alert-desc">ยืนวีซ่าเกิน 75 วัน</div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'visa-approved']) }}" class="text-decoration-none">
                <div class="alert-card">
                    <span class="alert-icon bg-green"><i class="mdi mdi-check-circle"></i></span>
                    <span class="alert-badge" style="background:#4caf50;">VISA</span>
                    <div class="alert-label">VISA อนุมัติแล้ว</div>
                    <div class="alert-value">{{ number_format($visaApproved) }}</div>
                    <div class="alert-desc">ยืนวีซ่าเกิน 75 วัน</div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'visa-rejected']) }}" class="text-decoration-none">
                <div class="alert-card">
                    <span class="alert-icon bg-orange"><i class="mdi mdi-close-circle"></i></span>
                    <span class="alert-badge" style="background:#ff9800;">VISA</span>
                    <div class="alert-label">VISA ไม่อนุมัติ</div>
                    <div class="alert-value">{{ number_format($visaRejected) }}</div>
                    <div class="alert-desc">ยืนวีซ่าเกิน 75 วัน</div>
                </div>
            </a>
        </div>

        <!-- Job Lead Notification -->
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('notifications.index') }}" class="text-decoration-none">
                <div class="alert-card" 
                     data-bs-toggle="tooltip" 
                     data-bs-placement="top" 
                     data-bs-html="true"
                     title="<div style='text-align: left; padding: 8px;'>
                                <strong style='color: #9c27b0; font-size: 14px;'>📋 เงื่อนไขการแจ้งเตือนใบสมัครงาน</strong>
                                <hr style='margin: 8px 0; border-color: #ddd;'>
                                <div style='margin-bottom: 10px;'>
                                    <strong>⏰ ระยะเวลาการแจ้งเตือน:</strong><br>
                                    • <span style='color: #2196f3;'>7 วัน</span> - แจ้งเตือนครั้งที่ 1<br>
                                    • <span style='color: #ff9800;'>14 วัน</span> - แจ้งเตือนครั้งที่ 2<br>
                                    • <span style='color: #f44336;'>21 วัน</span> - แจ้งเตือนครั้งสุดท้าย (สามารถรอได้)<br>
                                    • <span style='color: #d32f2f; font-weight: bold;'>มากกว่า 21 วัน</span> - บังคับถอนใบสมัคร
                                </div>
                                <hr style='margin: 8px 0; border-color: #ddd;'>
                                <div style='margin-bottom: 10px;'>
                                    <strong>🔄 การตรวจสอบอัตโนมัติ:</strong><br>
                                    • รอบเช็ค: <span style='color: #4caf50;'>วันละ 2 รอบ</span><br>
                                    • เวลา: <span style='color: #4caf50;'>ทุกวัน 08:00 น.</span>
                                    • เวลา: <span style='color: #4caf50;'>ทุกวัน 12:00 น.</span>
                                    • เวลา: <span style='color: #4caf50;'>ทุกวัน 17:00 น.</span>
                                </div>
                                <hr style='margin: 8px 0; border-color: #ddd;'>
                                <div style='font-size: 12px; color: #666;'>
                                    <strong>📌 สถานะที่ติดตาม:</strong><br>
                                    ร่าง | ส่งแล้ว | กำลังพิจารณา<br>
                                    นัดสัมภาษณ์ | เสนองาน
                                </div>
                            </div>">
                    <span class="alert-icon bg-purple"><i class="mdi mdi-briefcase-alert"></i></span>
                    <span class="alert-badge" style="background:#9c27b0;">ใบสมัครงาน</span>
                    <div class="alert-label">แจ้งเตือนใบสมัครงาน</div>
                    <div class="alert-value">{{ number_format($jobLeadNotifications) }}</div>
                    <div class="alert-desc">ต้องติดตาม/ตอบกลับ</div>
                </div>
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-lg-10">
            <div class="card shadow-sm p-4">
                <h5 class="mb-3">เปรียบเทียบจำนวนแจ้งเตือนแต่ละประเภทกับจำนวนคนงานทั้งหมด</h5>
                <div style="height:340px;">
                  <canvas id="alertCompareChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    </div>


    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Chart: เปรียบเทียบการแจ้งเตือนกับจำนวนคนงานทั้งหมด -->

   


<script>
  const alertCompareCtx = document.getElementById('alertCompareChart').getContext('2d');
  new Chart(alertCompareCtx, {
    type: 'bar',
    data: {
      labels: [
        'คนงานทั้งหมด',
        'ผลโรค ก่อสร้าง',
        'ผลโรค โรงงาน',
        'พาสปอร์ตหมดอายุ',
        'CID ก่อสร้าง',
        'CID โรงงาน',
        'ยังไม่ได้จ่ายเงินประกัน',
        'Affidavit หมดอายุ',
        'VISA ไม่ Update',
        'VISA อนุมัติแล้ว',
        'VISA ไม่อนุมัติ'
      ],
      datasets: [{
        label: 'จำนวน',
        data: [
          {{ $countAll ?? 0 }},
          {{ $scopeExpiringDiseaseConstruct ?? 0 }},
          {{ $scopeExpiringDiseaseFactory ?? 0 }},
          {{ $scopeExpiringPassport ?? 0 }},
          {{ $scopeExpiringCIDConstruct ?? 0 }},
          {{ $scopeExpiringCIDFactory ?? 0 }},
          {{ $scopeExpiringCidMoney ?? 0 }},
          {{ $scopeExpiringAffidavit ?? 0 }},
          {{ $visaNotUpdate ?? 0 }},
          {{ $visaApproved ?? 0 }},
          {{ $visaRejected ?? 0 }}
        ],
        backgroundColor: [
          '#2196f3', '#00bcd4', '#673ab7', '#2196f3', '#ff9800', '#ff9800', '#f44336', '#2196f3', '#f44336', '#4caf50', '#ff9800'
        ],
        borderRadius: 10,
        maxBarThickness: 60
      }]
    },
    options: {
      responsive: true,
      aspectRatio: 2.5,
      layout: {
        padding: { top: 20, bottom: 10, left: 10, right: 10 }
      },
      plugins: {
        legend: { display: false },
        title: { display: false },
        tooltip: {
          bodyFont: { size: 16 },
          titleFont: { size: 15 }
        }
      },
      scales: {
        x: {
          ticks: { font: { size: 15 } }
        },
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1, font: { size: 15 } }
        }
      }
    }
  });

  // Initialize Bootstrap Tooltips
  document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  });
</script>

</div>

@endsection
