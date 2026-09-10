<!-- Modal: Post New Job -->
<div id="modalPostJob" class="tf-modal-backdrop">
  <div class="tf-modal">
    <div class="tf-modal-header">
      <h3 id="jobModalTitle">Create New Job Requisition</h3>
      <button class="tf-modal-close" onclick="TF.closeModal('modalPostJob')">✕</button>
    </div>
    <form onsubmit="TF.handleCreateJobSubmit(event)">
      <div class="tf-modal-body">
        <div class="tf-form-group">
          <label>Job Title</label>
          <input type="text" id="newJobTitle" class="tf-input" placeholder="e.g. Lead Platform Engineer" required>
        </div>
        <div class="tf-form-group">
          <label>Department</label>
          <select id="newJobDept" class="tf-input">
            <option value="Engineering">Engineering</option>
            <option value="Product">Product</option>
            <option value="Infrastructure">Infrastructure</option>
            <option value="Data Science">Data Science</option>
          </select>
        </div>
        <div class="tf-form-group">
          <label>Required Experience (Years)</label>
          <input type="number" id="newJobExp" class="tf-input" step="0.5" min="0" value="4.0" required>
        </div>
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
          <div class="tf-form-group">
            <label>Min Salary ($)</label>
            <input type="number" id="newJobMinSalary" class="tf-input" value="95000">
          </div>
          <div class="tf-form-group">
            <label>Max Salary ($)</label>
            <input type="number" id="newJobMaxSalary" class="tf-input" value="135000">
          </div>
        </div>
        <div class="tf-form-group">
          <label>Weighted Skills</label>
          <p style="font-size:11px; color:var(--text-muted); margin-bottom:6px;">Configured with PHP (w:5), Laravel (w:5), MySQL (w:3) by default.</p>
        </div>
      </div>
      <div class="tf-modal-footer">
        <button type="button" class="tf-btn-outline" onclick="TF.closeModal('modalPostJob')">Cancel</button>
        <button id="jobModalSubmit" type="submit" class="tf-btn-primary">Publish Job</button>
      </div>
    </form>
  </div>
</div>
