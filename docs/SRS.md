# Software Requirements Specification (SRS)

## Project Title: 3DOS Ushering & Candidate Management System
**Standard Alignment:** IEEE 830-1998  
**Document Version:** 1.0.0  
**Status:** Approved  
**Framework Version:** Laravel 11.x (PHP 8.2+)  

---

## 1. Introduction

### 1.1 Purpose
This Software Requirements Specification (SRS) document specifies the complete functional and non-functional requirements for the **3DOS Ushering & Candidate Management System**. It serves as the primary technical specification for developers, database administrators, system architects, and software testers.

### 1.2 Document Conventions
- **FR:** Functional Requirement
- **NFR:** Non-Functional Requirement
- **UI:** User Interface Requirement
- **API:** Application Programming Interface Requirement
- **DB:** Database Requirement

### 1.3 Intended Audience
This document is intended for project managers, software engineers, QA engineers, system administrators, and system stakeholders (VPs, Presidents, Council Heads).

### 1.4 System Scope
The system provides a full-stack Web MVC application and RESTful API backend to manage candidate registration, usher recruitment attribution, candidate evaluation, interview scheduling, interviewer performance leaderboards, and administrative logging.

---

## 2. Overall Description

### 2.1 Product Perspective
The 3DOS Ushering & Candidate Management System operates as a self-contained web platform built on the Laravel framework. It provides both server-side rendered Blade views for administrative management and JSON REST endpoints for external system consumption or API integrations.

```
+-----------------------------------------------------------------------+
|                             CLIENT LAYER                              |
|   +-------------------------------+   +---------------------------+   |
|   |   Web Browser (Blade MVC UI)  |   |  HTTP API Clients (JSON)  |   |
|   +---------------+---------------+   +-------------+-------------+   |
+-------------------|---------------------------------|-----------------+
                    | HTTP/HTTPS                      | HTTP/HTTPS
                    v                                 v
+-----------------------------------------------------------------------+
|                          APPLICATION LAYER                            |
|   +-------------------------------+   +---------------------------+   |
|   |     Web Routes & Middleware   |   |   API Routes & AuthToken  |   |
|   +---------------+---------------+   +-------------+-------------+   |
|                   |                                 |                 |
|                   v                                 v                 |
|   +---------------------------------------------------------------+   |
|   |                 Controllers & Domain Services                 |   |
|   |  (DashboardController, ApplicantWebController, Service Layer) |   |
|   +-------------------------------+-------------------------------+   |
+-----------------------------------|-----------------------------------+
                                    | Eloquent ORM
                                    v
+-----------------------------------------------------------------------+
|                            DATABASE LAYER                             |
|    MySQL 8.0+ (registrations, councils, users, sessions, cache, jobs) |
+-----------------------------------------------------------------------+
```

### 2.2 Operating Environment
- **Operating System:** Linux / Windows / macOS compatible
- **Web Server:** Nginx 1.20+ or Apache 2.4+
- **PHP Environment:** PHP 8.2 or higher (Extensions: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath)
- **Database Server:** MySQL 8.0+ or MariaDB 10.5+
- **Build Tools:** Node.js 18+, NPM, Vite

---

## 3. Detailed Functional Requirements

### 3.1 Module 1: Public Applicant Registration & Usher Attribution Engine

#### [FR-1.1] Public Form Submission
The system shall provide a public web endpoint (`GET /register-form`, `POST /register-form` and `POST /api/registrations`) allowing applicants to submit their details.

#### [FR-1.2] Required Fields & Validation
The system shall validate application inputs:
- `name` (String, required)
- `email` (String, required, valid format, unique in `registrations` table)
- `phone` (String, required, max 20 characters)
- `college` (String, required)
- `level` (String, required, max 50 characters)
- `council` (String, optional, max 100 characters)
- `event_type` (String, optional, max 100 characters)
- `ushered_by` (String, optional, name of usher)

#### [FR-1.3] Initial State Assignment
Upon creation, the system shall assign a default rating of `Pending` and populate timestamps (`created_at`, `updated_at`).

---

### 3.2 Module 2: Authentication & Access Control

#### [FR-2.1] Dual Authentication Modes
1. **Web MVC Session Authentication:** `POST /login` establishes session via `AuthWebController`.
2. **API Token Authentication:** `POST /api/auth/login` validates credentials against `users` table and issues a token handled by `AuthenticateToken` middleware.

#### [FR-2.2] Role Hierarchy & Scoping Rules
- **President / VP:** Unrestricted access across all councils and administrative statistics.
- **Head / Instructor:** Scoped data visibility restricted to candidates matching the user's assigned council.

