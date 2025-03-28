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
            <form action="#" method="get">
      
                @method('get')
           

                <div class="row">
                    <div class="col-md-3 mt-3">
                        <label>Date Start (ผลโรคหมดอายุ) </label>
                        <input type="date" name="labour_disease_date_start" class="form-control" value="{{$request->labour_disease_date_start}}"
                            placeholder="Register Number">
                    </div>
                    <div class="col-md-3 mt-3">
                        <label>Date End (ผลโรคหมดอายุ) </label>
                        <input type="date" name="labour_disease_date_end" class="form-control" value="{{$request->labour_disease_date_end}}"
                            placeholder="Register Number">
                    </div>


                    <div class="col-md-3 mt-3">
                        <label>Data Start CID Expiry</label>
                        <input type="date" name="labour_cid_start" class="form-control" placeholder="Register Number" value="{{$request->labour_cid_start}}">
                    </div>

                    <div class="col-md-3 mt-3">
                        <label> Data End CID Expiry</label>
                        <input type="date" name="labour_cid_end" class="form-control" placeholder="Register Number" value="{{$request->labour_cid_end}}">
                    </div>

                </div>


                <div class="row">
                    <div class="col-md-3 mt-3">
                        <label> Customers Name</label>
                        <select name="labour_customer" class="form-select">
                            <option value="">All</option>
                            <option value="null">ยังไม่ระบุ</option>
                            @forelse ($customers as $item)
                                <option  @if($item->customer_id == $request->labour_customer) selected @endif value="{{ $item->customer_id }}">{{ $item->customer_name }}</option>
                            @empty
                            @endforelse

                        </select>
                    </div>

                    <div class="col-md-3 mt-3">
                        <label> Country Name</label>
                        <select name="labour_country" class="form-select">
                            <option value="all">All</option>

                            @forelse ($country as $item)
                                <option @if($item->country_id == $request->labour_country) selected @endif value="{{ $item->country_id }}">{{ $item->country_name_th }}</option>
                            @empty
                            @endforelse

                        </select>
                    </div>

                    <div class="col-md-3 mt-3">
                        <label> Job Group</label>
                        <select name="labour_job_group" class="form-select">
                            <option value="all">All</option>
                            @forelse ($jobGroup as $item)
                                <option @if($item->job_group_id == $request->labour_job_group) selected @endif value="{{ $item->job_group_id }}">{{ $item->job_group_name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-3 mt-3">
                        <label> Staff Name</label>
                        <select name="labour_staff" class="form-select">
                            <option value="all">All</option>
                            @forelse ($staffs as $item)
                                <option @if($item->staff_id == $request->labour_staff) selected @endif value="{{ $item->staff_id }}">{{ $item->staff_name }}({{ $item->staff_nickname }})
                                </option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-3 mt-3">
                        <label> Status </label>
                        <select name="labour_status" class="form-select">
                            <option @if($request->labour_status == 'all') selected @endif  value="all">All</option>
                            <option @if($request->labour_status == 'wait') selected @endif  value="wait">กำลังดำเนินการ</option>
                            <option @if($request->labour_status == 'success') selected @endif  value="success">บินแล้ว</option>
                            <option @if($request->labour_status == 'cancel') selected @endif  value="cancel">ยกเลิก</option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label> รอบสอบ</label>
                        <select name="labour_examination[]" class="form-select selectMulti text-dark" multiple="multiple"
                            style="width: 100%">
                            {{-- <option value="" selected >ทั้งหมด</option> --}}

                            @forelse ($examinationRound as $item)
                                <option 
                                value="{{ $item->examination_round_name }}">
                                    {{ date('d-m-Y', strtotime($item->examination_round_name)) }}
                                    ({{ $item->examination_round_note }})</option>
                            @empty
                            @endforelse
                        </select>
                    </div>

                    <div class="col-md-3 mt-3">
                        <label>สถานะการคืนเงินประกัน</label>
                        <select name="labour_cid_deposit_status" id="" class="form-select">
                            <option value="">All</option>
                            <option @if($request->labour_cid_deposit_status == 'None') selected @endif  value="None">None</option>
                            <option @if($request->labour_cid_deposit_status == 'ยกเลิก-คืนเงินประกัน') selected @endif  value="ยกเลิก-คืนเงินประกัน">ยกเลิก-คืนเงินประกัน</option>
                            <option @if($request->labour_cid_deposit_status == 'ยกเลิก-ไม่คืนเงินประกัน') selected @endif  value="ยกเลิก-ไม่คืนเงินประกัน">ยกเลิก-ไม่คืนเงินประกัน</option>
                        </select>
                    </div>





                </div>

                <button type="submit" class="btn btn-outline-success mt-3 float-end">
                    แสดงรายงาน
                </button>
                <a href="{{route('export.form.labour')}}" class="btn btn-outline-danger mt-3 ">
                    ล้างการค้นหา
                </a>
            </form>
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            Table Labours

            <form action="" method="post">
                @csrf
                @method('POST')
                <input type="hidden" name="labour_ids" value="{{$labours->pluck('labour_id')}}">

                <button class="btn btn-success text-white"> <i class="fa fa-file-excel text-white"></i> Export To Excel</button>
            </form>
        </div>
        <div class="card-body">
            <table class="table table">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อนามสกุล</th>
                        <th>Passport No.</th>
                        <th>Phone</th>
                        <th>นายจ้าง</th>
                        <th>เอกสาร</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($labours as $key => $item)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $item->labour_prefix }}.{{ $item->labour_firstname }} {{ $item->labour_lastname }}</td>
                        <td>{{ $item->labour_passport_number ?? 'N/A' }}</td>
                        <td>{{ $item->labour_phone ?? 'N/A' }}</td>
                        <td>{{ $item->customer?->customer_name ?? 'N/A' }}</td>

                        <td>
                            @if(count($item->labourFile) > 0)
                                @foreach ($item->labourFile as $file)
                               
                                @if ($file->labour_file_path)
                                <span class="badge rounded-pill bg-success"> {{ $file->labour_file_name }}</span>

                                @else
                                <span class="badge rounded-pill bg-danger"> {{ $file->labour_file_name }}</span>
                                @endif
                                   
                                @endforeach
                            @else
                                ไม่มีไฟล์
                            @endif
                        </td>
                    </tr>
                @empty
                @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('.selectMulti').select2();
        });
    </script>

@endsection
