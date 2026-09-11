<!-- Modal: Apply for Job (Candidate Experience) -->
<div id="modalApplyJob" class="tf-modal-backdrop">
  <div class="tf-modal">
    <div class="tf-modal-header">
      <div>
        <h3>Apply for Position</h3>
        <p id="applyModalJobTitle" style="font-size:12px; color:var(--text-muted); font-weight:600;">Senior Laravel Architect</p>
      </div>
      <button class="tf-modal-close" onclick="TF.closeModal('modalApplyJob')">✕</button>
    </div>
    <form id="applyJobForm" onsubmit="TF.handleApplySubmit(event)">
      <input type="hidden" id="applyModalJobId">
      <div class="tf-modal-body">
        <div class="tf-form-group">
          <label for="applyModalFullName">Full Name</label>
          <input type="text" id="applyModalFullName" class="tf-input" placeholder="e.g. Aarav Sharma" required>
        </div>
        <div class="tf-form-group">
          <label for="applyModalEmail">Email Address</label>
          <input type="email" id="applyModalEmail" class="tf-input" placeholder="candidate@talentflow.local" required>
        </div>
        <div class="tf-form-group">
          <label for="applyModalPhone">Phone Number (Optional)</label>
          <input type="tel" id="applyModalPhone" class="tf-input" placeholder="+91-98765-43210">
        </div>
        <div class="tf-form-group">
          <label for="applyModalResumeFile">Upload PDF Resume</label>
          <input type="file" id="applyModalResumeFile" accept="application/pdf,.pdf" class="tf-input" required>
          <p style="font-size:11px; color:var(--text-muted); margin-top:4px;">PDF only (max 5 MB). Triggers instant text extraction and 100-point candidate scoring.</p>
        </div>
        <p id="applyModalError" style="display:none; color:#c74631; font-size:12px; margin-top:8px; padding:8px 12px; background:#fff2f0; border-radius:6px;"></p>
      </div>
      <div class="tf-modal-footer">
        <button type="button" class="tf-btn-outline" onclick="TF.closeModal('modalApplyJob')">Cancel</button>
        <button type="submit" id="applyModalSubmit" class="tf-btn-primary">Submit Application</button>
      </div>
    </form>
  </div>
</div>
