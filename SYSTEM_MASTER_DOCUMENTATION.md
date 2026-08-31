# SYSTEM MASTER DOCUMENTATION

**Project:** بلدية إذنا — Idhna Municipality Digital Portal  
**Repository:** idna-org  
**Version:** 1.0  
**Date of Analysis:** 2026-08-31  
**Stack:** Laravel 13.17+ | PHP 8.3+ | Livewire 4.1+ | Tailwind CSS 4 | Vite 8  
**Architecture:** Domain-Driven Design (DDD) with 23 domains  
**Database:** SQLite (default) | 64 tables | 50+ models  
**Livewire Components:** 97 PHP files  
**Blade Views:** 120+ templates  
**Routes:** 140+ routes (web.php only, no API routes)  
**Seeders:** 20 seeders  
**Tests:** 99 test files (51 Feature + 48 Unit)  
**Total Major Features:** 18 (Homepage, Services, News, Projects, Complaints, Tenders, Jobs, Announcements, Facilities, Departments, Engineering Offices, Council, Water Schedule, Open Data, Chatbot, CMS, Carousels, Dynamic Forms)

---

# SYSTEM MAP

```
                         ┌──────────────────────────┐
                         │         CITIZEN          │
                         │    (Web Browser / Mobile) │
                         └────────────┬─────────────┘
                                      │
                         ┌────────────▼─────────────┐
                         │      PUBLIC WEBSITE       │
                         │   (Livewire Components)   │
                         └────────────┬─────────────┘
                                      │
          ┌───────────────────────────┼───────────────────────────┐
          │              │             │             │             │
     ┌────▼────┐   ┌─────▼─────┐ ┌────▼────┐ ┌─────▼────┐ ┌─────▼─────┐
     │Homepage │   │ Services  │ │  News   │ │Projects  │ │  Chatbot  │
     │Sections │   │ Portal    │ │ Portal  │ │ Portal   │ │   Widget  │
     │Slides   │   │ Categories│ │ Search  │ │ Search   │ │   + Page  │
     │Stats    │   │ Details   │ │ Detail  │ │ Detail   │ │           │
     │QuickLks │   │           │ │         │ │          │ │           │
     └────┬────┘   └─────┬─────┘ └────┬────┘ └─────┬────┘ └─────┬─────┘
          │              │             │             │             │
          └──────────────┴─────────────┴─────────────┴─────────────┘
                                      │
                         ┌────────────▼─────────────┐
                         │    DOMAIN SERVICES        │
                         │   (23 DDD Domains)        │
                         │   Actions + Repositories  │
                         └────────────┬─────────────┘
                                      │
                         ┌────────────▼─────────────┐
                         │     ELOQUENT MODELS       │
                         │    (50+ Models)           │
                         └────────────┬─────────────┘
                                      │
                         ┌────────────▼─────────────┐
                         │      DATABASE             │
                         │   (64 Tables - SQLite)    │
                         └────────────┬─────────────┘
                                      │
                         ┌────────────▼─────────────┐
                         │      CACHE (Database)     │
                         │  (Per-domain TTL cache)   │
                         └──────────────────────────┘
```

```
                         ┌──────────────────────────┐
                         │    ADMIN DASHBOARD        │
                         │  (Livewire Components)    │
                         └────────────┬─────────────┘
                                      │
     ┌────────────────────────────────┼────────────────────────────────┐
     │             │                  │                  │             │
┌────▼────┐  ┌─────▼─────┐  ┌────────▼──────┐  ┌───────▼──────┐  ┌───▼────┐
│Homepage │  │  Content  │  │  Municipality │  │   Chatbot    │  │  Users │
│ Manager │  │   CRUD    │  │   Profile     │  │  Dashboard   │  │ Roles  │
│Settings │  │News/Proj/ │  │   Contacts    │  │  Analytics   │  │Perms   │
│Slides   │  │Services/  │  │   Social      │  │  Unknown Q's │  │        │
│Sections │  │Tenders/   │  │   Media       │  │  Perf Monitor│  │        │
│Stats    │  │Jobs/etc   │  │   Council     │  │  Search Terms│  │        │
└─────────┘  └───────────┘  └───────────────┘  └──────────────┘  └────────┘
```

---

## Project Statistics

| Metric | Value |
|--------|-------|
| Total Domains | 23 |
| Total Routes | 140+ |
| Total Models | 50+ |
| Total Tables | 64 |
| Total Livewire Components | 97 PHP files |
| Total Blade Views | 120+ templates |
| Total Controllers | 0 (Livewire-only, no traditional controllers) |
| Total Services | 30+ |
| Total Repositories | 25+ |
| Total Actions | 130+ |
| Total Migrations | 73 |
| Total Tests | 99 (51 Feature + 48 Unit) |
| Total Seeders | 20 |
| Total Permissions | 170+ |
| Total Roles | 5 (Super Admin, Admin, Department Manager, Employee, Citizen) |

---

# 1. WHAT IS THIS SYSTEM?

This is the **official digital portal for Idhna Municipality (بلدية إذنا)**, a municipality in the Hebron Governorate of the West Bank, Palestine. The system provides:

1. **Public Website** — A citizen-facing portal with Arabic-first RTL design showing services, news, projects, complaints, tenders, jobs, announcements, facilities, departments, council information, water schedules, open data, and an AI-powered chatbot.

2. **Admin Dashboard** — A comprehensive CMS for municipality staff to manage all content, users, roles, permissions, and system settings.

3. **AI Chatbot** — An Arabic NLP-powered chatbot that helps citizens find services, submit complaints, check water schedules, and more — using rule-based + optional ML intent classification.

4. **Electronic Services Gateway** — Links citizens to the Palestinian e-government portal (PaleXPand) for actual service applications.

---

# 2. TECHNOLOGY STACK

## 2.1 Backend

| Technology | Version | Purpose | Key Files |
|-----------|---------|---------|-----------|
| **PHP** | ^8.3 | Runtime | `composer.json` |
| **Laravel** | ^13.17 | MVC Framework | `composer.json`, `bootstrap/app.php` |
| **Livewire** | ^4.1 | Reactive UI Components | `composer.json`, all `app/Livewire/` |
| **Livewire Blaze** | ^1.0 | Livewire styling | `composer.json` |
| **Spatie Permission** | ^8.3 | Roles & Permissions | `config/permission.php`, `config/permissions.php` |
| **Pest** | ^4.7 | Testing Framework | `tests/Pest.php` |
| **Laravel Pint** | ^1.27 | Code Style (PSR-12) | `pint.json` |
| **Larastan** | ^3.9 | Static Analysis (PHPStan L7) | `phpstan.neon` |
| **Faker** | ^1.24 | Test Data Generation | `composer.json` |

## 2.2 Frontend

| Technology | Version | Purpose | Key Files |
|-----------|---------|---------|-----------|
| **Tailwind CSS** | ^4.0.7 | Utility-First CSS | `resources/css/app.css` |
| **Vite** | ^8.0.0 | Build Tool | `vite.config.js` |
| **@tailwindcss/vite** | ^4.1.11 | Tailwind Vite Plugin | `vite.config.js` |
| **Alpine.js** | (via Livewire) | Minimal JS Interactivity | Livewire components |
| **Custom SVG Icons** | 200+ icons | Icon System | `resources/js/icons.js` |
| **Google Fonts (Alexandria)** | Arabic | Typography | `resources/css/app.css` |

## 2.3 Database & Infrastructure

| Technology | Purpose | Configuration |
|-----------|---------|--------------|
| **SQLite** | Default Database | `config/database.php`, `.env.example` |
| **Database Driver** | Cache, Session, Queue | All use `database` driver by default |
| **File Storage** | Local (`storage/app/public`) | `config/filesystems.php` |

## 2.4 External Integrations

| Integration | Purpose | Configuration |
|------------|---------|--------------|
| **PaleXPand Portal** | Electronic services gateway | `portal_url` field in `electronic_services` |
| **Google Fonts** | Alexandria font family | `resources/css/app.css` |

**No external AI/ML APIs are used.** The chatbot is entirely self-contained with rule-based + optional PHP-ML (Naive Bayes) classification. No OpenAI, no external NLP services.

---

# 3. ARCHITECTURE

## 3.1 Domain-Driven Design (DDD)

The project follows strict DDD with **23 bounded contexts (domains)**:

```
app/
├── Domains/                    # 23 Business Domains
│   ├── Announcements/          # Announcements CRUD
│   ├── Authentication/         # Login, Users, Sessions
│   ├── Chatbot/               # AI Chatbot (57 handlers)
│   ├── ChatbotAnalytics/      # Chatbot performance tracking
│   ├── CitizenWorkflows/      # Multi-step citizen forms
│   ├── Complaints/            # Citizen complaints
│   ├── ContactRequests/       # Contact form submissions
│   ├── Dashboard/             # Executive dashboard
│   ├── Department/            # Municipal departments
│   ├── ElectronicServices/    # E-services catalog
│   ├── EngineeringOffices/    # Licensed engineering offices
│   ├── Homepage/              # CMS for homepage
│   ├── Jobs/                  # Job listings
│   ├── Municipality/          # Municipality profile & council
│   ├── News/                  # News articles
│   ├── OpenData/              # Open datasets
│   ├── Projects/              # Municipal projects
│   ├── PublicFacilities/      # Public facilities
│   ├── RoleManagement/        # Roles & permissions
│   ├── SharedKernel/          # Shared models (Media, BusinessHours, EmergencyContact)
│   ├── Tenders/               # Procurement tenders
│   ├── UserManagement/        # User CRUD
│   └── WaterSchedule/         # Water distribution schedule
├── Livewire/                  # 97 Livewire components
├── Models/                    # User model (bridge)
├── Providers/                 # AppServiceProvider (registers all 23 domain providers)
├── Shared/                    # Contracts, Helpers, Traits, Exceptions
├── Http/Middleware/            # CheckPermission middleware
├── View/                      # Blade components & composers
└── Console/Commands/          # (Empty - no custom commands)
```

## 3.2 Domain Structure (Per Domain)

Each domain follows this consistent structure:

```
DomainName/
├── Actions/           # Single-action business logic classes (final, singleton)
├── Contracts/         # Interfaces (repositories, services)
├── DTOs/              # Data Transfer Objects
├── Enums/             # PHP 8.1+ Backed Enums
├── Models/            # Eloquent models (final, soft deletes)
├── Repositories/      # Eloquent implementations (readonly)
├── Services/          # Domain business logic
└── Providers/         # Domain ServiceProvider (binds interfaces)
```

## 3.3 Request Lifecycle

```
HTTP Request
    ↓
Route (routes/web.php)
    ↓
Middleware (auth, permission:xxx)
    ↓
Livewire Component (app/Livewire/)
    ↓
Domain Action (app/Domains/*/Actions/)
    ↓
Domain Service (app/Domains/*/Services/)
    ↓
Domain Repository (app/Domains/*/Repositories/)
    ↓
Eloquent Model (app/Domains/*/Models/)
    ↓
Database Query
    ↓
Response (Livewire View / Redirect)
```

## 3.4 Key Architecture Patterns

1. **Final Classes**: All models, repositories, services, actions are `final`
2. **Strict Typing**: `declare(strict_types=1)` in every PHP file
3. **Readonly Repositories**: All repositories use `readonly` class keyword
4. **Constructor Injection**: Consistent DI across all classes
5. **Interface Binding**: All repositories bound to interfaces in ServiceProviders
6. **Singleton Actions**: All Actions registered as singletons in ServiceProviders
7. **DTO Pattern**: Structured data transfer between layers
8. **Enum Casting**: PHP 8.1+ Backed Enums for all status/type fields
9. **Soft Deletes**: Used on most models (~30 tables)
10. **DB Transactions**: All repository create/update methods wrapped in `DB::transaction()`
11. **No Traditional Controllers**: Everything is Livewire-based (zero controllers)

---

# 4. DOMAINS

## 4.1 Domain Summary

| Domain | Responsibility | Models | Tables | Livewire | Actions |
|--------|---------------|--------|--------|----------|---------|
| **Announcements** | Official announcements | Announcement | announcements | 2 | 8 |
| **Authentication** | Login, sessions, 2FA | User, LoginActivity | users, login_activities | 4 | 5 |
| **Chatbot** | AI chatbot (57 handlers) | ChatIntent, ChatTrainingExample, ChatbotServiceAlias, ChatbotConversation, ChatbotMessage, ChatbotFeedback, ChatbotModelVersion | 7 tables | 3 | 1 |
| **ChatbotAnalytics** | Chatbot metrics | ChatbotIntentAnalytics, ChatbotUnknownQuestion, ChatbotSearchAnalytics, ChatbotWorkflowAnalytics, ChatbotPerformanceLog, ChatbotDatasetVersion | 6 tables | 4 | 0 |
| **CitizenWorkflows** | Multi-step citizen forms | WorkflowDraft | workflow_drafts | 0 | 7 Services |
| **Complaints** | Citizen complaints | Complaint | complaints | 4 | 7 |
| **ContactRequests** | Contact form | ContactRequest | contact_requests | 0 | 2 |
| **Dashboard** | Executive dashboard | — | — | 1 | 0 |
| **Department** | Municipal departments | Department | departments | 5 | 6 |
| **ElectronicServices** | E-services catalog | ElectronicService, ServiceCategory, ServiceView, ServicePortalClick, ServiceSearchTerm | 5 tables | 10 | 12 |
| **EngineeringOffices** | Licensed offices | EngineeringOffice | engineering_offices | 5 | 7 |
| **Homepage** | CMS for homepage | HomepageSetting, HomepageSlide, HomepageSection, HomepageQuickLink, HomepageStatistic, CarouselConfiguration | 6 tables | 9 | 20 |
| **Jobs** | Job listings | Job | job_offers | 4 | 7 |
| **Municipality** | Municipality profile, council | Municipality, CouncilMember, CouncilDecision, MunicipalityContact, MunicipalitySocialPlatform, MunicipalityExternalPlatform, MunicipalityCustomField | 7 tables | 16 | 18 |
| **News** | News articles | NewsItem | news_items | 4 | 6 |
| **OpenData** | Open datasets | OpenDataset | open_datasets | 4 | 3 |
| **Projects** | Municipal projects | Project | projects | 4 | 6 |
| **PublicFacilities** | Public facilities | Facility, FacilityCategory | 2 tables | 6 | 10 |
| **RoleManagement** | Roles & permissions | — | roles, permissions (Spatie) | 1 | 3 |
| **SharedKernel** | Shared models | Media, BusinessHour, EmergencyContact | 3 tables | 0 | 6 |
| **Tenders** | Procurement tenders | Tender | tenders | 4 | 8 |
| **UserManagement** | User CRUD | — | — | 1 | 4 |
| **WaterSchedule** | Water distribution | WaterArea, WaterSchedule, WaterMaintenance | 3 tables | 6 | 10 |

