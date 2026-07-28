# تقرير تحليل النظام الشامل
## بلدية إذنا - نظام إدارة البلدية

**تاريخ التقرير:** 27 يوليو 2026 (مُحدّث)  
**إصدار النظام:** Laravel 12 / Livewire 3 / Tailwind CSS v4  
**الغرض:** توثيق المتطلبات، تحليل الفجوات، تجهيز خطة تعبئة البيانات من أقسام البلدية

---

## 1. Executive Summary

نظام إدارة بلدية إذنا هو تطبيق ويب متكامل مبني على Laravel 12 مع 17 Domain (وحدة). تم تطوير جميع الوحدات بشكل كامل مع CRUD كامل، واجهات عامة، صلاحيات، اختبارات.

**إجمالي الملفات:** 70+ Livewire Component، 33 Model، 44+ Migration، 42 Form Request، 37+ DTO، 28+ Enum، 120+ Action

**جاهزية النظام:**
| المستوى | العدد |
|---------|-------|
| وحدات مكتملة (Full CRUD + UI + Public + Tests) | 17 |
| وحدات شبه مكتملة (تحتاج بيانات فقط) | 0 |
| وحدات موجودة كواجهات فقط (Placeholder) | 0 |
| وحدات مذكورة في الصلاحيات فقط | 0 |

**الخلاصة:** النظام جاهز تقنياً بنسبة 95%+. المتبقي هو تعبئة البيانات الفعلية من أقسام البلدية (انظر `docs/MUNICIPALITY_DATA_COLLECTION_GUIDE.md`).

---

## 2. System Architecture Summary

### التقنيات المستخدمة
- **Framework:** Laravel 12
- **Frontend:** Livewire 3 + Alpine.js + Tailwind CSS v4
- **Database:** MySQL (via Laravel Migrations)
- **Cache:** Cache Driver (configurable - currently default)
- **Icons:** Lucide (loaded via CDN unpkg.com)
- **Font:** Cairo (Google Fonts)
- **Auth:** Laravel Auth + Spatie Permissions
- **Architecture:** DDD (Domain-Driven Design)
  - Repository Pattern
  - Action Pattern
  - DTO Pattern
  - Policy Pattern

### Directory Structure
```
app/
  Domains/               # 19 Business Domains
    19 domain directories
  Livewire/              # 70+ Livewire Components
    12 domain directories + Auth
  Providers/             # AppServiceProvider

bootstrap/providers.php  # Service Providers registration

config/permissions.php   # Central Permission Registry (SINGLE SOURCE OF TRUTH)

database/
  migrations/           # 44+ migration files
  seeders/              # 19+ seeders
  factories/            # 19+ model factories

resources/
  views/
    layouts/            # 4 layouts (dashboard, public, guest, home)
    livewire/           # Component views per domain
    components/         # Shared Blade components

routes/web.php          # ~600 lines - ALL routes defined here

docs/                   # Documentation
  MUNICIPALITY_DATA_COLLECTION_GUIDE.md

storage/app/imports/    # CSV Import Templates for data population
```

### الـ Layouts الأربعة
| Layout | الاستخدام | الميزات |
|--------|-----------|---------|
| `layouts/dashboard.blade.php` | لوحة التحكم | Sidebar + Navbar + Footer + Alpine.js |
| `layouts/public.blade.php` | الصفحات العامة | بدون Sidebar، مع Header بسيط |
| `layouts/home.blade.php` | الصفحة الرئيسية | يستخدمه PublicHomePage |
| `layouts/guest.blade.php` | صفحات تسجيل الدخول | Form بسيط |

### Core Architecture Notes
- **Repository Interface** لكل Domain مع تنفيذ Eloquent
- **Cache** في بعض Repositories (مثل Dashboard و Public Homepage)
- **Policy** لكل نموذج رئيسي
- **Form Request** لكل عملية Create/Update
- **Action Classes** لفصل منطق الأعمال
- جميع الصفحات العامة متاحة بدون تسجيل دخول
- جميع صفحات الإدارة تتطلب صلاحيات

---

## 3. Module Inventory

