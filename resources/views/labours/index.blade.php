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

    

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
  <div class="modal-dialog "  style="max-width: 70%;">
    <div class="modal-content">
        <div class="card">
            <div class="card-body">
                <h4>ค้านหาข้อมูล</h4>
                <hr>
                <form action="" method="get">
                    <div class="row">
                        <div class="col-md-3 mt">
                            <label>FirstName</label>
                            <input type="text" class="form-control" name="labour_firstname" placeholder="First Name">
                        </div>
                        <div class="col-md-3 mt">
                            <label>LastName</label>
                            <input type="text" class="form-control" name="labour_lastname" placeholder="Last Name">
                        </div>
                        <div class="col-md-3 mt">
                            <label>Phone</label>
                            <input type="text" class="form-control" name="labour_phone" placeholder="++66">
                        </div>
                        <div class="col-md-3 mt">
                            <label>Passport No.</label>
                            <input type="text" class="form-control" name="labour_passport_number" placeholder="Passport No.">
                        </div>
                        
                    </div>
    
                    <div class="row">
                        <div class="col-md-3 mt-3">
                            <label> Country Name</label>
                            <select name="labour_country" class="form-select">
                                <option value="all" >All</option>
                            </select>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label> Job Group</label>
                            <select name="labour_job_group" class="form-select">
                                <option value="all" >All</option>
                            </select>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label> Staff Name</label>
                            <select name="labour_staff" class="form-select">
                                <option value="all" >All</option>
                            </select>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label> Status </label>
                            <select name="labour_status" class="form-select">
                                <option value="all" >All</option>
                            </select>
                        </div>
                    </div>
    
                    <button type="submit" class="btn btn-outline-secondary mt-3 float-end">
                        Search
                      </button>
                </form>
            </div>
        </div>
    </div>
  </div>
</div>


   
    <div class="card">
        <div class="card-body">
            <div class="row">
                <h4>ข้อมูลคนงาน  
                    <button type="button" class="btn btn-outline-secondary float-end" data-toggle="modal" data-target=".bd-example-modal-lg">Search</button>
                     <a href="{{route('labour.create')}}" class="btn btn-sm btn-primary "> <i class="fa fa-user"></i> เพิ่มข้อมูล</a></h4>
             
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
                                      
                                       <div class="progress mt-3">
                                       
                                        <div class="progress-bar  bg-success" role="progressbar" style="width: {{$item->labour_file_list}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="{{$item->labour_file_count}}">
                                          
                                        </div>
                                        </div>
                                    </th>
                                    
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
                                    <th>{{ $item->staff_nickname }}</th>
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
