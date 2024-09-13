<div class="card">
    <div class="card-body">
        <h4> ข้อมูลนายจ้าง</h4>
                <form action="{{route('customer.update',$customerModel->customer_id)}}" method="POST" >
                    @csrf
                    @method('PUT')
                  <div class="form-group">
                    <label for="recipient-name" class="col-form-label">ชื่อนายจ้าง</label>
                    <input type="text" name="customer_name" class="form-control" id="recipient-name" placeholder="ชื่อนายจ้าง" value="{{$customerModel->customer_name}}">
                  </div>

                  <div class="form-gorup">
                    <label>ประเทศ</label>
                    
                    <select name="country_id" class="form-control" id="">
                        <option value="active">--Select a Country--</option>
                        @foreach ($country as $item)
                        <option @if($item->country_id === $customerModel->country_id) selected @endif value="{{$item->country_id}}">{{$item->country_name_th}}</option>
                        @endforeach
                       
                    </select>
                  </div>

                  <div class="form-gorup">
                    <label>สถานะ</label>
                    <select name="customer_status" class="form-control" id="">
                        <option  @if($customerModel->customer_status === 'active') selected @endif value="active">Active</option>
                        <option  @if($customerModel->customer_status === 'disable') selected @endif value ="disable">Disable</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="message-text" class="col-form-label">บันทึกเพิ่มเติม:</label>
                    <textarea class="form-control" name="customer_note" id="message-text" placeholder="บันทึกเพิ่มเติม">{{$customerModel->customer_note}}</textarea>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                  </div>
                </form>
              </div>
    </div>
</div>