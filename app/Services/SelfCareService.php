<?php
namespace App\Services;



class SelfCareService
{
    protected $companyAuthService;
    protected $serviceNames;

    public function __construct(CompanyAuthService $companyAuthService)
    {
        $this->companyAuthService = $companyAuthService;
        $this->serviceNames = config('self_care.service_names', []);
    }

    // public function login(string $username, string $password): ?array
    // {
    //     $data = $this->companyAuthService->loginToSelfCare($username, $password);

    //     if (!$data) {
    //         return null;
    //     }

    //     // تجهيز البيانات المطلوبة من الـ response
    //     return [
    //         'token' => $data['T'],
    //         'full_name' => trim($data['customerAddress']['FirstName'] . ' ' . $data['customerAddress']['LastName']),
    //         'national_id' => $data['customerAddress']['DocumentId'],
    //         'birthdate' => $data['customerAddress']['Birthdate'],
    //         'mobile' => $data['customerAddress']['MobileNumber'],
    //         'city' => $data['customerAddress']['City'],
    //         'address' => $data['customerAddress']['Region'] . '، ' . $data['customerAddress']['Town'],
    //         'contracts' => collect($data['co'])->map(function ($contract) {
    //             return [
    //                 'contract_id' => $contract['coIdPub'],
    //                 'service' => $contract['dirnum'],
    //                 'activation_date' => $contract['coActivated'],
    //             ];
    //         })->toArray()
    //     ];
    // }
    public function login(string $username, string $password): array
    {
        $data = $this->companyAuthService->loginToSelfCare($username, $password);

        if (!$data) {
            return [
                'message' => 'فشل تسجيل الدخول',
                'success' => false,
                'data' => []
            ];
        }

        $customerId = $data['co'][0]['csIdPub'] ?? null;

        return [
            'message' => 'تم تسجيل الدخول بنجاح',
            'success' => true,
            'data' => [
                'token' => $data['T'],
                'customerID' => $customerId,
                'full_name' => trim($data['customerAddress']['FirstName'] . ' ' . $data['customerAddress']['LastName']),
                'national_id' => $data['customerAddress']['DocumentId'],
                'birthdate' => $data['customerAddress']['Birthdate'],
                'mobile' => $data['customerAddress']['MobileNumber'],
                'city' => $data['customerAddress']['City'],
                'address' => $data['customerAddress']['Region'] . '، ' . $data['customerAddress']['Town'],
                'contracts' => collect($data['co'])->map(function ($contract) {
                    return [
                        'contract_id' => $contract['coIdPub'],
                        'service' => $contract['dirnum'],
                        'activation_date' => $contract['coActivated'],
                    ];
                })->toArray()
            ]
        ];
    }

