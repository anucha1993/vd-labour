@php
    use App\Services\JobLeadNotificationService;
    $notificationService = app(JobLeadNotificationService::class);
    $unreadCount = $notificationService->getUnreadCount();
@endphp

@if($unreadCount > 0)
<span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
@endif