## 4.2 Domain Details

### Domain: Homepage
**Purpose:** CMS for the entire homepage — settings, hero slides, sections ordering, quick links, statistics, carousel configurations.

**Models:**
- `HomepageSetting` → `homepage_settings` (singleton row, site title, subtitle, portal URL, CTA buttons, mayor message)
- `HomepageSlide` → `homepage_slides` (hero carousel slides, scoped by `page_key`: 'home', 'services', 'departments', etc.)
- `HomepageSection` → `homepage_sections` (section ordering, enable/disable, title, subtitle, items_limit)
- `HomepageQuickLink` → `homepage_quick_links` (quick access links with icons)
- `HomepageStatistic` → `homepage_statistics` (counter numbers displayed on homepage)
- `CarouselConfiguration` → `carousel_configurations` (per-carousel settings: autoplay, slides count, navigation, RTL direction)

**Key Service:** `HomepageService` — loads all homepage data with caching.

**Key Files:**
- `app/Domains/Homepage/Services/HomepageService.php`
- `app/Domains/Homepage/Actions/` (20 action classes)
- `app/Livewire/Homepage/PublicHomePage.php`

### Domain: ElectronicServices
**Purpose:** E-services catalog linking citizens to the Palestinian e-government portal.

**Models:**
- `ServiceCategory` → `service_categories` (hierarchical with `parent_id` self-reference)
- `ElectronicService` → `electronic_services` (name, description, requirements, steps, fees as JSON, portal_url)
- `ServiceView` → `service_views` (view tracking)
- `ServicePortalClick` → `service_portal_clicks` (click tracking)
- `ServiceSearchTerm` → `service_search_terms` (aliases, keywords, phrases, citizen expressions — used by chatbot)

**Key Integration:** Each service has a `portal_url` pointing to `https://i.palexpand.ps/portal/...`

### Domain: Chatbot
**Purpose:** Arabic NLP chatbot with 57 intent handlers.

**Key Architecture:**
- `ProcessRuleBasedChatMessageAction` — 17-step message processing pipeline (~2200 lines)
- `HybridIntentPredictor` — Rule-based (30+ patterns) + optional ML (Naive Bayes)
- `SmartServiceSearch` — 13-signal weighted scoring with fuzzy matching
- `ConversationContextService` — State management with 20-minute TTL
- `ArabicTextNormalizer` — Arabic text normalization (diacritics, tashkeel, colloquial variations)

**Models:** ChatIntent, ChatTrainingExample, ChatbotConversation, ChatbotMessage, ChatbotFeedback, ChatbotServiceAlias, ChatbotModelVersion

**Config:** `config/chatbot.php` — ML enabled/disabled, search thresholds, cache TTLs, rate limits

### Domain: Municipality
**Purpose:** Municipality profile, council members, council decisions, contacts, social platforms, media.

**Models:**
- `Municipality` → `municipalities` (singleton, name, vision, mission, coordinates)
- `CouncilMember` → `council_members` (members with photo, social links, term dates)
- `CouncilDecision` → `council_decisions` (numbered decisions with attachments)
- `MunicipalityContact` → `municipality_contacts` (polymorphic-ready)
- `MunicipalitySocialPlatform` → `municipality_social_platforms`
- `MunicipalityExternalPlatform` → `municipality_external_platforms`
- `MunicipalityCustomField` → `municipality_custom_fields`

---

# 5. DATABASE

## 5.1 All Tables (64 tables)

### Core System Tables
| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `users` | System users | id, name, email, password, department_id, status, login_attempts, locked_until, two_factor_enabled |
| `sessions` | User sessions | id, user_id, payload, last_activity |
| `cache` | Cache storage | key, value, expiration |
| `cache_locks` | Cache locks | key, owner, expiration |
| `jobs` | Queue jobs | queue, payload, attempts, available_at |
| `job_batches` | Batch jobs | name, total_jobs, pending_jobs, failed_jobs |
| `failed_jobs` | Failed jobs | uuid, connection, queue, payload, exception |
| `password_reset_tokens` | Password reset | email, token |

### Authentication Tables
| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `login_activities` | Login audit log | user_id, ip_address, user_agent, event_type, successful, session_id |

### Permission Tables (Spatie)
| Table | Purpose |
|-------|---------|
| `permissions` | Permission definitions (name, guard_name, group, display_name) |
| `roles` | Role definitions (name, guard_name) |
| `model_has_permissions` | User ↔ Permission pivot |
| `model_has_roles` | User ↔ Role pivot |
| `role_has_permissions` | Role ↔ Permission pivot |

### Content Tables
| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `departments` | Municipal departments | name, slug, manager_name, phone, email, status, is_public, is_featured |
| `news_items` | News articles | title_ar, title_en, slug, category, content, cover_image_path, status, is_public |
| `projects` | Municipal projects | name_ar, name_en, slug, category, project_status, budget, implementation_percentage |
| `announcements` | Official announcements | title, slug, type, priority, status, content, desktop_image_path, mobile_image_path |
| `tenders` | Procurement tenders | title_ar, title_en, slug, category, submission_deadline, status, tender_documents |
| `job_offers` | Job listings | title, slug, employment_type, salary, vacancies, requirements, status |
| `open_datasets` | Open data files | title, slug, type, category, file_path, file_format, status |

### Service Tables
| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `service_categories` | Service categories (hierarchical) | name, slug, parent_id, icon, status, is_public |
| `electronic_services` | Electronic services | name, slug, requirements(JSON), steps(JSON), fees(JSON), portal_url, status |
| `service_views` | Service view tracking | electronic_service_id, ip_hash, viewed_at |
| `service_portal_clicks` | Portal click tracking | electronic_service_id, ip_hash, clicked_at |
| `service_search_terms` | Chatbot search terms | electronic_service_id, term, normalized_term, type, weight |

### Facility Tables
| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `facility_categories` | Facility categories | name, slug, icon, is_active |
| `public_facilities` | Public facilities | name, slug, cover_image_path, gallery(JSON), services(JSON), status |

### Engineering Offices
| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `engineering_offices` | Licensed offices | office_name, slug, license_number, specializations(JSON), approval_status |

### Municipality Tables
| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `municipalities` | Municipality profile | name_ar, name_en, vision, mission, latitude, longitude |
| `municipality_contacts` | Contact info | municipality_id, type, label, value, icon |
| `municipality_social_platforms` | Social media links | municipality_id, name, slug, icon, url |
| `municipality_external_platforms` | External platforms | municipality_id, name, icon, url, category |
| `municipality_custom_fields` | Custom data fields | municipality_id, key, value, type |
| `council_members` | Council members | full_name, slug, position, photo_path, term_start, term_end |
| `council_decisions` | Council decisions | decision_number, title, content, type, status, attachment_path |

### Homepage CMS Tables
| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `homepage_settings` | Site settings (singleton) | site_title, site_subtitle, portal_url, mayor_message |
| `homepage_slides` | Hero/carousel slides | page_key, title, image_path, button_text, is_active, sort_order |
| `homepage_sections` | Section ordering | key, title, is_enabled, sort_order, items_limit, settings(JSON) |
| `homepage_quick_links` | Quick access links | title, icon, url, type, is_active, sort_order |
| `homepage_statistics` | Counter statistics | label, value, suffix, icon, is_active, sort_order |
| `carousel_configurations` | Carousel settings | key, name, type, autoplay, autoplay_delay, desktop_slides, mobile_slides |

### Complaint System
| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `complaints` | Citizen complaints | tracking_number, citizen_name, phone, category, department_id, status, priority |
| `contact_requests` | Contact form submissions | tracking_number, name, phone, message, source, status |

### Water Schedule
| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `water_areas` | Water distribution areas | name, slug, is_active |
| `water_schedules` | Schedule entries | water_area_id, schedule_date, start_time, end_time, status |
| `water_maintenances` | Maintenance notices | title, starts_at, ends_at, affected_areas(JSON) |

### Chatbot Tables
| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `chat_intents` | Intent definitions | name, label_ar, is_active, minimum_confidence |
| `chat_training_examples` | Training data | chat_intent_id, text, normalized_text, source, weight |
| `chatbot_conversations` | Active conversations | session_id, user_id, status, metadata(JSON), last_intent |
| `chatbot_messages` | Conversation messages | conversation_id, role, content, metadata(JSON) |
| `chatbot_feedback` | User feedback | message_id, type, comment |
| `chatbot_service_aliases` | Service aliases for chatbot | alias, service_key, is_active |
| `chatbot_model_versions` | ML model versions | version, status, path |
| `chatbot_intent_analytics` | Intent tracking | conversation_id, predicted_intent, confidence, execution_time_ms |
| `chatbot_unknown_questions` | Unhandled questions | question, normalized_question, admin_status, occurrence_count |
| `chatbot_search_analytics` | Search tracking | search_query, matched_service_id, search_score, no_result |
| `chatbot_workflow_analytics` | Workflow tracking | workflow_type, completion_percentage, was_completed |
| `chatbot_performance_logs` | Performance metrics | context, handler_class, duration_ms, slow_flag |
| `chatbot_dataset_versions` | Dataset versioning | version_tag, fingerprint, example_count |

### Citizen Workflows
| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `workflow_drafts` | Multi-step form drafts | workflow_type, session_id, current_step, answers(JSON), status |

### Shared/Polymorphic Tables
| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `media` | File attachments (polymorphic) | mediable_type, mediable_id, collection, disk, path, mime_type |
| `business_hours` | Working hours (polymorphic) | hourable_type, hourable_id, day, opening_time, closing_time |
| `emergency_contacts` | Emergency contacts (polymorphic) | contactable_type, contactable_id, name, phone |

## 5.2 Database Relationships

```
User
 ├── belongsTo Department
 ├── hasMany LoginActivity
 ├── hasMany (via Spatie) Roles
 └── hasMany (via Spatie) Permissions

Department
 ├── hasMany ElectronicService
 ├── hasMany Job
 ├── hasMany Complaint
 └── belongsTo User (created_by, updated_by)

Municipality
 ├── hasMany MunicipalityContact
 ├── hasMany MunicipalitySocialPlatform
 ├── hasMany MunicipalityExternalPlatform
 ├── hasMany MunicipalityCustomField
 ├── morphMany Media
 ├── morphMany BusinessHour
 └── morphMany EmergencyContact

ServiceCategory
 ├── belongsTo self (parent_id)
 ├── hasMany self (children)
 └── hasMany ElectronicService

ElectronicService
 ├── belongsTo ServiceCategory
 ├── belongsTo Department
 ├── hasMany ServiceView
 ├── hasMany ServicePortalClick
 └── hasMany ServiceSearchTerm

FacilityCategory
 └── hasMany Facility

Facility
 └── belongsTo FacilityCategory

HomepageSlide
 ├── scoped by page_key ('home', 'services', 'departments', etc.)
 └── belongsTo User (created_by, updated_by)

CouncilMember
 └── belongsTo User (created_by, updated_by)

CouncilDecision
 └── belongsTo User (created_by, updated_by)

ChatbotConversation
 ├── belongsTo User
 └── hasMany ChatbotMessage

ChatbotMessage
 ├── belongsTo ChatbotConversation
 └── hasOne ChatbotFeedback

ChatIntent
 └── hasMany ChatTrainingExample

WaterArea
 └── hasMany WaterSchedule

WaterSchedule
 └── belongsTo WaterArea

NewsItem, Project, Tender, Job, Announcement
 └── belongsTo User (created_by, updated_by)

Complaint
 ├── belongsTo Department
 ├── belongsTo User (assigned_to, created_by)
 └── belongsTo User (submitter)

WorkflowDraft
 └── (standalone, polymorphic via final_entity_type/id)
```

---

# 6. ROUTES

All routes are defined in `routes/web.php` (636 lines). No `api.php` exists.

## 6.1 Public Routes (No Auth Required)

