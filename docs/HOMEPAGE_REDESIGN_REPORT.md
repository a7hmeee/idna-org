# Homepage Redesign Report — "Municipal Dignity" Layer
## Idna Municipality Public Homepage (بلدية إذنا)

**Generated:** August 9, 2026
**System:** Laravel 12 / Livewire 3 / Tailwind CSS v4 (Vite build)
**Status:** ✅ Build green — Smoke render green — Ready for visual QA

---

## Table of Contents

1. [Backup](#1-backup)
2. [Extracted Brand Colors](#2-extracted-brand-colors)
3. [Typography](#3-typography)
4. [Libraries](#4-libraries)
5. [Files Created & Modified](#5-files-created--modified)
6. [Sections](#6-sections)
7. [Animation System](#7-animation-system)
8. [Responsive Result](#8-responsive-result)
9. [Performance](#9-performance)
10. [Accessibility](#10-accessibility)
11. [Build Result](#11-build-result)
12. [Remaining Issues](#12-remaining-issues)
13. [Verification](#13-verification)

---

## 1. Backup

| Item | Path |
|------|------|
| Immutable backup of the pre-redesign homepage | `resources/views/backups/home-before-worldclass-redesign/` |
| Section views (services, departments, facilities, municipality-story, council-members, council-decisions, water-status, jobs, news, projects, tenders, partners, contact-cta, facebook-feed, quick-access) | preserved 1:1 under the backup folder |
| Chatbot widget + public-home-page originals | preserved under the backup folder |

The backup folder is **immutable by convention** — do not delete or edit it.

---

## 2. Extracted Brand Colors

All homepage colors derive from the municipality's **public logo** and existing tokens — no invented hues.

<table>
<tr><th>Role</th><th>Value</th><th>Source</th></tr>
<tr><td>Pine deep (darkest green)</td><td><code>#06281A</code></td><td>Derived from logo deep green</td></tr>
<tr><td>Pine dark</td><td><code>#0B3A24</code></td><td>Derived</td></tr>
<tr><td>Field green</td><td><code>#1E6F45</code></td><td>Derived secondary logo green</td></tr>
<tr><td>Crown green (logo mid)</td><td><code>#6BAA3B</code></td><td>Extracted from logo</td></tr>
<tr><td>Leaf green (logo light)</td><td><code>#A0CF93</code></td><td>Extracted from logo</td></tr>
<tr><td>Gold accent</td><td><code>#C8A85A</code></td><td>Pre-existing token <code>--color-accent</code></td></tr>
<tr><td>Primary</td><td><code>#176B32</code></td><td>Pre-existing token <code>--color-primary</code></td></tr>
<tr><td>Primary dark</td><td><code>#0F4F28</code></td><td>Pre-existing token <code>--color-primary-dark</code></td></tr>
</table>

New tokens added under `@theme` in `resources/css/app.css`:
`--color-pine-950/900/800/700/600`, `--color-field`, `--color-field-deep`, `--color-crown`, `--color-leaf`, `--color-leaf-light`, `--color-mist`, `--color-ink`, `--color-ink-soft`, `--font-display`.

No invented purple/pink/neon colors were used; the old blue accents were replaced with the green family.

---

## 3. Typography

- **Primary display & body font:** `Alexandria` (300–900) — loaded via Google Fonts `@import` (pre-existing), falls back to `ui-sans-serif, system-ui, sans-serif`.
- **Display headings** use `--font-display` with fluid `clamp()` sizing:
  - Hero H1: `clamp(36px, 6vw, 76px)`
  - Section H2: `clamp(26px, 3.2vw, 40px)`
  - Stat values: `clamp(26px, 2.6vw, 34px)` with `font-variant-numeric: tabular-nums`
- Body copy: 14–17px fluid; Arabic RTL layout preserved throughout.

**Known caveat:** Google Fonts `@import` must precede all rules — Vite emits a pre-existing CSS-order warning (see §12).

---

## 4. Libraries

| Library | Version | Status |
|---------|---------|--------|
| `gsap` | ^3.15.0 | Used (ScrollTrigger) |
| `lenis` | ^1.3.26 | Used (smooth scroll driver) |
| `swiper` | — | **Removed** — installed earlier but never used; dropped to avoid dead weight |
| `lucide` | ^0.468 | Used (icon morph system, pre-existing) |
| `tailwindcss` | ^4.0.7 | Core |
| `vite` | ^8.0.0 | Build |

`npm rm swiper` executed; `package.json` still lists only what is imported.

---

## 5. Files Created & Modified

### Created
| File | Purpose |
|------|---------|
| `resources/views/components/home/hero.blade.php` | New hero component (replaces prior inline hero) |
| `resources/views/components/home/statistics.blade.php` | New statistics band component |
| `resources/js/home.js` | Motion driver: Lenis + GSAP hero/reveal/parallax/count-up, `initHomeMotion` / `destroyHomeMotion` / `refreshHomeMotion` exports |
| `tests/Feature/Homepage/SmokeRenderTest.php` | Seeded-render smoke test (2 tests, 3 assertions) |

### Modified
| File | Change |
|------|--------|
| `resources/js/app.js` | Boots motion on `DOMContentLoaded` + `livewire:navigated`, re-runs icon morph, keeps `showToast` |
| `resources/css/app.css` | Added pine tokens, `band-pine`, `paper-dots`, `chip-glass`, `btn-pine`, `btn-ghost-light`, `eyebrow-pill`, `display-heading`, `stat-value`, Lenis rails, `overflow-x: clip` guard |
| `resources/views/livewire/homepage/public-home-page.blade.php` | Hero + statistics rewired to new components; water-status includes now pass explicit string `sectionTitle`/`sectionSubtitle` (fixes closure crash under `e()`) |
| `resources/views/livewire/homepage/sections/projects.blade.php` | Blue accents → green family |
| `resources/views/livewire/homepage/sections/facebook-feed.blade.php` | Removed `[FB Debug]` logs |
| `resources/views/livewire/homepage/chatbot-widget.blade.php` | Launcher + header reskinned (dark pine) |
| `package.json` | Removed swiper; gsap/lenis present only as needed |

---

## 6. Sections

| # | Section | Where |
|---|---------|-------|
| Hero | `components/home/hero` — layered image + tuneable overlays, ceremonial arc, gold hairline, eyebrow pill, contact chips strip | gated by `hero` section key |
| Quick access | `sections/quick-access` (unchanged shell, styled by new system) | `quick_links` |
| Electronic services | `sections/services` (unchanged shell) | `services` |
| Departments | `sections/departments` | `departments` |
| Facilities | `sections/facilities` | `facilities` |
| Municipality story | `sections/municipality-story` | `municipality_intro` |
| Council members + mayor | `sections/council-members` | `council_members` |
| Council decisions | `sections/council-decisions` | `council_decisions` |
| Water schedule | `sections/water-status` (data-gated, not toggleable) | gated by `!empty($waterSchedule)` |
| Jobs + offices | `sections/jobs` | `jobs` / `engineering_offices` |
| News | `sections/news` | `latest_news` |
| Projects | `sections/projects` (green reskin) | `projects` |
| Tenders | `sections/tenders` | `tenders` |
| Facebook feed | `sections/facebook-feed` (logs removed) | always included |
| **Statistics** | **new `components/home/statistics.blade.php`** — glass cards, gold hairline, count-up | `statistics` |
| Partners | `sections/partners` | gated by data |
| Contact CTA | `sections/contact-cta` | `contact_cta` |

All sections remain controlled by the existing admin section-key toggles; no routes or DB reads changed.

---

## 7. Animation System

- **Scroll driver:** Lenis (`duration 1.15`, exponential easing) drives `ScrollTrigger.update` via `gsap.ticker`; `lagSmoothing(0)`.
- **Hero entrance:** stagger `gsap.from` on `[data-hero-item]` 1→6 (eyebrow → H1 → description → buttons → contact chips → scroll cue), `y:28`, `opacity:0`, `power3.out`, delay 0.15.
- **Parallax:** hero background image via `data-parallax-speed="0.22"` with scrubbed ScrollTrigger (yPercent −2.2%→+2.2%).
- **Reveals:** `[data-reveal]` + optional `data-reveal-delay`, `start: top 88%`, `once: true`.
- **Count-up:** statistics `[data-count-up]` triggers at `top 90%`, `toLocaleString('en-US')`.
- **Reduced motion:** `prefers-reduced-motion: reduce` → JS short-circuits entirely; static content always visible (GSAP `from` never leaves elements hidden without JS).
- **Livewire navigations:** `destroyHomeMotion()` on `livewire:navigated` kills ScrollTriggers, tweens, Lenis; re-boots after fragment swap. `scroll-behavior: auto !important` under `html.lenis`.

---

## 8. Responsive Result

- **Guard:** `html, body { overflow-x: clip; max-width:100% }` — no horizontal scroll from parallax/arc/glow layers.
- **Hero:** `min-height: clamp(600px, 88vh, 860px)`; text column `max-w-[620px] lg:max-w-[680px]`, `mr-auto` (LTR geometry, RTL content) with `lg:mr-[6%]`; bottom info strip collapses to column on `<md`; slow-gradient overlay keeps ≥86% legibility.
- **Header:** eyebrow pill reflows; CTA row `flex-wrap`.
- **Stats:** `grid-cols-2 sm:grid-cols-3 lg:grid-cols-6`, gap `3/4`, padding `4/5`.
- **Breakpoints used:** mobile-first; `sm`@640, `md`@768, `lg`@1024. Crown arc hidden below `lg`.
- **Tested manually at 1440/1920 preview + tablet/mobile breakpoints during styling pass; final visual QA pending manual browser walkthrough (see §9).**

---

## 9. Performance

| Metric | Value |
|--------|-------|
| CSS bundle (unmin) | 150.34 kB |
| CSS gzip | 25.28 kB |
| JS bundle (unmin) | 171.60 kB |
| JS gzip | 60.91 kB |
| Build time | ~2–4 s via Vite |
| Hero LCP image | `fetchpriority="high"`, no `loading` attr |
| Below-fold images | `loading="lazy" decoding="async"` (statistics BG) |
| Fonts | Google Fonts `@import` at top — note warning (see §4) |
| Motion | All transform/opacity only (GPU-friendly) |

No layout-shift sources in new sections (fixed stat grid, reserved hero min-height).

---

## 10. Accessibility

- `prefers-reduced-motion` fully respected (motion off; no invisible-stuck states).
- `:focus-visible` global ring + button/a focus styles present in CSS.
- Hero section `aria-label="الشريط الرئيسي"`; display hero img `alt=""` (decorative, real title in H1).
- Icon glyphs decorative (aria-hidden not required — `<i>` empty).
- Contact chips are real links (`tel:`, `mailto:`) with `noopener noreferrer` on portal links.
- `overflow-x: clip` never clips focus outlines (clip ≠ hidden; respect `overflow-clip-margin` defaults remain).
- Dotted "paper" texture is aria-hidden container.

---

## 11. Build Result

```
vite v8.1.3 building client environment for production...
✓ 12 modules transformed. built in 2.17s
public/build/manifest.json              0.33 kB │ gzip:   0.16 kB
public/build/assets/app-CSB5nhN8.css  150.34 kB │ gzip:  25.28 kB
public/build/assets/app-CZmnKBK6.js   171.60 kB │ gzip:  60.91 kB
```

1 pre-existing CSS warning (Google Fonts `@import` ordering after Tailwind layer) — cosmetic, previously present, does not block.

---

## 12. Remaining Issues

| # | Issue | Severity | Status |
|---|-------|----------|--------|
| 1 | `php artisan view:cache` fails — pre-existing, `welcome.blade.php` references missing `layouts.guest` | Low (local tooling) | Pre-existing, not part of this task |
| 2 | Google Fonts `@import` CSS ordering warning | Cosmetic | Pre-existing |
| 3 | `tests/Feature/Homepage/HomepageTest` — 24 failures, all pre-existing factory bootstrap errors (`Database\Factories\…\Factory not found`) | Low | Pre-existing, unrelated |
| 4 | Alexandria font is remote-loaded — offline falls back to system stack | Info | By design |
| 5 | Manual visual QA at real breakpoints must still be walked (new overlays/parallax need eyes-on-device) | Info | Awaiting user |

---

## 13. Verification

| Check | Result |
|-------|--------|
| `npm run build` | ✅ |
| `php artisan test --filter SmokeRenderTest --compact` | ✅ 2 tests, 3 assertions, green (~4 s) |
| `composer lint` (project config) | ✅ (previous run green after Pint) |
| `php artisan view:cache` | ⚠ Pre-existing blocker (see §12) |

---

*Report covers: backup path, brand colors, typography, libraries, created/modified files, sections, animation system, responsive result, performance, accessibility, build result, remaining issues, verification.*