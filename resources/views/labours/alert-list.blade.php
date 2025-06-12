@extends('layouts.main')

@section('content')
<h4 class="mb-3">รายชื่อแรงงานตามแจ้งเตือน: {{ strtoupper($type) }}</h4>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ชื่อ</th>
            <th>นามสกุล</th>
            <th>เบอร์</th>
            <th>ประเทศ</th>
            <th>เลข Passport</th>
            <th>หมดอายุ Passport</th>
            <th>บริษัทนายจ้าง</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($labours as $labour)
            <tr>
                <td>{{ $labour->labour_firstname }}</td>
                <td>{{ $labour->labour_lastname }}</td>
                <td>{{ $labour->labour_phone }}</td>
                <td>{{ $labour->labour_country }}</td>
                <td>{{ $labour->labour_passport_number }}</td>
                <td>{{ $labour->labour_passport_expiry }}</td>
                <td>{{ $labour->customer->customer_name ?? '-' }}</td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item"
                                href="{{ route('labour.edit', $labour->labour_id) }}">แก้ไขข้อมูล</a>
                                @can('view labour')
                                <a class="dropdown-item view-doc" href="{{route('labour.viewDocs',$labour->labour_id)}}">ดูเอกสาร</a>
                                @endcan
                           

                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="7">ไม่มีข้อมูล</td></tr>
        @endforelse
    </tbody>
</table>

<div class="modal fade bd-example-modal-sm modal-lg" id="view-doc" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // modal add user
        $(".view-doc").click("click", function(e) {
            e.preventDefault();
            $("#view-doc")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });
    });
</script>
@endsection
