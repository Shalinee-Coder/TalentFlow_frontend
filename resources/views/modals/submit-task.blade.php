<!-- Modal: Submit Technical Task (Candidate Experience) -->
<div id="modalSubmitTask" class="tf-modal-backdrop">
  <div class="tf-modal">
    <div class="tf-modal-header">
      <div>
        <h3>Submit Technical Assessment</h3>
        <p id="submitTaskModalTitle" style="font-size:12px; color:var(--text-muted); font-weight:600;">Technical Task</p>
      </div>
      <button class="tf-modal-close" onclick="TF.closeModal('modalSubmitTask')">✕</button>
    </div>
    <form id="submitTaskForm" onsubmit="TF.handleSubmitTask(event)">
      <input type="hidden" id="submitTaskId">
      <div class="tf-modal-body">
        <div class="tf-form-group">
          <label for="submitTaskUrl">Solution Repository / Demo URL</label>
          <input type="url" id="submitTaskUrl" class="tf-input" placeholder="https://github.com/username/repository" required>
          <p style="font-size:11px; color:var(--text-muted); margin-top:4px;">Link to your public repository, deployment, or recorded demonstration.</p>
        </div>
        <div class="tf-form-group">
          <label for="submitTaskNotes">Notes / Narrative</label>
          <textarea id="submitTaskNotes" class="tf-input" rows="4" placeholder="Briefly describe your solution, architecture choices, test instructions, and assumptions..."></textarea>
        </div>
        <p id="submitTaskError" style="display:none; color:#c74631; font-size:12px; margin-top:8px; padding:8px 12px; background:#fff2f0; border-radius:6px;"></p>
      </div>
      <div class="tf-modal-footer">
        <button type="button" class="tf-btn-outline" onclick="TF.closeModal('modalSubmitTask')">Cancel</button>
        <button type="submit" id="submitTaskBtn" class="tf-btn-primary">Submit Assessment</button>
      </div>
    </form>
  </div>
</div>