| Method | URL | Name | Handler | Purpose |
|--------|-----|------|---------|---------|
| GET | `/` | `home` | `PublicHomePage` | Homepage |
| GET | `/login` | `login` | `Login` | Login page |
| GET | `/forgot-password` | `password.request` | `ForgotPassword` | Password reset request |
| GET | `/reset-password/{token}/{email}` | `password.reset` | `ResetPassword` | Password reset form |
| GET | `/jobs` | `public.jobs.index` | `PublicJobsIndex` | Public jobs listing |
| GET | `/jobs/{job:slug}` | `public.jobs.show` | `PublicJobShow` | Job detail |
| GET | `/water-schedule` | `public.water-schedule` | `PublicWaterSchedule` | Water schedule |
| GET | `/facilities` | `public.facilities.index` | `PublicFacilitiesIndex` | Facilities listing |
| GET | `/facilities/{facility:slug}` | `public.facilities.show` | `PublicFacilityShow` | Facility detail |
| GET | `/services` | `public.services.index` | `PublicServicesPortal` | Services portal |
| GET | `/services/{category:slug}` | `public.services.category` | `PublicServicesCategory` | Services by category |
| GET | `/services/{category:slug}/{service:slug}` | `public.services.show` | `PublicServiceDetail` | Service detail |
| GET | `/departments` | `public.departments.index` | `PublicDepartmentsPortal` | Departments listing |
| GET | `/departments/{department:slug}` | `public.departments.show` | `PublicDepartmentShow` | Department detail |
| GET | `/engineering-offices` | `public.engineering-offices.index` | `PublicEngineeringOfficesIndex` | Offices listing |
| GET | `/engineering-offices/{office:slug}` | `public.engineering-offices.show` | `PublicEngineeringOfficeShow` | Office detail |
| GET | `/open-data` | `public.open-data.index` | `OpenDataIndex` | Open data portal |
| GET | `/about` | `public.municipality.about` | `PublicMunicipalityAbout` | About page |
| GET | `/council` | `public.council.index` | `PublicCouncilMembersPortal` | Council members |
| GET | `/council/{councilMember:slug}` | `public.council.show` | `PublicCouncilMemberProfile` | Member profile |
| GET | `/council/decisions` | `public.council.decisions.index` | `PublicCouncilDecisionsIndex` | Decisions listing |
| GET | `/council/decisions/{decision}` | `public.council.decisions.show` | `PublicCouncilDecisionShow` | Decision detail |
| GET | `/announcements` | `public.announcements.index` | `PublicAnnouncementsIndex` | Announcements |
| GET | `/announcements/{announcement:slug}` | `public.announcements.show` | `PublicAnnouncementShow` | Announcement detail |
| GET | `/news` | `public.news.index` | `PublicNewsIndex` | News listing |
| GET | `/news/{newsItem:slug}` | `public.news.show` | `PublicNewsShow` | News detail |
| GET | `/projects` | `public.projects.index` | `PublicProjectsIndex` | Projects listing |
| GET | `/projects/{project:slug}` | `public.projects.show` | `PublicProjectShow` | Project detail |
| GET | `/complaints/submit` | `public.complaints.submit` | `PublicComplaintForm` | Submit complaint |
| GET | `/complaints/track` | `public.complaints.track` | `PublicComplaintTracking` | Track complaint |
| GET | `/tenders` | `public.tenders.index` | `PublicTendersIndex` | Tenders listing |
| GET | `/tenders/{tender:slug}` | `public.tenders.show` | `PublicTenderShow` | Tender detail |
| GET | `/chatbot` | `chatbot` | `ChatbotPage` | Chatbot full page |

## 6.2 Authenticated Routes (Permission-Gated)

**Dashboard:** `GET /dashboard` → `ExecutiveDashboard`

**Users:** `GET /users` → `UserIndex` (permission: `view users`)

**Roles:** `GET /roles` → `RoleIndex` (permission: `view roles`)

**Departments:**
- `GET /dashboard/departments` → `DepartmentsIndex` (permission: `departments.view`)
- `GET /dashboard/departments/create` → `DepartmentForm` (permission: `departments.create`)
- `GET /dashboard/departments/{department}/edit` → `DepartmentForm` (permission: `departments.update`)
- `GET /dashboard/departments/{department}` → `DepartmentShow` (permission: `departments.view`)

**Electronic Services (Categories):**
- `GET /electronic-services/categories` → `ServiceCategoriesIndex` (permission: `service_categories.view`)
- `GET /electronic-services/categories/create` → `ServiceCategoryForm` (permission: `service_categories.create`)
- `GET /electronic-services/categories/{category}/edit` → `ServiceCategoryForm` (permission: `service_categories.update`)
- `GET /electronic-services/categories/{category}` → `ServiceCategoryShow` (permission: `service_categories.view`)

**Electronic Services (Services):**
- `GET /electronic-services/services` → `ElectronicServicesIndex` (permission: `electronic_services.view`)
- `GET /electronic-services/services/create` → `ElectronicServiceForm` (permission: `electronic_services.create`)
- `GET /electronic-services/services/{service}/edit` → `ElectronicServiceForm` (permission: `electronic_services.update`)
- `GET /electronic-services/services/{service}` → `ElectronicServiceShow` (permission: `electronic_services.view`)
- `GET /electronic-services/analytics` → `ElectronicServiceAnalytics` (permission: `electronic_services.analytics`)

**Engineering Offices:**
- `GET /dashboard/engineering-offices` → `EngineeringOfficesIndex` (permission: `engineering_offices.view`)
- `GET /dashboard/engineering-offices/create` → `EngineeringOfficeForm` (permission: `engineering_offices.create`)
- `GET /dashboard/engineering-offices/{office}/edit` → `EngineeringOfficeForm` (permission: `engineering_offices.update`)
- `GET /dashboard/engineering-offices/{office}` → `EngineeringOfficeShow` (permission: `engineering_offices.view`)

**News:**
- `GET /dashboard/news` → `NewsIndex` (permission: `news.view`)
- `GET /dashboard/news/create` → `NewsForm` (permission: `news.create`)
- `GET /dashboard/news/{newsItem}/edit` → `NewsForm` (permission: `news.update`)

**Projects:**
- `GET /dashboard/projects` → `ProjectsIndex` (permission: `projects.view`)
- `GET /dashboard/projects/create` → `ProjectForm` (permission: `projects.create`)
- `GET /dashboard/projects/{project}/edit` → `ProjectForm` (permission: `projects.update`)

**Complaints:**
- `GET /dashboard/complaints` → `ComplaintsIndex` (permission: `complaints.view`)
- `GET /dashboard/complaints/create` → `ComplaintForm` (permission: `complaints.create`)
- `GET /dashboard/complaints/{complaint}/edit` → `ComplaintForm` (permission: `complaints.update`)

**Tenders:**
- `GET /dashboard/tenders` → `TendersIndex` (permission: `tenders.view`)
- `GET /dashboard/tenders/create` → `TenderForm` (permission: `tenders.create`)
- `GET /dashboard/tenders/{tender}/edit` → `TenderForm` (permission: `tenders.update`)

**Homepage:**
- `GET /homepage` → `HomepageDashboard` (permission: `homepage.view`)
- `GET /homepage/settings` → `HomepageSettingsForm` (permission: `homepage.update`)
- `GET /homepage/slides` → `HomepageSlidesIndex` (permission: `homepage.slides.view`)
- `GET /homepage/slides/create` → `HomepageSlideForm` (permission: `homepage.slides.create`)
- `GET /homepage/slides/{slide}/edit` → `HomepageSlideForm` (permission: `homepage.slides.update`)
- `GET /homepage/sections` → `HomepageSectionsManager` (permission: `homepage.sections.update`)
- `GET /homepage/quick-links` → `HomepageQuickLinksIndex` (permission: `homepage.quick_links.view`)
- `GET /homepage/quick-links/create` → `HomepageQuickLinkForm` (permission: `homepage.quick_links.create`)
- `GET /homepage/quick-links/{quickLink}/edit` → `HomepageQuickLinkForm` (permission: `homepage.quick_links.update`)
- `GET /homepage/statistics` → `HomepageStatisticsIndex` (permission: `homepage.statistics.view`)
- `GET /homepage/statistics/create` → `HomepageStatisticForm` (permission: `homepage.statistics.create`)
- `GET /homepage/statistics/{statistic}/edit` → `HomepageStatisticForm` (permission: `homepage.statistics.update`)

**Page Carousels:**
- `GET /page-carousels` → `PageCarouselsIndex` (permission: `homepage.slides.view`)
- `GET /page-carousels/config` → `CarouselConfigManager` (permission: `homepage.slides.view`)
- `GET /page-carousels/create` → `PageCarouselForm` (permission: `homepage.slides.create`)
- `GET /page-carousels/{slide}/edit` → `PageCarouselForm` (permission: `homepage.slides.update`)

**Jobs:**
- `GET /dashboard/jobs` → `JobsIndex` (permission: `jobs.view`)
- `GET /dashboard/jobs/create` → `JobForm` (permission: `jobs.create`)
- `GET /dashboard/jobs/{job}/edit` → `JobForm` (permission: `jobs.update`)

**Announcements:**
- `GET /dashboard/announcements` → `AnnouncementsIndex` (permission: `announcements.view`)
- `GET /dashboard/announcements/create` → `AnnouncementForm` (permission: `announcements.create`)
- `GET /dashboard/announcements/{announcement}/edit` → `AnnouncementForm` (permission: `announcements.update`)

**Water Schedule:**
- `GET /dashboard/water-schedule` → `WaterScheduleDashboard` (permission: `water.view`)
- `GET /dashboard/water-schedule/areas` → `WaterAreasIndex` (permission: `water.view`)
- `GET /dashboard/water-schedule/areas/create` → `WaterAreasForm` (permission: `water.create`)
- `GET /dashboard/water-schedule/areas/{waterArea}/edit` → `WaterAreasForm` (permission: `water.update`)
- `GET /dashboard/water-schedule/maintenance` → `WaterMaintenanceIndex` (permission: `water.view`)
- `GET /dashboard/water-schedule/maintenance/create` → `WaterMaintenanceForm` (permission: `water.create`)
- `GET /dashboard/water-schedule/maintenance/{maintenance}/edit` → `WaterMaintenanceForm` (permission: `water.update`)

**Public Facilities:**
- `GET /dashboard/facilities` → `FacilitiesIndex` (permission: `facilities.view`)
- `GET /dashboard/facilities/create` → `FacilityForm` (permission: `facilities.create`)
- `GET /dashboard/facilities/{facility}/edit` → `FacilityForm` (permission: `facilities.update`)
- `GET /dashboard/facilities/categories` → `FacilityCategoriesIndex` (permission: `facility_categories.view`)
- `GET /dashboard/facilities/categories/create` → `FacilityCategoriesForm` (permission: `facility_categories.create`)
- `GET /dashboard/facilities/categories/{category}/edit` → `FacilityCategoriesForm` (permission: `facility_categories.update`)

**Municipality:**
- `GET /dashboard/municipality` → `MunicipalityIndex` (permission: `municipality.view`)
- `GET /dashboard/municipality/general-info` → `MunicipalityGeneralInfo` (permission: `municipality.update`)
- `GET /dashboard/municipality/contacts` → `MunicipalityContacts` (permission: `municipality.contacts.manage`)
- `GET /dashboard/municipality/social` → `MunicipalitySocial` (permission: `municipality.social.manage`)
- `GET /dashboard/municipality/platforms` → `MunicipalityPlatforms` (permission: `municipality.platforms.manage`)
- `GET /dashboard/municipality/custom-fields` → `MunicipalityCustomFields` (permission: `municipality.custom-fields.manage`)
- `GET /dashboard/municipality/media` → `MunicipalityMedia` (permission: `municipality.media.manage`)
- `GET /dashboard/municipality/business-hours` → `MunicipalityBusinessHours` (permission: `municipality.business-hours.manage`)
- `GET /dashboard/municipality/emergency-contacts` → `MunicipalityEmergencyContacts` (permission: `municipality.emergency-contacts.manage`)

**Council Decisions:**
- `GET /dashboard/municipality/council-decisions` → `CouncilDecisionsIndex` (permission: `council_decisions.view`)
- `GET /dashboard/municipality/council-decisions/create` → `CouncilDecisionForm` (permission: `council_decisions.create`)
- `GET /dashboard/municipality/council-decisions/{councilDecision}/edit` → `CouncilDecisionForm` (permission: `council_decisions.update`)
- `GET /dashboard/municipality/council-decisions/{councilDecision}` → `CouncilDecisionShow` (permission: `council_decisions.view`)

**Council Members:**
- `GET /dashboard/municipality/council-members` → `CouncilMembersIndex` (permission: `council_members.view`)
- `GET /dashboard/municipality/council-members/create` → `CouncilMemberForm` (permission: `council_members.create`)
- `GET /dashboard/municipality/council-members/{councilMember}/edit` → `CouncilMemberForm` (permission: `council_members.update`)
- `GET /dashboard/municipality/council-members/{councilMember}` → `CouncilMemberProfile` (permission: `council_members.view`)

**Open Data:**
- `GET /dashboard/open-data` → `OpenDataAdminIndex` (permission: `open_data.view`)
- `GET /dashboard/open-data/create` → `OpenDataAdminForm` (permission: `open_data.create`)
- `GET /dashboard/open-data/{dataset}/edit` → `OpenDataAdminForm` (permission: `open_data.update`)

**Chatbot Dashboard:**
- `GET /dashboard/chatbot` → `ChatbotDashboard` (permission: `chatbot.view`)
- `GET /dashboard/chatbot/unknown-questions` → `UnknownQuestionsManager` (permission: `chatbot.view`)
- `GET /dashboard/chatbot/performance` → `PerformanceMonitor` (permission: `chatbot.view`)
- `GET /dashboard/chatbot/search-terms` → `SearchTermManager` (permission: `chatbot.view`)

**Debug (Local Only):**
- `GET /setup-database` → Run migrations + seeders (local only)
- `GET /debug-permissions` → Debug permission info (local only)
- `GET /seed-carousels` → Seed carousel data (local only)

---

# 7. LIVEWIRE COMPONENTS

## 7.1 Component Inventory

### Public Components

