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
                <h4>  
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-sm">เพิ่มนายจ้าง</button>
                </h4>

                <br>
                <div class="table-responsive">
                    <table class="table table" id="customer">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ชื่อนายจ้าง</th>
                                <th>ประเทศ</th>
                                <th>สถานะ</th>
                                <th>Date Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                          @forelse ($customers as $key => $item)
                              <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$item->customer_name}}</td>
                                <td>{{$item->country_name_th}}</td>
                                <td>{{$item->customer_status === 'active' ? 'Active' : 'Disable'}}</td>
                                <td>{{date('d-m-Y',strtotime($item->created_at))}}</td>
                                <td>
                                    <a href="{{route('customer.edit',$item->customer_id)}}" class="btn btn-info btn-sm customer-edit"> แก้ไข</a>
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
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-body">
                <h4> เพิ่มข้อมูลนายจ้าง</h4>
                <form action="{{route('customer.store')}}" method="POST" >
                    @csrf
                    @method('POST')
                  <div class="form-group">
                    <label for="recipient-name" class="col-form-label">ชื่อนายจ้าง</label>
                    <input type="text" name="customer_name" class="form-control" id="recipient-name" placeholder="ชื่อนายจ้าง">
                  </div>

                  <div class="form-gorup">
                    <label>ประเทศ</label>
                    
                    <select name="country_id" class="form-control" id="">
                        <option value="active">--Select a Country--</option>
                        @foreach ($country as $item)
                        <option value="{{$item->country_id}}">{{$item->country_name_th}}</option>
                        @endforeach
                       
                    </select>
                  </div>

                  <div class="form-gorup">
                    <label>สถานะ</label>
                    <select name="customer_status" class="form-control" id="">
                        <option value="active">Active</option>
                        <option value="disable">Disable</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="message-text" class="col-form-label">บันทึกเพิ่มเติม:</label>
                    <textarea class="form-control" name="customer_note" id="message-text" placeholder="บันทึกเพิ่มเติม"></textarea>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                  </div>
                </form>
              </div>
          </div>
        </div>
      </div>

      <style>
        .modal {
            width: 1000px;
            height: 1000px;
            left: 40%;
            top: 30%;
            margin-left: -150px;
            margin-top: -150px;
        }
    </style>


      <div class="modal fade bd-example-modal-sm modal-lg" id="edit-customer" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              ...
          </div>
      </div>
  </div>


    <script>
          $(document).ready(function() {
// modal add user
$(".customer-edit").click("click", function(e) {
    e.preventDefault();
    $("#edit-customer")
        .modal("show")
        .addClass("modal-lg")
        .find(".modal-content")
        .load($(this).attr("href"));
});


$('#customer').DataTable();

});


    </script>
@endsection
