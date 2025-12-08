@extends('layouts.main')

@section('title', 'การแจ้งเตือนการติดตามใบสมัครงาน')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-bell"></i> การแจ้งเตือนการติดตามใบสมัครงาน
                        @if($unreadCount > 0)
                        <span class="badge bg-danger">{{ $unreadCount }} รายการใหม่</span>
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 15%">เลขที่ใบสมัคร</th>
                                    <th style="width: 15%">ผู้สมัคร</th>
                                    <th style="width: 15%">งาน</th>
                                    <th style="width: 10%">สถานะ</th>
                                    <th style="width: 10%">ประเภทการแจ้งเตือน</th>
                                    <th style="width: 10%">วันที่แจ้งเตือน</th>
                                    <th style="width: 10%">การตอบกลับ</th>
                                    <th style="width: 10%">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $notification)
                                <tr class="{{ !$notification->is_read ? 'table-warning' : '' }}">
                                    <td>
                                        @if(!$notification->is_read)
                                        <span class="badge bg-danger">NEW</span>
                                        @endif
                                    </td>
                                    <td>{{ $notification->jobLead->job_lead_number }}</td>
                                    <td>{{ $notification->jobLead->lead->getFullNameAttribute() }}</td>
                                    <td>{{ $notification->jobLead->job->job_name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $notification->jobLead->getStatusColor() }}">
                                            {{ $notification->current_status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($notification->notification_type === 'over_21_days') bg-danger
                                            @elseif($notification->notification_type === '21_days') bg-warning
                                            @else bg-info
                                            @endif
                                        ">
                                            {{ $notification->getDaysLabel() }}
                                        </span>
                                    </td>
                                    <td>{{ $notification->sent_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($notification->responded_at)
                                            @if($notification->response === 'wait')
                                                <span class="badge bg-success">รอต่อไป</span>
                                            @else
                                                <span class="badge bg-secondary">ถอนใบสมัคร</span>
                                            @endif
                                            <br><small class="text-muted">{{ $notification->responded_at->format('d/m/Y H:i') }}</small>
                                        @else
                                            <span class="badge bg-warning">รอการตอบกลับ</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$notification->responded_at)
                                        <button type="button" class="btn btn-sm btn-primary" 
                                                onclick="showResponseModal({{ $notification->notification_id }}, '{{ $notification->notification_type }}')">
                                            <i class="bi bi-reply"></i> ตอบกลับ
                                        </button>
                                        @else
                                        <a href="{{ route('notifications.show', $notification->notification_id) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> ดูรายละเอียด
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $notifications->links() }}
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> ไม่มีการแจ้งเตือน
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Response Modal -->
<div class="modal fade" id="responseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ตอบกลับการแจ้งเตือน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="responseForm">
                    <input type="hidden" id="notification_id">
                    <input type="hidden" id="notification_type">
                    
                    <div class="mb-3">
                        <label class="form-label">เลือกการดำเนินการ <span class="text-danger">*</span></label>
                        <div id="responseOptions">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="response" id="response_wait" value="wait">
                                <label class="form-check-label" for="response_wait">
                                    <i class="bi bi-clock-history text-success"></i> รอต่อไป (ระบบจะแจ้งเตือนอีกครั้งในอีก 7 วัน)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="response" id="response_withdraw" value="withdraw">
                                <label class="form-check-label" for="response_withdraw">
                                    <i class="bi bi-x-circle text-danger"></i> ถอนใบสมัคร
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">หมายเหตุ</label>
                        <textarea class="form-control" name="note" rows="3" placeholder="ระบุเหตุผล (ถ้ามี)"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" onclick="submitResponse()">
                    <i class="bi bi-send"></i> ยืนยัน
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    let responseModal;
    
    document.addEventListener('DOMContentLoaded', function() {
        responseModal = new bootstrap.Modal(document.getElementById('responseModal'));
    });
    
    window.showResponseModal = function(notificationId, notificationType) {
        document.getElementById('notification_id').value = notificationId;
        document.getElementById('notification_type').value = notificationType;
        
        // ถ้าเป็น over_21_days ให้ซ่อนตัวเลือก "รอต่อไป"
        if (notificationType === 'over_21_days') {
            document.getElementById('response_wait').parentElement.style.display = 'none';
            document.getElementById('response_withdraw').checked = true;
        } else {
            document.getElementById('response_wait').parentElement.style.display = 'block';
            document.getElementById('response_wait').checked = true;
        }
        
        responseModal.show();
    }
    
    window.submitResponse = function() {
        const form = document.getElementById('responseForm');
        const formData = new FormData(form);
        const notificationId = document.getElementById('notification_id').value;
        
        const response = formData.get('response');
        if (!response) {
            alert('กรุณาเลือกการดำเนินการ');
            return;
        }
        
        fetch(`/notifications/${notificationId}/respond`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                response: response,
                note: formData.get('note')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                responseModal.hide();
                window.location.reload();
            } else {
                alert('เกิดข้อผิดพลาด: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        });
    }
</script>
@endsection
