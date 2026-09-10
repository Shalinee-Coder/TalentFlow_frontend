# TalentFlow – Recruitment & Resume Management System

**TalentFlow** is a modern, enterprise-ready Recruitment and Applicant Tracking System (ATS) built with **Laravel 11**, **Laravel Sanctum**, and **MySQL/SQLite**. It provides an end-to-end hiring pipeline with automated candidate scoring, asynchronous PDF resume text parsing, interview conflict scheduling, deadline automations, and recruitment analytics.

---

## Key Features

1. **Authentication & Authorization (Sanctum + Policies):**
   - Role-Based Access Control (RBAC): `Admin`, `Recruiter`, `Candidate`.
   - Granular Laravel Policies protecting models at the API layer.
   - Secure token-based API authentication.

2. **Job Management:**
   - Full job lifecycle (`draft` -> `published` -> `closed`).
   - Weighted skill matrix differentiating required vs. optional skills.
   - Comprehensive filtering by department, experience, and application deadlines.

3. **Resume Management & Queue Processing:**
   - PDF validation (MIME, extension, and 5MB size limit).
   - Dedicated private storage disk (`resumes`) preventing direct public exposure.
   - Asynchronous `ProcessResumeJob` queue processing with idempotency and retry backoff.
   - Text tokenization and NLP extraction for skills, years of experience, and education levels.

4. **100-Point Candidate Scoring Engine:**
   - **Required Skills:** 40 points max (weighted match).
   - **Optional / Bonus Skills:** 10 points max.
   - **Experience Match:** 25 points max (proportional comparison against job requirements).
   - **Education Level:** 15 points max (Doctorate, Master, Bachelor, Diploma).
   - **Bonus / Profile Completeness:** 10 points max (Portfolio/GitHub link, verified contacts).

5. **Hiring Pipeline State Machine & Auditing:**
   - Formal stage progression: `Applied` -> `Screening` -> `Shortlisted` -> `Interview` -> `Technical Task` -> `Hired` / `Rejected`.
   - Transition guards preventing illegal skips (e.g. `Applied` directly to `Hired` is rejected).
   - Immutable audit trail in `application_status_histories` tracking actor, timestamp, and transition remarks.

6. **Interview Scheduler & Conflict Validation:**
   - Scheduling with automatic conflict detection (blocks exact, partial, or complete overlapping intervals for the same interviewer).
   - Allows clean back-to-back interviews (e.g. 10:00-11:00 and 11:00-12:00).
   - Status updates and cancellations.

7. **Technical Tasks & Submissions:**
   - Recruiter task assignments with submission instructions and deadlines.
   - Candidate submission endpoint with URL and solution narrative.
   - Guard against submitting overdue or closed tasks.
   - Recruiter review and grading workflow.

8. **Automation & Scheduler:**
   - Automated 24-hour deadline reminder notification sent to candidates.
   - Automated overdue task state transition and alerting.
   - Daily automated expiration of closed jobs.

9. **Dashboard Analytics:**
   - Role-aware KPIs: Total jobs, published jobs, active candidates, interviews this week.
   - Aggregate pipeline distribution across all stages.
   - Weekly interview calendar view and average candidate score metrics.

10. **In-App Database Notifications:**
    - Real-time alerts for application submission, status transitions, interview bookings, task deadlines, and recruiter reviews.

---

## Technology Stack & Requirements

- **PHP:** 8.2 or higher
- **Composer:** 2.x
- **Framework:** Laravel 11.x
- **Database:** MySQL 8.x or SQLite 3
- **Queue Connection:** Database / Redis
- **Testing Framework:** PHPUnit 10+

---

## Installation & Setup

### 1. Clone Repository & Install Dependencies
```bash
git clone https://github.com/your-username/NewTalentFlow.git
cd NewTalentFlow
composer install
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`. By default, TalentFlow is configured to use SQLite for instant zero-config setup:
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```
Or for MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=talentflow
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. Run Migrations & Seeders
```bash
touch database/database.sqlite
php artisan migrate --seed
```

---

## Default Test Credentials

All seeded accounts use password: `password`

| Role | Name | Email | Password |
|---|---|---|---|
| **Admin** | System Administrator | `admin@talentflow.local` | `password` |
| **Recruiter** | Sarah Connor (Lead Tech Recruiter) | `recruiter1@talentflow.local` | `password` |
| **Recruiter** | Michael Scott (Hiring Manager) | `recruiter2@talentflow.local` | `password` |
| **Candidate** | Alice Johnson (Senior PHP) | `candidate1@talentflow.local` | `password` |
| **Candidate** | Bob Smith (Full Stack) | `candidate2@talentflow.local` | `password` |
| **Candidate** | Charlie Brown (Backend Architect) | `candidate3@talentflow.local` | `password` |
| **Candidate** | Diana Prince (Junior Developer) | `candidate4@talentflow.local` | `password` |
| **Candidate** | Ethan Hunt (Principal Engineer) | `candidate5@talentflow.local` | `password` |

---

## Running Queue Workers

Resume processing and notification deliveries run via Laravel Queues:

```bash
php artisan queue:work --tries=3 --timeout=90
```

---

## Running the Scheduler

For local development and testing deadline automations:
```bash
php artisan schedule:work
```

For production deployment, configure the cron job on your server:
```cron
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## Running Automated Tests

Run the complete test suite:
```bash
php artisan test
```

Or via PHPUnit directly:
```bash
./vendor/bin/phpunit
```

Test coverage includes:
- `AuthTest`: Registration, login, logout, profile, and role barriers.
- `JobTest`: Public listing, recruiter CRUD, skill synchronization, and status changes.
- `ResumeTest`: PDF upload validation, MIME rejection, and queue dispatch.
- `ApplicationPipelineTest`: Application submission, unique constraints, and state transition guards.
- `InterviewSchedulerTest`: Exact overlap, partial overlap, back-to-back allowance, and interviewer concurrency.
- `TechnicalTaskTest`: Task assignment, candidate submission, overdue protection, and reviewer evaluation.
- `DashboardTest`: Aggregate metrics, pipeline distribution, and candidate access barriers.
- `CandidateScoringTest`: 100-point formula, weighted skills, experience ratios, and education hierarchy.

---

## API Documentation & Postman Collection

- Complete API specification available in: [`docs/API.md`](docs/API.md)
- Importable Postman collection located at: [`postman/TalentFlow.postman_collection.json`](postman/TalentFlow.postman_collection.json)
