@extends('layouts.app')

@section('title', 'TalentFlow – Recruitment & Resume Management ATS')

@section('content')
  <!-- Separated View: Dashboard & Analytics -->
  @include('pages.dashboard')

  <!-- Separated View: Jobs Explorer & Postings -->
  @include('pages.jobs')

  <!-- Separated View: Resume Parsing & 100-Point Scoring Engine -->
  @include('pages.resumes')

  <!-- Separated View: Hiring Pipeline Kanban Board -->
  @include('pages.pipeline')

  <!-- Separated View: Interview Scheduler with Conflict Guard -->
  @include('pages.interviews')

  <!-- Separated View: Notifications -->
  @include('pages.notifications')

  <!-- Separated View: Technical Task Assessments & Submissions -->
  @include('pages.tasks')
@endsection
