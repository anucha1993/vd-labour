{{-- Timeline for Lead Activities (from Job Applications) --}}
<div class="activity-timeline">
    @forelse($activities as $activity)
        <div class="timeline-item">
            <div class="timeline-marker {{ $activity->getActivityColor() }}">
                <i class="{{ $activity->getActivityIcon() }}"></i>
            </div>
            <div class="timeline-content">
                <div class="timeline-header">
                    <span class="timeline-title">{{ $activity->getActivityDescription() }}</span>
                    <span class="timeline-time text-muted">
                        <i class="bi bi-clock"></i> {{ $activity->created_at->format('d/m/Y H:i') }}
                    </span>
                </div>
                
                <div class="timeline-body">
                    {{-- Show job information --}}
                    @if(isset($activity->job_info))
                        <div class="mb-2">
                            <span class="badge bg-dark">
                                <i class="bi bi-briefcase"></i> {{ $activity->job_info['number'] }}
                            </span>
                            <small class="text-muted ms-2">
                                {{ $activity->job_info['name'] }} 
                                @if($activity->job_info['country'])
                                    ({{ $activity->job_info['country'] }})
                                @endif
                            </small>
                        </div>
                    @endif
                    
                    @if($activity->activity_type === 'status_changed' || $activity->activity_type === 'bulk_updated')
                        <div class="status-change">
                            @if($activity->old_status)
                                <span class="badge bg-secondary">{{ $activity->old_status }}</span>
                                <i class="bi bi-arrow-right mx-2"></i>
                            @endif
                            @if($activity->new_status)
                                @php
                                    $statusColors = [
                                        'ร่าง' => 'secondary',
                                        'ส่งแล้ว' => 'primary',
                                        'กำลังพิจารณา' => 'info',
                                        'นัดสัมภาษณ์' => 'warning',
                                        'เสนองาน' => 'warning',
                                        'ตอบรับ' => 'success',
                                        'ปฏิเสธ' => 'danger',
                                        'ถอน' => 'dark'
                                    ];
                                    $badgeColor = $statusColors[$activity->new_status] ?? 'primary';
                                @endphp
                                <span class="badge bg-{{ $badgeColor }}">
                                    {{ $activity->new_status }}
                                </span>
                            @endif
                        </div>
                    @endif
                    
                    @if($activity->reason)
                        <div class="mt-2">
                            <strong class="text-danger">เหตุผล:</strong>
                            <p class="mb-1 text-danger">{{ $activity->reason }}</p>
                        </div>
                    @endif
                    
                    @if($activity->remarks)
                        <div class="mt-2">
                            <strong>หมายเหตุ:</strong>
                            <p class="mb-1">{{ $activity->remarks }}</p>
                        </div>
                    @endif
                    
                    @if($activity->changes && is_array($activity->changes) && count($activity->changes) > 0)
                        <div class="mt-2">
                            <strong>การเปลี่ยนแปลง:</strong>
                            <ul class="mb-1">
                                @foreach($activity->changes as $key => $value)
                                    <li>{{ $key }}: {{ is_array($value) ? json_encode($value) : $value }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="mt-2 text-muted small">
                        <i class="bi bi-person-circle"></i>
                        โดย {{ $activity->user->name ?? 'ระบบ' }}
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-4">
            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
            <p class="mt-2">ยังไม่มีประวัติการดำเนินการ</p>
            <small>ผู้สนใจยังไม่เคยสมัครงานหรือมีการเปลี่ยนแปลงสถานะ</small>
        </div>
    @endforelse
</div>

<style>
.activity-timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    display: flex;
    position: relative;
    padding-bottom: 30px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 19px;
    top: 40px;
    bottom: -10px;
    width: 2px;
    background: #e0e0e0;
}

.timeline-marker {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 15px;
    position: relative;
    z-index: 1;
}

.timeline-marker.success {
    background-color: #28a745;
}

.timeline-marker.primary {
    background-color: #007bff;
}

.timeline-marker.warning {
    background-color: #ffc107;
}

.timeline-marker.danger {
    background-color: #dc3545;
}

.timeline-marker.info {
    background-color: #17a2b8;
}

.timeline-marker.secondary {
    background-color: #6c757d;
}

.timeline-content {
    flex: 1;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.timeline-title {
    font-weight: 600;
    font-size: 1rem;
    color: #333;
}

.timeline-time {
    font-size: 0.85rem;
}

.timeline-body {
    font-size: 0.9rem;
    color: #555;
}

.status-change {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.status-change .badge {
    font-size: 0.85rem;
    padding: 5px 10px;
}

@media (max-width: 576px) {
    .timeline-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .timeline-time {
        margin-top: 5px;
    }
}
</style>