| Component | File | Purpose |
|-----------|------|---------|
| `PublicHomePage` | `app/Livewire/Homepage/PublicHomePage.php` | Homepage rendering |
| `PublicServicesPortal` | `app/Livewire/ElectronicServices/PublicServicesPortal.php` | Services listing |
| `PublicServicesCategory` | `app/Livewire/ElectronicServices/PublicServicesCategory.php` | Category-filtered services |
| `PublicServiceDetail` | `app/Livewire/ElectronicServices/PublicServiceDetail.php` | Service detail + view tracking |
| `PublicNewsIndex` | `app/Livewire/News/PublicNewsIndex.php` | News listing with search |
| `PublicNewsShow` | `app/Livewire/News/PublicNewsShow.php` | News detail + view tracking |
| `PublicProjectsIndex` | `app/Livewire/Projects/PublicProjectsIndex.php` | Projects listing |
| `PublicProjectShow` | `app/Livewire/Projects/PublicProjectShow.php` | Project detail |
| `PublicAnnouncementsIndex` | `app/Livewire/Announcements/PublicAnnouncementsIndex.php` | Announcements listing |
| `PublicAnnouncementShow` | `app/Livewire/Announcements/PublicAnnouncementShow.php` | Announcement detail |
| `PublicJobsIndex` | `app/Livewire/Jobs/PublicJobsIndex.php` | Jobs listing |
| `PublicJobShow` | `app/Livewire/Jobs/PublicJobShow.php` | Job detail |
| `PublicTendersIndex` | `app/Livewire/Tenders/PublicTendersIndex.php` | Tenders listing |
| `PublicTenderShow` | `app/Livewire/Tenders/PublicTenderShow.php` | Tender detail |
| `PublicFacilitiesIndex` | `app/Livewire/PublicFacilities/PublicFacilitiesIndex.php` | Facilities listing |
| `PublicFacilityShow` | `app/Livewire/PublicFacilities/PublicFacilityShow.php` | Facility detail |
| `PublicDepartmentsPortal` | `app/Livewire/Department/PublicDepartmentsPortal.php` | Departments listing |
| `PublicDepartmentShow` | `app/Livewire/Department/PublicDepartmentShow.php` | Department detail |
| `PublicEngineeringOfficesIndex` | `app/Livewire/EngineeringOffices/PublicEngineeringOfficesIndex.php` | Offices listing |
| `PublicEngineeringOfficeShow` | `app/Livewire/EngineeringOffices/PublicEngineeringOfficeShow.php` | Office detail |
| `PublicMunicipalityAbout` | `app/Livewire/Municipality/PublicMunicipalityAbout.php` | About page |
| `PublicCouncilMembersPortal` | `app/Livewire/Council/PublicCouncilMembersPortal.php` | Council members |
| `PublicCouncilMemberProfile` | `app/Livewire/Council/PublicCouncilMemberProfile.php` | Member profile |
| `PublicCouncilDecisionsIndex` | `app/Livewire/Council/PublicCouncilDecisionsIndex.php` | Decisions listing |
| `PublicCouncilDecisionShow` | `app/Livewire/Council/PublicCouncilDecisionShow.php` | Decision detail |
| `PublicWaterSchedule` | `app/Livewire/WaterSchedule/PublicWaterSchedule.php` | Water schedule |
| `OpenDataIndex` | `app/Livewire/OpenData/OpenDataIndex.php` | Open data portal |
| `PublicComplaintForm` | `app/Livewire/Complaints/PublicComplaintForm.php` | Submit complaint |
| `PublicComplaintTracking` | `app/Livewire/Complaints/PublicComplaintTracking.php` | Track complaint |
| `PublicPageCarousel` | `app/Livewire/PublicPageCarousel.php` | Shared carousel component |

### Chatbot Components

| Component | File | Purpose |
|-----------|------|---------|
| `ChatbotPage` | `app/Livewire/Chatbot/ChatbotPage.php` | Full-page chatbot |
| `ChatbotWidget` | `app/Livewire/Chatbot/ChatbotWidget.php` | Floating widget |

### Auth Components

| Component | File | Purpose |
|-----------|------|---------|
| `Login` | `app/Livewire/Auth/Login.php` | Login form |
| `ForgotPassword` | `app/Livewire/Auth/ForgotPassword.php` | Password reset request |
| `ResetPassword` | `app/Livewire/Auth/ResetPassword.php` | Password reset form |
| `ChangePassword` | `app/Livewire/Auth/ChangePassword.php` | Change password form |

### Dashboard Components

| Component | File | Purpose |
|-----------|------|---------|
| `ExecutiveDashboard` | `app/Livewire/Dashboard/ExecutiveDashboard.php` | Executive dashboard |
| `UserIndex` | `app/Livewire/Users/UserIndex.php` | User management |
| `RoleIndex` | `app/Livewire/Roles/RoleIndex.php` | Role management |

### Admin Content Components

| Component | File | Purpose |
|-----------|------|---------|
| `DepartmentsIndex` | `app/Livewire/Department/DepartmentsIndex.php` | Department list |
| `DepartmentForm` | `app/Livewire/Department/DepartmentForm.php` | Department create/edit |
| `DepartmentShow` | `app/Livewire/Department/DepartmentShow.php` | Department detail |
| `ServiceCategoriesIndex` | `app/Livewire/ElectronicServices/ServiceCategoriesIndex.php` | Category list |
| `ServiceCategoryForm` | `app/Livewire/ElectronicServices/ServiceCategoryForm.php` | Category create/edit |
| `ServiceCategoryShow` | `app/Livewire/ElectronicServices/ServiceCategoryShow.php` | Category detail |
| `ElectronicServicesIndex` | `app/Livewire/ElectronicServices/ElectronicServicesIndex.php` | Service list |
| `ElectronicServiceForm` | `app/Livewire/ElectronicServices/ElectronicServiceForm.php` | Service create/edit |
| `ElectronicServiceShow` | `app/Livewire/ElectronicServices/ElectronicServiceShow.php` | Service detail |
| `ElectronicServiceAnalytics` | `app/Livewire/ElectronicServices/ElectronicServiceAnalytics.php` | Service analytics |
| `EngineeringOfficesIndex` | `app/Livewire/EngineeringOffices/EngineeringOfficesIndex.php` | Office list |
| `EngineeringOfficeForm` | `app/Livewire/EngineeringOffices/EngineeringOfficeForm.php` | Office create/edit |
| `EngineeringOfficeShow` | `app/Livewire/EngineeringOffices/EngineeringOfficeShow.php` | Office detail |
| `NewsIndex` | `app/Livewire/News/NewsIndex.php` | News list |
| `NewsForm` | `app/Livewire/News/NewsForm.php` | News create/edit |
| `ProjectsIndex` | `app/Livewire/Projects/ProjectsIndex.php` | Projects list |
| `ProjectForm` | `app/Livewire/Projects/ProjectForm.php` | Project create/edit |
| `TendersIndex` | `app/Livewire/Tenders/TendersIndex.php` | Tenders list |
| `TenderForm` | `app/Livewire/Tenders/TenderForm.php` | Tender create/edit |
| `ComplaintsIndex` | `app/Livewire/Complaints/ComplaintsIndex.php` | Complaints list |
| `ComplaintForm` | `app/Livewire/Complaints/ComplaintForm.php` | Complaint create/edit |
| `JobsIndex` | `app/Livewire/Jobs/JobsIndex.php` | Jobs list |
| `JobForm` | `app/Livewire/Jobs/JobForm.php` | Job create/edit |
| `AnnouncementsIndex` | `app/Livewire/Admin/Announcements/AnnouncementsIndex.php` | Announcements list |
| `AnnouncementForm` | `app/Livewire/Admin/Announcements/AnnouncementForm.php` | Announcement create/edit |
| `FacilitiesIndex` | `app/Livewire/PublicFacilities/FacilitiesIndex.php` | Facilities list |
| `FacilityForm` | `app/Livewire/PublicFacilities/FacilityForm.php` | Facility create/edit |
| `FacilityCategoriesIndex` | `app/Livewire/PublicFacilities/FacilityCategoriesIndex.php` | Category list |
| `FacilityCategoriesForm` | `app/Livewire/PublicFacilities/FacilityCategoriesForm.php` | Category create/edit |
| `OpenDataAdminIndex` | `app/Livewire/OpenData/Admin/OpenDataAdminIndex.php` | Open data list |
| `OpenDataAdminForm` | `app/Livewire/OpenData/Admin/OpenDataAdminForm.php` | Open data create/edit |

### Homepage CMS Components

| Component | File | Purpose |
|-----------|------|---------|
| `HomepageDashboard` | `app/Livewire/Homepage/HomepageDashboard.php` | Homepage dashboard |
| `HomepageSettingsForm` | `app/Livewire/Homepage/HomepageSettingsForm.php` | Settings form |
| `HomepageSlidesIndex` | `app/Livewire/Homepage/HomepageSlidesIndex.php` | Slides list |
| `HomepageSlideForm` | `app/Livewire/Homepage/HomepageSlideForm.php` | Slide create/edit |
| `HomepageSectionsManager` | `app/Livewire/Homepage/HomepageSectionsManager.php` | Section ordering |
| `HomepageQuickLinksIndex` | `app/Livewire/Homepage/HomepageQuickLinksIndex.php` | Quick links list |
| `HomepageQuickLinkForm` | `app/Livewire/Homepage/HomepageQuickLinkForm.php` | Quick link create/edit |
| `HomepageStatisticsIndex` | `app/Livewire/Homepage/HomepageStatisticsIndex.php` | Statistics list |
| `HomepageStatisticForm` | `app/Livewire/Homepage/HomepageStatisticForm.php` | Statistic create/edit |
| `PageCarouselsIndex` | `app/Livewire/PageCarousels/PageCarouselsIndex.php` | Carousel slides list |
| `PageCarouselForm` | `app/Livewire/PageCarousels/PageCarouselForm.php` | Carousel slide create/edit |
| `CarouselConfigManager` | `app/Livewire/PageCarousels/CarouselConfigManager.php` | Carousel configuration |

### Municipality Components

| Component | File | Purpose |
|-----------|------|---------|
| `MunicipalityIndex` | `app/Livewire/Municipality/MunicipalityIndex.php` | Municipality profile |
| `MunicipalityGeneralInfo` | `app/Livewire/Municipality/MunicipalityGeneralInfo.php` | General info form |
| `MunicipalityContacts` | `app/Livewire/Municipality/MunicipalityContacts.php` | Contacts CRUD |
| `MunicipalitySocial` | `app/Livewire/Municipality/MunicipalitySocial.php` | Social platforms CRUD |
| `MunicipalityPlatforms` | `app/Livewire/Municipality/MunicipalityPlatforms.php` | External platforms CRUD |
| `MunicipalityCustomFields` | `app/Livewire/Municipality/MunicipalityCustomFields.php` | Custom fields CRUD |
| `MunicipalityMedia` | `app/Livewire/Municipality/MunicipalityMedia.php` | Media management |
| `MunicipalityBusinessHours` | `app/Livewire/Municipality/MunicipalityBusinessHours.php` | Business hours CRUD |
| `MunicipalityEmergencyContacts` | `app/Livewire/Municipality/MunicipalityEmergencyContacts.php` | Emergency contacts CRUD |
| `CouncilMembersIndex` | `app/Livewire/Municipality/CouncilMembersIndex.php` | Council members list |
| `CouncilMemberForm` | `app/Livewire/Municipality/CouncilMemberForm.php` | Member create/edit |
| `CouncilMemberProfile` | `app/Livewire/Municipality/CouncilMemberProfile.php` | Member detail |
| `CouncilDecisionsIndex` | `app/Livewire/Municipality/CouncilDecisionsIndex.php` | Decisions list |
| `CouncilDecisionForm` | `app/Livewire/Municipality/CouncilDecisionForm.php` | Decision create/edit |
| `CouncilDecisionShow` | `app/Livewire/Municipality/CouncilDecisionShow.php` | Decision detail |

### Water Schedule Components

| Component | File | Purpose |
|-----------|------|---------|
| `WaterScheduleDashboard` | `app/Livewire/WaterSchedule/WaterScheduleDashboard.php` | Water schedule management |
| `WaterAreasIndex` | `app/Livewire/WaterSchedule/WaterAreasIndex.php` | Areas list |
| `WaterAreasForm` | `app/Livewire/WaterSchedule/WaterAreasForm.php` | Area create/edit |
| `WaterMaintenanceIndex` | `app/Livewire/WaterSchedule/WaterMaintenanceIndex.php` | Maintenance list |
| `WaterMaintenanceForm` | `app/Livewire/WaterSchedule/WaterMaintenanceForm.php` | Maintenance create/edit |

### Chatbot Admin Components

| Component | File | Purpose |
|-----------|------|---------|
| `ChatbotDashboard` | `app/Livewire/Admin/Chatbot/ChatbotDashboard.php` | Chatbot statistics |
| `UnknownQuestionsManager` | `app/Livewire/Admin/Chatbot/UnknownQuestionsManager.php` | Unknown questions management |
| `PerformanceMonitor` | `app/Livewire/Admin/Chatbot/PerformanceMonitor.php` | Performance monitoring |
| `SearchTermManager` | `app/Livewire/Admin/Chatbot/SearchTermManager.php` | Search term management |

---

# 8. HOMEPAGE

## 8.1 How the Homepage Loads

1. **Route:** `GET /` → `PublicHomePage::class`
2. **Component:** `app/Livewire/Homepage/PublicHomePage.php`
3. **Service:** `HomepageService::getAll()` — loads all data with cache
4. **Blade:** `resources/views/livewire/homepage/public-home-page.blade.php`

## 8.2 Homepage Sections (Ordered by sort_order)

The homepage sections are stored in `homepage_sections` table and ordered by `sort_order`. The `HomepageSection` model has a `key` field that identifies each section. The `HomepageSectionsManager` Livewire component allows admins to:
- Toggle sections on/off (`is_enabled`)
- Reorder sections (`sort_order`)
- Set items limit per section
- Edit section titles and subtitles

### Default Sections (from `HomepageSeeder`):

