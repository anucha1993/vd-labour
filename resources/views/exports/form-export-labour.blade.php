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
        <div class="card-header">
            รายงาน
        </div>
        <div class="card-body">
            <form action="{{route('labour.export')}}" method="get">
              @csrf

                <div class="row">
                    <div class="col-md-3 mt-3">
                        <label>Date Start (ผลโรคหมดอายุ) </label>
                        <input type="date" name="labour_disease_date_start" class="form-control"
                            placeholder="Register Number" >
                    </div>
                    <div class="col-md-3 mt-3">
                        <label>Date End (ผลโรคหมดอายุ) </label>
                        <input type="date" name="labour_disease_date_end" class="form-control"
                            placeholder="Register Number" >
                    </div>

                    
                    <div class="col-md-3 mt-3">
                        <label>Data Start CID Expiry</label>
                        <input type="date" name="labour_cid_start" class="form-control"
                            placeholder="Register Number"  >
                    </div>
                    
                    <div class="col-md-3 mt-3">
                        <label> Data End CID Expiry</label>
                        <input type="date" name="labour_cid_end" class="form-control"
                            placeholder="Register Number"  >
                    </div>

                </div>


                <div class="row">
                    <div class="col-md-3 mt-3">
                        <label> Country Name</label>
                        <select name="labour_country" class="form-select">
                            <option value="all" >All</option>
                            @forelse ($customers as $item)
                            <option value="{{$item->customer_id}}" >{{$item->customer_name}}</option>
                            @empty
                                
                            @endforelse
                            
                        </select>
                    </div>
                    <div class="col-md-3 mt-3">
                        <label> Job Group</label>
                        <select name="labour_job_group" class="form-select">
                            <option value="all" >All</option>
                            @forelse ($jobGroup as $item)
                            <option value="{{ $item->job_group_id }}">{{ $item->job_group_name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-3 mt-3">
                        <label> Staff Name</label>
                        <select name="labour_staff" class="form-select">
                            <option value="all" >All</option>
                            @forelse ($staffs as $item)
                            <option value="{{$item->staff_id}}">{{$item->staff_name}}({{$item->staff_nickname}})</option>
                            @empty
                                
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-3 mt-3">
                        <label> Status </label>
                        <select name="labour_status" class="form-select">
                            <option value="all" >All</option>
                            <option value="wait">กำลังดำเนินการ</option>
                            <option value="success">บินแล้ว</option>
                            <option value="cancel">ยกเลิก</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-outline-success mt-3 float-end"> <i class="fa fa-file-excel"></i>
                    Report
                  </button>
            </form>
        </div>
    </div>

@endsection