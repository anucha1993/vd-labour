<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>VD-LABOURS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Bootstrap 5 Bundle (Modal, Popper, etc.) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery (for Select2 only, not for Bootstrap 5 modal) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <!-- Select2 CSS & JS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <!-- DataTables Bootstrap 5.3 CSS (ถูกต้อง) -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" />

  <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('../assets/images/favicon.png') }}" />
  <!-- DataTables JS (core + Bootstrap 5.3 integration) -->
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

  <style>
    body {
      font-family: 'Prompt', sans-serif;
      background: linear-gradient(to right, #f8fbff, #e5f0ff);
      min-height: 100vh;
    }
    .sidebar {
      height: 100vh;
      background: #001d3d;
      color: white;
      position: fixed;
    }
    .sidebar a {
      color: #adb5bd;
      text-decoration: none;
      display: block;
      padding: 10px 20px;
      transition: all 0.2s;
    }
    .sidebar a:hover, .sidebar .active {
      background: #003566;
      color: #fff;
    }
    /* Dropdown Sidebar */
    .sidebar-dropdown {
      position: relative;
    }
    .sidebar-dropdown > a {
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .sidebar-dropdown-menu {
      display: none;
      padding-left: 15px;
      margin-top: 5px;
    }
    .sidebar-dropdown-menu.show {
      display: block;
    }
    .sidebar-dropdown-menu a {
      padding: 8px 20px;
      font-size: 0.9em;
      border-left: 2px solid #4a5568;
      margin-left: 10px;
    }
    .sidebar-dropdown-menu a:hover {
      border-left-color: #fff;
    }
    .dropdown-icon {
      transition: transform 0.3s;
    }
    .dropdown-icon.rotate {
      transform: rotate(90deg);
    }
    .text-purple {
      color: #9c27b0 !important;
    }
    .bg-purple {
      background-color: #9c27b0 !important;
    }
  .content {
  margin-left: 250px;
  padding: 30px;
  padding-top: 80px; /* เพิ่มจากเดิม */
}

    .card-custom {
      border: none;
      border-radius: 20px;
      box-shadow: 0 0 25px rgba(0, 0, 0, 0.05);
    }
    .card-title {
      font-weight: bold;
    }

    /* Modern Table Styling */
    .table {
      background: #fff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 2px 16px 0 rgba(178,60,60,0.07);
      margin-bottom: 32px;
    }
    .table thead {
      background: #fbeee6;
      color: #b23c3c;
      font-weight: 600;
      border-bottom: 2px solid #003566;
    }
    .table th {
      padding: 14px 18px;
      vertical-align: middle;
      border: none;
      font-size: 1.08em;
      letter-spacing: 0.5px;
      font-weight: bold;
    }
    .table td {
      padding: 14px 18px;
      vertical-align: middle;
      border: none;
      font-size: 0.98em;
      font-weight: 400;
    }
    .table-striped > tbody > tr:nth-of-type(odd) {
      background-color: #fff7f3;
    }
    .table-hover > tbody > tr:hover {
      background: #ffe5e0;
      transition: background 0.2s;
    }
    .table tbody tr {
      border-bottom: 1px solid #f5c6cb;
    }
    .table-responsive {
      border-radius: 16px;
      overflow-x: auto;
      box-shadow: 0 2px 16px 0 rgba(178,60,60,0.07);
    }
    @media (max-width: 767px) {
      .table th, .table td {
        padding: 10px 8px;
        font-size: 0.95em;
      }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar p-4" style="width:250px;">
  <h4 class="mb-4">📘 VD-LABOURS</h4>
 <a href="{{ route('my-leads.index') }}"
     class="{{ Request::routeIs('my-leads.*') ? 'active' : '' }}">
     <i class="bi bi-people-fill me-2"></i> ผู้สมัครของเรา</a>
  <a href="{{ route('dashboards.index') }}"
     class="{{ Request::routeIs('dashboards.index') ? 'active' : '' }}">
     <i class="bi bi-house-fill me-2"></i> Dashboard</a>

  <a href="{{ route('labour.index') }}"
     class="{{ Request::routeIs('labour.index') ? 'active' : '' }}">
     <i class="bi bi-person-badge-fill me-2"></i> ข้อมูลคนงาน</a>

  <a href="{{ route('leads.index') }}"
     class="{{ Request::routeIs('leads.*') && !Request::routeIs('my-leads.*') ? 'active' : '' }}">
     <i class="bi bi-person-plus-fill me-2"></i> ข้อมูลผู้สมัคร</a>

 

  @can('view demand')
  <a href="{{ route('demands.index') }}"
     class="{{ Request::routeIs('demands.*') ? 'active' : '' }}">
     <i class="bi bi-file-earmark-text-fill me-2"></i>Demands</a>
  @endcan

  <!-- Job Application Management Dropdown -->
  @canany(['job-list', 'job-lead-list', 'job-dashboard'])
  <div class="sidebar-dropdown">
    <a class="{{ Request::routeIs('jobs.*', 'job-leads.*') || request()->routeIs('job-dashboard.index') ? 'active' : '' }}">
      <span><i class="bi bi-briefcase-fill me-2"></i> ระบบใบสมัครงาน</span>
      <i class="bi bi-chevron-right dropdown-icon"></i>
    </a>
    <div class="sidebar-dropdown-menu {{ Request::routeIs('jobs.*', 'job-leads.*') || request()->routeIs('job-dashboard.index') ? 'show' : '' }}">
      @can('job-dashboard')
      <a href="{{ route('jobs.dashboard') }}"
         class="{{ request()->routeIs('job-dashboard.index') ? 'active' : '' }}">
         <i class="bi bi-graph-up me-2"></i> สรุป</a>
      @endcan
      
      @can('job-list')
      <a href="{{ route('jobs.index') }}"
         class="{{ Request::routeIs('jobs.index', 'jobs.show', 'jobs.create', 'jobs.edit') ? 'active' : '' }}">
         <i class="bi bi-briefcase me-2"></i> จัดการงาน</a>
      @endcan
      
      @can('job-lead-list')
      <a href="{{ route('job-leads.index') }}"
         class="{{ Request::routeIs('job-leads.*') ? 'active' : '' }}">
         <i class="bi bi-person-lines-fill me-2"></i> จัดการใบสมัคร</a>
      @endcan

      @can('job-lead-convert')
      <a href="{{ route('job-leads.conversion.index') }}"
         class="{{ Request::routeIs('job-leads.conversion.*') ? 'active' : '' }}">
         <i class="bi bi-arrow-repeat me-2"></i>รอ Convert 
         @if(isset($pendingConversionCount) && $pendingConversionCount > 0)
         <span class="badge bg-danger rounded-pill">{{ number_format($pendingConversionCount) }}</span>
         @endif
      </a>
      @endcan
    </div>
  </div>
  @endcanany

  <a href="{{ route('export.form.labour') }}"
     class="{{ Request::routeIs('export.form.labour') ? 'active' : '' }}">
     <i class="bi bi-clipboard-data-fill me-2"></i> รายงาน</a>

  <a href="{{ route('customer.index') }}"
     class="{{ Request::routeIs('customer.index') ? 'active' : '' }}">
     <i class="bi bi-briefcase-fill me-2"></i> ข้อมูลนายจ้าง</a>

  <a href="{{ route('category.examination') }}"
     class="{{ Request::routeIs('category.examination') ? 'active' : '' }}">
     <i class="bi bi-calendar2-week-fill me-2"></i> รอบสอบ</a>

  <!-- Settings Dropdown -->
  <div class="sidebar-dropdown">
    <a class="{{ Request::routeIs('file-manage.*', 'roles.*', 'permissions.*', 'users.*', 'positions.*', 'jobgroups.*', 'staff-sub.*', 'staff.*') ? 'active' : '' }}">
      <span><i class="bi bi-gear-fill me-2"></i> ตั้งค่าระบบ</span>
      <i class="bi bi-chevron-right dropdown-icon"></i>
    </a>
    <div class="sidebar-dropdown-menu {{ Request::routeIs('file-manage.*', 'roles.*', 'permissions.*', 'users.*', 'positions.*', 'jobgroups.*', 'staff-sub.*', 'staff.*') ? 'show' : '' }}">
      <a href="{{ route('file-manage.index') }}"
         class="{{ Request::routeIs('file-manage.*') ? 'active' : '' }}">
         <i class="bi bi-folder-fill me-2"></i> จัดการเอกสาร</a>
      
      <a href="{{ route('jobgroups.index') }}"
         class="{{ Request::routeIs('jobgroups.*') ? 'active' : '' }}">
         <i class="bi bi-diagram-3 me-2"></i> กลุ่มงาน</a>
      
      <a href="{{ route('positions.index') }}"
         class="{{ Request::routeIs('positions.*') ? 'active' : '' }}">
         <i class="bi bi-briefcase me-2"></i> ตำแหน่งงาน</a>
      
      <a href="{{ route('staff-sub.index') }}"
         class="{{ Request::routeIs('staff-sub.*') ? 'active' : '' }}">
         <i class="bi bi-people-fill me-2"></i> สายหางาน</a>
      
      <a href="{{ route('staff.index') }}"
         class="{{ Request::routeIs('staff.*') ? 'active' : '' }}">
         <i class="bi bi-person-badge me-2"></i> รายชื่อสรรหา</a>
      
      <a href="{{ route('roles.index') }}"
         class="{{ Request::routeIs('roles.*') ? 'active' : '' }}">
         <i class="bi bi-shield-lock-fill me-2"></i> Roles</a>
      
      <a href="{{ route('permissions.index') }}"
         class="{{ Request::routeIs('permissions.*') ? 'active' : '' }}">
         <i class="bi bi-key-fill me-2"></i> Permissions</a>
      
      <a href="{{ route('users.index') }}"
         class="{{ Request::routeIs('users.index') ? 'active' : '' }}">
         <i class="bi bi-people-fill me-2"></i> Users</a>
    </div>
  </div>



<div class="text-center text-secondary small mt-5" style="position:absolute; bottom:18px; left:0; width:100%; opacity:0.85;">
  <span>VD-LABOURS V2.1.0 <br> Updated At 06/12/2025</span>
</div>
</div>



<!-- Topbar Notification -->

<nav class="navbar navbar-expand-lg navbar-light px-3" style="min-height:64px; z-index: 2147483647; position: fixed; top: 0; left: 0; width: 110vw;">
  <div class="container">
    <span class="navbar-brand d-none d-lg-block"></span>
    {{-- @php dd($scopeExpiringDiseaseConstruct, $scopeExpiringDiseaseFactory); @endphp --}}
    <div class="ms-auto d-flex align-items-center gap-3">
      <!-- Notification Bell -->
      <div class="dropdown">
        @php
          $hasAlert =
            ($scopeExpiringDiseaseConstruct ?? 0) > 0 ||
            ($scopeExpiringDiseaseFactory ?? 0) > 0 ||
            ($scopeExpiringPassport ?? 0) > 0 ||
            ($scopeExpiringCIDConstruct ?? 0) > 0 ||
            ($scopeExpiringCIDFactory ?? 0) > 0 ||
            ($scopeExpiringCidMoney ?? 0) > 0 ||
            ($scopeExpiringAffidavit ?? 0) > 0 ||
            ($visaNotUpdate ?? 0) > 0 ||
            ($visaApproved ?? 0) > 0 ||
            ($visaRejected ?? 0) > 0 ||
            ($jobLeadNotifications ?? 0) > 0;
        @endphp
        <button class="btn position-relative p-0 border-0 bg-transparent{{ $hasAlert ? '' : ' disabled' }}" id="notifyDropdown" data-bs-toggle="dropdown" aria-expanded="false" {{ $hasAlert ? '' : 'tabindex="-1" aria-disabled="true"' }}>
          <i class="bi bi-bell fs-3"></i>
          <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle animate-blink" style="width:13px; height:13px;"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2" aria-labelledby="notifyDropdown" style="min-width:320px;">
          @if($hasAlert)
            <li class="dropdown-header fw-bold text-danger">แจ้งเตือนหมดอายุ</li>
            <li><hr class="dropdown-divider"></li>
            @if(($scopeExpiringDiseaseConstruct ?? 0) > 0)
            <li>
              <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('dashboards.index') }}#alert-exp-disease-construct">
                <i class="bi bi-hospital text-info"></i>
                <span>ผลโรค ก่อสร้าง หมดอายุ</span>
                <span class="badge bg-danger ms-auto">{{ $scopeExpiringDiseaseConstruct }}</span>
              </a>
            </li>
            @endif
            @if(($scopeExpiringDiseaseFactory ?? 0) > 0)
            <li>
              <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('dashboards.index') }}#alert-exp-disease-factory">
                <i class="bi bi-hospital-fill text-primary"></i>
                <span>ผลโรค โรงงาน หมดอายุ</span>
                <span class="badge bg-danger ms-auto">{{ $scopeExpiringDiseaseFactory }}</span>
              </a>
            </li>
            @endif
            @if(($scopeExpiringPassport ?? 0) > 0)
            <li>
              <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('dashboards.index') }}#alert-exp-passport">
                <i class="bi bi-file-earmark-text text-secondary"></i>
                <span>พาสปอร์ตหมดอายุ</span>
                <span class="badge bg-danger ms-auto">{{ $scopeExpiringPassport }}</span>
              </a>
            </li>
            @endif
            @if(($scopeExpiringCIDConstruct ?? 0) > 0)
            <li>
              <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('dashboards.index') }}#alert-exp-cid-construct">
                <i class="bi bi-card-heading text-warning"></i>
                <span>CID ก่อสร้าง หมดอายุ</span>
                <span class="badge bg-danger ms-auto">{{ $scopeExpiringCIDConstruct }}</span>
              </a>
            </li>
            @endif
            @if(($scopeExpiringCIDFactory ?? 0) > 0)
            <li>
              <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('dashboards.index') }}#alert-exp-cid-factory">
                <i class="bi bi-card-list text-warning"></i>
                <span>CID โรงงาน หมดอายุ</span>
                <span class="badge bg-danger ms-auto">{{ $scopeExpiringCIDFactory }}</span>
              </a>
            </li>
            @endif
            @if(($scopeExpiringCidMoney ?? 0) > 0)
            <li>
              <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('dashboards.index') }}#alert-exp-cid-money">
                <i class="bi bi-cash-coin text-danger"></i>
                <span>ยังไม่ได้จ่ายเงินประกัน</span>
                <span class="badge bg-danger ms-auto">{{ $scopeExpiringCidMoney }}</span>
              </a>
            </li>
            @endif
            @if(($scopeExpiringAffidavit ?? 0) > 0)
            <li>
              <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('dashboards.index') }}#alert-exp-affidavit">
                <i class="bi bi-file-earmark-text text-primary"></i>
                <span>Affidavit หมดอายุ</span>
                <span class="badge bg-danger ms-auto">{{ $scopeExpiringAffidavit }}</span>
              </a>
            </li>
            @endif
            @if(($visaNotUpdate ?? 0) > 0)
            <li>
              <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('labours.alert.list', ['type' => 'visa-not-update']) }}">
                <i class="bi bi-passport text-danger"></i>
                <span>VISA ไม่ Update</span>
                <span class="badge bg-danger ms-auto">{{ $visaNotUpdate }}</span>
              </a>
            </li>
            @endif
            @if(($visaApproved ?? 0) > 0)
            <li>
              <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('labours.alert.list', ['type' => 'visa-approved']) }}">
                <i class="bi bi-check-circle text-success"></i>
                <span>VISA อนุมัติแล้ว</span>
                <span class="badge bg-success ms-auto">{{ $visaApproved }}</span>
              </a>
            </li>
            @endif
            @if(($visaRejected ?? 0) > 0)
            <li>
              <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('labours.alert.list', ['type' => 'visa-rejected']) }}">
                <i class="bi bi-x-circle text-warning"></i>
                <span>VISA ไม่อนุมัติ</span>
                <span class="badge bg-warning ms-auto">{{ $visaRejected }}</span>
              </a>
            </li>
            @endif
            @if(($jobLeadNotifications ?? 0) > 0)
            <li>
              <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('notifications.index') }}">
                <i class="bi bi-briefcase-fill text-purple"></i>
                <span>แจ้งเตือนใบสมัครงาน</span>
                <span class="badge bg-purple ms-auto">{{ $jobLeadNotifications }}</span>
              </a>
            </li>
            @endif
          @else
            <li class="dropdown-header text-center text-muted">ไม่มีรายการแจ้งเตือนหมดอายุ</li>
          @endif
        </ul>
      </div>

      <!-- User Info & Logout -->
      @auth
      <div class="d-flex align-items-center gap-2">
        <i class="bi bi-person-circle fs-4"></i>
        <span class="d-none d-md-inline text-dark fw-semibold">{{ Auth::user()->name }}</span>
      </div>
      <form action="{{ route('logout') }}" method="POST" class="m-0">
        @csrf
        <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center gap-2">
          <i class="bi bi-box-arrow-right"></i>
          <span>ออกจากระบบ</span>
        </button>
      </form>
      @endauth
    </div>
  </div>
  
</nav>

<!-- Content -->
<div class="content" style="">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="display-6">ระบบข้อมูลคนงาน</h1>
    {{-- <button class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> เพิ่มข้อมูล</button> --}}
  </div>

   @yield('content')

   
</div>

<!-- DataTables Init Helper (auto for .datatable class) -->
<script>
  $(function() {
    if ($('.datatable').length) {
      $('.datatable').DataTable();
    }
  });

  // Sidebar Dropdown Toggle
  $(document).ready(function() {
    $('.sidebar-dropdown > a').click(function(e) {
      e.preventDefault();
      const $dropdown = $(this).siblings('.sidebar-dropdown-menu');
      const $icon = $(this).find('.dropdown-icon');
      
      // Toggle dropdown
      $dropdown.toggleClass('show');
      $icon.toggleClass('rotate');
    });
  });
</script>

</body>
<style>
  .animate-blink {
    animation: blink 1s steps(2, start) infinite;
  }
  @keyframes blink {
    to { visibility: hidden; }
  }
</style>


</html>