| Key | Purpose | Data Source |
|-----|---------|-------------|
| `hero` | Hero carousel with slides | `homepage_slides` WHERE `page_key='home'` |
| `services` | Featured services | `electronic_services` WHERE `is_featured=1` |
| `news` | Latest news | `news_items` WHERE `status='published'` |
| `projects` | Featured projects | `projects` WHERE `is_featured=1` |
| `announcements` | Active announcements | `announcements` WHERE `status='published'` |
| `jobs` | Open job listings | `job_offers` WHERE `status='published'` |
| `tenders` | Active tenders | `tenders` WHERE `status='published'` |
| `departments` | Departments overview | `departments` WHERE `is_public=1` |
| `facilities` | Public facilities | `public_facilities` WHERE `is_public=1` |
| `engineering-offices` | Engineering offices | `engineering_offices` WHERE `is_public=1` |
| `council-members` | Council members | `council_members` WHERE `is_public=1` |
| `water-schedule` | Water schedule | `water_schedules` + `water_areas` |
| `open-data` | Open datasets | `open_datasets` WHERE `status='published'` |
| `quick-links` | Quick access links | `homepage_quick_links` WHERE `is_active=1` |
| `statistics` | Counter numbers | `homepage_statistics` WHERE `is_active=1` |

## 8.3 Homepage Settings

Stored in `homepage_settings` table (singleton row):

| Field | Default Value | Purpose |
|-------|--------------|---------|
| `site_title` | بلدية إذنا | Browser title |
| `site_subtitle` | بلدية إذنا - الخدمات الإلكترونية | Subtitle |
| `portal_url` | https://i.palexpand.ps/portal | E-government portal link |
| `primary_button_text` | الدخول إلى البوابة | Main CTA text |
| `secondary_button_text` | تعرف على البلدية | Secondary CTA text |
| `welcome_title` | مرحباً بكم في بلدية إذنا | Welcome section title |
| `mayor_message_title` | رسالة رئيس البلدية | Mayor section title |
| `mayor_message` | (Arabic text) | Mayor message |
| `show_mayor_message` | true | Toggle mayor section |
| `contact_cta_title` | تواصل معنا | Contact CTA title |

---

# 9. IMAGES SYSTEM

## 9.1 How Images Are Stored

Images are stored in `storage/app/public/` using the `public` disk. The filesystem symlink is at `public/storage`.

## 9.2 Image Fields by Model

| Model | Field | Storage Path | Purpose |
|-------|-------|-------------|---------|
| `HomepageSlide` | `image_path` | `homepage/` | Hero desktop image |
| `HomepageSlide` | `mobile_image_path` | `homepage/` | Hero mobile image |
| `HomepageSetting` | `mayor_image_path` | `homepage/` | Mayor photo |
| `NewsItem` | `cover_image_path` | `news/` | News cover image |
| `NewsItem` | `mobile_image_path` | `news/` | News mobile image |
| `Project` | `cover_image_path` | `projects/` | Project cover |
| `Announcement` | `desktop_image_path` | `announcements/` | Announcement image |
| `Announcement` | `mobile_image_path` | `announcements/` | Announcement mobile |
| `Department` | `cover_image_path` | `departments/` | Department cover |
| `Facility` | `cover_image_path` | `facilities/` | Facility cover |
| `CouncilMember` | `photo_path` | `council/` | Member photo |
| `User` | `avatar` | `avatars/` | User avatar |
| `Job` | `attachment_path` | `jobs/` | Job attachment |
| `Tender` | `tender_documents` | (JSON array) | Tender docs |
| `ElectronicService` | (none — uses portal_url) | — | Links to external portal |

## 9.3 Polymorphic Media

The `media` table supports polymorphic file attachments:
- `mediable_type` / `mediable_id` — polymorphic relation
- `collection` — media collection name
- `disk` — storage disk (default: 'public')
- `path` — file path
- `mime_type`, `size`, `width`, `height` — metadata
- `title`, `alt` — accessibility
- `display_order` — ordering within collection
- `is_active` — visibility toggle

Used by: `Municipality` model (via `morphMany(Media::class, 'mediable')`)

## 9.4 How to Change an Image

**Example: Change Hero Slide Image**
1. Login to dashboard
2. Navigate to `Homepage` → `Slides`
3. Click edit on the slide
4. Upload new image via Livewire file upload
5. Save — image is stored in `storage/app/public/homepage/`
6. Old image remains (not automatically deleted)
7. Cache is cleared
8. Homepage reflects new image

---

# 10. CMS SYSTEM

The entire public website is CMS-driven. Every section's data comes from database tables managed through the admin dashboard.

## 10.1 CMS Data Flow

```
Admin Dashboard (Livewire)
    ↓
Form Submission (wire:submit)
    ↓
Livewire Component Method
    ↓
Domain Action / Repository
    ↓
Eloquent Model
    ↓
Database Table
    ↓
Cache Invalidation
    ↓
Public Livewire Component
    ↓
Database Query (with cache)
    ↓
Blade View
    ↓
Browser
```

## 10.2 Content Types

| Content Type | Table | Model | Dashboard Route | Public Route |
|-------------|-------|-------|----------------|-------------|
| News | `news_items` | `NewsItem` | `/dashboard/news` | `/news` |
| Projects | `projects` | `Project` | `/dashboard/projects` | `/projects` |
| Announcements | `announcements` | `Announcement` | `/dashboard/announcements` | `/announcements` |
| Tenders | `tenders` | `Tender` | `/dashboard/tenders` | `/tenders` |
| Jobs | `job_offers` | `Job` | `/dashboard/jobs` | `/jobs` |
| Services | `electronic_services` | `ElectronicService` | `/electronic-services/services` | `/services` |
| Service Categories | `service_categories` | `ServiceCategory` | `/electronic-services/categories` | `/services` |
| Departments | `departments` | `Department` | `/dashboard/departments` | `/departments` |
| Facilities | `public_facilities` | `Facility` | `/dashboard/facilities` | `/facilities` |
| Facility Categories | `facility_categories` | `FacilityCategory` | `/dashboard/facilities/categories` | `/facilities` |
| Engineering Offices | `engineering_offices` | `EngineeringOffice` | `/dashboard/engineering-offices` | `/engineering-offices` |
| Council Members | `council_members` | `CouncilMember` | `/dashboard/municipality/council-members` | `/council` |
| Council Decisions | `council_decisions` | `CouncilDecision` | `/dashboard/municipality/council-decisions` | `/council/decisions` |
| Open Data | `open_datasets` | `OpenDataset` | `/dashboard/open-data` | `/open-data` |
| Water Areas | `water_areas` | `WaterArea` | `/dashboard/water-schedule/areas` | `/water-schedule` |
| Water Schedules | `water_schedules` | `WaterSchedule` | `/dashboard/water-schedule` | `/water-schedule` |
| Water Maintenance | `water_maintenances` | `WaterMaintenance` | `/dashboard/water-schedule/maintenance` | `/water-schedule` |
| Homepage Settings | `homepage_settings` | `HomepageSetting` | `/homepage/settings` | `/` |
| Homepage Slides | `homepage_slides` | `HomepageSlide` | `/homepage/slides` | `/` |
| Homepage Sections | `homepage_sections` | `HomepageSection` | `/homepage/sections` | `/` |
| Homepage Quick Links | `homepage_quick_links` | `HomepageQuickLink` | `/homepage/quick-links` | `/` |
| Homepage Statistics | `homepage_statistics` | `HomepageStatistic` | `/homepage/statistics` | `/` |
| Carousel Config | `carousel_configurations` | `CarouselConfiguration` | `/page-carousels/config` | Various pages |
| Municipality | `municipalities` | `Municipality` | `/dashboard/municipality` | `/about` |
| Complaints | `complaints` | `Complaint` | `/dashboard/complaints` | `/complaints/submit` |

---

# 11. CAROUSEL SYSTEM

## 11.1 How Carousels Work

Carousels are powered by the `HomepageSlide` model with a `page_key` field that determines which page the slide belongs to.

**Tables involved:**
- `homepage_slides` — slide content (title, description, image, buttons)
- `carousel_configurations` — per-carousel behavior settings

## 11.2 Carousel Configuration

The `CarouselConfiguration` model stores per-carousel settings:

| Field | Default | Purpose |
|-------|---------|---------|
| `key` | (unique) | Carousel identifier |
| `name` | (required) | Display name |
| `type` | `hero` | Carousel type |
| `desktop_slides` | 1 | Slides visible on desktop |
| `tablet_slides` | 1 | Slides visible on tablet |
| `mobile_slides` | 1 | Slides visible on mobile |
| `autoplay` | true | Auto-advance slides |
| `autoplay_delay` | 8000 | Delay between slides (ms) |
| `loop` | false | Loop back to start |
| `show_navigation` | true | Show prev/next arrows |
| `show_pagination` | true | Show dot indicators |
| `pause_on_hover` | true | Pause on mouse hover |
| `direction` | `rtl` | Slide direction |
| `transition` | `slide` | Transition type |

## 11.3 Page Keys

| `page_key` | Page | Purpose |
|-------------|------|---------|
| `home` | Homepage | Hero carousel |
| `services` | Services portal | Services hero |
| `departments` | Departments portal | Departments hero |
| `facilities` | Facilities portal | Facilities hero |
| `jobs` | Jobs portal | Jobs hero |
| `engineering-offices` | Engineering offices | Offices hero |
| `water-schedule` | Water schedule | Water hero |
| `open-data` | Open data | Data hero |
| `announcements` | Announcements | Announcements hero |
| `council` | Council | Council hero |

## 11.4 How to Add a New Carousel

1. Add a slide via `homepage_slides` table with the new `page_key`
2. Add carousel configuration via `/page-carousels/config`
3. Use `<livewire:public-page-carousel :pageKey="'new-key'" />` in the Blade view
4. The `PublicPageCarousel` component automatically loads slides and config from cache

---

# 12. CHATBOT

## 12.1 Architecture

The chatbot is the most complex subsystem with 57 handlers, 29 contracts, and a 17-step processing pipeline.

### Frontend
- `ChatbotPage` — Full-page chat view
- `ChatbotWidget` — Floating toggle widget (appears on all public pages)
- Both use the `BaseChatbot` trait for shared logic
- UI: Arabic-first, RTL, responsive

### Processing Pipeline (`ProcessRuleBasedChatMessageAction`)

```
User Message
    ↓
Step 1: Normalize Arabic Text (ArabicTextNormalizer)
    ↓
Step 2: Load Conversation Context (ConversationContextService, 20min TTL)
    ↓
Step 3: Check Trusted Action Keys (button clicks: main-menu:xxx, service:xx)
    ↓
Step 4: Check Typed Water Area Keys (water-area:{id})
    ↓
Step 5: Check Workflow Action Keys (workflow:confirm, workflow:cancel)
    ↓
Step 6: Global Commands (cancel, back — reset context)
    ↓
Step 7: Compound Switch (cancel + navigate)
    ↓
Step 8: Standalone Greetings (regex, never destroy workflow)
    ↓
Step 9: Thanks Messages (acknowledge, keep draft)
    ↓
Step 10: Resume Workflow (CitizenWorkflowEngine::resume())
    ↓
Step 11: Active Workflow Input (processInput if in workflow state)
    ↓
Step 12: Clarification Resolution (ClarificationResolver)
    ↓
Step 13: Domain Switch Detection (HybridIntentPredictor + MunicipalityDomainRouter)
    ↓
Step 14: Service Property Follow-up (ServicePropertyIntentDetector)
    ↓
Step 15: Guided Service Discovery (category → service browsing)
    ↓
Step 16: Context Follow-up (use active service context)
    ↓
Step 17: Smart Service Search (13-signal scoring, auto-select/clarify)
    ↓
Step 18: Fallback Loop Guard (2 consecutive fallbacks → force main menu)
    ↓
Response
```

### Intent Prediction

**HybridIntentPredictor** combines:

1. **Rule-Based (RuleIntentDetector):** 30+ Arabic patterns, checked in priority order
2. **ML Classification (PhpMlIntentClassifier):** Optional Naive Bayes trained on `chat_training_examples`
   - Enabled via `CHATBOT_ML_ENABLED=true`
   - Minimum confidence: 0.70
   - Falls back to rules if ML confidence < threshold

**Intent Categories (50+):**
- `greeting`, `thanks`, `farewell`
- `service_search`, `service_overview`, `service_fees`, `service_requirements`, `service_steps`, `service_duration`, `service_location`, `service_online_link`
- `water_schedule`, `water_schedule_today`, `water_schedule_next`, `water_area_search`
- `jobs_open`, `latest_jobs`, `job_deadline`
- `latest_news`, `news_search`
- `latest_announcements`
- `latest_council_decisions`, `council_decision_search`
- `facilities_list`, `engineering_offices_list`, `council_members_list`
- `municipality_contact`, `municipality_phone`, `municipality_email`, `municipality_address`, `municipality_working_hours`, `municipality_about`
- `departments_list`
- `create_complaint`, `complaint_tracking`
- `complaint_category_selection`, `complaint_department_selection`
- `cancel`, `back`, `unknown`
- And more...

### Service Search (SmartServiceSearch)

When intent is unknown, the chatbot performs smart service search:
1. Loads all service documents (cached 1 hour)
2. Scores via `ServiceSearchScorer` with 13 weighted signals:
   - Exact official name (1.00), exact phrase (0.98), exact alias (0.95)
   - Contained name (0.90), contained phrase (0.86), contained alias (0.82)
   - Keyword exact (0.72), token overlap (0.65)
   - Citizen expression exact (0.88), contained (0.78)
   - Description overlap (0.40), category overlap (0.25)
   - Context boost (+0.08), priority boost (+0.05)
   - Typo tolerance via `ArabicTypoMatcher` (Levenshtein ≤ 0.75)
3. **Auto-select** if score ≥ 0.88 and gap ≥ 0.15
4. **Clarification** if score ≥ 0.55 and multiple candidates

### Conversation State

Tracked in `chatbot_conversations.metadata` JSON column:

| State | Description |
|-------|-------------|
| `Normal` | Default state |
| `WaitingForClarification` | Multiple matches, waiting for selection |
| `WaitingForSelection` | Waiting for numbered selection |
| `WaitingForServiceSelection` | Service browsing |
| `WaitingForTrackingNumber` | Complaint tracking |
| `WorkflowCollectingData` | Multi-step form in progress |
| `WorkflowConfirming` | Confirming workflow data |
| `WorkflowInterrupting` | Workflow interrupted by domain switch |

