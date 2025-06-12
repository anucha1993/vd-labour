<?php

if (! function_exists('labourStatusBadge')) {
    /**
     * คืนค่า HTML badge สำหรับสถานะใบสั่งซื้อ
     *
     * @param  string  $status
     * @return string
     */
    function labourStatusBadge(string $status): string
    {
        return match (strtolower($status)) {
            'wait'        => 'กำลังดำเนินการ',
            'success'     => 'บินแล้ว',
            'cancel'      => 'ยกเลิก',
            default       => '>ไม่ทราบสถานะ',
        };
    }
}
