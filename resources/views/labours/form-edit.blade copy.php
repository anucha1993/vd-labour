@extends('layouts.main')
@section('content')
    <div class="row">
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

        <div class="card card-custom shadow-sm mb-4">
            <div class="card-body bg-light rounded-4 p-4">
                <form action="{{ route('labour.update', $labourModel->labour_id) }}" enctype="multipart/form-data"
                    method="post" id="form-create">
                    @csrf
                    @method('put')
                    <div class="mb-4 border-bottom pb-2 d-flex align-items-center justify-content-between">
                        <h4 class="mb-0"><i class="bi bi-person-badge me-2 text-primary"></i> ข้อมูลคนงาน</h4>
                        <a class="btn btn-outline-danger btn-sm" target="_blank"
                                href="{{ route('labour.print', $labourModel->labour_id) }}"> <i class="fa fa-print"></i> พิมพ์ข้อมูล</a>
                    </div>
                    <div class="mb-3 text-muted small">ตำแหน่งไฟล์ : <a href="#">{{ env('LOCATION_DRIVE') }}{{ '\\' . $labourModel->labour_path }}</a></div>

                    
            
            {{-- labour Group  --}}
            <hr>
          
            </div>
            <hr>
            {{-- labour Status  --}}
            <h4>ข้อมูลสถานะ</h4>
            
                <br>
                <br>

                <div class="row mt-3">
                    <hr>
                    <h4>ข้อมูลบัญชี</h4>


                    


                </div>

            </div>
            <hr>
            {{-- labour Status  --}}
            {{-- labour File  --}}
            
            <br>
            <br>


            {{-- @endcan
                     --}}
            <button type="submit" class="btn btn-sm float-end btn-success" form="form-create"
                @cannot('update labour') readonly @endcannot><i class="fa fa-save"></i>
                อัทเดพข้อมูล</button>


        </div>
    </div>
    </div>






   
    
@endsection
