<!-- Modal: Apply for Job (Candidate Experience) -->
<div id="modalApplyJob" class="tf-modal-backdrop">
  <div class="tf-modal">
    <div class="tf-modal-header">
      <div>
        <h3>Apply for Position</h3>
        <p id="applyModalJobTitle" style="font-size:12px; color:var(--text-muted);">Senior Laravel Architect</p>
      </div>
      <button class="tf-modal-close" onclick="TF.closeModal('modalApplyJob')">✕</button>
    </div>
    <form onsubmit="TF.handleApplySubmit(event)">
      <input type="hidden" id="applyModalJobId">
      <div class="tf-modal-body">
        <div class="tf-form-group">
          <label>Full Name</label>
          <input type="text" class="tf-input" value="Alice Johnson" required>
        </div>
        <div class="tf-form-group">
          <label>Email Address</label>
          <input type="email" class="tf-input" value="candidate1@talentflow.local" required>
        </div>
        <div class="tf-form-group">
          <label>Upload PDF Resume</label>
          <input type="file" accept="application/pdf" class="tf-input" required>
          <p style="font-size:11px; color:var(--text-muted);">Triggers asynchronous queue text parsing and automated 100-pt scoring.</p>
        </div>
      </div>
      <div class="tf-modal-footer">
        <button type="button" class="tf-btn-outline" onclick="TF.closeModal('modalApplyJob')">Cancel</button>
        <button type="submit" class="tf-btn-primary">Submit Application</button>
      </div>
    </form>
  </div>
</div>
