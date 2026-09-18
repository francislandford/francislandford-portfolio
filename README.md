# Francis Landford — Portfolio Site Content & Requirements

This document compiles all content pulled from the existing site (**francislandford.com**) plus the full requirements list gathered for the new build (Laravel + Livewire). Use this as the source-of-truth reference during development and content seeding.

---

## 1. Personal Information

| Field | Value |
|---|---|
| Name | Francis Landford |
| Title / Tagline | Software Engineer \| Creative Problem Solver \| Innovative Software Solutions & Modern Applications |
| Hero Headline | "Building Smart Software Solutions" |
| Hero Sub-headline | "I create and develop tailored digital solutions that help individuals and organizations achieve their goals through meaningful user experiences." |
| Site Meta Description | Professional software engineer specializing in innovative solutions, scalable systems, and modern applications. Showcasing creative projects and cutting-edge technology. |
| Phone | +231 777 810 466 |
| Email | contact@francislandford.com |
| Address | Soul Clinic, New Paynesville, Monrovia, Liberia |
| Website | https://francislandford.com |

### Social Links
- Facebook: https://web.facebook.com/francis.veteran
- X (Twitter): https://x.com/veteranfrancis
- YouTube: https://www.youtube.com
- LinkedIn: https://www.linkedin.com/in/francis-landford-a25707a2
- Instagram: referenced on-site (256,215 followers claimed) — handle/URL not directly listed, needs confirmation

### Stats / Highlights (Home Page)
- **5+** Years of Experience
- **10+** Projects Completed
- **10+** Satisfied / Happy Clients
- **5** Awards Won/Received
- "5 years with professional design software" (caption under hero)

### Tech Stack (displayed as logos on home page)
- PHP
- Laravel
- React
- React Native
- Express
- MySQL

### Existing Site Images (for reference/reuse or inspiration — not necessarily to migrate)
- `chatgpt-image-sep-1-2025-07-38-09-am.png` — header/logo image
- `chatgpt-image-sep-2-2025-11-54-13-am.png` — header/logo image (alt version)
- `2fpax-removebg-preview.png` — hero image
- `raf360x360075tfafafa-ca443f4786u3.png` — hero secondary image
- `2fpax.jpg` — OG/social share image
- `1756658761555.jpg`, `francis-landford.jpg` — gallery/Instagram preview images

---

## 2. Existing Site Navigation (for reference)

- Home
- Services
- Portfolio (labeled "Portfolio" in nav, routes to `/projects`)
- Blog
- Contact

> Note: this is the OLD site's nav. See Section 5 below for the NEW site's approved navigation structure.

---

## 3. Services (5 total)

| Service | Description | Old URL |
|---|---|---|
| Mobile Applications Development | Powerful and user-friendly mobile apps for iOS and Android. | /services/mobile-applications-development |
| Web Development | Custom, responsive, and scalable websites for your business. | /services/web-development |
| UI/UX Design | Intuitive, user-friendly, and visually appealing designs. | /services/uiux-design |
| SEO Optimization | Boost your visibility and rank higher on search engines. | /services/seo-optimization |
| Content Creation | Engaging and creative content tailored to your brand's voice. | /services/content-creation |

> Individual service detail-page content (beyond the one-liner above) was not retrievable directly — pending paste from user.

---

## 4. Projects (5 total)

### 1. Cargo & Vessel Operations Mobile App (DockMaster Mobile)
- **Category:** Mobile Development
- **Client:** Bam Global
- **Start Date:** Mar 01, 2026
- **Description:** A mobile application for managing cargo and vessel boarding operations with offline-first functionality and automatic data synchronization.

### 2. University Management System
- **Category:** Development
- **Client:** Assemblies of God University
- **Start Date:** Mar 31, 2026
- **Description:** A comprehensive university management system that handles students, lecturers, courses, roles, and academic operations through a centralized and secure platform.

### 3. Cargo & Vessel Operations Management System (DockMaster)
- **Category:** Development
- **Client:** Bam Global
- **Start Date:** Mar 31, 2026
- **Description:** A cargo and vessel operations system that manages boarding, unboarding, and cargo tracking with offline-first mobile support and real-time synchronization.

### 4. FrontPage Africa Digital Platform
- **Category:** Development, Design
- **Client:** FrontPage Africa Online
- **Start Date:** Oct 10, 2022
- **Live URL:** https://frontpageafricaonline.com
- **Description:** A modern, high-performance news website built to deliver real-time updates, manage digital content efficiently, and provide a seamless reading experience across devices.