### Knowledge Sources

All data comes from the database — zero hardcoded content:
- `electronic_services` + `service_categories` + `service_search_terms` — Services
- `departments` — Department information
- `municipalities` — Municipality profile
- `municipality_contacts` — Contact information
- `water_areas` + `water_schedules` — Water schedules
- `job_offers` — Job listings
- `news_items` — News
- `announcements` — Announcements
- `council_decisions` — Council decisions
- `public_facilities` — Facilities
- `engineering_offices` — Engineering offices
- `council_members` — Council members
- `chatbot_service_aliases` — Service aliases for citizen-friendly names

### Configuration (`config/chatbot.php`)

| Setting | Default | Purpose |
|---------|---------|---------|
| `CHATBOT_ENABLED` | false | Enable/disable chatbot |
| `CHATBOT_ML_ENABLED` | false | Enable ML classification |
| `CHATBOT_RULE_BASED_ENABLED` | true | Enable rule-based detection |
| `CHATBOT_CONTEXT_TTL` | 1200 (20min) | Conversation context TTL |
| `CHATBOT_SESSION_TTL` | 3600 (1hr) | Session TTL |
| `CHATBOT_MAX_MESSAGE_LENGTH` | 500 | Max message length |
| `CHATBOT_RATE_LIMIT_MAX` | 30 | Max messages per minute |
| `CHATBOT_SEARCH_LIMIT` | 5 | Max search results |
| `CHATBOT_SMART_SEARCH_ENABLED` | true | Enable smart search |
| `CHATBOT_STORE_MESSAGES` | true | Store messages in DB |
| `CHATBOT_ANALYTICS_ENABLED` | true | Enable analytics |
| `CHATBOT_PUBLIC_WIDGET_ENABLED` | true | Show widget on public pages |
| `CHATBOT_TRACE` | (env) | Enable JSONL tracing |

### Chatbot Cache TTLs

| Cache Key | TTL |
|-----------|-----|
| `municipality_info` | 86400 (24hr) |
| `departments` | 3600 (1hr) |
| `facilities` | 3600 (1hr) |
| `engineering_offices` | 3600 (1hr) |
| `council_members` | 3600 (1hr) |
| `jobs` | 900 (15min) |
| `news` | 600 (10min) |
| `announcements` | 600 (10min) |
| `council_decisions` | 900 (15min) |
| `water_schedules` | 300 (5min) |

---

# 13. AUTHENTICATION & AUTHORIZATION

## 13.1 Authentication

**Login:** `app/Livewire/Auth/Login.php`
- Email + password authentication
- Rate limiting: 5 attempts per minute (`throttle:login`)
- Account lockout: `login_attempts` counter, `locked_until` timestamp
- Session-based authentication (database driver)

**Password Reset:** `app/Livewire/Auth/ForgotPassword.php` + `ResetPassword.php`
- Token-based via `password_reset_tokens` table

**Two-Factor:** Fields exist in `users` table (`two_factor_secret`, `two_factor_enabled`) but **not implemented in UI**.

## 13.2 Authorization (Spatie Permission)

**Guard:** `web` (default)

**Roles (5):**

| Role | Permissions | Purpose |
|------|------------|---------|
| **Super Admin** | All 170+ permissions | Full system access |
| **Admin** | ~100 permissions | Most management features |
| **Department Manager** | ~15 permissions | Limited department access |
| **Employee** | ~10 permissions | Basic access |
| **Citizen** | (none) | Public website only |

**Permission Groups (20 modules):**
- users, roles, departments, news, services, complaints, projects, tenders, settings, system, service_categories, engineering_offices, electronic_services, homepage, page_carousels, water_schedule, jobs, announcements, public_facilities, open_data, municipality, chatbot

**Middleware:** `CheckPermission` (`app/Http/Middleware/CheckPermission.php`)
- Registered as `permission` in `bootstrap/app.php`
- Usage: `Route::middleware('permission:news.create')`
- Checks `$user->can($permission)` via Spatie

---

# 14. ADMIN DASHBOARD

## 14.1 Dashboard Structure

```
ADMIN DASHBOARD
├── Executive Dashboard (/dashboard)
│   ├── Statistics cards
│   └── Recent activity
├── Homepage Management (/homepage)
│   ├── Dashboard (/homepage)
│   ├── Settings (/homepage/settings)
│   ├── Slides (/homepage/slides)
│   ├── Sections (/homepage/sections)
│   ├── Quick Links (/homepage/quick-links)
│   └── Statistics (/homepage/statistics)
├── Content Management
│   ├── News (/dashboard/news)
│   ├── Projects (/dashboard/projects)
│   ├── Announcements (/dashboard/announcements)
│   ├── Tenders (/dashboard/tenders)
│   ├── Jobs (/dashboard/jobs)
│   └── Open Data (/dashboard/open-data)
├── Services Management
│   ├── Service Categories (/electronic-services/categories)
│   ├── Electronic Services (/electronic-services/services)
│   ├── Service Analytics (/electronic-services/analytics)
│   └── Page Carousels (/page-carousels)
├── Municipality
│   ├── General Info (/dashboard/municipality/general-info)
│   ├── Contacts (/dashboard/municipality/contacts)
│   ├── Social Platforms (/dashboard/municipality/social)
│   ├── External Platforms (/dashboard/municipality/platforms)
│   ├── Custom Fields (/dashboard/municipality/custom-fields)
│   ├── Media (/dashboard/municipality/media)
│   ├── Business Hours (/dashboard/municipality/business-hours)
│   ├── Emergency Contacts (/dashboard/municipality/emergency-contacts)
│   ├── Council Members (/dashboard/municipality/council-members)
│   └── Council Decisions (/dashboard/municipality/council-decisions)
├── Operations
│   ├── Departments (/dashboard/departments)
│   ├── Engineering Offices (/dashboard/engineering-offices)
│   ├── Public Facilities (/dashboard/facilities)
│   ├── Water Schedule (/dashboard/water-schedule)
│   └── Complaints (/dashboard/complaints)
├── Chatbot
│   ├── Dashboard (/dashboard/chatbot)
│   ├── Unknown Questions (/dashboard/chatbot/unknown-questions)
│   ├── Performance (/dashboard/chatbot/performance)
│   └── Search Terms (/dashboard/chatbot/search-terms)
├── System
│   ├── Users (/users)
│   ├── Roles (/roles)
│   ├── Reports (/reports)
│   └── Settings (/settings)
└── Auth
    ├── Login (/login)
    ├── Change Password (/change-password)
    └── Logout (POST /logout)
```

---

# 15. PERMISSION MATRIX

| Module | Permission | Super Admin | Admin | Dept Manager | Employee |
|--------|-----------|:-----------:|:-----:|:------------:|:--------:|
| Users | view users | ✓ | ✓ | | |
| Users | create users | ✓ | | | |
| Roles | view roles | ✓ | ✓ | | |
| Departments | departments.view | ✓ | ✓ | ✓ | |
| Departments | departments.create | ✓ | ✓ | | |
| News | news.view | ✓ | ✓ | ✓ | |
| News | news.create | ✓ | ✓ | ✓ | |
| Services | view services | ✓ | ✓ | ✓ | ✓ |
| Complaints | complaints.view | ✓ | ✓ | ✓ | ✓ |
| Complaints | complaints.create | ✓ | ✓ | ✓ | ✓ |
| Projects | projects.view | ✓ | ✓ | ✓ | |
| Tenders | tenders.view | ✓ | ✓ | ✓ | |
| Homepage | homepage.view | ✓ | | | |
| Homepage | homepage.update | ✓ | | | |
| Chatbot | chatbot.view | ✓ | ✓ | | |
| Municipality | municipality.view | ✓ | | | |

---

# 16. CACHE SYSTEM

## 16.1 Cache Configuration

- **Driver:** `database` (default, configurable via `CACHE_STORE`)
- **Session:** `database` driver
- **Queue:** `database` driver

## 16.2 Cache Keys

| Key | Source | TTL | Invalidation |
|-----|--------|-----|-------------|
| `homepage.public.data` | `HomepageService` | 3600s | On any homepage content change |
| `page-carousel:{key}` | `PublicPageCarousel` | 3600s | On carousel slide save |
| Chatbot domain caches | Various services | 300-86400s | Per-domain TTL |
| Spatie Permission cache | `config/permission.php` | 24hr | On permission changes |

## 16.3 Cache Invalidation

Cache is invalidated in Livewire component methods when content is saved/deleted. For example:
- `CouncilMember` model clears `homepage.public.data` and `page-carousel:council-members` on `saved`/`deleted` events
- `CouncilDecision` model clears `homepage.public.data` on `saved`/`deleted`

## 16.4 Known Issue: `__PHP_Incomplete_Class`

**NOT FOUND IN CODEBASE** — No evidence of serialization issues. All cached data is either primitive types or arrays. Models are not cached directly; only their query results (as arrays/collections) are cached.

---

# 17. QUEUES & JOBS

- **Queue Connection:** `database` (default)
- **Queue Tables:** `jobs`, `job_batches`, `failed_jobs`
- **Custom Jobs:** No custom job classes found in `app/Jobs/`
- **Scheduled Tasks:** No custom scheduled commands found in `routes/console.php` (only the default `inspire` command)
- **Queue Workers:** Run via `composer dev` which includes `php artisan queue:listen --tries=1`

The system currently runs synchronously for most operations. Queue infrastructure exists but is minimally used.

---

# 18. EXTERNAL INTEGRATIONS

| Integration | Purpose | Configuration | Files |
|------------|---------|--------------|-------|
| **PaleXPand Portal** | E-government service gateway | `portal_url` field in `electronic_services` table | `electronic_services.portal_url` |
| **Google Fonts** | Alexandria Arabic font | CSS `@import` in `resources/css/app.css` | `resources/css/app.css` |

**No external AI/ML APIs.** No OpenAI, no Google Cloud, no Azure. The chatbot runs entirely on PHP with rule-based + optional PHP-ML (Naive Bayes) classification.

**No SMS integration.** No email sending configured (MAIL_MAILER=log).

**No payment integration.**

**No social media API integration.** Social links are display-only.

---

# 19. FRONTEND ARCHITECTURE

## 19.1 Build System

- **Vite 8** with `laravel-vite-plugin` and `@tailwindcss/vite`
- **Entry Points:** `resources/css/app.css`, `resources/js/app.js`
- **Output:** `public/build/`

## 19.2 CSS (Tailwind CSS 4)

Configuration in `resources/css/app.css`:
- **Font:** Alexandria (Google Fonts, Arabic)
- **Primary Color:** `#176B32` (municipality green)
- **Secondary Color:** `#17243A` (dark navy)
- **Accent Color:** `#C8A85A` (gold)
- **Background:** `#FAF9F5` (warm white)
- **Surface:** `#FFFFFF`

Design tokens defined via `@theme` directive with 50+ CSS custom properties including:
- Radius tokens (sm, md, lg, xl, 2xl, 3xl)
- Shadow tokens (sm, md, lg, xl)
- Container sizes
- Typography scale

## 19.3 JavaScript

- **Alpine.js** (via Livewire) for reactive UI
- **Custom SVG Icons** (`resources/js/icons.js`) — 200+ inline SVG icons
- **`replaceIcons()`** function for dynamic icon rendering
- **`showToast()`** global function for notifications

## 19.4 Responsive System

- **Mobile:** Single column, hamburger nav, reduced carousel slides
- **Tablet:** 2-column layouts
- **Desktop:** Full sidebar, 3-4 column grids
- **Reduced Motion:** Supported via `@media (prefers-reduced-motion)`

## 19.5 RTL

Full RTL support — the interface is Arabic-first. The `direction` property on carousels defaults to `rtl`.

## 19.6 Dark Mode

**NOT IMPLEMENTED.** No dark mode support found.

---

# 20. TESTING

## 20.1 Test Infrastructure

- **Framework:** Pest PHP 4.7
- **Database:** SQLite `:memory:` (configured in `phpunit.xml`)
- **Cache:** Array driver
- **Session:** Array driver
- **Mail:** Array driver

## 20.2 Test Files

**51 Feature Tests:**
- Homepage (4), Chatbot (16), Authentication (3), WaterSchedule (3), Tenders (2), Projects (2), News (2), OpenData (2), PageCarousel (2), Complaints (2), Departments (1), Jobs (1), Announcements (1), ElectronicServices (1), PublicFacilities (1), EngineeringOffices (1), Municipality (2), RepositoryBindings (1), BladeViews (1)

**48 Unit Tests:**
- Chatbot (18), CitizenWorkflows (14), Authentication (3), Municipality (2), ContactRequests (1), Console (1)

## 20.3 Running Tests

```bash
composer test          # lint + types:check + pest tests
php artisan test       # all tests
php artisan test --filter ChatbotTest  # specific test
```

## 20.4 Largest Test File

`tests/Feature/Chatbot/ChatbotTest.php` — 1931 lines, comprehensive chatbot testing

---

# 21. DEPLOYMENT

## 21.1 Setup

```bash
composer setup
# Runs: composer install, key:generate, migrate, npm install, npm run build
```

## 21.2 Development

```bash
composer dev
# Runs concurrently: php artisan serve, queue:listen, npm run dev
```

## 21.3 Production Build

