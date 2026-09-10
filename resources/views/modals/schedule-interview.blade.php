<!-- Modal: Schedule Interview (Conflict Guard Demo) -->
<div id="modalSchedule" class="tf-modal-backdrop">
  <div class="tf-modal">
    <div class="tf-modal-header">
      <h3>Schedule Candidate Interview</h3>
      <button class="tf-modal-close" onclick="TF.closeModal('modalSchedule')">✕</button>
    </div>
    <form onsubmit="TF.handleScheduleSubmit(event)">
      <div class="tf-modal-body">
        <div class="tf-form-group">
          <label>Candidate</label>
          <select id="interviewApplication" class="tf-input" required>
            <option value="">Loading applications...</option>
          </select>
        </div>
        <div class="tf-form-group">
          <label>Interviewer</label>
          <select id="interviewInterviewer" class="tf-input">
            <option>Current account</option>
          </select>
        </div>
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
          <div class="tf-form-group">
            <label>Date</label>
            <input type="date" id="interviewDate" class="tf-input" value="2026-10-15" required>
          </div>
          <div class="tf-form-group">
            <label>Time (UTC)</label>
            <select id="interviewTime" class="tf-input">
              <option value="09:00">09:00 AM</option>
              <option value="10:00">10:00 AM (Test Conflict!)</option>
              <option value="11:00">11:00 AM (Back-to-Back)</option>
              <option value="14:00">02:00 PM</option>
            </select>
          </div>
        </div>
        <div class="tf-form-group">
          <label>Meeting Link</label>
          <input id="interviewMeetingLink" type="url" class="tf-input" value="https://meet.google.com/talentflow-interview" required>
        </div>
      </div>
      <div class="tf-modal-footer">
        <button type="button" class="tf-btn-outline" onclick="TF.closeModal('modalSchedule')">Cancel</button>
        <button type="submit" class="tf-btn-primary">Confirm & Book</button>
      </div>
    </form>
  </div>
</div>
