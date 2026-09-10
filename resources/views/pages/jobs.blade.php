<!-- View: Job Openings & Requisitions -->
<div id="view-jobs" class="tf-view">
  <div class="tf-greeting-bar">
    <div class="tf-greeting-text">
      <h2>Job Openings & Requisitions</h2>
      <p>Explore, create, and manage hiring requirements.</p>
    </div>
    <div class="tf-greeting-actions">
      <button class="tf-btn-primary" onclick="TF.openPostJobModal()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        + Create New Job
      </button>
    </div>
  </div>

  <div id="jobsGridContainer" class="tf-job-grid">
    <!-- Rendered dynamically by TF.renderJobs() in talentflow-app.js -->
  </div>
</div>