```bash
npm run build         # Vite production build
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## 21.4 Environment Variables

| Variable | Used By | Purpose | Required |
|----------|---------|---------|----------|
| `APP_NAME` | App | Application name ("بلدية إذنا") | Yes |
| `APP_ENV` | App | Environment (local/production) | Yes |
| `APP_KEY` | App | Encryption key | Yes |
| `APP_DEBUG` | App | Debug mode | Yes |
| `APP_URL` | App | Application URL | Yes |
| `APP_LOCALE` | App | Locale (ar) | Yes |
| `DB_CONNECTION` | Database | Database driver (sqlite) | Yes |
| `SESSION_DRIVER` | Session | Session driver (database) | Yes |
| `QUEUE_CONNECTION` | Queue | Queue driver (database) | Yes |
| `CACHE_STORE` | Cache | Cache driver (database) | Yes |
| `MAIL_MAILER` | Mail | Mail driver (log) | Yes |
| `MAIL_FROM_ADDRESS` | Mail | From address | Yes |
| `MAIL_FROM_NAME` | Mail | From name | Yes |
| `CHATBOT_ENABLED` | Chatbot | Enable chatbot | No (default: false) |
| `CHATBOT_ML_ENABLED` | Chatbot | Enable ML classification | No (default: false) |
| `CHATBOT_RULE_BASED_ENABLED` | Chatbot | Enable rule-based | No (default: true) |
| `CHATBOT_CONTEXT_TTL` | Chatbot | Context TTL (seconds) | No (default: 1200) |
| `CHATBOT_SESSION_TTL` | Chatbot | Session TTL | No (default: 3600) |
| `CHATBOT_MAX_MESSAGE_LENGTH` | Chatbot | Max message length | No (default: 500) |
| `CHATBOT_RATE_LIMIT_MAX` | Chatbot | Rate limit | No (default: 30) |
| `CHATBOT_SEARCH_LIMIT` | Chatbot | Search result limit | No (default: 5) |
| `CHATBOT_SMART_SEARCH_ENABLED` | Chatbot | Smart search | No (default: true) |
| `CHATBOT_STORE_MESSAGES` | Chatbot | Store messages | No (default: true) |
| `CHATBOT_ANALYTICS_ENABLED` | Chatbot | Analytics | No (default: true) |
| `CHATBOT_PUBLIC_WIDGET_ENABLED` | Chatbot | Show widget | No (default: true) |
| `CHATBOT_TRACE` | Chatbot | Enable tracing | No |
| `VITE_APP_NAME` | Vite | Frontend app name | No |

---

# 22. SEEDERS

| Seeder | Purpose | Environment |
|--------|---------|-------------|
| `RolePermissionSeeder` | Creates all permissions and 5 roles | All |
| `SuperAdminSeeder` | Creates admin@idhna.ps with Super Admin role | All |
| `PageCarouselSeeder` | Seeds carousel slides for all pages | All |
| `IdnaMagazineSeeder` | Seeds news articles | Non-production |
| `ElectronicServicesIdnaSeeder` | Seeds 6 categories, ~20 services | Non-production |
| `EngineeringOfficeSeeder` | Seeds engineering offices | Non-production |
| `TenderSeeder` | Seeds tenders | Non-production |
| `ComplaintSeeder` | Seeds complaints | Non-production |
| `PublicFacilitySeeder` | Seeds facilities | Non-production |
| `HomepageSeeder` | Seeds homepage settings, slides, sections, links, stats | Manual |
| `ChatbotTrainingSeeder` | Seeds 300+ training examples for 30+ intents | Manual |
| `DepartmentSeeder` | Seeds 8 departments | Manual |
| `MunicipalityDemoSeeder` | Seeds full municipality profile | Manual |
| `AnnouncementSeeder` | Seeds announcements | Manual |
| `CouncilDecisionSeeder` | Seeds council decisions | Manual |
| `CouncilMemberSeeder` | Seeds council members | Manual |
| `JobSeeder` | Seeds job listings | Manual |
| `NewsSeeder` | Seeds news items | Manual |
| `ProjectSeeder` | Seeds projects | Manual |
| `WaterScheduleSeeder` | Seeds water schedule | Manual |

---

# 23. HOW DO I... (Practical Guide)

## How to add a news item?
1. Login → Dashboard → News (`/dashboard/news`)
2. Click "Create" → Fill: title (Arabic), slug (auto-generated), category, summary, content (rich text), cover image, mobile image
3. Set status to "published" or "draft"
4. Toggle "featured" if needed
5. Save — appears on homepage if featured, and on `/news`

## How to edit a news item?
1. Dashboard → News → Click edit on the item
2. Modify fields → Save

## How to delete a news item?
1. Dashboard → News → Click delete on the item
2. Confirm — soft deleted (can be restored)

## How to add an announcement?
1. Dashboard → Announcements → Create
2. Fill: title, type (general/urgent/maintenance), priority, content, images
3. Set publish date, expiry date
4. Save

## How to add a council decision?
1. Dashboard → Municipality → Council Decisions → Create
2. Fill: decision number, title, type, status, content, session number
3. Upload attachment (PDF)
4. Set decision date
5. Save

## How to add a project?
1. Dashboard → Projects → Create
2. Fill: name (Arabic + English), category, status, summary, description
3. Upload cover image, gallery
4. Set budget, dates, contractor, funding entity
5. Set implementation percentage
6. Save

## How to add a service?
1. Dashboard → Electronic Services → Categories → Create category first
2. Dashboard → Electronic Services → Services → Create
3. Fill: name, category, department, summary, description
4. Add requirements (JSON array), steps, fees, documents
5. Set portal_url (PaleXPand link)
6. Add search terms for chatbot (aliases, keywords, citizen expressions)
7. Save

## How to add a department?
1. Dashboard → Departments → Create
2. Fill: name, description, manager info, contact details
3. Upload cover image
4. Set visibility and featured status
5. Save

## How to add a job?
1. Dashboard → Jobs → Create
2. Fill: title, employment type, location, salary, description
3. Add requirements, responsibilities, benefits, required documents
4. Set application method and deadline
5. Save

## How to add a facility?
1. Dashboard → Facilities → Categories → Create category
2. Dashboard → Facilities → Create
3. Fill: name, category, description, address, contact info
4. Upload cover image, gallery
5. Add services, features, rules (JSON arrays)
6. Save

## How to add an engineering office?
1. Dashboard → Engineering Offices → Create
2. Fill: office name, engineer name, license number, contact info
3. Add specializations (JSON array)
4. Save

## How to add a water area?
1. Dashboard → Water Schedule → Areas → Create
2. Fill: name, description
3. Save

## How to add a water schedule?
1. Dashboard → Water Schedule → Select area and date
2. Add time slots with status (available/not available)
3. Save

## How to change the hero image?
1. Dashboard → Homepage → Slides
2. Edit the slide → Upload new image
3. Save — image stored in `storage/app/public/homepage/`

## How to change a section title?
1. Dashboard → Homepage → Sections
2. Edit the section → Change title/subtitle
3. Save

## How to hide a section?
1. Dashboard → Homepage → Sections
2. Toggle "Enabled" off for the section
3. Save — section disappears from homepage

## How to reorder sections?
1. Dashboard → Homepage → Sections
2. Drag to reorder or change sort_order values
3. Save

## How to add a carousel slide?
1. Dashboard → Page Carousels → Create
2. Select page_key (home, services, departments, etc.)
3. Upload desktop and mobile images
4. Fill title, description, button text, button URL
5. Save

## How to configure a carousel?
1. Dashboard → Page Carousels → Config
2. Edit the carousel configuration
3. Set autoplay, delay, slides count, navigation, pagination
4. Save

## How to add a quick link?
1. Dashboard → Homepage → Quick Links → Create
2. Fill: title, description, icon, URL
3. Set type and external flag
4. Save

## How to add a statistic?
1. Dashboard → Homepage → Statistics → Create
2. Fill: label (e.g., "عدد السكان"), value, suffix, icon
3. Save — displays as animated counter on homepage

## How to add a user?
1. Dashboard → Users → Create
2. Fill: name, email, password
3. Assign role
4. Save

## How to add a role?
1. Dashboard → Roles → Create
2. Fill: name
3. Select permissions from matrix
4. Save

## How to add a chatbot knowledge source?
1. Add/update electronic service with full details
2. Dashboard → Chatbot → Search Terms
3. Add search terms (aliases, keywords, phrases, citizen expressions)
4. Clear chatbot cache
5. Test via `/chatbot`

## How to add a contact?
1. Dashboard → Municipality → Contacts → Create
2. Fill: type (phone/email/address), label, value
3. Save

## How to add a social platform?
1. Dashboard → Municipality → Social → Create
2. Fill: name, icon, URL
3. Save

## How to add an open dataset?
1. Dashboard → Open Data → Create
2. Fill: title, type, category, description
3. Upload file or set external URL
4. Save

---

# 24. HOW TO EXTEND THE SYSTEM

## How to add a new Feature?

1. Create domain: `app/Domains/FeatureName/`
2. Add subdirectories: `Actions/`, `Contracts/`, `DTOs/`, `Enums/`, `Models/`, `Repositories/`, `Services/`, `Providers/`
3. Create Model with `$fillable`, `casts()`, relationships
4. Create migration in `database/migrations/`
5. Create Repository (implements contract, readonly, uses DB::transaction)
6. Create ServiceProvider (binds interface to implementation)
7. Register in `AppServiceProvider`
8. Create Livewire components (admin + public)
9. Add routes in `routes/web.php`
10. Add permissions in `config/permissions.php`
11. Add to `RolePermissionSeeder`
12. Create Blade views in `resources/views/livewire/`
13. Add tests

## How to add a new Domain?

```bash
# Create directory structure
mkdir -p app/Domains/NewDomain/{Actions,Contracts,DTOs,Enums,Models,Repositories,Services,Providers}
```

Follow existing patterns — every file must have `declare(strict_types=1)` and use `final` classes.

## How to add a new Model?

1. Create `app/Domains/Domain/Models/ModelName.php`
2. Extend `Illuminate\Database\Eloquent\Model`
3. Add `use HasFactory, SoftDeletes`
4. Define `$fillable`, `casts()`, relationships
5. Create migration
6. Add to repository
7. Register in ServiceProvider

## How to add a new Migration?

```bash
php artisan make:migration create_new_table --create=new_table
```

Follow conventions: `declare(strict_types=1)`, proper types, foreign keys.

## How to add a new Livewire Component?

1. Create `app/Livewire/Domain/ComponentName.php`
2. Create view `resources/views/livewire/domain/component-name.blade.php`
3. Add route in `routes/web.php`
4. Add permission middleware if admin

## How to add a new API Endpoint?

**NOT APPLICABLE** — The system has no API routes. All communication is via Livewire.

## How to add a new Carousel?

1. Add slides to `homepage_slides` table with new `page_key`
2. Add configuration in `carousel_configurations`
3. Use `<livewire:public-page-carousel :pageKey="'new-key'" />` in Blade

## How to add a new Chatbot Intent?

1. Add case to `ChatbotIntent` enum
2. Add training examples via `ChatbotTrainingSeeder` or admin dashboard
3. Create handler implementing `ChatResponseHandlerInterface`
4. Register in `ChatbotServiceProvider`
5. Add to `ChatResponseHandlerRegistry`
6. Test

---

# 25. DEPENDENCY MAP

```
Homepage
 ├── HomepageService (caches all data)
 │    ├── HomepageSetting (DB: homepage_settings)
 │    ├── HomepageSlide (DB: homepage_slides WHERE page_key='home')
 │    ├── HomepageSection (DB: homepage_sections)
 │    ├── HomepageQuickLink (DB: homepage_quick_links)
 │    └── HomepageStatistic (DB: homepage_statistics)
 ├── PublicHomePage (Livewire)
 │    └── Blade: public-home-page.blade.php
 └── PublicPageCarousel (Livewire, shared)

Services Portal
 ├── PublicServicesPortal (Livewire)
 │    ├── ElectronicService::published() (DB: electronic_services)
 │    ├── ServiceCategory::where('is_public', 1) (DB: service_categories)
 │    └── Carousel (via PublicPageCarousel)
 └── PublicServiceDetail (Livewire)
      ├── ElectronicService::where('slug', $slug)
      ├── ServiceView (view tracking)
      └── ServicePortalClick (click tracking)

Chatbot
 ├── ChatbotPage / ChatbotWidget (Livewire)
 │    └── BaseChatbot trait
 ├── ProcessRuleBasedChatMessageAction (17-step pipeline)
 │    ├── ArabicTextNormalizer
 │    ├── ConversationContextService (DB: chatbot_conversations)
 │    ├── HybridIntentPredictor
 │    │    ├── RuleIntentDetector (30+ patterns)
 │    │    └── PhpMlIntentClassifier (optional ML)
 │    ├── ChatResponseHandlerRegistry → 57 Handlers
 │    ├── SmartServiceSearch
 │    │    ├── ServiceSearchScorer (13 signals)
 │    │    └── ArabicTypoMatcher (Levenshtein)
 │    ├── ClarificationResolver
 │    ├── GuidedServiceDiscoveryService
 │    ├── MunicipalityDomainRouter
 │    ├── ServicePropertyIntentDetector
 │    └── CitizenWorkflowEngine
 └── MunicipalityServiceQueryAdapter
      ├── ElectronicService (DB)
      ├── ServiceCategory (DB)
      └── ServiceSearchTerm (DB)

Admin Dashboard
 ├── ExecutiveDashboard (Livewire)
 │    └── DashboardRepository
 ├── All Admin CRUD Components
 │    └── Domain Actions → Repositories → Models → DB
 └── Chatbot Admin
      ├── ChatbotDashboard (analytics)
      ├── UnknownQuestionsManager
      ├── PerformanceMonitor
      └── SearchTermManager
