# ระบบแจ้งเตือนการติดตามใบสมัครงาน (Job Lead Notification System)

## ภาพรวมระบบ

ระบบนี้จะติดตามและแจ้งเตือนสถานะใบสมัครงานที่อยู่ในสถานะดังต่อไปนี้:
- ร่าง
- ส่งแล้ว
- กำลังพิจารณา
- นัดสัมภาษณ์
- เสนองาน

## กลไกการทำงาน

### 1. การติดตามอัตโนมัติ
- เมื่อมีการสร้างหรืออัปเดตใบสมัครงานเป็นสถานะที่ต้องติดตาม ระบบจะเริ่มนับวันโดยอัตโนมัติ
- ระบบจะรัน Task ทุกวันเวลา 09:00 น. เพื่อตรวจสอบและสร้างการแจ้งเตือน

### 2. ระดับการแจ้งเตือน

#### 7 วัน (Type: 7_days)
- แจ้งเตือนครั้งแรกหลังจากส่งใบสมัคร 7 วัน
- สามารถเลือก:
  - **รอต่อไป**: ระบบจะแจ้งเตือนอีกครั้งในอีก 7 วัน (ที่ 14 วัน)
  - **ถอนใบสมัคร**: เปลี่ยนสถานะเป็น "ถอน" และหยุดติดตาม

#### 14 วัน (Type: 14_days)
- แจ้งเตือนหลังจากส่งใบสมัคร 14 วัน (หรือ 7 วันหลังจากตอบว่ารอต่อไปครั้งแรก)
- สามารถเลือก:
  - **รอต่อไป**: ระบบจะแจ้งเตือนอีกครั้งในอีก 7 วัน (ที่ 21 วัน)
  - **ถอนใบสมัคร**: เปลี่ยนสถานะเป็น "ถอน" และหยุดติดตาม

#### 21 วัน (Type: 21_days)
- แจ้งเตือนหลังจากส่งใบสมัคร 21 วัน
- สามารถเลือก:
  - **รอต่อไป**: ครั้งสุดท้ายที่สามารถเลือกรอได้
  - **ถอนใบสมัคร**: เปลี่ยนสถานะเป็น "ถอน" และหยุดติดตาม

#### มากกว่า 21 วัน (Type: over_21_days)
- แจ้งเตือนหลังจากที่ตอบว่ารอต่อไปที่ 21 วัน แล้วผ่านไปอีก 7 วัน
- **ไม่สามารถเลือก "รอต่อไป" ได้อีก**
- สามารถเลือกได้เพียง: **ถอนใบสมัคร** เท่านั้น

## ไฟล์ที่สร้างขึ้น

### Database
- `database/migrations/2025_12_04_060335_create_job_lead_notifications_table.php`
  - Table: `job_lead_notifications`
  - Columns: notification_id, job_lead_id, notification_type, current_status, sent_at, responded_at, response, responded_by, is_read, note

### Models
- `app/Models/jobs/JobLeadNotificationModel.php`
  - จัดการข้อมูลการแจ้งเตือน
  - มี Relationships กับ JobLeadModel และ User

### Services
- `app/Services/JobLeadNotificationService.php`
  - `startTracking()`: เริ่มติดตามใบสมัครงาน
  - `stopTracking()`: หยุดติดตามใบสมัครงาน
  - `checkAndCreateNotifications()`: ตรวจสอบและสร้างการแจ้งเตือน
  - `respondToNotification()`: ตอบสนองการแจ้งเตือน
  - `getUnreadNotifications()`: ดึงการแจ้งเตือนที่ยังไม่ได้อ่าน
  - `getUnreadCount()`: นับจำนวนการแจ้งเตือนที่ยังไม่ได้อ่าน

### Observer
- `app/Observers/JobLeadObserver.php`
  - ติดตามการเปลี่ยนแปลงสถานะของ JobLeadModel
  - เริ่ม/หยุดการติดตามอัตโนมัติ

### Controller
- `app/Http/Controllers/JobLeadNotificationController.php`
  - `index()`: แสดงรายการการแจ้งเตือน
  - `unread()`: API สำหรับดึงการแจ้งเตือนที่ยังไม่ได้อ่าน
  - `markAsRead()`: ทำเครื่องหมายว่าอ่านแล้ว
  - `respond()`: ตอบสนองการแจ้งเตือน (รอต่อไป/ถอนใบสมัคร)
  - `show()`: แสดงรายละเอียดการแจ้งเตือน

### Commands
- `app/Console/Commands/CheckJobLeadNotifications.php`
  - Command: `php artisan joblead:check-notifications`
  - ตรวจสอบและสร้างการแจ้งเตือนทุกวัน

### Views
- `resources/views/job-lead-notifications/index.blade.php`
  - หน้าแสดงรายการการแจ้งเตือนทั้งหมด
  - มี Modal สำหรับตอบกลับการแจ้งเตือน
- `resources/views/components/notification-badge.blade.php`
  - Component แสดงจำนวนการแจ้งเตือนที่ยังไม่ได้อ่าน

