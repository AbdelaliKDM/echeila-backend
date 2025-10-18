<?php

return [
    'trip' => 'رحلة',
    'trips' => 'رحلات',
    'id' => 'معرف الرحلة',
    'driver' => 'السائق',
    'passenger' => 'الراكب',
    'type' => 'نوع الرحلة',
    'status' => 'الحالة',
    'date' => 'التاريخ',
    'amount' => 'المبلغ',

    // Trip Types
    'types' => [
        'taxi_ride' => 'ركوب تاكسي',
        'car_rescue' => 'إنقاذ السيارة',
        'cargo_transport' => 'نقل الشحنات',
        'water_transport' => 'النقل المائي',
        'paid_driving' => 'القيادة المدفوعة',
        'international_trip' => 'رحلة دولية',
        'mrt_trip' => 'رحلة موريتانيا',
        'esp_trip' => 'رحلة إسبانيا',
    ],

    // Trip Status
    'statuses' => [
        'pending' => 'قيد الانتظار',
        'accepted' => 'مقبولة',
        'ongoing' => 'قيد التنفيذ',
        'completed' => 'مكتملة',
        'cancelled' => 'ملغاة',
    ],
];
