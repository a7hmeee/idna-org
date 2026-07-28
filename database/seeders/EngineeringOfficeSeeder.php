<?php

namespace Database\Seeders;

use App\Domains\EngineeringOffices\Enums\EngineeringOfficeApprovalStatus;
use App\Domains\EngineeringOffices\Enums\EngineeringOfficeStatus;
use App\Domains\EngineeringOffices\Models\EngineeringOffice;
use Illuminate\Database\Seeder;

final class EngineeringOfficeSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = 1;

        $offices = [
            [
                'office_name' => 'مكتب الهندسة المعماري',
                'engineer_name' => 'أحمد محمد عودة',
                'license_number' => 'ENG-2024-001',
                'phone' => '022345678',
                'mobile' => '0599123456',
                'email' => 'ahmed.ouda@example.com',
                'address' => 'إذنا - شارع القدس - عمارة الحاج نمر',
                'specializations' => ['تصميم معماري', 'إشراف هندسي', 'تراخيص بناء'],
                'approval_status' => EngineeringOfficeApprovalStatus::Approved,
                'status' => EngineeringOfficeStatus::Active,
                'notes' => 'مكتب معتمد من البلدية',
                'is_public' => true,
                'sort_order' => 1,
                'approved_at' => now(),
            ],
            [
                'office_name' => 'مكتب الهندسة المدنية',
                'engineer_name' => 'سامي جبر',
                'license_number' => 'ENG-2024-002',
                'phone' => '022-987654',
                'mobile' => '0599987654',
                'email' => 'sami.jabr@example.com',
                'address' => 'إدنا - شارع البلدية - مجمع الفارس',
                'specializations' => ['هندسة مدنية', 'مساحة', 'تصميم إنشائي'],
                'approval_status' => EngineeringOfficeApprovalStatus::Approved,
                'status' => EngineeringOfficeStatus::Active,
                'is_public' => true,
                'sort_order' => 2,
                'approved_at' => now(),
            ],
            [
                'office_name' => 'مكتب الخبراء الهندسيين',
                'engineer_name' => 'نادر شحادة',
                'license_number' => 'ENG-2024-003',
                'phone' => '022-555123',
                'mobile' => '0599555123',
                'email' => 'nader.sh@example.com',
                'specializations' => ['دراسات جدوى', 'إدارة مشاريع', 'استشارات هندسية'],
                'approval_status' => EngineeringOfficeApprovalStatus::Pending,
                'status' => EngineeringOfficeStatus::Active,
                'is_public' => false,
                'sort_order' => 3,
            ],
            [
                'office_name' => 'مكتب الإبداع الهندسي',
                'engineer_name' => 'لينا مصطفى',
                'license_number' => 'ENG-2024-004',
                'mobile' => '0599777888',
                'email' => 'lina.m@example.com',
                'address' => 'إدنا - دوار البلدة القديمة',
                'specializations' => ['تصميم داخلي', 'تنسيق مواقع', 'هندسة خضراء'],
                'approval_status' => EngineeringOfficeApprovalStatus::Suspended,
                'status' => EngineeringOfficeStatus::Active,
                'notes' => 'موقوف لحين تجديد الترخيص',
                'is_public' => false,
                'sort_order' => 4,
                'suspended_at' => now(),
            ],
            [
                'office_name' => 'مكتب المستقبل الهندسي',
                'engineer_name' => 'خالد عواد',
                'license_number' => 'ENG-2024-005',
                'phone' => '022-111222',
                'mobile' => '0599111222',
                'address' => 'إدنا - شارع رام الله',
                'specializations' => ['هندسة كهرباء', 'هندسة ميكانيك', 'طاقة متجددة'],
                'approval_status' => EngineeringOfficeApprovalStatus::Expired,
                'status' => EngineeringOfficeStatus::Inactive,
                'is_public' => false,
                'sort_order' => 5,
                'expires_at' => now()->subMonth(),
            ],
        ];

        foreach ($offices as $office) {
            EngineeringOffice::updateOrCreate(
                ['license_number' => $office['license_number']],
                array_merge($office, ['created_by' => $adminId])
            );
        }
    }
}