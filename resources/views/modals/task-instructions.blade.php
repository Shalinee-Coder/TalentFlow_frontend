<!-- Modal: Technical Task Instructions & Rubric -->
<div id="modalTaskInstructions" class="tf-modal-backdrop">
  <div class="tf-modal" style="max-width: 580px;">
    <div class="tf-modal-header">
      <div>
        <h3 id="taskInstructionsTitle">Task Instructions & Criteria</h3>
        <p id="taskInstructionsSubtitle" style="font-size:12px; color:var(--text-muted); font-weight:600; margin-top:2px;">Technical Assessment</p>
      </div>
      <button class="tf-modal-close" onclick="TF.closeModal('modalTaskInstructions')">✕</button>
    </div>
    <div class="tf-modal-body">
      <div style="background:var(--surface-subtle); padding:14px 16px; border-radius:var(--radius-sm); font-size:13px; margin-bottom:14px; border:1px solid var(--border-subtle);">
        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
          <span style="color:var(--text-muted);">Candidate:</span>
          <strong id="taskInstructionsCandidate" style="color:var(--text-primary);">-</strong>
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
          <span style="color:var(--text-muted);">Role / Opening:</span>
          <strong id="taskInstructionsJob" style="color:var(--text-primary);">-</strong>
        </div>
        <div style="display:flex; justify-content:space-between;">
          <span style="color:var(--text-muted);">Submission Due Date:</span>
          <strong id="taskInstructionsDue" style="color:var(--text-primary);">-</strong>
        </div>
      </div>

      <div class="tf-form-group">
        <label style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-muted);">Assessment Instructions & Requirements</label>
        <div id="taskInstructionsContent" style="white-space:pre-wrap; line-height:1.6; font-size:13px; color:var(--text-primary); background:var(--surface); border:1px solid var(--border-color); border-radius:var(--radius-sm); padding:14px; max-height:220px; overflow-y:auto;"></div>
      </div>

      <div id="taskInstructionsGuidelines" style="font-size:12px; color:var(--text-muted); line-height:1.5; background:rgba(99,102,241,0.06); border-left:3px solid var(--primary-color); padding:10px 14px; border-radius:4px;">
        📌 <strong>Hiring Pipeline Integration:</strong> This technical assessment evaluates candidate proficiency directly against job criteria. Progress and status can be tracked directly in the Hiring Pipeline.
      </div>
    </div>
    <div class="tf-modal-footer" style="display:flex; justify-content:space-between; align-items:center;">
      <button type="button" class="tf-btn-outline" onclick="TF.closeModal('modalTaskInstructions')">Close</button>
      <div style="display:flex; gap:8px;">
        <button type="button" class="tf-btn-primary" id="btnTaskOpenHiring" onclick="TF.openHiringFromTaskModal()">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
          Open Hiring Pipeline
        </button>
      </div>
    </div>
  </div>
</div>
