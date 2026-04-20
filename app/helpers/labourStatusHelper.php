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
            'hold'        => 'ชะลอดำเนินการ',
            'success'     => 'บินแล้ว',
            'cancel'      => 'ยกเลิก',
            default       => '>ไม่ทราบสถานะ',
        };
    }
}

use App\Models\labours\labourModel;

if (!function_exists('getExpiringDiseaseConstruct')) {
    function getExpiringDiseaseConstruct() {
        return labourModel::expiringDiseaseConstruct()->count();
    }
}
if (!function_exists('getExpiringDiseaseFactory')) {
    function getExpiringDiseaseFactory() {
        return labourModel::expiringDiseaseFactory()->count();
    }
}
if (!function_exists('getExpiringPassport')) {
    function getExpiringPassport() {
        return labourModel::expiringPassport()->count();
    }
}
if (!function_exists('getExpiringIdCard')) {
    function getExpiringIdCard() {
        return labourModel::expiringIdCard()->count();
    }
}
if (!function_exists('getExpiringCIDConstruct')) {
    function getExpiringCIDConstruct() {
        return labourModel::expiringCIDConstruct()->count();
    }
}
if (!function_exists('getExpiringCIDFactory')) {
    function getExpiringCIDFactory() {
        return labourModel::expiringCIDFactory()->count();
    }
}
if (!function_exists('getExpiringCidMoney')) {
    function getExpiringCidMoney() {
        return labourModel::expiringCidMoney()->count();
    }
}
if (!function_exists('getExpiringAffidavit')) {
    function getExpiringAffidavit() {
        return labourModel::expiringAffidavit()->count();
    }
}

// VISA Helper Functions
if (!function_exists('getVisaNotUpdate')) {
    function getVisaNotUpdate() {
        return labourModel::VisaNotUpdate()->count();
    }
}

if (!function_exists('getVisaApproved')) {
    function getVisaApproved() {
        return labourModel::VisaApproved()->count();
    }
}

if (!function_exists('getVisaRejected')) {
    function getVisaRejected() {
        return labourModel::VisaRejected()->count();
    }
}
