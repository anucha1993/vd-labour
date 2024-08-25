@extends('layouts.main')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <strong>{{ $message }}</strong>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="row">
                <h4>ข้อมูลคนงาน</h4>
                <br>
                <div class="table-responsive">
                    <table class="table table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Full-Name</th>
                                <th>Register No.</th>
                                <th>Passport No.</th>
                                <th>Phone</th>
                                <th>Docs.</th>
                                <th>Status.</th>
                                <th>staff</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($labours as $key => $item)
                                <tr>
                                    <th>{{ $key + 1 }}</th>
                                    <th>{{ $item->labour_prefix . '.' . $item->labour_firstname . ' ' . $item->labour_lastname }}
                                    </th>
                                    <th>{{ $item->labour_register_number }}</th>
                                    <th>{{ $item->labour_passport_number ? $item->labour_passport_number : 'ไม่พบข้อมูล' }}
                                    </th>
                                    <th>{{ $item->labour_phone }}</th>
                                    <th>
                                       {{$item->labour_file_count.'/'.$item->labour_file_list}}
                                    </th>
                                    <th>{{ $item->staff_nickname }}</th>
                                    <th>
                                        @if ($item->labour_status === 'wait')
                                            <span class="badge rounded-pill bg-primary">กำลังดำเนินการ</span>
                                        @endif
                                        @if ($item->labour_status === 'success')
                                            <span class="badge rounded-pill bg-success">บินแล้ว</span>
                                        @endif
                                        @if ($item->labour_status === 'cancel')
                                            <span class="badge rounded-pill bg-danger">ยกเลิก</span>
                                        @endif
                                    </th>
                                    <th>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                               Actions
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{route('labour.edit',$item->labour_id)}}">แก้ไขข้อมูล</a>
                                                <a class="dropdown-item" href="#">ดูเอกสาร</a>

                                            </div>
                                        </div>
                                    </th>
                                </tr>

                                
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
@endsection