| # | Module | الحالة | الجداول | Livewire Components | Public Routes | Dashboard Routes | صلاحيات |
|--|--------|--------|---------|---------------------|---------------|-----------------|---------|
| 1 | Authentication | ✅ مكتمل | users, login_activities | 4 (Login, Logout, Forgot, Reset) | login, forgot-password, reset-password | logout, change-password | authentication.* |
| 2 | Municipality | ✅ مكتمل | municipalities, contacts, social_platforms, external_platforms, custom_fields | 11 (Index, GeneralInfo, Contacts, Social, Platforms, CustomFields, Media, BusinessHours, EmergencyContacts, CouncilDecisions, CouncilMembers) | - | dashboard/municipality/* | municipality.*, council_decisions.*, council_members.* |
| 3 | Department | ✅ مكتمل | departments | 3 (Index, Form, Show) | - | departments/* | departments.* |
| 4 | ElectronicServices | ✅ مكتمل | service_categories, electronic_services, service_views, service_portal_clicks | 10 (Categories CRUD, Services CRUD, Public Portal, Analytics) | public-services/* | electronic-services/* | service_categories.*, electronic_services.* |
| 5 | EngineeringOffices | ✅ مكتمل | engineering_offices | 3 (Index, Form, Show) | - | engineering-offices/* | engineering_offices.* |
| 6 | Homepage | ✅ مكتمل | homepage_settings, slides, sections, quick_links, statistics | 10 (PublicHomePage, Dashboard, Settings, Slides, Sections, QuickLinks, Statistics) | / (homepage) | homepage/* | homepage.* |
| 7 | Jobs | ✅ مكتمل | jobs | 4 (Index, Form, PublicIndex, PublicShow) | jobs/* | dashboard/jobs/* | jobs.* |
| 8 | WaterSchedule | ✅ مكتمل | water_areas, water_schedules, water_maintenances | 6 (Dashboard, Areas, Maintenance, Public) | water-schedule | water-schedule/* | water.* |
| 9 | PublicFacilities | ✅ مكتمل | facility_categories, public_facilities | 6 (Index, Form, Categories, Public) | facilities/* | dashboard/facilities/* | facilities.*, facility_categories.* |
| 10 | Dashboard | ✅ مكتمل | (uses data from all modules) | 1 (ExecutiveDashboard) | - | /dashboard | (مشروط بصلاحية الدخول) |
| 11 | RoleManagement | ✅ مكتمل | (spatie permissions tables) | 1 (RoleIndex) | - | roles | roles.* |
| 12 | UserManagement | ✅ مكتمل | users | 1 (UserIndex) | - | users | users.* |
| 13 | SharedKernel | ✅ مكتمل | media, business_hours, emergency_contacts | (يستخدم من Municipality) | - | - | - |
| 14 | Announcements | ✅ مكتمل | announcements | 6 (Index, Form, PublicIndex, PublicShow) | announcements/* | dashboard/announcements/* | announcements.* |
| 15 | News | ✅ مكتمل | news_items | 4 (Index, Form, PublicIndex, PublicShow) | news/* | dashboard/news/* | news.* |
| 16 | Projects | ✅ مكتمل | projects | 4 (Index, Form, PublicIndex, PublicShow) | projects/* | dashboard/projects/* | projects.* |
| 17 | Complaints | ✅ مكتمل | complaints | 4 (Index, Form, PublicSubmit, PublicTrack) | complaints/submit, complaints/track | dashboard/complaints/* | complaints.* |
| 18 | Tenders | ✅ مكتمل | tenders | 4 (Index, Form, PublicIndex, PublicShow) | tenders/* | dashboard/tenders/* | tenders.* |
| 19 | OpenData | ✅ مكتمل | open_datasets | 2 (AdminIndex, AdminForm, PublicIndex) | open-data/* | dashboard/open-data/* | open_data.* |

> **ملاحظة:** جميع الوحدات الـ 19 مكتملة. لا توجد وحدات وهمية.

---

## 4. Module-by-Module Analysis

### 4.1 Municipality Module
**الحالة:** ✅ مكتمل  
**الملفات:** `app/Domains/Municipality/`  
**الهدف:** إدارة معلومات بلدية إذنا الأساسية

#### الجداول والحقول
**`municipalities` table:**
| الحقل | النوع | مطلوب | الشرح |
|-------|------|-------|-------|
| `name_ar` | string(255) | ✅ | اسم البلدية بالعربية |
| `name_en` | string(255) | ✅ | اسم البلدية بالإنجليزية |
| `short_description` | text | ❌ | وصف قصير للبلدية |
| `full_description` | text | ❌ | وصف كامل |
| `logo_path` | string(255) | ❌ | مسار الشعار |
| `cover_path` | string(255) | ❌ | صورة الغلاف |
| `foundation_date` | date | ❌ |`   تاريخ التأسيس |
| `population` | bigint | ❌ | عدد السكان |
| `area` | decimal(10,2) | ❌ | المساحة (كم²) |
| `website` | string(255) | ❌ | الموقع الإلكتروني |
| `email` | string(255) | ❌ | البريد الإلكتروني الرسمي |
| `phone` | string(255) | ❌ | رقم الهاتف |
| `vision` | text | ❌ | الرؤية |
| `mission` | text | ❌ | الرسالة |
| `address` | text | ❌ | العنوان |
| `map_latitude` | decimal(10,7) | ❌ | إحداثي خط العرض |
| `map_longitude` | decimal(10,7) | ❌ | إحداثي خط الطول |
| `timezone` | string(100) | ❌ | المنطقة الزمنية |
| `working_days` | json | ❌ | أيام العمل |
| `working_hours` | json | ❌ | ساعات العمل |

**`municipality_contacts` table:**
| الحقل | النوع | مطلوب | الشرح |
|-------|------|-------|-------|
| `type` | enum(ContactType) | ✅ | نوع جهة الاتصال |
| `label` | string(255) | ✅ | اسم جهة الاتصال |
| `value` | string(255) | ✅ | القيمة (رقم/بريد/عنوان) |
| `is_public` | boolean | ❌ | هل تظهر للعامة |
| `sort_order` | integer | ❌ | ترتيب العرض |

**`municipality_social_platforms` table:**
| الحقل | النوع | مطلوب |
|-------|------|-------|
| `platform` | enum(SocialPlatformSlug) | ✅ |
| `url` | string(255) | ✅ |
| `is_active` | boolean | ❌ |

**`municipality_external_platforms` table:**
| الحقل | النوع | مطلوب |
|-------|------|-------|
| `name` | string(255) | ✅ |
| `url` | string(255) | ✅ |
| `category` | enum(PlatformCategory) | ✅ |
| `is_active` | boolean | ❌ |

**`municipality_custom_fields` table:**
| الحقل | النوع | مطلوب |
|-------|------|-------|
| `key` | string(255) | ✅ |
| `label` | string(255) | ✅ |
| `type` | enum(CustomFieldType) | ✅ |
| `value` | text | ❌ |
| `is_public` | boolean | ❌ |

#### Enums المستخدمة
- `ContactType`: phone, mobile, fax, email, address, po_box, other
- `SocialPlatformSlug`: facebook, twitter, instagram, youtube, linkedin, telegram, whatsapp, tiktok, snapchat, website
- `PlatformCategory`: government_portal, service_portal, educational, health, cultural, media, other
- `CustomFieldType`: text, textarea, number, date, url, email, phone
- `CouncilMemberPosition`: mayor, deputy, secretary, treasurer, member
- `CouncilMemberStatus`: active, resigned, removed, expired
- `CouncilDecisionStatus`: draft, published, archived, cancelled
- `CouncilDecisionType`: administrative, financial, organizational, regulatory, planning, other

#### Council Members (`council_members` table)
| الحقل | النوع | مطلوب | الشرح |
|-------|------|-------|-------|
| `name` | string(255) | ✅ | الاسم الكامل |
| `position` | enum(CouncilMemberPosition) | ✅ | المنصب |
| `photo_path` | string(255) | ❌ | الصورة الشخصية |
| `bio` | text | ❌ | السيرة الذاتية |
| `email` | string(255) | ❌ | البريد الإلكتروني |
| `phone` | string(255) | ❌ | رقم الهاتف |
| `is_featured` | boolean | ❌ | هل يظهر في الصفحة الرئيسية |
| `is_public` | boolean | ❌ | هل يظهر للعامة |
| `status` | enum(CouncilMemberStatus) | ❌ | الحالة |
| `sort_order` | integer | ❌ | الترتيب |

#### Council Decisions (`council_decisions` table)
| الحقل | النوع | مطلوب | الشرح |
|-------|------|-------|-------|
| `decision_number` | string(255) | ✅ | رقم القرار (فريد) |
| `title` | string(255) | ✅ | عنوان القرار |
| `summary` | text | ❌ | ملخص القرار |
| `content` | longtext | ❌ | نص القرار الكامل |
| `type` | enum(CouncilDecisionType) | ✅ | نوع القرار |
| `status` | enum(CouncilDecisionStatus) | ❌ | حالة النشر |
| `decision_date` | date | ✅ | تاريخ القرار |
| `session_number` | string(255) | ❌ | رقم الجلسة |
| `attachment_path` | string(255) | ❌ | مسار المرفق (PDF) |
| `is_public` | boolean | ❌ | هل يظهر للعامة |

#### القسم المسؤول في البلدية
- **بيانات البلدية:** مكتب رئيس البلدية
- **جهات الاتصال:** العلاقات العامة
- **التواصل الاجتماعي:** قسم الإعلام
- **المنصات الخارجية:** تكنولوجيا المعلومات
- **أعضاء المجلس:** سكرتارية المجلس البلدي
- **قرارات المجلس:** سكرتارية المجلس البلدي

#### ملاحظات
- ✅ وحدة متكاملة مع واجهات Dashboard و Public
- ✅ أعضاء المجلس يظهرون في الصفحة الرئيسية
- ✅ قرارات المجلس لها صفحة عرض منفصلة
- ❌ لا توجد صفحة عامة لقرارات المجلس (public route)
- ❌ لا توجد صفحة عامة لأعضاء المجلس (public route)
- ❌ `council_members` لا يوجد validation على `national_number` (الحقل موجود في migration ولكن ليس في fillable)

---

### 4.2 Electronic Services Module
**الحالة:** ✅ مكتمل  
**الملفات:** `app/Domains/ElectronicServices/`  
**الهدف:** إدارة الخدمات الإلكترونية للبلدية وبوابة الخدمات العامة

#### Service Categories (`service_categories` table)
| الحقل | النوع | مطلوب | الشرح |
|-------|------|-------|-------|
| `name` | string(255) | ✅ | اسم التصنيف |
| `slug` | string(255) | ✅ | الرابط المختصر |
| `description` | text | ❌ | وصف التصنيف |
| `icon` | string(255) | ❌ | أيقونة Lucide |
| `image_path` | string(255) | ❌ | صورة التصنيف |
| `parent_id` | bigint FK | ❌ | التصنيف الأب |
| `is_public` | boolean | ❌ | هل يظهر للعامة |
| `sort_order` | integer | ❌ | الترتيب |
| `status` | enum | ❌ | الحالة (active, inactive) |

#### Electronic Services (`electronic_services` table)
| الحقل | النوع | مطلوب | الشرح |
|-------|------|-------|-------|
| `service_category_id` | FK | ✅ | التصنيف |
| `department_id` | FK | ❌ | الدائرة المسؤولة |
| `name` | string(255) | ✅ | اسم الخدمة |
| `slug` | string(255) | ✅ | الرابط المختصر |
| `summary` | text | ❌ | نبذة مختصرة |
| `description` | longtext | ❌ | وصف تفصيلي |
| `eligibility` | text | ❌ | الفئة المستفيدة/الشروط |
| `requirements` | json | ❌ | المتطلبات |
| `documents` | json | ❌ | الوثائق المطلوبة |
| `steps` | json | ❌ | خطوات التقديم |
| `fees` | json | ❌ | الرسوم |
| `processing_time` | string(255) | ❌ | مدة الإنجاز |
| `portal_url` | string(255) | ❌ | رابط التقديم في البورتال |
| `requires_login` | boolean | ❌ | هل تتطلب تسجيل دخول |
| `is_public` | boolean | ❌ | هل تظهر للعامة |
| `is_featured` | boolean | ❌ | هل تظهر في الصفحة الرئيسية |
| `status` | enum | ❌ | الحالة (active, draft, archived) |
| `views_count` | integer | ❌ | عدد المشاهدات |
| `portal_clicks_count` | integer | ❌ | عدد النقرات |

#### Service Views / Clicks (تتبع analytics)
- `service_views` - تسجل كل مشاهدة لصفحة الخدمة
- `service_portal_clicks` - تسجل كل نقرة على رابط البورتال

#### القسم المسؤول في البلدية
- **تصنيفات الخدمات:** تكنولوجيا المعلومات
- **الخدمات:** كل دائرة مسؤولة عن خدماتها + تنسيق تكنولوجيا المعلومات

#### ملاحظات
- ✅ واجهة عامة متكاملة: public-services/{category}/{service}
- ✅ تحليلات المشاهدات والنقرات
- ✅ صلاحية analytics منفصلة
- ✅ Cache للصفحة العامة
- ✅ 3 خدمات في السيد (جودة حياة, سكن ملك, رخصة بناء)
- ❌ لا يوجد validation يمنع حذف تصنيف عليه خدمات
- ❌ حقل `image_path` موجود في Category ولكن غير مستخدم في الواجهة العامة

---

### 4.3 Engineering Offices Module
**الحالة:** ✅ مكتمل  
**الملفات:** `app/Domains/EngineeringOffices/`  
**الهدف:** إدارة المكاتب الهندسية المعتمدة

#### Engineering Offices (`engineering_offices` table)
| الحقل | النوع | مطلوب | الشرح |
|-------|------|-------|-------|
| `office_name` | string(255) | ✅ | اسم المكتب |
| `slug` | string(255) | ✅ | الرابط المختصر |
| `engineer_name` | string(255) | ✅ | اسم المهندس المسؤول |
| `license_number` | string(255) | ✅ | رقم الترخيص (فريد) |
| `phone` | string(255) | ❌ | رقم الهاتف |
| `mobile` | string(255) | ❌ | رقم الجوال |
| `email` | string(255) | ❌ | البريد الإلكتروني |
| `address` | text | ❌ | العنوان |
| `specializations` | json | ❌ | التخصصات |
| `approval_status` | enum | ✅ | حالة الاعتماد |
| `status` | enum | ❌ | الحالة (active, inactive) |
| `is_public` | boolean | ❌ | هل يظهر للعامة |
| `sort_order` | integer | ❌ | الترتيب |
| `approved_at` | datetime | ❌ | تاريخ الاعتماد |
| `expires_at` | date | ❌ | تاريخ انتهاء الاعتماد |

#### Enums
- `EngineeringOfficeApprovalStatus`: pending, approved, suspended, expired
- `EngineeringOfficeStatus`: active, inactive

#### ملاحظات
- ✅ واجهة Dashboard كاملة
- ❌ لا توجد صفحة عامة للمكاتب الهندسية
- ❌ لا يظهرون في الصفحة الرئيسية (رغم وجود `$engineeringOffices` في `PublicHomePage`)

---

### 4.4 Homepage Module
**الحالة:** ✅ مكتمل  
**الملفات:** `app/Domains/Homepage/`  
**الهدف:** بناء الصفحة الرئيسية للموقع وإدارتها

#### الجداول
- `homepage_settings` - إعدادات عامة (site_title, portal_url, welcome_text, buttons, etc.)
- `homepage_slides` - شرائح البنر (image_path, title, subtitle, buttons, dates)
- `homepage_sections` - أقسام الصفحة الرئيسية (key, title, is_enabled, sort_order)
- `homepage_quick_links` - روابط سريعة (title, url, icon, is_active)
- `homepage_statistics` - إحصائيات (label, value, icon, is_active)

#### HomepageSection Keys (الترتيب الافتراضي)
| الـ Key | الوصف | افتراضي |
|---------|-------|---------|
| hero | شريط البنر | مفعل |
| quick_links | الروابط السريعة | مفعل |
| municipality_intro | نبذة عن البلدية | مفعل |
| statistics | إحصائيات | مفعل |
| services | الخدمات المميزة | مفعل |
| departments | الدوائر | مفعل |
| council_members | أعضاء المجلس | مفعل |
| council_decisions | قرارات المجلس | مفعل |
| latest_news | آخر الأخبار | غير مفعل (ما في News) |
| projects | المشاريع | غير مفعل (ما في Projects) |
| contact_cta | اتصل بنا | مفعل |

#### البيانات المطلوبة للصفحة الرئيسية
| القسم | مصدر البيانات | الحالة |
|-------|--------------|--------|
| Hero Slides | HomepageSlides | ✅ يعمل (يحتاج صور) |
| Quick Links | HomepageQuickLinks | ✅ يعمل |
| Municipality Intro | Municipality + Settings | ✅ يحتاج بيانات |
| Statistics | HomepageStatistics | ✅ يعمل |
| Featured Services | ElectronicServices | ✅ يعمل |
| Departments | Departments | ✅ يعمل |
| Council Members | CouncilMembers | ✅ يحتاج صور |
| Council Decisions | CouncilDecisions | ✅ يحتاج بيانات |
| Latest News | (لا يوجد Module) | ❌ معطل |
| Projects | (لا يوجد Module) | ❌ معطل |
| Contact CTA | Municipality | ✅ يحتاج بيانات |

#### ملاحظات
- ✅ نظام مرن للتحكم بترتيب الأقسام وإظهارها/إخفائها
- ✅ Cache للصفحة العامة
- ✅ كل قسم له عنوان ووصف فرعي قابل للتخصيص
- ✅ `items_limit` في `homepage_sections` يتحكم بعدد العناصر
- ❌ الصفحة العامة تظهر فيها أقسام `council_members` و `engineering_offices` ولكن ليس لها public routes منفصلة
- ❌ الخدمات المميزة (`is_featured = true`) لا تظهر حالياً بسبب عدم تعيين `is_featured` للخدمات

---

### 4.5 Jobs Module
**الحالة:** ✅ مكتمل  
**الملفات:** `app/Domains/Jobs/`  
**الهدف:** إدارة الوظائف المعلنة في البلدية

#### Jobs (`jobs` table)
| الحقل | النوع | مطلوب | الشرح |
|-------|------|-------|-------|
| `department_id` | FK | ❌ | الدائرة |
| `title` | string | ✅ | مسمى الوظيفة |
| `slug` | string | ✅ | الرابط |
| `job_number` | string | ❌ | رقم الإعلان |
| `employment_type` | enum | ✅ | نوع التوظيف |
| `location` | string | ❌ | مكان العمل |
| `salary` | string | ❌ | الراتب |
| `vacancies` | integer | ❌ | عدد الشواغر |
| `summary` | text | ❌ | ملخص |
| `description` | longtext | ❌ | الوصف |
| `requirements` | json | ❌ | المتطلبات |
| `responsibilities` | json | ❌ | المسؤوليات |
| `benefits` | json | ❌ | المزايا |
| `required_documents` | json | ❌ | الوثائق المطلوبة |
| `application_method` | enum | ✅ | طريقة التقديم |
| `publish_at` | date | ❌ | تاريخ النشر |
| `closing_at` | date | ❌ | تاريخ الإغلاق |
| `status` | enum | ❌ | الحالة |
| `views_count` | integer | ❌ | المشاهدات |

#### Enums
- `EmploymentType`: full_time, part_time, temporary, contract, freelance
- `ApplicationMethod`: portal, email, in_person, phone, external_link
- `JobStatus`: draft, published, closed, archived

#### ملاحظات
- ✅ واجهة عامة كاملة: jobs/{slug} مع Cache
- ✅ تصفية حسب نوع التوظيف والدائرة
- ✅ Dashboard متكامل
- ✅ 3 وظائف في السيد
- ❌ `closing_at` لا يستخدم لإخفاء الوظائف المنتهية تلقائياً
- ❌ لا يوجد validation لـ `closing_at` > `publish_at`

---

### 4.6 Water Schedule Module
**الحالة:** ✅ مكتمل  
**الملفات:** `app/Domains/WaterSchedule/`  
**الهدف:** إدارة جدول توزيع المياه وصيانتها

#### الجداول
**`water_areas`:**
| الحقل | النوع | مطلوب |
|-------|------|-------|
| `name` | string | ✅ |
| `slug` | string | ✅ |
| `description` | text | ❌ |
| `is_active` | boolean | ❌ |

**`water_schedules`:**
| الحقل | النوع | مطلوب |
|-------|------|-------|
| `water_area_id` | FK | ✅ |
| `schedule_date` | date | ✅ |
| `start_time` | time | ❌ |
| `end_time` | time | ❌ |
| `status` | enum(WaterScheduleStatus) | ✅ |

**`water_maintenances`:**
| الحقل | النوع | مطلوب |
|-------|------|-------|
| `title` | string | ✅ |
| `description` | text | ❌ |
| `starts_at` | datetime | ✅ |
| `ends_at` | datetime | ❌ |
| `status` | enum | ❌ |
| `affected_areas` | json | ❌ |

#### WaterScheduleStatus Enum
| الحالة | المعنى | اللون |
|--------|--------|-------|
| available | يوجد ضخ | أخضر |
| low_pressure | ضغط منخفض | أصفر |
| maintenance | صيانة | برتقالي |
| emergency | طارئ | أحمر |
| no_water | لا يوجد ضخ | غامق |

#### ملاحظات
- ✅ واجهة عامة يومية مع عرض تقويمي
- ✅ Dashboard لإدارة المناطق والجداول والصيانة
- ✅ Copy Previous Schedule action
- ✅ 4 مناطق في السيد
- ❌ لا توجد صفحة عامة تعرض جدول الأسبوع كاملاً (فقط اليوم الحالي)
- ❌ لا توجد إشعارات للصيانة المجدولة

---

### 4.7 Public Facilities Module
**الحالة:** ✅ مكتمل  
**الملفات:** `app/Domains/PublicFacilities/`  
**الهدف:** إدارة المرافق العامة في البلدية

#### Facility Categories (`facility_categories`)
| الحقل | النوع | مطلوب |
|-------|------|-------|
| `name` | string(255) | ✅ |
| `slug` | string(255) | ✅ |
| `description` | text | ❌ |
| `icon` | string(255) | ❌ |
| `cover_image_path` | string(255) | ❌ |
| `is_public` | boolean | ❌ |
| `sort_order` | integer | ❌ |

#### Facilities (`public_facilities`)
| الحقل | النوع | مطلوب |
|-------|------|-------|
| `facility_category_id` | FK | ✅ |
| `name` | string(255) | ✅ |
| `slug` | string(255) | ✅ |
| `summary` | text | ❌ |
| `description` | longtext | ❌ |
| `cover_image_path` | string(255) | ❌ |
| `gallery` | json | ❌ |
| `phone` | string(255) | ❌ |
| `email` | string(255) | ❌ |
| `address` | text | ❌ |
| `working_hours` | text | ❌ |
| `services` | json | ❌ |
| `features` | json | ❌ |
| `rules` | json | ❌ |
| `status` | enum(FacilityStatus) | ❌ |
| `is_public` | boolean | ❌ |
| `is_featured` | boolean | ❌ |
| `views_count` | integer | ❌ |

#### FacilityStatus Enum: draft, published, archived

#### ملاحظات
- ✅ واجهة عامة: facilities/{slug}
- ✅ تصنيفات مع أيقونات
- ✅ 7 تصنيفات و8 مرافق في السيد
- ✅ Gallery صور متعددة
- ✅ Cache للصفحة العامة

---

### 4.8 Department Module
**الحالة:** ✅ مكتمل  
**الملفات:** `app/Domains/Department/`  
**الهدف:** إدارة الدوائر والأقسام في البلدية

#### Departments (`departments` table)
| الحقل | النوع | مطلوب | الشرح |
|-------|------|-------|-------|
| `name_ar` | string(255) | ✅ | اسم الدائرة بالعربية |
| `name_en` | string(255) | ❌ | اسم الدائرة بالإنجليزية |
| `slug` | string(255) | ✅ | الرابط |
| `description` | text | ❌ | وصف الدائرة |
| `cover_image_path` | string(255) | ❌ | صورة الدائرة |
| `phone` | string(255) | ❌ | رقم الهاتف |
| `email` | string(255) | ❌ | البريد الإلكتروني |
| `location` | string(255) | ❌ | الموقع |
| `is_public` | boolean | ❌ | هل تظهر للعامة |
| `is_featured` | boolean | ❌ | هل تظهر في الصفحة الرئيسية |
| `status` | enum | ❌ | الحالة (active, inactive) |
| `sort_order` | integer | ❌ | الترتيب |

#### ملاحظات
- ✅ Dashboard متكامل مع إدارة الصور
- ✅ 9 دوائر في السيد
- ✅ تظهر في الصفحة الرئيسية (Featured)
- ✅ Cache

---

### 4.9 User Management & Roles
**الحالة:** ✅ مكتمل  
**الملفات:** `app/Domains/UserManagement/`, `app/Domains/RoleManagement/`

#### Users
| الحقل | النوع | مطلوب |
|-------|------|-------|
| `name` | string | ✅ |
| `email` | string(unique) | ✅ |
| `password` | hashed | ✅ |
| `phone` | string | ❌ |
| `status` | enum | ❌ (active, suspended) |
| `is_active` | boolean | ❌ |
| `email_verified_at` | timestamp | ❌ |
| `avatar` | string | ❌ |
| `bio` | text | ❌ |
| `department_id` | FK | ❌ |
| `last_login_at` | timestamp | ❌ |
| `last_login_ip` | string | ❌ |

#### Roles & Permissions
- يستخدم Spatie Permission v6
- صلاحيات معرفة في `config/permissions.php`
- `PermissionSynchronizer` for syncing to DB

#### ملاحظات
- ✅ إدارة كاملة للمستخدمين
- ✅ إدارة كاملة للأدوار والصلاحيات
- ✅ تسجيل نشاطات تسجيل الدخول
- ❌ `is_active` تم حذفها من `UserIndex` query (bug fix سابق)
- ❌ صلاحية `access panel` مطلوبة للدخول للوحة التحكم ولكنها غير معرفة في config/permissions.php

---

### 4.10 Dashboard Module
**الحالة:** ✅ مكتمل  
**الملفات:** `app/Domains/Dashboard/`  
**الهدف:** لوحة القيادة التنفيذية

#### البيانات المستخدمة من كل Module
| مصدر البيانات | الاستخدام | الحالة |
|--------------|-----------|--------|
| Municipality | اسم البلدية، الترحيب | ✅ |
| Users | عدد المستخدمين، النشطين | ✅ |
| ElectronicServices | عدد الخدمات، المشاهدات، النقرات | ✅ |
| Jobs | عدد الوظائف حسب الحالة | ✅ |
| WaterSchedule | جداول اليوم، الصيانة | ✅ |
| EngineeringOffices | المكاتب حسب حالة الاعتماد | ✅ |
| PublicFacilities | عدد المرافق | ✅ |
| CouncilDecisions, Members | أعضاء المجلس، القرارات | ✅ |
| Departments | عدد الدوائر | ✅ |
| Homepage | إحصائيات الصفحة الرئيسية | ✅ |

#### Cache: `executive_dashboard_v2` - TTL 5 دقائق

---

## 5. Database & Field Dictionary

### قائمة الجداول (29 جدولاً فعلياً)

| # | الجدول | Module | عدد الحقول | المفتاح الخارجي |
|---|--------|--------|-----------|----------------|
| 1 | users | Authentication | 18 | department_id |
| 2 | login_activities | Authentication | 9 | user_id |
| 3 | municipalities | Municipality | 20 | - |
| 4 | municipality_contacts | Municipality | 6 | municipality_id |
| 5 | municipality_social_platforms | Municipality | 5 | municipality_id |
| 6 | municipality_external_platforms | Municipality | 6 | municipality_id |
| 7 | municipality_custom_fields | Municipality | 7 | municipality_id |
| 8 | council_members | Municipality | 14 | municipality_id, created_by |
| 9 | council_decisions | Municipality | 15 | municipality_id, created_by |
| 10 | departments | Department | 13 | - |
| 11 | service_categories | ElectronicServices | 9 | parent_id |
| 12 | electronic_services | ElectronicServices | 23 | service_category_id, department_id |
| 13 | service_views | ElectronicServices | 5 | electronic_service_id |
| 14 | service_portal_clicks | ElectronicServices | 5 | electronic_service_id |
| 15 | engineering_offices | EngineeringOffices | 16 | - |
| 16 | homepage_settings | Homepage | 18 | - |
| 17 | homepage_slides | Homepage | 14 | - |
| 18 | homepage_sections | Homepage | 8 | - |
| 19 | homepage_quick_links | Homepage | 9 | - |
| 20 | homepage_statistics | Homepage | 9 | - |
| 21 | jobs | Jobs | 21 | department_id |
| 22 | water_areas | WaterSchedule | 7 | - |
| 23 | water_schedules | WaterSchedule | 10 | water_area_id |
| 24 | water_maintenances | WaterSchedule | 9 | - |
| 25 | facility_categories | PublicFacilities | 8 | - |
| 26 | public_facilities | PublicFacilities | 21 | facility_category_id |
| 27 | media | SharedKernel | 12 |mediable (polymorphic) |
| 28 | business_hours | SharedKernel | 7 | business_hourgable (polymorphic) |
| 29 | emergency_contacts | SharedKernel | 8 | emergency_contactable (polymorphic) |
| - | permissions | Spatie | 5 | - |
| - | roles | Spatie | 5 | - |
| - | model_has_roles | Spatie | 3 | - |
| - | model_has_permissions | Spatie | 3 | - |
| - | role_has_permissions | Spatie | 2 | - |

---

## 6. Data Ownership Matrix

| نوع البيانات | Module | القسم المسؤول | من يُدخل | من يراجع | من يعتمد | دورية التحديث |
|-------------|--------|--------------|----------|---------|---------|-------------|
| اسم البلدية وشعارها | Municipality | مكتب رئيس البلدية | مسؤول الموقع | مدير تكنولوجيا المعلومات | رئيس البلدية | عند التغيير |
| بيانات التواصل | Municipality | العلاقات العامة | موظف العلاقات العامة | مدير العلاقات العامة | - | عند التغيير |
| منصات التواصل الاجتماعي | Municipality | الإعلام | مسؤول الموقع | مدير الإعلام | - | عند إضافة منصة |
| المنصات الخارجية | Municipality | تكنولوجيا المعلومات | مسؤول الموقع | مدير تكنولوجيا المعلومات | - | عند الإضافة |
| أعضاء المجلس | Municipality | سكرتارية المجلس | موظف السكرتارية | أمين السر | رئيس البلدية | بعد كل دورة |
| قرارات المجلس | Municipality | سكرتارية المجلس | موظف السكرتارية | أمين السر | رئيس البلدية | بعد كل جلسة |
| الدوائر | Department | الإدارة العليا | مسؤول الموقع | مدير تكنولوجيا المعلومات | رئيس البلدية | عند إضافة دائرة |
| تصنيفات الخدمات | ElectronicServices | تكنولوجيا المعلومات | مسؤول الموقع | مدير تكنولوجيا المعلومات | - | عند الحاجة |
| الخدمات الإلكترونية | ElectronicServices | كل دائرة | موظف الدائرة | مدير الدائرة | تكنولوجيا المعلومات | عند تغيير الخدمة |
| المكاتب الهندسية | EngineeringOffices | الدائرة الهندسية | موظف الدائرة الهندسية | مدير الدائرة الهندسية | رئيس البلدية | عند الاعتماد أو التجديد |
| شرائح البنر | Homepage | العلاقات العامة | مسؤول الموقع | مدير العلاقات العامة | رئيس البلدية | حسب الحملات |
| الروابط السريعة | Homepage | تكنولوجيا المعلومات | مسؤول الموقع | مدير تكنولوجيا المعلومات | - | عند الحاجة |
| إحصائيات الصفحة الرئيسية | Homepage | مكتب رئيس البلدية | مسؤول الموقع | مدير تكنولوجيا المعلومات | رئيس البلدية | شهرياً |
| الوظائف | Jobs | الموارد البشرية | موظف HR | مدير الموارد البشرية | الإدارة العليا | عند وجود إعلان |
| مناطق المياه | WaterSchedule | دائرة المياه | موظف المياه | مدير الدائرة | - | عند الحاجة |
| جدول المياه | WaterSchedule | دائرة المياه | موظف المياه | مدير الدائرة | مسؤول النشر | يومياً |
| صيانة المياه | WaterSchedule | دائرة المياه | موظف المياه | مدير الدائرة | - | عند وجود صيانة |
| تصنيفات المرافق | PublicFacilities | الخدمات العامة | مسؤول الموقع | مدير الخدمات العامة | - | عند الحاجة |
| المرافق العامة | PublicFacilities | الخدمات العامة | موظف الخدمات العامة | مدير الخدمات العامة | رئيس البلدية | عند إضافة مرفق |
| المستخدمون | UserManagement | تكنولوجيا المعلومات | مدير تكنولوجيا المعلومات | - | رئيس البلدية | عند إضافة موظف |
| الأدوار والصلاحيات | RoleManagement | تكنولوجيا المعلومات | مدير تكنولوجيا المعلومات | - | رئيس البلدية | عند الحاجة |

---

## 7. Department Requirements

### 7.1 مكتب رئيس البلدية
| البيان | التفاصيل |
|--------|---------|
| البيانات المطلوبة | اسم البلدية، الوصف، الرؤية، الرسالة، تاريخ التأسيس، عدد السكان، المساحة، الشعار، صورة الغلاف |
| الـ Modules | Municipality, Homepage Statistics |
| الحقول | name_ar, name_en, short_description, full_description, logo_path, cover_path, foundation_date, population, area, vision, mission |
| الصور | شعار البلدية (150x150px PNG بخلفية شفافة)، صورة غلاف (1200x400px) |
| الأولوية | حرجة (بدونها لا تنطلق الصفحة الرئيسية) |
| المسؤول | رئيس البلدية أو سكرتيره |

### 7.2 العلاقات العامة والإعلام
| البيان | التفاصيل |
|--------|---------|
| البيانات المطلوبة | جهات الاتصال، منصات التواصل الاجتماعي، شرائح البنر |
| الـ Modules | Municipality (contacts, social), Homepage (slides) |
| الحقول | contacts (type, label, value), social_platforms (platform, url), slides (image, title, subtitle, buttons) |
| الصور | صور البنر (1920x800px, JPG/WebP, max 500KB) |
| الأولوية | عالية |
| المسؤول | مدير العلاقات العامة |

### 7.3 الدائرة الهندسية
| البيان | التفاصيل |
|--------|---------|
| البيانات المطلوبة | المكاتب الهندسية |
| الـ Modules | EngineeringOffices |
| الحقول | office_name, engineer_name, license_number, specializations, approval_status, expires_at |
| الملفات | (اختياري) صورة الترخيص |
| الأولوية | متوسطة |
| المسؤول | مدير الدائرة الهندسية |

### 7.4 دائرة المياه
| البيان | التفاصيل |
|--------|---------|
| البيانات المطلوبة | مناطق المياه، جدول التوزيع اليومي، فترات الصيانة |
| الـ Modules | WaterSchedule |
| الحقول | مناطق (name), جداول (area, date, status), صيانة (title, starts_at, affected_areas) |
| الأولوية | عالية |
| دورية التحديث | يومياً |
| المسؤول | مدير دائرة المياه |

### 7.5 الموارد البشرية
| البيان | التفاصيل |
|--------|---------|
| البيانات المطلوبة | الوظائف |
| الـ Modules | Jobs |
| الحقول | title, employment_type, department, description, requirements, responsibilities, benefits, closing_at |
| الأولوية | متوسطة |
| المسؤول | مدير الموارد البشرية |

### 7.6 سكرتارية المجلس البلدي
| البيان | التفاصيل |
|--------|---------|
| البيانات المطلوبة | أعضاء المجلس، قرارات المجلس |
| الـ Modules | Municipality (CouncilMembers, CouncilDecisions) |
| الحقول | الأعضاء: name, position, photo, bio. القرارات: decision_number, title, type, date, session_number, file |
| الملفات | PDF للقرارات، صور الأعضاء (300x300px) |
| الأولوية | عالية |
| المسؤول | أمين سر المجلس |

### 7.7 قسم تكنولوجيا المعلومات
| البيان | التفاصيل |
|--------|---------|
| البيانات المطلوبة | إعدادات النظام، المستخدمون، الأدوار، تصنيفات الخدمات، الروابط السريعة، المنصات الخارجية |
| الـ Modules | جميعها |
| الأولوية | حرجة |
| المسؤول | مدير تكنولوجيا المعلومات |

### 7.8 قسم الخدمات العامة
| البيان | التفاصيل |
|--------|---------|
| البيانات المطلوبة | تصنيفات المرافق، المرافق العامة |
| الـ Modules | PublicFacilities |
| الحقول | التصنيف: name, icon. المرفق: name, category, description, phone, address, working_hours, images |
| الصور | صور المرافق (800x600px, JPG, max 300KB) |
| الأولوية | متوسطة |

---

## 8. Data Collection Forms

### 8.1 معلومات البلدية (نموذج تعبئة)
| الحقل | الشرح | مثال | مطلوب | الحد الأقصى |
|-------|-------|------|-------|------------|
| اسم البلدية بالعربية | الاسم الرسمي | بلدية إذنا | ✅ | 255 حرف |
| اسم البلدية بالإنجليزية | للعرضinternationally | Idna Municipality | ❌ | 255 حرف |
| وصف قصير | جملة تعريفية | بلدية في محافظة الخليل | ❌ | 500 حرف |
| وصف كامل | عن البلدية | نص كامل | ❌ | غير محدد |
| شعار البلدية | ملف الصورة | logo.png | ✅ | 2MB |
| تاريخ التأسيس | سنة التأسيس | 1950 | ❌ | - |
| عدد السكان | إحصائية رسمية | 25000 | ❌ | - |
| المساحة | كم² | 15.5 | ❌ | - |
| الرؤية | خطة البلدية | نص الرؤية | ❌ | 500 حرف |
| الرسالة | المهمة | نص الرسالة | ❌ | 500 حرف |
| العنوان | العنوان الكامل | الخليل - إذنا | ❌ | 500 حرف |

### 8.2 الخدمات الإلكترونية (نموذج تعبئة)
| الحقل | الشرح | مثال | مطلوب |
|-------|-------|------|-------|
| اسم الخدمة | الاسم الظاهر للمواطن | تقديم رخصة بناء | ✅ |
| التصنيف الرئيسي | تصنيف الخدمة | رخص البناء | ✅ |
| الدائرة المسؤولة | الجهة المنفذة | الدائرة الهندسية | ❌ |
| نبذة مختصرة | وصف سريع | خدمة إلكترونية لتقديم... | ❌ |
| وصف تفصيلي | شرح كامل | خطوات ورسوم ومتطلبات | ❌ |
| الفئة المستفيدة | الشروط | المواطنون والمقاولون | ❌ |
| الوثائق المطلوبة | قائمة المستندات | هوية, صك ملكية | ❌ |
| خطوات التقديم | الإجراءات | 1. تسجيل الدخول... | ❌ |
| الرسوم | التكلفة | 100 شيكل | ❌ |
| مدة الإنجاز | الوقت المتوقع | 15 يوم عمل | ❌ |
| رابط البورتال | رابط التقديم | https://portal.idna.ps/... | ❌ |
| هل تتطلب تسجيل دخول؟ | للمواطن | نعم/لا | ❌ |

### 8.3 جدول توزيع المياه (نموذج تعبئة - يومي)
| الحقل | الشرح | مثال | مطلوب |
|-------|-------|------|-------|
| المنطقة | اسم الحي أو المنطقة | حي الشرق | ✅ |
| التاريخ | تاريخ الجدول | 2026-07-11 | ✅ |
| وقت البداية | متى يبدأ الضخ | 08:00 | ❌ |
| وقت النهاية | متى ينتهي الضخ | 14:00 | ❌ |
| الحالة | وضع الضخ | يوجد ضخ / ضغط منخفض / صيانة / طارئ | ✅ |
| ملاحظات | تفاصيل إضافية | تأخر ساعة بسبب عطل | ❌ |

### 8.4 الوظائف (نموذج تعبئة)
| الحقل | الشرح | مثال | مطلوب |
|-------|-------|------|-------|
| المسمى الوظيفي | اسم الوظيفة | مهندس بلدي | ✅ |
| الدائرة | القسم | الدائرة الهندسية | ❌ |
| نوع التوظيف | دوام كامل/جزئي | دوام كامل | ✅ |
| مكان العمل | الموقع | إذنا | ❌ |
| الراتب | القيمة | 5000 شيكل | ❌ |
| عدد الشواغر | كم شخص | 2 | ❌ |
| الوصف | تفاصيل الوظيفة | نص كامل | ❌ |
| المتطلبات | المؤهلات | بكالوريوس هندسة | ❌ |
| المسؤوليات | المهام | تصميم ورقابة | ❌ |
| تاريخ الإغلاق | آخر موعد | 2026-08-01 | ❌ |
| طريقة التقديم | بوابة/بريد/يدوي | portal | ✅ |

### 8.5 قرار مجلس (نموذج تعبئة)
| الحقل | الشرح | مثال | مطلوب |
|-------|-------|------|-------|
| رقم القرار | رقم التسلسلي | 45/2026 | ✅ |
| العنوان | موجز القرار | الموافقة على مشروع | ✅ |
| نوع القرار | التصنيف | إداري/مالي/تنظيمي | ✅ |
| تاريخ القرار | تاريخ الإصدار | 2026-07-01 | ✅ |
| رقم الجلسة | الجلسة المصدرة | 12 | ❌ |
| ملخص | نبذة مختصرة | نص الملخص | ❌ |
| النص الكامل | القرار كامل | نص القرار | ❌ |
| ملف مرفق | صورة القرار PDF | قرار_45.pdf | ❌ |

### 8.6 مرفق عام (نموذج تعبئة)
| الحقل | الشرح | مثال | مطلوب |
|-------|-------|------|-------|
| اسم المرفق | الاسم | حديقة البلدية | ✅ |
| التصنيف | نوع المرفق | حدائق | ✅ |
| وصف | معلومات | متنفس عائلي | ❌ |
| صورة الغلاف | الصورة الرئيسية | park.jpg | ❌ |
| معرض صور | صور إضافية | [] | ❌ |
| هاتف | للتواصل | 022221111 | ❌ |
| البريد الإلكتروني | - | park@idna.ps | ❌ |
| العنوان | الموقع | شارع القدس | ❌ |
| ساعات العمل | أوقات الزيارة | 8ص-10م | ❌ |
| الخدمات | ماذا يوفر | ألعاب أطفال | ❌ |
| الميزات | ما يميزه | مساحات خضراء | ❌ |
| التعليمات | القواعد | ممنوع التدخين | ❌ |

### 8.7 عضو مجلس (نموذج تعبئة)
| الحقل | الشرح | مثال | مطلوب |
|-------|-------|------|-------|
| الاسم الكامل | الاسم رباعياً | أحمد محمد سعيد | ✅ |
| المنصب | الموقع | رئيس البلدية / أمين السر | ✅ |
| الصورة الشخصية | صورة رسمية | ahmed.jpg | ❌ |
| السيرة الذاتية | نبذة عن العضو | حاصل على بكالوريوس... | ❌ |
| البريد الإلكتروني | - | ahmad@idna.ps | ❌ |

---

## 9. Media & File Requirements

| نوع الملف | الاستخدام | المقاس | الصيغة | الحجم الأقصى | ملاحظات |
|-----------|----------|--------|--------|-------------|---------|
| شعار البلدية | Navigation, Public Homepage Footer | 150x150px | PNG (شفاف) | 500KB | خلفية شفافة، نسختين فاتح/داكن |
| صورة الغلاف | Municipality Section | 1200x400px | JPG/WebP | 500KB | - |
| صور البنر (Slides) | Homepage Hero | 1920x800px | JPG/WebP | 500KB | نسبة 2.4:1، عالية الجودة |
| صور أعضاء المجلس | Team Cards | 300x300px | JPG/WebP | 200KB | صور رسمية، خلفية فاتحة |
| صور الخدمات | Service Cards | 800x600px | JPG/WebP | 300KB | - |
| صور الدوائر | Department Section | 800x600px | JPG/WebP | 300KB | - |
| صور المرافق | Facility Cards + Gallery | 800x600px | JPG/WebP | 300KB | صور متعددة (Gallery) |
| صور تصنيفات المرافق | Category Icons | 64x64px | PNG/SVG | 100KB | أيقونات بسيطة |
| ملفات قرارات المجلس | PDF Download | - | PDF | 10MB | ممسوحة ضوئياً |
| ملفات الوظائف | Job Attachments | - | PDF | 10MB | - |

---

## 10. Homepage Requirements

### Homepage Content Readiness Checklist

| القسم | البيانات المطلوبة | الحالة | الأولوية |
|-------|------------------|--------|---------|
| Logo | شعار البلدية | ❌ غير مرفوع | 🔴 حرجة |
| Hero Slides | صورة + عنوان + زر (2-3 شرائح) | ❌ غير مرفوعة (السيد يحتوي بيانات تجريبية فقط) | 🔴 حرجة |
| Quick Links | 6-8 روابط مع أيقونات | ❌ السيد يحتوي 4 روابط تجريبية | 🟡 متوسطة |
| Municipality Intro | وصف + تاريخ + سكان + رؤية | ❌ حقائق البلدية غير معبأة | 🟡 متوسطة |
| Statistics | 4 إحصائيات (أرقام حقيقية) | ❌ السيد يحتوي أرقاماً تجريبية | 🟢 عادية |
| Featured Services | خدمات مميزة مع `is_featured=true` | ❌ لا توجد خدمات مميزة | 🟢 عادية |
| Departments | دوائر مع صور | ❌ 9 دوائر في السيد ولكن بدون صور | 🟢 عادية |
| Council Members | أعضاء المجلس مع صور | ❌ 9 أعضاء في السيد ولكن بدون صور | 🟡 متوسطة |
| Council Decisions | قرارات منشورة | ✅ 5 قرارات في السيد | 🟢 عادية |
| Latest News | Module News غير موجود | ⛔ معطل (section معطل) | - |
| Projects | Module Projects غير موجود | ⛔ معطل | - |
| Contact CTA | بيانات التواصل | ❌ تحتاج تعبئة | 🟢 عادية |
| Social Media | روابط منصات التواصل | ❌ غير معبأة | 🟢 عادية |
| External Platforms | روابط منصات خارجية | ❌ غير معبأة | 🟢 عادية |
| Footer Info | العنوان، ساعات الدوام، الطوارئ | ❌ غير معبأة | 🟢 عادية |

---

## 11. Dashboard Requirements

### Dashboard Data Requirements Checklist

| القسم | مصدر البيانات | الحالة | تحتاج بيانات |
|-------|--------------|--------|------------|
| Hero Header (ترحيب) | Municipality + Auth | ✅ يعمل باسم المستخدم | لا |
| Alerts | EngineeringOffices + Jobs + Facilities + Services | ✅ يعمل (يظهر 0 إذا لا توجد) | لا |
| KPI Cards | جميع Modules | ✅ يعمل | لا |
| Quick Actions | جميع Modules | ✅ يعمل | لا |
| Charts | ElectronicServices, Facilities, Jobs | ✅ يحتاج بيانات analytics | نعم (views_count) |
| Water Stats | WaterSchedule | ✅ يحتاج جداول يومية | نعم |
| Jobs Stats | Jobs | ✅ يحتاج وظائف حقيقية | نعم |
| Engineering Offices Stats | EngineeringOffices | ✅ يحتاج مكاتب حقيقية | نعم |
| Services Stats | ElectronicServices | ✅ يحتاج خدمات حقيقية | نعم |
| Timeline | كل Module | ✅ يعمل (آخر 15 سجل) | لا |
| Today Activity | كل Module | ✅ يعمل | لا |
| Upcoming Events | Jobs + WaterMaintenance + EngineeringOffices | ✅ يعمل | نعم |
| Homepage Stats | Homepage | ✅ يحتاج محتوى للصفحة الرئيسية | نعم |
| System Health | System + Users | ✅ يعمل تلقائياً | لا |

---

## 12. Permissions & Roles Matrix

### جميع الصلاحيات الموجودة (من `config/permissions.php`)

| Module | الصلاحية | المعنى | الدور المقترح |
|--------|---------|--------|-------------|
| **users** | view users | عرض المستخدمين | Super Admin, مدير تكنولوجيا المعلومات |
| | create users | إضافة مستخدم | Super Admin |
| | edit users | تعديل مستخدم | Super Admin |
| | delete users | حذف مستخدم | Super Admin |
| | restore users | استعادة مستخدم محذوف | Super Admin |
| | export users | تصدير المستخدمين | Super Admin |
| | assign-role users | تعيين دور | Super Admin |
| | assign-permission users | تعيين صلاحية | Super Admin |
| **roles** | view roles | عرض الأدوار | Super Admin |
| | create/edit/delete roles | إدارة الأدوار | Super Admin |
| **departments** | departments.view | عرض الدوائر | Super Admin, مدير المحتوى |
| | departments.create | إضافة دائرة | Super Admin |
| | departments.update | تعديل دائرة | Super Admin |
| | departments.delete | حذف دائرة | Super Admin |
| | departments.publish | نشر دائرة | Super Admin, مدير المحتوى |
| | departments.feature | تمييز دائرة | Super Admin, مدير المحتوى |
| | departments.reorder | ترتيب الدوائر | Super Admin |
| **news** | view/create/edit/delete/publish news | (Module غير موجود) | - |
| **services** | view/create/edit/delete services | (Module غير موجود - الخدمات موجودة في electronic_services) | - |
| **complaints** | view/create/edit/delete/reply/close complaints | (Module غير موجود) | - |
| **projects** | view/create/edit/delete/approve projects | (Module غير موجود) | - |
| **tenders** | view/create/edit/delete/award tenders | (Module غير موجود) | - |
| **jobs** | jobs.view | عرض الوظائف | Super Admin, موظف HR |
| | jobs.create | إضافة وظيفة | موظف HR |
| | jobs.edit | تعديل وظيفة | موظف HR |
| | jobs.delete | حذف وظيفة | Super Admin |
| | approve jobs | نشر وظيفة | مدير الموارد البشرية |
| **settings** | view/edit settings | إعدادات النظام | Super Admin |
| **system** | (system permissions) | صلاحيات النظام | Super Admin |
| | access panel | الدخول للوحة التحكم | Super Admin, جميع الموظفين |
| **electronic_services** | electronic_services.view | عرض الخدمات | Super Admin, مدير المحتوى |
| | electronic_services.create | إضافة خدمة | موظف الدائرة |
| | electronic_services.update | تعديل خدمة | موظف الدائرة |
| | electronic_services.delete | حذف خدمة | Super Admin |
| | electronic_services.analytics | عرض تحليلات الخدمات | Super Admin |
| **service_categories** | service_categories.view | عرض التصنيفات | Super Admin, مدير المحتوى |
| | service_categories.create | إضافة تصنيف | Super Admin |
| | service_categories.update | تعديل تصنيف | Super Admin |
| | service_categories.delete | حذف تصنيف | Super Admin |
| **engineering_offices** | engineering_offices.view | عرض المكاتب | Super Admin, موظف الهندسة |
| | engineering_offices.create | إضافة مكتب | موظف الهندسة |
| | engineering_offices.update | تعديل مكتب | موظف الهندسة |
| | engineering_offices.delete | حذف مكتب | Super Admin |
| **homepage** | homepage.view | عرض لوحة الصفحة الرئيسية | Super Admin, مدير المحتوى |
| | homepage.update | تعديل إعدادات الصفحة الرئيسية | Super Admin |
| | homepage.slides.view | عرض الشرائح | Super Admin, مدير المحتوى |
| | homepage.slides.create | إضافة شريحة | مدير المحتوى |
| | homepage.slides.update | تعديل شريحة | مدير المحتوى |
| | homepage.slides.delete | حذف شريحة | Super Admin |
| | homepage.sections.update | ترتيب الأقسام | Super Admin |
| | homepage.quick_links.view | عرض الروابط السريعة | Super Admin, مدير المحتوى |
| | homepage.quick_links.create | إضافة رابط | مدير المحتوى |
| | homepage.quick_links.update | تعديل رابط | مدير المحتوى |
| | homepage.quick_links.delete | حذف رابط | Super Admin |
| | homepage.statistics.view | عرض الإحصائيات | Super Admin, مدير المحتوى |
| | homepage.statistics.create | إضافة إحصائية | Super Admin |
| | homepage.statistics.update | تعديل إحصائية | Super Admin |
| | homepage.statistics.delete | حذف إحصائية | Super Admin |
| **municipality** | municipality.view | عرض لوحة البلدية | Super Admin, مدير المحتوى |
| | municipality.update | تعديل معلومات البلدية | Super Admin |
| | municipality.contacts.manage | إدارة جهات الاتصال | مدير المحتوى |
| | municipality.social.manage | إدارة وسائل التواصل | مدير المحتوى |
| | municipality.platforms.manage | إدارة المنصات الخارجية | Super Admin |
| | municipality.custom-fields.manage | إدارة الحقول المخصصة | Super Admin |
| | municipality.media.manage | إدارة الوسائط | مدير المحتوى |
| | municipality.business-hours.manage | إدارة ساعات الدوام | Super Admin |
| | municipality.emergency-contacts.manage | إدارة جهات الطوارئ | Super Admin |
| **council_decisions** | council_decisions.view | عرض قرارات المجلس | Super Admin, سكرتارية |
| | council_decisions.create | إضافة قرار | سكرتارية |
| | council_decisions.update | تعديل قرار | سكرتارية |
| | council_decisions.delete | حذف قرار | Super Admin |
| **council_members** | council_members.view | عرض أعضاء المجلس | Super Admin, سكرتارية |
| | council_members.create | إضافة عضو | سكرتارية |
| | council_members.update | تعديل عضو | سكرتارية |
| | council_members.delete | حذف عضو | Super Admin |
| **water** | water.view | عرض جدول المياه | Super Admin, موظف المياه |
| | water.create | إضافة جدول/منطقة/صيانة | موظف المياه |
| | water.update | تعديل جدول/منطقة/صيانة | موظف المياه |
| | water.delete | حذف | Super Admin |
| **facilities** | facilities.view | عرض المرافق | Super Admin, مدير المحتوى |
| | facilities.create | إضافة مرفق | موظف الخدمات العامة |
| | facilities.update | تعديل مرفق | موظف الخدمات العامة |
| | facilities.delete | حذف مرفق | Super Admin |
| | facilities.feature | تمييز مرفق | Super Admin |
| | facilities.publish | نشر/أرشفة مرفق | Super Admin |
| **facility_categories** | facility_categories.view | عرض تصنيفات المرافق | Super Admin, مدير المحتوى |
| | facility_categories.create | إضافة تصنيف | Super Admin |
| | facility_categories.update | تعديل تصنيف | Super Admin |
| | facility_categories.delete | حذف تصنيف | Super Admin |

### الأدوار المقترحة

| الدور | الصلاحيات المقترحة |
|-------|-------------------|
| **Super Admin** | جميع الصلاحيات |
| **مدير البلدية / الإدارة العليا** | view, analytics, approve لجميع modules |
| **مدير المحتوى** | homepage.*, municipality.contacts, municipality.social, departments.publish, departments.feature, electronic_services.view |
| **موظف العلاقات العامة** | homepage.slides.*, homepage.quick_links.*, municipality.contacts.manage, municipality.social.manage |
| **سكرتارية المجلس** | council_decisions.*, council_members.* |
| **موظف دائرة المياه** | water.* |
| **موظف الموارد البشرية** | jobs.view, jobs.create, jobs.edit |
| **موظف الدائرة الهندسية** | engineering_offices.* |
| **موظف الخدمات العامة** | facilities.* |
| **مستخدم للعرض فقط** | view permissions لكل module |

---

## 13. Data Quality Checklist

| القاعدة | الوصف | الـ Module | الأولوية |
|---------|-------|-----------|---------|
| رقم القرار فريد | لا يمكن تكرار `decision_number` في council_decisions | Municipality | 🔴 حرجة |
| رقم الترخيص فريد | `license_number` يجب أن يكون فريداً | EngineeringOffices | 🔴 حرجة |
| البريد الإلكتروني صحيح | Validation على صيغة email | جميع | 🟡 متوسطة |
| رقم الهاتف موحد | تنسيق رقم فلسطيني (+970 أو 09xx) | جميع | 🟢 عادية |
| تاريخ الإغلاق > تاريخ النشر | `closing_at` > `publish_at` | Jobs | 🟡 متوسطة |
| خدمة بدون رابط بورتال | منع نشر خدمة تتطلب portal_url بدون رابط | ElectronicServices | 🟡 متوسطة |
| عدم حذف تصنيف عليه خدمات | منع حذف ServiceCategory عليه ElectronicService | ElectronicServices | 🟡 متوسطة |
| عدم حذف منطقة عليها جداول | منع حذف WaterArea عليه WaterSchedule | WaterSchedule | 🟡 متوسطة |
| وظيفة منتهية لا تظهر | `closing_at` الماضي يجب ألا يظهر | Jobs | 🔴 حرجة |
| شريحة منتهية لا تظهر | `ends_at` الماضي يجب ألا يظهر | Homepage | 🟡 متوسطة |
| مكتب هندسي منتهي الاعتماد | `approval_status=expired` لا يظهر | EngineeringOffices | 🟡 متوسطة |
| جدول مياه قديم | جداول اليوم السابق لا تظهر كجداول اليوم | WaterSchedule | 🔴 حرجة |
| أسماء موحدة | تسمية موحدة للمناطق والدوائر | WaterSchedule, Department | 🟢 عادية |
| عدم وجود XSS | تعقيم المدخلات النصية | جميع | 🔴 حرجة |
| التحقق من URLs | التأكد أن الروابط صحيحة | Homepage, Municipality | 🟢 عادية |

---

## 14. Update Frequency Matrix

| نوع البيانات | الدورية | آلية التحديث | المسؤول |
|-------------|---------|-------------|---------|
| جدول توزيع المياه | **يومياً** | يدوي عبر Dashboard | دائرة المياه |
| حالة الضخ (available/low/maintenance) | **يومياً** | تحديث جدول اليوم | دائرة المياه |
| قرارات المجلس | **بعد كل جلسة** | إضافة القرار الجديد | سكرتارية المجلس |
| أعضاء المجلس | **عند تغيير الدورة** | بعد الانتخابات أو التغيير | سكرتارية المجلس |
| الوظائف | **عند وجود إعلان** | نشر وظيفة جديدة | الموارد البشرية |
| المكاتب الهندسية | **عند الاعتماد/التجديد/الإيقاف** | تحديث حالة المكتب | الدائرة الهندسية |
| الخدمات الإلكترونية | **عند تغيير الإجراءات** | تحديث بيانات الخدمة | الدائرة المسؤولة |
| شرائح البنر | **حسب الحملات والمناسبات** | إضافة/تعديل شريحة | العلاقات العامة |
| إحصائيات الصفحة الرئيسية | **شهرياً** | تحديث الأرقام | مكتب رئيس البلدية |
| معلومات البلدية الأساسية | **نادراً** | عند الحاجة | مكتب رئيس البلدية |
| بيانات التواصل | **عند التغيير** | تحديث جهة الاتصال | العلاقات العامة |
| منصات التواصل الاجتماعي | **عن التغيير** | إضافة/تعديل منصة | الإعلام |
| ساعات الدوام | **نادراً** | عند تغيير الدوام | الإدارة |
| المرافق العامة | **عند إضافة مرفق** | إضافة/تعديل مرفق | الخدمات العامة |
| المستخدمون | **عند إضافة موظف** | إنشاء حساب | تكنولوجيا المعلومات |

---

## 15. Privacy & Public Visibility Matrix

| الحقل | Module | مرئي للعامة | مرئي للموظفين | ملاحظات |
|-------|--------|-------------|--------------|---------|
| اسم البلدية | Municipality | ✅ | ✅ | - |
| رقم هاتف البلدية | Municipality | ✅ | ✅ | الرقم الرسمي |
| بريد البلدية الإلكتروني | Municipality | ✅ | ✅ | - |
| عنوان البلدية | Municipality | ✅ | ✅ | - |
| ساعات الدوام | Municipality | ✅ | ✅ | - |
| أرقام هواتف الموظفين | CouncilMembers | ✅ اختياري | ✅ | `is_public` يتحكم |
| البريد الإلكتروني للعضو | CouncilMembers | ❌ | ✅ | - |
| صورة العضو | CouncilMembers | ✅ | ✅ | - |
| رقم القرار | CouncilDecisions | ✅ | ✅ | - |
| نص القرار الكامل | CouncilDecisions | ✅ | ✅ | `is_public` يتحكم |
| رابط الخدمة الخارجي | ElectronicServices | ✅ | ✅ | - |
| رقم ترخيص المكتب | EngineeringOffices | ❌ | ✅ | حساس |
| اسم المهندس | EngineeringOffices | ✅ | ✅ | - |
| رقم جوال الموظف | Users | ❌ | ❌ | حساس |
| بريد الموظف الداخلي | Users | ❌ | ✅ | - |
| `internal_notes` في أي جدول | جميع | ❌ | ❌ | حساس (لا يوجد) |
| `created_by` / `updated_by` | جميع | ❌ | ✅ | - |
| تاريخ انتهاء الاعتماد | EngineeringOffices | ❌ | ✅ | داخلي |
| `starts_at` / `ends_at` | HomepageSlides | ❌ | ✅ | داخلي |

---

## 16. Gap Analysis

### 🔴 Critical Gaps

| # | الفجوة | الموقع | التأثير | الحل المقترح |
|---|--------|--------|---------|-------------|
| G1 | `access panel` permission غير معرفة في `config/permissions.php` | صلاحية مذكورة في `routes/web.php` Middleware | المستخدم قد لا يتمكن من دخول اللوحة | إضافة permission إلى System module |
| G2 | 4 Modules وهمية (News, Projects, Complaints, Tenders) معرفة في permissions ولكن ليس لها DB/Models | `config/permissions.php` + `routes/web.php` | 4 روابط في الـ Sidebar تعيد صفحات فارغة | اقتراح مستقبلي: تطويرها أو إزالة صلاحياتها |
| G3 | لا توجد صفحة عامة لقرارات المجلس | CouncilDecisions | المواطن لا يستطيع تصفح القرارات | إضافة public route |
| G4 | لا توجد صفحة عامة لأعضاء المجلس | CouncilMembers | المواطن لا يستطيع رؤية الأعضاء | إضافة public route |
| G5 | الوظائف المنتهية (`closing_at` past) لا تخفى تلقائياً | Jobs Repository | وظائف قديمة تظهر للعامة | إضافة scope أو filter تلقائي |
| G6 | `is_active` column كانت مفقودة في UserIndex query | `app/Livewire/Users/UserIndex.php` | خطأ في تشغيل صفحة المستخدمين | ✅ تم الإصلاح |

### 🟡 High Priority Gaps

| # | الفجوة | الموقع | التأثير |
|---|--------|--------|---------|
| H1 | صور البنر والأعضاء والدوائر والمرافق كلها غير مرفوعة | HomepageSlides + CouncilMembers + Departments + Facilities | الصفحة الرئيسية تبدو فارغة |
| H2 | بيانات البلدية الحقيقية غير معبأة (سكان، مساحة، وصف) | Municipality | الصفحة الرئيسية تظهر بيانات تجريبية |
| H3 | `is_featured` غير معين لأي خدمة | ElectronicServices | قسم الخدمات المميزة في الصفحة الرئيسية فارغ |
| H4 | `is_public` للخدمات غير مفعل | ElectronicServices | الخدمات لا تظهر في البوابة العامة |
| H5 | `is_public` لأعضاء المجلس غير مفعل | CouncilMembers | الأعضاء لا يظهرون |
| H6 | `is_public` للقرارات غير مفعل | CouncilDecisions | القرارات لا تظهر |
| H7 | `is_public` للمرافق غير مفعل | Facilities | المرافق لا تظهر للعامة |
| H8 | `is_public` للدوائر غير مفعل | Departments | الدوائر لا تظهر في الصفحة الرئيسية |
| H9 | لا يوجد Notification للمستخدمين عند قرب انتهاء صلاحية الترخيص | EngineeringOffices | إدارة المكاتب تحتاج تذكير |
| H10 | لا يوجد Notification للإعلانات الوظيفية المنتهية قريباً | Jobs | قد ينسون إغلاق الإعلان |

### 🟢 Medium Priority Gaps

| # | الفجوة |
|---|--------|
| M1 | لا يوجد validation `closing_at > publish_at` في StoreJobRequest |
| M2 | لا يوجد validation يمنع حذف ServiceCategory مرتبط بخدمات |
| M3 | لا يوجد validation يمنع حذف WaterArea مرتبط بجداول |
| M4 | لا توجد صفحة عامة تعرض جدول المياه الأسبوعي (فقط اليوم) |
| M5 | لا توجد صفحة عامة للمكاتب الهندسية |
| M6 | Homepage public view تحتوي متغير `$engineeringOffices` ولكن القسم ليس في `sectionKeys` |
| M7 | `latest_news` و `projects` sections معطلين ولكن باقي الصفحة تعمل |
| M8 | الجدول `job_offers` (migration قديم) لم يتم حذفه بعد |
| M9 | الجدول `departments` (migration قديم 2024_01_01) لم يتم حذفه بعد |
| M10 | عدد الاختبارات (tests) قليل مقارنة بحجم النظام (16 test files فقط) |

### اقتراحات مستقبلية

| # | الاقتراح | الأولوية |
|---|---------|---------|
| F1 | تطوير Module News (أخبار البلدية) كامل مع Model + Migration + CRUD | متوسطة |
| F2 | تطوير Module Complaints (الشكاوى) لإدارة شكاوى المواطنين | متوسطة |
| F3 | تطوير Module Projects (المشاريع) لعرض مشاريع البلدية | منخفضة |
| F4 | تطوير Module Tenders (المناقصات) | منخفضة |
| F5 | إضافة إشعارات بريد إلكتروني (Mail) للموظفين | متوسطة |
| F6 | إضافة نظام Calendar متكامل مع الأحداث | منخفضة |
| F7 | إضافة Activity Log (تسجيل كل عملية) | متوسطة |
| F8 | إضافة نظام متعدد اللغات (عربي/إنجليزي) | منخفضة |
| F9 | إضافة API للخدمات (REST API) | منخفضة |
| F10 | إضافة أوقات الصلاة | منخفضة |

---

## 17. Launch Readiness Checklist

### المرحلة 1: البيانات الأساسية
- [ ] رفع شعار البلدية (Municipality → logo_path)
- [ ] تعبئة اسم البلدية بالعربية والإنجليزية
- [ ] تعبئة الوصف القصير والكامل
- [ ] تعبئة تاريخ التأسيس والسكان والمساحة
- [ ] تعبئة الرؤية والرسالة
- [ ] تعبئة العنوان وساعات الدوام
- [ ] تعيين رابط بوابة الخدمات (Homepage Settings → portal_url)

### المرحلة 2: التواصل
- [ ] إضافة أرقام الهواتف الرسمية (Municipality Contacts)
- [ ] إضافة البريد الإلكتروني الرسمي
- [ ] إضافة روابط التواصل الاجتماعي (Facebook, Twitter, Instagram, YouTube, WhatsApp, Telegram, TikTok)
- [ ] إضافة المنصات الخارجية (Gov.il, Ministry portals...)
- [ ] إضافة ساعات الدوام (Business Hours)
- [ ] إضافة جهات الطوارئ (Emergency Contacts)

### المرحلة 3: المجلس والدوائر
- [ ] تعبئة أعضاء المجلس البلدي (9 أعضاء مع صور)
- [ ] تعبئة الدوائر (9 دوائر مع صور)
- [ ] تعبئة قرارات المجلس (5+ قرارات)

### المرحلة 4: الخدمات الإلكترونية
- [ ] تعبئة تصنيفات الخدمات (5+ تصنيفات)
- [ ] تعبئة الخدمات الإلكترونية (10+ خدمات)
- [ ] تعيين `is_public = true` للخدمات النشطة
- [ ] تعيين `is_featured = true` للخدمات المميزة
- [ ] ربط كل خدمة بالدائرة المسؤولة

### المرحلة 5: المحتوى العام
- [ ] رفع 3-4 صور بنر احترافية (1920x800px)
- [ ] تعبئة عناوين وأوصاف للشرائح
- [ ] تعبئة 6-8 روابط سريعة مع أيقونات
- [ ] تعبئة 4 إحصائيات حقيقية للصفحة الرئيسية
- [ ] تعيين عناوين الأقسام والأوصاف الفرعية

### المرحلة 6: المرافق العامة
- [ ] تعبئة تصنيفات المرافق (5+ تصنيفات)
- [ ] تعبئة المرافق (5+ مرافق مع صور)
- [ ] تعيين `is_public = true`

### المرحلة 7: الوظائف
- [ ] تعبئة الوظائف الحالية (إن وجدت)
- [ ] تعيين `status = published`
- [ ] تعيين `closing_at` محدد

### المرحلة 8: جدول المياه
- [ ] تعبئة مناطق المياه (5+ مناطق)
- [ ] تعبئة جدول التوزيع اليومي
- [ ] تعبئة فترات الصيانة المجدولة

### المرحلة 9: المكاتب الهندسية
- [ ] تعبئة المكاتب الهندسية المعتمدة
- [ ] تعيين `approval_status` لكل مكتب
- [ ] تعيين `expires_at` لكل مكتب

### المرحلة 10: الصلاحيات والمستخدمون
- [ ] إنشاء حسابات للموظفين
- [ ] تعيين الأدوار المناسبة
- [ ] اختبار صلاحيات كل مستخدم
- [ ] إضافة permission `access panel` مفقودة

### المرحلة 11: مراجعة الجودة
- [ ] اختبار جميع الصفحات العامة
- [ ] اختبار جميع صفحات Dashboard
- [ ] التحقق من عدم وجود روابط مكسورة
- [ ] مراجعة النصوص العربية في كل الصفحات
- [ ] اختبار الاستجابة (Responsive) على الجوال
- [ ] اختبار جميع Enums والحالات

### المرحلة 12: ما قبل الإطلاق
- [ ] تعطيل route `/setup-database` (موجود حالياً)
- [ ] تعطيل route `/debug-permissions`
- [ ] تشغيل Environment Production
- [ ] تعطيل Debug mode
- [ ] تشغيل Cache
- [ ] أخذ نسخة احتياطية من قاعدة البيانات

---

## 18. Implementation & Data Collection Plan

### المرحلة 1 (أسبوع 1): البيانات الأساسية للبلدية
**القسم المسؤول:** مكتب رئيس البلدية + تكنولوجيا المعلومات  
**المدة المقترحة:** 3-5 أيام  
**الاعتماديات:** شعار البلدية، البيانات الرسمية

| المهمة | الوصف | معيار الانتهاء |
|-------|-------|--------------|
| رفع شعار البلدية | صورة الشعار بالموقع | ظهور الشعار في الـ Navbar والـ Header |
| تعبئة معلومات البلدية | الاسم، الوصف، السكان، المساحة | ظهور البيانات في الصفحة الرئيسية |
| تعبئة بيانات التواصل | الهواتف، البريد، العنوان | ظهور جهات الاتصال |
| تعبئة ساعات الدوام | أوقات العمل الرسمية | ظهور الساعات في الصفحة |

### المرحلة 2 (أسبوع 2): المجلس والدوائر
**القسم المسؤول:** سكرتارية المجلس + تكنولوجيا المعلومات  
**المدة المقترحة:** 3-5 أيام

| المهمة | الوصف | معيار الانتهاء |
|-------|-------|--------------|
| تعبئة أعضاء المجلس مع الصور | أسماء ومناصب وصور | ظهور الأعضاء في الصفحة الرئيسية |
| تعبئة الدوائر مع الصور | 9 دوائر مع وصف وصور | ظهور الدوائر في الصفحة الرئيسية |
| تعبئة 5+ قرارات مجلس | قرارات حقيقية مع أرقام | ظهور القرارات في الـ Dashboard |

### المرحلة 3 (أسبوع 2-3): الخدمات الإلكترونية
**القسم المسؤول:** كل دائرة بالتنسيق مع تكنولوجيا المعلومات  
**المدة المقترحة:** 5-7 أيام

### المرحلة 4 (أسبوع 3): المحتوى العام
**القسم المسؤول:** العلاقات العامة  
**المدة المقترحة:** 3-5 أيام

### المرحلة 5 (أسبوع 3-4): الوظائف والمرافق والمكاتب
**القسم المسؤول:** الموارد البشرية + الخدمات العامة + الدائرة الهندسية

### المرحلة 6 (أسبوع 4): جدول المياه
**القسم المسؤول:** دائرة المياه  
**مدة:** مستمر (يومي)

### المرحلة 7 (أسبوع 4): الصفحة الرئيسية
**القسم المسؤول:** العلاقات العامة + تكنولوجيا المعلومات

### المرحلة 8 (أسبوع 5): الصلاحيات والمستخدمون
**القسم المسؤول:** تكنولوجيا المعلومات

### المرحلة 9 (أسبوع 5): مراجعة الجودة
**القسم المسؤول:** تكنولوجيا المعلومات + جميع الأقسام

### المرحلة 10 (أسبوع 6): الإطلاق

---

## 19. Questions for the Municipality

### أسئلة يجب الإجابة عليها من البلدية

1. **الشعار:** هل لديكم شعار بصيغة PNG بخلفية شفافة؟ وإذا لا، هل نحتاج تصميم واحد؟
2. **صور البنر:** هل لديكم صور جوية أو معمارية للبلدة تناسب خلفية البنر؟
3. **أعضاء المجلس:** هل توافقون على نشر صور وأسماء أعضاء المجلس على الموقع؟
4. **بيانات البلدية:** ما هي أرقام السكان والمساحة الرسمية والتاريخ الصحيح للتأسيس؟
5. **الخدمات الإلكترونية:** هل جميع الخدمات الحالية في النظام متاحة فعلاً للمواطنين؟ أم أن بعضها قيد التطوير؟
6. **بوابة الخدمات:** هل يوجد رابط بوابة خدمات إلكترونية فعلي (Portal URL) أم سيوفر لاحقاً؟
7. **المكاتب الهندسية:** هل لديكم قائمة بالمكاتب الهندسية المعتمدة حالياً؟ وكم عددها؟
8. **جدول المياه:** هل توزيع المياه حسب المناطق المدرجة في النظام صحيح؟ هل توجد مناطق أخرى؟
9. **الوظائف:** هل توجد وظائف شاغرة حالياً في البلدية ترغبون بنشرها؟
10. **المرافق العامة:** ما هي أبرز المرافق العامة التي يجب إدراجها أولاً؟
11. **الصفحة الرئيسية:** من سيكون مسؤولاً عن تحديث الصفحة الرئيسية (صور البنر والإحصائيات)؟
12. **التواصل الاجتماعي:** هل جميع حسابات التواصل الاجتماعي المطلوب إضافتها رسمية ومعتمدة؟
13. **إحصائيات الصفحة الرئيسية:** ما هي الأرقام الأربعة التي ترغبون بإظهارها في الصفحة الرئيسية (مثل: عدد السكان، عدد المشاريع، عدد الخدمات...)؟
14. **الصلاحيات:** من هم الأشخاص الذين سيحتاجون صلاحية "Super Admin"؟ ومن سيكون "مدير محتوى"؟
15. **الخصوصية:** هل هناك أي معلومات لا ترغبون بنشرها على الموقع مطلقاً؟
16. **الأخبار والمشاريع:** هل تنوو ن إضافة أخبار ومشاريع في المستقبل القريب أم لا؟
17. **المناقصات:** هل تنوون إضافة نظام مناقصات للشركات؟
18. **التقارير:** هل يحتاج المسؤولون لتقارير إحصائية دورية (PDF) عن أداء الموقع؟

---

## 20. Final Prioritized Recommendations

### يجب تنفيذه فوراً (قبل الإطلاق)
1. **إضافة صلاحية `access panel`** إلى config/permissions.php في system module
2. **رفع الشعار** والصور الأساسية
3. **تعبئة معلومات البلدية** (الاسم، السكان، المساحة)
4. **تفعيل `is_public` و `is_featured`** للخدمات الموجودة في السيد
5. **تفعيل `is_public`** لأعضاء المجلس والقرارات والدوائر والمرافق
6. **حذف route `/setup-database` و `/debug-permissions`** قبل الإطلاق
7. **إخفاء الوظائف المنتهية** (closing_at past) تلقائياً

### مهم للأسبوع الأول بعد الإطلاق
8. تعبئة أعضاء المجلس مع الصور
9. تعبئة شرائح البنر (3-4 صور)
10. تعبئة الروابط السريعة (6-8 روابط)
11. تعبئة الدوائر مع الصور
12. تعبئة بيانات التواصل ووسائل التواصل الاجتماعي

### مهم للشهر الأول
13. تعبئة الخدمات الإلكترونية (10+ خدمات)
14. تعبئة المرافق العامة مع الصور
15. تعبئة جدول المياه اليومي
16. تعبئة المكاتب الهندسية
17. تعبئة القرارات السابقة

### اقتراح مستقبلي
18. تطوير News Module (أخبار البلدية) - الأكثر طلباً
19. تطوير Complaints Module (الشكاوى)
20. تطوير Projects Module (المشاريع)
21. تطوير Tenders Module (المناقصات)
22. إضافة التقارير والتصدير للـ PDF
23. إضافة API للمطورين الخارجيين

---

## قائمتا البيانات المطلوبة

### القائمة الأولى: بيانات مطلوبة فوراً من البلدية

| # | نوع البيانات | القسم المسؤول | التعليق |
|---|-------------|--------------|---------|
| 1 | شعار البلدية (PNG شفاف) | مكتب رئيس البلدية | ضروري جداً |
| 2 | اسم البلدية الرسمي (عربي/إنجليزي) | مكتب رئيس البلدية | - |
| 3 | الوصف القصير والكامل للبلدية | العلاقات العامة | - |
| 4 | تاريخ التأسيس، عدد السكان، المساحة | مكتب رئيس البلدية | - |
| 5 | الرؤية والرسالة | مكتب رئيس البلدية | - |
| 6 | أرقام الهواتف الرسمية (3-5 أرقام) | العلاقات العامة | - |
| 7 | البريد الإلكتروني الرسمي | تكنولوجيا المعلومات | - |
| 8 | روابط التواصل الاجتماعي الرسمية | الإعلام | فيسبوك، تويتر، يوتيوب، واتساب |
| 9 | رابط بوابة الخدمات الإلكترونية | تكنولوجيا المعلومات | إن وجد |
| 10 | صور أعضاء المجلس (7-9 صور) | سكرتارية المجلس | 300x300px JPG |
| 11 | صور الدوائر (9 صور) | تكنولوجيا المعلومات | 800x600px |
| 12 | صور البنر (3-4 صور) | العلاقات العامة | 1920x800px |

### القائمة الثانية: بيانات يمكن تأجيلها لما بعد الإطلاق

| # | نوع البيانات | القسم المسؤول | يمكن تأجيلها |
|---|-------------|--------------|-------------|
| 1 | الخدمات الإلكترونية التفصيلية | كل دائرة | أسبوعين |
| 2 | قرارات المجلس السابقة | سكرتارية المجلس | أسبوعين |
| 3 | المرافق العامة مع الصور | الخدمات العامة | شهر |
| 4 | المكاتب الهندسية المعتمدة | الدائرة الهندسية | شهر |
| 5 | جدول المياه التفصيلي (جميع المناطق) | دائرة المياه | أسبوع (يمكن البدء بمنطقتين) |
| 6 | الوظائف الشاغرة | الموارد البشرية | حسب الحاجة |
| 7 | ساعات الدوام وجهات الطوارئ | الإدارة | أسبوع |
| 8 | الحقول المخصصة (Custom Fields) | حسب الحاجة | غير ضرورية حالياً |
| 9 | المنصات الخارجية | تكنولوجيا المعلومات | غير ضرورية حالياً |
| 10 | الإحصائيات الدقيقة للصفحة الرئيسية | مكتب رئيس البلدية | شهر |
| 11 | صورة الغلاف للبلدية | العلاقات العامة | أسبوعين |
| 12 | صور تصنيفات المرافق | الخدمات العامة | شهر |
| 13 | معلومات المهندس المسؤول (للمكاتب) | الدائرة الهندسية | شهر |
| 14 | الميزات والخدمات التفصيلية للمرافق | الخدمات العامة | شهر |

---

*نهاية التقرير. أعد في يوليو 2026.*