```

---

# 26. CRITICAL FILES

| File | Why Critical | What Happens If Modified |
|------|-------------|------------------------|
| `routes/web.php` | All routes (140+) | Breaking any route breaks functionality |
| `app/Domains/Chatbot/Actions/ProcessRuleBasedChatMessageAction.php` | 17-step chatbot pipeline | Chatbot breaks completely |
| `app/Domains/Homepage/Services/HomepageService.php` | Homepage data orchestrator | Homepage shows no data |
| `app/Domains/Chatbot/Services/HybridIntentPredictor.php` | Intent classification | Wrong intent detection |
| `app/Domains/Chatbot/Services/SmartServiceSearch.php` | Service search scoring | Service lookup fails |
| `config/permissions.php` | All 170+ permissions | Authorization breaks |
| `database/seeders/RolePermissionSeeder.php` | Role setup | Permission matrix breaks |
| `app/Livewire/Chatbot/BaseChatbot.php` | Chatbot UI logic | Chatbot interface breaks |
| `app/Providers/AppServiceProvider.php` | Registers all 23 domains | System won't boot |
| `resources/css/app.css` | Design tokens + Tailwind config | Entire UI breaks |
| `resources/js/app.js` | Icons + toasts + Livewire hooks | Icons disappear, toasts broken |
| `resources/js/icons.js` | 200+ SVG icons | All icons disappear |
| `app/Domains/Chatbot/Services/ArabicTextNormalizer.php` | Arabic text normalization | Chatbot can't understand Arabic |
| `app/Domains/Chatbot/Services/ConversationContextService.php` | Session state management | Conversation loses context |
| `config/chatbot.php` | Chatbot configuration | Chatbot behavior changes |

---

# 27. DANGEROUS AREAS

| Area | Risk | Why |
|------|------|-----|
| Database migrations | High | Breaking schema affects entire system |
| Chatbot pipeline | High | 2200-line action, complex state management |
| Permission system | High | One wrong permission = security hole |
| Homepage cache | Medium | Stale cache = wrong content displayed |
| Arabic text normalizer | Medium | Wrong normalization = chatbot misunderstands |
| Carousel system | Medium | Multiple page_key dependencies |
| Seeders | Low | Only affects fresh installs or dev |
| Blade templates | Low | UI-only changes, no data risk |
| CSS/JS assets | Low | Visual only, no data risk |

---

# 28. BUGS & ISSUES

### Critical
**NOT FOUND** — No critical bugs found in codebase analysis.

### High
| Problem | Location | Cause | Impact | Fix |
|---------|----------|-------|--------|-----|
| No API routes | `routes/web.php` | Architecture decision | Mobile apps can't integrate | Add `routes/api.php` if needed |
| No email sending | `.env.example` | MAIL_MAILER=log | No notifications sent | Configure real mail driver |
| No queue workers in production | `composer dev` | Dev-only setup | Jobs may not process | Set up queue worker |

### Medium
| Problem | Location | Cause | Impact | Fix |
|---------|----------|-------|--------|-----|
| No dark mode | `resources/css/app.css` | Not implemented | Limited accessibility | Add dark mode support |
| No image optimization | Livewire uploads | No intervention/Imagine | Large images served | Add image processing |
| No automatic cache invalidation | Various models | Manual invalidation only | Stale data possible | Add model observers |
| Debug routes in production | `routes/web.php` | `can:access panel` middleware | Protected by permission | Remove or add env check |
| Temporary debug files in root | Root directory | Debug scripts | Cluttered repo | Remove debug_*.php, trace_*.php |

### Low
| Problem | Location | Cause | Impact | Fix |
|---------|----------|-------|--------|-----|
| Minimal README | `README.md` | Only "idna-org" | No onboarding docs | Expand README |
| No `.env` validation | Config | No required check | Silent failures | Add env validation |
| Two-factor not in UI | `users` table | Fields exist but unused | 2FA not functional | Implement UI |

---

# 29. TECHNICAL DEBT

| Issue | Location | Impact |
|-------|----------|--------|
| Debug files in root | `debug_*.php`, `trace_*.php`, `repro_*.php` | Repo clutter |
| Temporary output files | `out.txt`, `out2.txt`, `result.txt` | Repo clutter |
| No custom Artisan commands | `app/Console/Commands/` empty | Missing automation |
| No queue jobs | `app/Jobs/` doesn't exist | Synchronous processing only |
| No model observers | No `app/Observers/` | Manual cache invalidation |
| No form request classes | No `FormRequest` classes | Validation in Livewire |
| Minimal Blade components | Only ~30 components | Some duplication |
| No API layer | No `routes/api.php` | No mobile/external integration |
| No event system | No custom events/listeners | Tight coupling possible |
| Mixed permission naming | `view users` vs `news.view` | Inconsistent convention |

---

# 30. FEATURE MATRIX

| Feature | Frontend | Backend | Domain | Model | DB | Admin | Chatbot |
|---------|----------|---------|--------|-------|----|-------|---------|
| Homepage | PublicHomePage | HomepageService | Homepage | HomepageSetting, HomepageSlide, HomepageSection, HomepageQuickLink, HomepageStatistic | 6 tables | HomepageDashboard + 6 forms | Yes (municipality info) |
| News | PublicNewsIndex, PublicNewsShow | — | News | NewsItem | news_items | NewsIndex, NewsForm | Yes (latest_news) |
| Projects | PublicProjectsIndex, PublicProjectShow | — | Projects | Project | projects | ProjectsIndex, ProjectForm | Yes (via chatbot) |
| Services | PublicServicesPortal, PublicServiceDetail | MunicipalityServiceQueryAdapter | ElectronicServices | ElectronicService, ServiceCategory | 5 tables | 7 admin components | Yes (service_search) |
| Complaints | PublicComplaintForm, PublicComplaintTracking | CitizenWorkflowEngine | Complaints | Complaint | complaints | ComplaintsIndex, ComplaintForm | Yes (create_complaint) |
| Tenders | PublicTendersIndex, PublicTenderShow | — | Tenders | Tender | tenders | TendersIndex, TenderForm | No |
| Jobs | PublicJobsIndex, PublicJobShow | — | Jobs | Job | job_offers | JobsIndex, JobForm | Yes (jobs_open) |
| Announcements | PublicAnnouncementsIndex, PublicAnnouncementShow | — | Announcements | Announcement | announcements | AnnouncementsIndex, AnnouncementForm | Yes (latest_announcements) |
| Facilities | PublicFacilitiesIndex, PublicFacilityShow | — | PublicFacilities | Facility, FacilityCategory | 2 tables | 4 admin components | Yes (facilities_list) |
| Departments | PublicDepartmentsPortal, PublicDepartmentShow | — | Department | Department | departments | 3 admin components | Yes (departments_list) |
| Engineering Offices | PublicEngineeringOfficesIndex, PublicEngineeringOfficeShow | — | EngineeringOffices | EngineeringOffice | engineering_offices | 3 admin components | Yes (engineering_offices_list) |
| Council | PublicCouncilMembersPortal, PublicCouncilMemberProfile, PublicCouncilDecisionsIndex, PublicCouncilDecisionShow | — | Municipality | CouncilMember, CouncilDecision | 2 tables | 6 admin components | Yes (council_members_list) |
| Water Schedule | PublicWaterSchedule | — | WaterSchedule | WaterArea, WaterSchedule, WaterMaintenance | 3 tables | 6 admin components | Yes (water_schedule) |
| Open Data | OpenDataIndex | — | OpenData | OpenDataset | open_datasets | 2 admin components | No |
| Chatbot | ChatbotPage, ChatbotWidget | ProcessRuleBasedChatMessageAction | Chatbot | 7 models | 7 tables | 4 admin components | Core |
| CMS | All public views | HomepageService | Homepage | All homepage models | 6 tables | HomepageDashboard | Partial |
| Carousels | PublicPageCarousel | — | Homepage | HomepageSlide, CarouselConfiguration | 2 tables | 3 admin components | No |
| Auth | Login, ForgotPassword, ResetPassword, ChangePassword | LogoutAction | Authentication | User, LoginActivity | 2 tables | UserIndex, RoleIndex | No |

---

# 31. ANALYSIS CONFIDENCE

## CONFIRMED (Found in code)
- All 23 domains and their structure
- All 64 database tables and their schemas
- All 97 Livewire components
- All 140+ routes
- All 50+ models and relationships
- All 170+ permissions
- All 5 roles
- Chatbot 17-step processing pipeline
- Smart service search with 13 signals
- Arabic text normalization
- Rule-based + optional ML intent prediction
- Conversation state management
- CMS-driven homepage
- Carousel system with page_key scoping
- Image storage in `storage/app/public/`
- Database cache driver
- Pest testing with SQLite in-memory
- Tailwind CSS 4 with Alexandria font
- 200+ custom SVG icons
- Spatie Permission integration

## INFERRED (Supported by code)
- Production deployment likely uses standard Laravel deployment (no Docker/K8s config found)
- Queue workers may not be running in production (no supervisor config found)
- Email notifications likely not configured (MAIL_MAILER=log in .env.example)
- No CDN for assets (standard public/build)

## NOT FOUND
- Docker/containerization configuration
- Kubernetes manifests
- CI/CD deployment pipeline (only lint/test CI)
- API documentation (no API routes exist)
- Load testing configuration
- Backup strategy documentation
- Monitoring/alerting setup
- Log aggregation
- Error tracking (Sentry, etc.)

## NEEDS VERIFICATION
- Production database configuration (SQLite vs MySQL/PostgreSQL)
- Production cache driver (database vs Redis)
- Production queue driver
- SSL/HTTPS configuration
- Domain/hosting setup
- PaleXPand portal integration status
- Chatbot ML model training status
- Actual seed data in production

---

# 32. PROJECT EXPLAINED LIKE I'M NEW

1. **The citizen opens the website** (e.g., `idhna.ps`). The homepage loads.

2. **The homepage is built by `PublicHomePage` Livewire component.** It calls `HomepageService::getAll()` which loads settings, slides, sections, quick links, and statistics from the database. Everything is cached for 1 hour.

3. **Each section on the homepage** (hero, services, news, projects, etc.) is controlled by the `homepage_sections` table. Admins can enable/disable sections, reorder them, and set titles from the dashboard.

4. **The hero carousel** shows slides from `homepage_slides` where `page_key='home'`. Each slide has a title, description, image, and button. The carousel behavior (autoplay, speed, navigation) is configured in `carousel_configurations`.

5. **When the citizen clicks a section** (e.g., "Services"), they go to `/services` which loads `PublicServicesPortal`. This shows all published services from `electronic_services` table, grouped by category from `service_categories`.

6. **When the citizen clicks a service**, they see full details including requirements, steps, fees, and a link to the Palestinian e-government portal (`palexpand.ps`) where they can actually apply.

7. **The chatbot** (bottom-right widget) lets citizens ask questions in Arabic. The chatbot normalizes Arabic text, predicts the intent (greeting, search, water schedule, etc.), and routes to one of 57 handlers. It knows about all services, departments, schedules, and more from the database.

8. **The admin logs in** at `/login` with email/password. They see the Executive Dashboard with statistics.

9. **The admin manages content** through the sidebar: News, Projects, Services, Jobs, Tenders, Announcements, Facilities, Departments, Engineering Offices, Council, Water Schedule, Open Data, Homepage, Chatbot, Users, Roles.

10. **Every CRUD operation** follows the same pattern: Livewire component → Domain Action → Repository → Model → Database. All changes are wrapped in `DB::transaction()`.

11. **Permissions** control who can do what. There are 170+ permissions across 20 modules. The `Super Admin` role has all permissions. Other roles have subsets.

12. **Cache** is used extensively. Homepage data, chatbot data, carousel slides — all cached. Cache is invalidated when content changes.

13. **The chatbot** is the most complex part. It processes every message through 17 steps: normalize text, load context, check for button clicks, check for workflows, detect greetings, detect thanks, resume workflows, process workflow input, resolve clarifications, detect domain switches, detect service property follow-ups, guide service discovery, search for services, and handle fallbacks.

---

# 33. EXECUTIVE SUMMARY

## What This System Actually Is

The Idhna Municipality Digital Portal is a **comprehensive municipal e-government platform** built for a Palestinian municipality in the Hebron Governorate. It serves as the single digital interface between the municipality and its ~45,000 citizens.

**The system has three major components:**

1. **Public Website** — An Arabic-first, RTL, responsive website providing citizens with access to municipal services, news, projects, jobs, tenders, complaints, facilities, departments, council information, water schedules, and open data. The design uses the municipality's brand colors (green #176B32, navy #17243A, gold #C8A85A) with the Alexandria Arabic font.

2. **Admin Dashboard** — A comprehensive CMS allowing municipality staff to manage all content through Livewire-based interfaces. Every piece of public content is database-driven and manageable from the dashboard. The permission system (170+ permissions, 5 roles) ensures appropriate access control.

3. **AI Chatbot** — A sophisticated Arabic NLP chatbot that helps citizens navigate services, submit complaints, check water schedules, and find information. It uses a rule-based + optional ML hybrid approach with 57 intent handlers, 13-signal service scoring, and conversation state management.

**The architecture is Domain-Driven Design** with 23 bounded contexts, each containing Actions, Contracts, DTOs, Enums, Models, Repositories, Services, and Providers. The system uses **zero traditional controllers** — everything is Livewire-based.

**The database has 64 tables** covering users, permissions, departments, services, news, projects, complaints, tenders, jobs, facilities, council, water schedules, chatbot data, homepage CMS, and analytics.

**The most complex subsystem is the chatbot**, with a 17-step message processing pipeline, Arabic text normalization, intent prediction (rule-based + optional Naive Bayes ML), smart service search with 13 weighted signals, conversation context management, and citizen workflow support.

**The system is production-ready** with 99 tests, CI/CD workflows for linting and testing, and a clear deployment path. However, it lacks some enterprise features like API routes, containerization, monitoring, and external integrations beyond the Palestinian e-government portal.

**Key technical decisions:**
- PHP 8.3+ with strict typing throughout
- Laravel 13 with Livewire 4 (no Inertia, no traditional controllers)
- Tailwind CSS 4 with custom design tokens
- SQLite default (suitable for the expected traffic)
- Database-driven cache, session, and queue
- No external AI/ML APIs — entirely self-contained
- Arabic-first design with full RTL support
- CMS-driven architecture where all content is database-managed

---

*End of SYSTEM_MASTER_DOCUMENTATION*
*Generated: 2026-08-31*
*Analysis performed by examining all source files in the repository.*
