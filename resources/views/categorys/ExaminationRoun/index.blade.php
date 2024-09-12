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
               
            </div>
        </div>
    </div>
  </div>
</div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    
                </div>
                <h4>ข้อมูลรอบสอบ  
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-sm">เพิ่มข้อมูล</button>
                </h4>

                <br>
                <div class="table-responsive">
                    <table class="table table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>วันที่สอบ</th>
                                <th>ข้อมูลรอบสอบ</th>
                                <th>สถานะ</th>
                                <th>Date Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ExaminationRoun as $key => $item)
                                <tr>
                                    <td>{{ $key+1}}</td>
                                    <td>{{ date('d-m-Y',strtotime($item->examination_round_name))}}</td>
                                    <td>{{ $item->examination_round_note ? $item->examination_round_note : "NULL" }}</td>
                                    <td>{{ $item->examination_round_status}}</td>
                                    
                                    <td>{{date('d-m-Y',strtotime($item->created_at))}}</td>
                                    <td>
                                        <a href="{{route('category.examination.cancel',$item->examination_round_id)}}" onclick="return confirm('ยืนยันการยกเลิกรอบสอบ')" class="btn btn-sm text-danger"> </i> Cancel</a>
                                    </td>
                                </tr>
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-body">
                <form action="{{route('category.examination.store')}}" method="POST" >
                    @csrf
                    @method('POST')
                  <div class="form-group">
                    <label for="recipient-name" class="col-form-label">รอบสอบ:</label>
                    <input type="date" name="examination_round_name" class="form-control" id="recipient-name" placeholder="วัน-เดือน-ปี">
                  </div>
                  <div class="form-gorup">
                    <label>สถานะ</label>
                    <select name="examination_round_status" class="form-control" id="">
                        <option value="active">Active</option>
                        <option value="disable">Disable</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="message-text" class="col-form-label">บันทึกเพิ่มเติม:</label>
                    <textarea class="form-control" name="examination_round_note" id="message-text"></textarea>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                  </div>
                </form>
              </div>
          </div>
        </div>
      </div>
@endsection
