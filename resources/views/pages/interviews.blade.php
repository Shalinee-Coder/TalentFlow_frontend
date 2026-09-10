<!-- View: Interview Scheduler & Conflict Guard -->
<div id="view-interviews" class="tf-view">
  <div class="tf-greeting-bar">
    <div class="tf-greeting-text">
      <h2>Interview Scheduler & Conflict Guard</h2>
      <p>Schedules candidate interviews with automatic overlap validation.</p>
    </div>
    <div class="tf-greeting-actions">
      <button class="tf-btn-primary" onclick="TF.openScheduleModal()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Schedule Interview
      </button>
    </div>
  </div>

  <div id="interviewsGridContainer" class="tf-interview-grid">
    <!-- Rendered dynamically by TF.renderInterviews() in talentflow-app.js -->
  </div>
</div>