### 5. Tall Youths Foundation Digital Platform
- **Category:** Development
- **Client:** Tall Youths Foundation
- **Live URL:** https://tallyouthsfoundation.org
- **Description:** A modern website for a youth-focused nonprofit organization, designed to showcase initiatives, engage supporters, and improve outreach.

> Individual project detail-page content (additional screenshots, tech stack per project, outcomes) was not retrievable directly — pending paste from user.

---

## 5. New Site — Approved Navigation Structure

- Home
- About
- Services *(mega menu)*
- Blog
- Projects
- E-Learning *(mega menu — courses and related items)*
- Contact Us

---

## 6. Full Feature & Requirements List (New Build)

### 6.1 Core Platform
- All-in-one portfolio site built on **Laravel + Livewire**
- Personal information / About section
- Blog
- Projects showcase
- Links for both web and mobile applications
- Additional "interesting features" (open-ended, AI features below cover much of this)

### 6.2 AI Features
- AI writing assistant (admin-side blog drafting/editing help)
- Auto-tagging / categorization of blog content
- TL;DR / auto-summary generator for posts
- RAG-based chatbot — "Ask my portfolio" (answers visitor questions grounded in site content)
- Semantic search across blog + projects
- GitHub activity summarizer (auto-generated "what I've been working on" digest)
- AI-generated project descriptions/blurbs from README/source content

### 6.3 Billing / Usage Tracking
- **Every service that costs money must track and expose billing/usage info**, not just perform its action
- Consistent `BillableService` interface/trait pattern across all such services (AI calls, third-party APIs, email, SMS, storage, etc.)
- Usage logging (tokens, cost, timestamp, attribution to specific content/action where relevant)
- Admin dashboard/widget showing running totals and spend
- Optional budget cap / kill-switch for expensive calls

### 6.4 Experience & Resume
- Experience section — work history, timeline-style
- Well-designed, **printable resume** with a dedicated print-optimized layout / PDF export (distinct from the on-site experience section)

### 6.5 E-Learning
- Courses/tutorials section
- Model still TBD: free content, paid courses, or interactive (quizzes, progress tracking) — to be clarified before build
- Appears in nav as its own mega menu with courses and related items

### 6.6 Payments
- Online payment support
- Mobile money support (region-dependent — e.g., MTN MoMo, Orange Money for Liberia/West Africa)
- Likely tied to e-learning purchases; may also support donations/tips or paid content access

### 6.7 Navigation
- Home, About, Services (mega menu), Blog, Projects, E-Learning (mega menu), Contact Us

### 6.8 Design & Quality Bar
- Premium, visually striking frontend that captures visitor attention
- Polished animations/interactions, strong design system, fast performance — not a generic template look
- Sophisticated admin panel — beyond basic CRUD:
  - Rich dashboards/analytics (visitor stats, revenue, AI usage/billing, course enrollments)
  - Granular content management
  - Role-based access control
  - Genuinely well-designed admin UI (customized Filament or custom-built)

### 6.9 Content Blocks (All Admin-Manageable)
- Gallery (image/media showcase)
- Testimonials (client/colleague feedback)
- Trusted Companies (logos of companies worked with)
- Social media links
- Skills section (tech stack/competencies)
- Blog, Projects, Services, Experience/Resume, E-Learning content
- **Rule: nothing hardcoded on the frontend — every content block must be manageable from the admin panel**

### 6.10 SEO
- Meta tags (title, description) — editable per-page/per-post in admin
- XML sitemap
- Structured data (schema.org — Person, Article, Organization, etc.)
- Clean URLs / slugs
- Open Graph and Twitter Card tags
- Canonical URLs
- Carries forward and improves on patterns already used on the current site (canonical tags, OG tags, per-page meta descriptions)

---

## 7. Outstanding / Pending Items

- [ ] Paste content from `/blog` and individual blog posts (not reachable via automated fetch)
- [ ] Paste content from individual service detail pages (Mobile Apps Dev, Web Dev, UI/UX, SEO, Content Creation)
- [ ] Paste content from individual project detail pages (additional screenshots, tech stack, outcomes per project)
- [ ] Confirm `/about` and `/contact` page content if different from home page
- [ ] Upload actual image assets (logos, hero images, gallery, project screenshots) if they should be reused
- [ ] Decide E-Learning model: free / paid / interactive
- [ ] Decide payment gateway(s) for online payments and mobile money (region-dependent)
- [ ] Confirm Instagram handle/URL (referenced on-site but not directly linked)

---

*Compiled for use during development — content should be seeded into the database/CMS once the schema is in place. No code has been written yet per current project phase.*