    //this is working 99%
//     public function getServicesWithNames(string $contractId, string $authToken): array
// {
//     $response = $this->companyAuthService->getServices($contractId, $authToken);

//     if (!is_array($response)) {
//         return []; // أو ممكن ترجع رسالة خطأ أو استثناء
//     }

//     $serviceNames = config('self_care.services'); // هون جبت أسماء الخدمات من الكونفيغ

//     // عدل اسم كل خدمة حسب ID
//     $mapped = collect($response)->map(function ($item) use ($serviceNames) {
//         $id = $item['Id'] ?? null;
//         $item['Name'] = $serviceNames[$id] ?? 'خدمة غير معروفة';
//         return $item;
//     });

//     return $mapped->toArray();
// }
public function getServicesWithNames(string $contractId, string $authToken): array
{
    $response = $this->companyAuthService->getServices($contractId, $authToken);

    if (!is_array($response)) {
        return [
            'message' => 'فشل جلب الخدمات',
            'success' => false,
            'data' => []
        ];
    }

    $serviceNames = config('self_care.services');

    $services = collect($response)->map(function ($item) use ($serviceNames) {
        return [
            'الخدمة' => $serviceNames[$item['Id']] ?? 'خدمة غير معروفة',
            'الحالة' => $this->translateStatus($item['Status'] ?? null),
            'التاريخ' => \Carbon\Carbon::parse($item['StatusDate'])->format('Y/m/d'),
        ];
    })->toArray();

    return [
        'message' => 'تم جلب الخدمات بنجاح',
        'success' => true,
        'data' => $services
    ];
}

private function translateStatus(?int $status): string
{
    return match ($status) {
        1 => 'غير فعال',
        2 => 'فعال',
        3 => 'معلّق',
        4 => 'منتهي',
        default => 'غير معروف',
    };
}
//app/Services/SelfCareService.php

// public function getCustomerBills(string $customerId, string $authToken): array
// {
//     $bills = $this->companyAuthService->getBills_slefcare($customerId, $authToken);

//     if (!is_array($bills)) {
//         return []; // أو ممكن ترجع رسالة خطأ أو ترمي استثناء
//     }

//     // لو حابب تنسق الفواتير أو تضيف تحويل أسماء الأشهر أو غيره، ممكن تعدل هون
//     return $bills;
// }

// app/Services/SelfCareService.php

// public function getFormattedBills($customerId, $authToken)
// {
//     $rawData = $this->companyAuthService->getBills_slefcare($customerId, $authToken);

//     if (!$rawData || !is_array($rawData)) {
//         return [];
//     }

//     $bills = $rawData;

//     return collect($bills)->map(function ($bill) {
//         return [
//             'الاسم' => ($bill['adrFname'] ?? '') . ' ' . ($bill['adrLname'] ?? ''),
//             'رقم العقد' => $bill['contractIdPub'] ?? '-',
//             'شهر الفاتورة' => $bill['entryMonth'] ?? '-',
//             'تاريخ الاستحقاق' => $bill['dueDate'] ?? '-',
//             'الفاتورة ل.س' => number_format($bill['documentAmountDoc']['value'] ?? 0, 1),
//             'المتبقي ل.س' => number_format($bill['openAmountDoc']['value'] ?? 0, 1),
//         ];
//     })->toArray();
// }
// public function getFormattedBills($customerId, $authToken)
// {
//     $rawData = $this->companyAuthService->getBills_slefcare($customerId, $authToken);

//     if (!$rawData || !is_array($rawData)) {
//         return [
//             'الارضى' => [],
//             'الراوتر' => [],
//         ];
//     }

//     // نفصل الفواتير حسب contractIdPub (ممكن تعدل لو عندك معيار أفضل)
//     $landlineBills = collect($rawData)->filter(function ($bill) {
//         // مثال: كل فاتورة contractIdPub تحتوي على 6189765 هي أرضي
//         // غير هذا الشرط حسب بياناتك
//         return str_contains($bill['contractIdPub'] ?? '', '6189765');
//     })->values();

//     $routerBills = collect($rawData)->filter(function ($bill) {
//         // كل فاتورة contractIdPub تحتوي على 8092339 هي راوتر
//         // غير هذا الشرط حسب بياناتك
//         return str_contains($bill['contractIdPub'] ?? '', '8092339');
//     })->values();

//     // دالة مساعدة لتحويل الفواتير
//     $formatBills = function ($bills) {
//         return $bills->map(function ($bill, $index) {
//             return [
//                 '#' => $index + 1,
//                 'الشهر' => $bill['entryMonth'] ?? '-',
//                 'الدورة' => $bill['entryDate'] ?? '-',
//                 'الفاتورة ل.س' => number_format(floatval($bill['documentAmountDoc']['amount'] ?? 0), 1),
//                 'المتبقي ل.س' => number_format(floatval($bill['openAmountDoc']['amount'] ?? 0), 1),
//                 'طباعة' => '',
//             ];
//         })->toArray();
//     };

//     return [
//         'الارضى' => $formatBills($landlineBills),
//         'الراوتر' => $formatBills($routerBills),
//     ];
// }
public function getFormattedBills($customerId, $authToken, $contractId): array
{
    $rawData = $this->companyAuthService->getBills_slefcare($customerId, $authToken);

    if (!$rawData || !is_array($rawData)) {
        return [
            'message' => 'فشل جلب الفواتير',
            'success' => false,
            'data' => []
        ];
    }

    $filteredBills = collect($rawData)->filter(function ($bill) use ($contractId) {
        return isset($bill['contractIdPub']) && $bill['contractIdPub'] === $contractId;
    })->values();

    $formatted = $filteredBills->map(function ($bill, $index) {
        return [
            '#' => $index + 1,
            'الشهر' => $bill['entryMonth'] ?? '-',
            'الدورة' => $bill['entryDate'] ?? '-',
            'الفاتورة ل.س' => number_format(floatval($bill['documentAmountDoc']['amount'] ?? 0), 1),
            'المتبقي ل.س' => number_format(floatval($bill['openAmountDoc']['amount'] ?? 0), 1),
            'طباعة' => '',
        ];
    })->toArray();

    return [
        'message' => 'تم جلب الفواتير بنجاح',
        'success' => true,
        'data' => $formatted
    ];
}

}
