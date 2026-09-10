# TALENTFLOW REST API SPECIFICATION (v1)

Base URL: `http://localhost:8000/api/v1`

All responses follow a consistent JSON envelope:

### Standard Success Envelope
```json
{
  "success": true,
  "message": "Operation description",
  "data": {}
}
```

### Standard Error Envelope
```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field": ["Validation message"]
  }
}
```

---

## Table of Contents
1. [Authentication](#1-authentication)
2. [Job Management](#2-job-management)
3. [Skill Management](#3-skill-management)
4. [Resume Management](#4-resume-management)
5. [Application Pipeline](#5-application-pipeline)
6. [Interview Scheduler](#6-interview-scheduler)
7. [Technical Tasks & Submissions](#7-technical-tasks--submissions)
8. [Dashboard Analytics](#8-dashboard-analytics)
9. [Notifications](#9-notifications)

---

## 1. Authentication

### 1.1 Register Candidate
- **Endpoint:** `POST /auth/register`
- **Auth Required:** No (Public)
- **Role:** N/A (Registers as Candidate)
- **Request Body:**
```json
{
  "name": "Jane Candidate",
  "email": "jane@example.com",
  "password": "password",
  "password_confirmation": "password",
  "phone": "+1-555-0101",
  "total_experience": 4.5,
  "highest_education": "Master",
  "current_company": "Tech Corp",
  "current_position": "Developer",
  "location": "San Francisco, CA"
}
```
- **Success (201 Created):**
```json
{
  "success": true,
  "message": "Candidate registration successful.",
  "data": {
    "user": {
      "id": 1,
      "name": "Jane Candidate",
      "email": "jane@example.com",
      "role": "candidate",
      "role_display": "Candidate"
    },
    "token": "1|sanctum_token_string",
    "role": "candidate"
  }
}
```

### 1.2 Login
- **Endpoint:** `POST /auth/login`
- **Auth Required:** No (Public)
- **Request Body:**
```json
{
  "email": "recruiter1@talentflow.local",
  "password": "password"
}
```
- **Success (200 OK):**
```json
{
  "success": true,
  "message": "Login successful.",
  "data": {
    "user": {
      "id": 2,
      "name": "Sarah Connor",
      "email": "recruiter1@talentflow.local",
      "role": "recruiter"
    },
    "token": "2|sanctum_token_string",
    "role": "recruiter"
  }
}
```

### 1.3 Logout
- **Endpoint:** `POST /auth/logout`
- **Auth Required:** Yes (Sanctum)
- **Success (200 OK):**
```json
{
  "success": true,
  "message": "Logged out successfully."
}
```

### 1.4 Get Profile (Me)
- **Endpoint:** `GET /auth/me`
- **Auth Required:** Yes (Sanctum)
- **Success (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "name": "Sarah Connor",
    "email": "recruiter1@talentflow.local",
    "role": "recruiter",
    "candidate_profile": null
  }
}
```

---

## 2. Job Management

### 2.1 List Jobs
- **Endpoint:** `GET /jobs`
- **Auth Required:** Optional (Public gets published jobs; Recruiter can filter own jobs)
- **Query Params:** `status`, `department`, `max_experience`, `search`, `sort` (`deadline`, `oldest`, `latest`), `page`, `per_page`
- **Success (200 OK):** Paginated JSON array of `JobResource`.

### 2.2 Create Job
- **Endpoint:** `POST /jobs`
- **Auth Required:** Yes
- **Roles:** `admin`, `recruiter`
- **Request Body:**
```json
{
  "title": "Senior Laravel Architect",
  "department": "Engineering",
  "description": "Design and scale high-throughput applications.",
  "required_experience": 5.0,
  "salary_min": 120000,
  "salary_max": 160000,
  "application_deadline": "2026-12-31 23:59:59",
  "status": "published",
  "skills": [
    { "skill_id": 1, "is_required": true, "weight": 5 },
    { "skill_id": 2, "is_required": true, "weight": 4 }
  ]
}
```
- **Success (201 Created):** Job resource with synced skills.

### 2.3 Show Job
- **Endpoint:** `GET /jobs/{job}`
- **Auth Required:** Optional

### 2.4 Update Job
- **Endpoint:** `PUT /jobs/{job}`
- **Auth Required:** Yes
- **Roles:** `admin`, `recruiter` (Owner only)

### 2.5 Update Job Status
- **Endpoint:** `PATCH /jobs/{job}/status`
- **Auth Required:** Yes
- **Roles:** `admin`, `recruiter` (Owner only)
- **Request Body:**
```json
{ "status": "closed" }
```

### 2.6 Delete Job
- **Endpoint:** `DELETE /jobs/{job}`
- **Auth Required:** Yes
- **Roles:** `admin`, `recruiter` (Owner only)

---

## 3. Skill Management

### 3.1 List Skills
- **Endpoint:** `GET /skills`
- **Query Params:** `search`

### 3.2 Create Skill
- **Endpoint:** `POST /skills`
- **Roles:** `admin`, `recruiter`
- **Request Body:** `{ "name": "GraphQL" }`

### 3.3 Update Skill
- **Endpoint:** `PUT /skills/{skill}`
- **Roles:** `admin`, `recruiter`

### 3.4 Delete Skill
- **Endpoint:** `DELETE /skills/{skill}`
- **Roles:** `admin` only

---

## 4. Resume Management

### 4.1 Upload Resume
- **Endpoint:** `POST /resumes`
- **Auth Required:** Yes
- **Roles:** `candidate`
- **Content-Type:** `multipart/form-data`
- **Payload:** `resume` (PDF file, max 5MB)
- **Success (201 Created):**
```json
{
  "success": true,
  "message": "Resume uploaded successfully and queued for processing.",
  "data": {
    "id": 1,
    "original_filename": "candidate_cv.pdf",
    "mime_type": "application/pdf",
    "file_size_formatted": "452.1 KB",
    "processing_status": "uploaded",
    "download_url": "http://localhost:8000/api/v1/resumes/1/download"
  }
}
```

### 4.2 Get Resume Details
- **Endpoint:** `GET /resumes/{resume}`
- **Roles:** `admin`, `recruiter` (associated job), `candidate` (owner)

### 4.3 Check Resume Status
- **Endpoint:** `GET /resumes/{resume}/status`
- **Returns:** `{ "status": "processed", "is_processed": true }`

### 4.4 Download Resume
- **Endpoint:** `GET /resumes/{resume}/download`
- **Returns:** Protected PDF file stream.

---

## 5. Application Pipeline

### 5.1 Apply for Job
- **Endpoint:** `POST /jobs/{job}/apply`
- **Roles:** `candidate`
- **Request Body:**
```json
{ "resume_id": 1 }
```
*(Or upload inline `resume` PDF)*
- **Validation:** Disallows duplicate application for same `(job_id, candidate_id)`.

### 5.2 List Applications
- **Endpoint:** `GET /applications`
- **Query Params:** `job_id`, `status`, `min_score`, `sort` (`score_desc`, `score_asc`, `latest`), `page`
- **Access Scope:** Candidates see only their own applications; Recruiters see applications for their jobs.

### 5.3 Show Application Details
- **Endpoint:** `GET /applications/{application}`
- **Includes:** Score, status histories, interviews, technical tasks.

### 5.4 Update Application Status (Transition Stage)
- **Endpoint:** `PATCH /applications/{application}/status`
- **Roles:** `admin`, `recruiter`
- **Pipeline:** `applied` -> `screening` -> `shortlisted` -> `interview` -> `technical_task` -> `hired` / `rejected`
- **Request Body:**
```json
{
  "status": "shortlisted",
  "remarks": "Candidate passed initial screening bar."
}
```
- **Failure on invalid transition (422):** Returns allowed next stages.

### 5.5 Get Application Audit Trail
- **Endpoint:** `GET /applications/{application}/history`
- **Returns:** Chronological array of status transitions with actor and remarks.

---

## 6. Interview Scheduler

### 6.1 Schedule Interview
- **Endpoint:** `POST /applications/{application}/interviews`
- **Roles:** `admin`, `recruiter`
- **Request Body:**
```json
{
  "interviewer_id": 2,
  "scheduled_at": "2026-10-15 14:00:00",
  "duration": 60,
  "meeting_link": "https://meet.google.com/xyz-uvw-rst",
  "notes": "System architecture deep-dive"
}
```
- **Conflict Guard (422):** Rejects overlapping interview times for the same interviewer.

### 6.2 List Interviews
- **Endpoint:** `GET /interviews`
- **Query Params:** `status`, `from_date`

### 6.3 Show Interview
- **Endpoint:** `GET /interviews/{interview}`

### 6.4 Update / Reschedule Interview
- **Endpoint:** `PUT /interviews/{interview}`

### 6.5 Cancel Interview
- **Endpoint:** `PATCH /interviews/{interview}/cancel`

---

## 7. Technical Tasks & Submissions

### 7.1 Assign Technical Task
- **Endpoint:** `POST /applications/{application}/tasks`
- **Roles:** `admin`, `recruiter`
- **Request Body:**
```json
{
  "title": "Build Async Resume Parser",
  "description": "Implement an idempotent queue worker with retry logic.",
  "instructions": "Push code to public repo.",
  "due_at": "2026-10-25 18:00:00"
}
```

### 7.2 List Tasks
- **Endpoint:** `GET /tasks`

### 7.3 Get Task Details
- **Endpoint:** `GET /tasks/{task}`

### 7.4 Update Task Working Status
- **Endpoint:** `PATCH /tasks/{task}/status`
- **Payload:** `{ "status": "in_progress" }`

### 7.5 Submit Task Solution
- **Endpoint:** `POST /tasks/{task}/submit`
- **Roles:** `candidate` (assigned applicant)
- **Request Body:**
```json
{
  "submission_url": "https://github.com/developer/assessment-solution",
  "submission_content": "Detailed README and benchmark results included."
}
```
- **Deadline Guard (422):** Overdue tasks cannot be submitted.

### 7.6 View Submissions
- **Endpoint:** `GET /tasks/{task}/submissions`

### 7.7 Review & Grade Submission
- **Endpoint:** `PATCH /submissions/{submission}/review`
- **Roles:** `admin`, `recruiter`
- **Request Body:**
```json
{
  "status": "accepted",
  "review_notes": "Clean SOLID principles and comprehensive test cases."
}
```

---

## 8. Dashboard Analytics

### 8.1 Get Overview KPIs
- **Endpoint:** `GET /dashboard/overview`
- **Roles:** `admin`, `recruiter`
- **Response:**
```json
{
  "success": true,
  "data": {
    "total_jobs": 12,
    "published_jobs": 8,
    "total_applications": 45,
    "active_candidates": 24,
    "interviews_this_week": 6,
    "average_candidate_score": 82.4
  }
}
```

### 8.2 Get Pipeline Distribution
- **Endpoint:** `GET /dashboard/pipeline`
- **Roles:** `admin`, `recruiter`
- **Response:**
```json
{
  "success": true,
  "data": {
    "Applied": 15,
    "Screening": 8,
    "Shortlisted": 6,
    "Interview": 4,
    "Technical Task": 3,
    "Hired": 2,
    "Rejected": 7
  }
}
```

### 8.3 Get Weekly Interview Schedule
- **Endpoint:** `GET /dashboard/interviews`
- **Roles:** `admin`, `recruiter`

---

## 9. Notifications

### 9.1 List Notifications
- **Endpoint:** `GET /notifications`
- **Auth Required:** Yes (Sanctum)

### 9.2 Mark Notification as Read
- **Endpoint:** `PATCH /notifications/{id}/read`

### 9.3 Mark All Notifications as Read
- **Endpoint:** `POST /notifications/read-all`