### Routes
```php
Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [JobLeadNotificationController::class, 'index'])->name('index');
    Route::get('/unread', [JobLeadNotificationController::class, 'unread'])->name('unread');
    Route::post('/{id}/read', [JobLeadNotificationController::class, 'markAsRead'])->name('read');
    Route::post('/{id}/respond', [JobLeadNotificationController::class, 'respond'])->name('respond');
    Route::get('/{id}', [JobLeadNotificationController::class, 'show'])->name('show');
});
```

## วิธีใช้งาน

### 1. เริ่มต้นใช้งาน
```bash
# Run migration
php artisan migrate

# ทดสอบ Command (manual)
php artisan joblead:check-notifications
```

### 2. ตั้งค่า Scheduler (Production)
```bash
# เพิ่ม Cron job (Linux/Mac)
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1

# หรือใช้ Task Scheduler (Windows)
```

### 3. ดูการแจ้งเตือน
- เข้าไปที่ URL: `/notifications`
- ดูจำนวนการแจ้งเตือนที่ยังไม่ได้อ่านผ่าน Badge ในเมนู

### 4. ตอบกลับการแจ้งเตือน
1. คลิกปุ่ม "ตอบกลับ" ในรายการการแจ้งเตือน
2. เลือกการดำเนินการ:
   - **รอต่อไป** (สำหรับ 7, 14, 21 วัน เท่านั้น)
   - **ถอนใบสมัคร** (ทุกกรณี)
3. ระบุหมายเหตุ (ถ้ามี)
4. คลิก "ยืนยัน"

## การแสดงผลในเมนู

เพิ่ม Badge แสดงจำนวนการแจ้งเตือนในเมนู:
```blade
<a href="{{ route('notifications.index') }}">
    <i class="bi bi-bell"></i> การแจ้งเตือน
    @include('components.notification-badge')
</a>
```

## ข้อควรระวัง

1. **Scheduler ต้องรันเป็นประจำ**: ตรวจสอบว่า Cron job หรือ Task Scheduler ทำงานอย่างถูกต้อง
2. **Performance**: ถ้ามีใบสมัครงานจำนวนมาก ควรพิจารณาเพิ่ม Index ในฐานข้อมูล
3. **Timezone**: ตรวจสอบ timezone ใน `config/app.php` ให้ถูกต้อง
4. **Queue**: สำหรับระบบขนาดใหญ่ ควรพิจารณาใช้ Queue สำหรับการส่งการแจ้งเตือน

## ตัวอย่างการทำงาน

### สถานการณ์ 1: ส่งใบสมัครและรอต่อเนื่อง
```
Day 0:  ส่งใบสมัคร (สถานะ: ส่งแล้ว)
Day 7:  แจ้งเตือนครั้งที่ 1 → เลือก "รอต่อไป"
Day 14: แจ้งเตือนครั้งที่ 2 → เลือก "รอต่อไป"
Day 21: แจ้งเตือนครั้งที่ 3 → เลือก "รอต่อไป"
Day 28: แจ้งเตือนครั้งที่ 4 → สามารถเลือกได้เพียง "ถอนใบสมัคร" เท่านั้น
```

### สถานการณ์ 2: ส่งใบสมัครและถอนเร็ว
```
Day 0:  ส่งใบสมัคร (สถานะ: ส่งแล้ว)
Day 7:  แจ้งเตือนครั้งที่ 1 → เลือก "ถอนใบสมัคร"
        → สถานะเปลี่ยนเป็น "ถอน"
        → หยุดการแจ้งเตือน
```

## API Endpoints

### GET /notifications/unread
ดึงการแจ้งเตือนที่ยังไม่ได้อ่าน (สำหรับ AJAX)
```json
{
  "notifications": [...],
  "count": 5
}
```

### POST /notifications/{id}/respond
ตอบสนองการแจ้งเตือน
```json
{
  "response": "wait|withdraw",
  "note": "เหตุผล"
}
```

Response:
```json
{
  "success": true,
  "message": "บันทึกการเลื่อนเวลาเรียบร้อย..."
}
```

## Log & Debug

ระบบจะบันทึก Log ไว้ที่:
- `storage/logs/laravel.log`

ตัวอย่าง Log:
```
[2025-12-04 09:00:00] local.INFO: Started tracking job lead #JL-2025-001 with status: ส่งแล้ว
[2025-12-04 09:00:00] local.INFO: Created 7_days notification for job lead #JL-2025-001
[2025-12-04 09:00:00] local.INFO: Processed 10 job leads for notifications
```

## ปัญหาที่อาจพบและวิธีแก้ไข

### 1. Scheduler ไม่ทำงาน
```bash
# ตรวจสอบ Cron job
crontab -l

# ทดสอบ Scheduler
php artisan schedule:run

# ดู Log
tail -f storage/logs/laravel.log
```

### 2. Observer ไม่ทำงาน
- ตรวจสอบว่า Observer ได้ลงทะเบียนใน `AppServiceProvider::boot()`
- Clear cache: `php artisan cache:clear`

### 3. การแจ้งเตือนไม่ถูกต้อง
- ตรวจสอบ `created_at` ของ JobLeadModel
- ตรวจสอบว่ามีการตอบกลับการแจ้งเตือนหรือยัง
- ดู Log เพื่อตรวจสอบว่า Command ทำงานหรือไม่
