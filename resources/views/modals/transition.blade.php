<!-- Modal: Pipeline Status Transition -->
<div id="modalTransition" class="tf-modal-backdrop">
  <div class="tf-modal">
    <div class="tf-modal-header">
      <div>
        <h3>Advance Candidate Pipeline Stage</h3>
        <p id="transitionCandidateName" style="font-size:12px; color:var(--text-muted); font-weight:700;">Alice Johnson</p>
        <p id="transitionJobTitle" style="font-size:11px; color:var(--text-muted);">Senior Laravel Architect</p>
      </div>
      <button class="tf-modal-close" onclick="TF.closeModal('modalTransition')">✕</button>
    </div>
    <form onsubmit="TF.handleTransitionSubmit(event)">
      <input type="hidden" id="transitionAppId">
      <div class="tf-modal-body">
        <div style="background:var(--surface-subtle); padding:12px 16px; border-radius:var(--radius-sm); font-size:13px; display:flex; justify-content:space-between;">
          <span>Current Stage:</span>
          <strong id="transitionCurrentStatus" style="color:var(--primary-color);">SCREENING</strong>
        </div>
        <div class="tf-form-group">
          <label>Select Target Stage (Enforces Transition Guard)</label>
          <select id="transitionTargetStatus" class="tf-input">
            <option value="screening">Screening</option>
            <option value="shortlisted">Shortlisted</option>
            <option value="interview">Interview</option>
            <option value="technical_task">Technical Task</option>
            <option value="hired">Hired</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>
        <div class="tf-form-group">
          <label>Audit Remarks</label>
          <textarea id="transitionRemarks" class="tf-input" rows="3" placeholder="Enter evaluation feedback or rationale for audit trail..."></textarea>
        </div>
        <p id="transitionError" style="display:none; color:#c74631; font-size:12px; margin-top:8px; padding:8px 12px; background:#fff2f0; border-radius:6px;"></p>
      </div>
      <div class="tf-modal-footer">
        <button type="button" class="tf-btn-outline" onclick="TF.closeModal('modalTransition')">Cancel</button>
        <button type="submit" class="tf-btn-primary">Save Transition</button>
      </div>
    </form>
  </div>
</div>