---

### 3.3 Module 3: Applicant Administration & Dashboard

#### [FR-3.1] Dashboard Metric Counters
The main dashboard (`GET /dashboard`) shall compute real-time candidate statistics:
$$\text{Total Applicants} = \sum \text{registrations}$$
$$\text{Accepted} = \text{Count}(\text{rating} = \text{'Acceptance'})$$
$$\text{Waitlist (B)} = \text{Count}(\text{rating} = \text{'B'})$$
$$\text{Rejected} = \text{Count}(\text{rating} = \text{'Rejection'})$$
$$\text{Pending} = \text{Count}(\text{rating} = \text{'Pending'} \lor \text{rating IS NULL})$$

#### [FR-3.2] Multi-Criteria Filtering & Pagination
The system shall allow filtering applicants by search keyword (`name`/`email`), academic `level`, `rating`, `event_type`, and `council`, returning paginated results (20 items per page) preserving query strings.

---

### 3.4 Module 4: Candidate Evaluation & Question Matrix Engine

#### [FR-4.1] Council Question Hydration
The system shall load question sets via `CouncilQuestionsService::getForCouncil($councilName)`, supplying category-specific questions (Soft Skills & Technical Skills) tailored to:
- Marketing
- Backend Development
- Frontend Development
- CEO
- Stock Market
- Default fallback set for other councils

#### [FR-4.2] Evaluation Sheet Persistence
The system (`PATCH /applicants/{id}`) shall persist structured interview data:
- `rating`: Enum (`Pending`, `Acceptance`, `B`, `Rejection`)
- `interviewed_by`: Username of interviewer
- `interview_time`: Datetime string
- `interview_questions`: JSON array of scored question objects
- `interview_notes`: JSON array of qualitative notes
- `notes`: General text notes

---

### 3.5 Module 5: Analytics & Performance Leaderboards

#### [FR-5.1] Usher Statistics (`GET /usher-stats`)
Calculates total recruits per usher, excluding empty/NA values (`'', 'NA', 'N/A', 'none', 'null'`), displaying active usher count and average candidates per usher:
$$\text{Average Recruits Per Usher} = \frac{\text{Total Applicants}}{\text{Active Ushers Count}}$$

#### [FR-5.2] Interviewer Leaderboard (`GET /interviewer-stats`)
Aggregates completed interviews by interviewer username:
- Verifies interviewers against valid system users in the `users` table.
- Groups and separates performance statistics by user role: Instructors vs. Heads/VPs.
- Provides per-council interviewer activity breakdown.

---

## 4. Data Model & Database Schema

### 4.1 Schema Definition: `registrations`

```sql
CREATE TABLE `registrations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `college` varchar(255) NOT NULL,
  `level` varchar(50) NOT NULL,
  `council` varchar(100) DEFAULT NULL,
  `event_type` varchar(100) DEFAULT NULL,
  `ushered_by` varchar(255) DEFAULT NULL,
  `rating` enum('Pending','Acceptance','B','Rejection') NOT NULL DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `interview_time` datetime DEFAULT NULL,
  `interviewed_by` varchar(100) DEFAULT NULL,
  `interview_questions` longtext DEFAULT NULL,
  `interview_notes` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `registrations_email_unique` (`email`),
  KEY `registrations_rating_index` (`rating`),
  KEY `registrations_level_index` (`level`),
  KEY `registrations_council_index` (`council`),
  KEY `registrations_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4.2 Schema Definition: `councils`

```sql
CREATE TABLE `councils` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `councils_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 5. Non-Functional Requirements (NFRs)

### 5.1 Security Requirements
- **Password Hashing:** All user passwords hashed via `Bcrypt` algorithm.
- **CSRF Protection:** Web forms protected via `@csrf` token verification middleware.
- **SQL Injection Prevention:** Eloquent ORM parameterized queries used exclusively.
- **XSS Prevention:** Automatic HTML escaping via Blade `{{ $variable }}` syntax.

### 5.2 Performance Requirements
- **Index Optimization:** Database queries on filtering attributes utilize B-Tree composite indices (`rating`, `level`, `council`, `email`).
- **Response Latency:** Server response time under 200ms for paginated dashboard requests.

### 5.3 Maintainability & Code Quality
- Architectural pattern: Model-View-Controller (MVC) separation with dedicated Service layer (`CouncilQuestionsService`).
- Compliance with PSR-12 coding standard.
