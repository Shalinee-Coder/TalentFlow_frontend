<!-- Modal: Authentication -->
<div id="modalAuth" class="tf-modal-backdrop">
  <div class="tf-modal">
    <div class="tf-modal-header">
      <h3>Account Access</h3>
      <button class="tf-modal-close" onclick="TF.closeModal('modalAuth')">✕</button>
    </div>
    <div class="tf-modal-body">
      <form onsubmit="TF.handleLoginSubmit(event)" style="margin-bottom:24px;">
        <div class="tf-form-group">
          <label for="loginEmail">Email Address</label>
          <input id="loginEmail" type="email" class="tf-input" placeholder="you@example.com" required autocomplete="email">
        </div>
        <div class="tf-form-group">
          <label for="loginPassword">Password</label>
          <input id="loginPassword" type="password" class="tf-input" placeholder="Your password" required autocomplete="current-password">
        </div>
        <p id="loginError" style="display:none; color:#c74631; font-size:12px; margin-bottom:10px;"></p>
        <button type="submit" class="tf-btn-primary" style="width:100%; justify-content:center;">Sign In</button>
      </form>

      <!-- 1-Click Demo Accounts -->
      <div style="padding-top:14px; border-top:1px solid var(--border-subtle, #e5e7eb);">
        <p style="font-size:11px; font-weight:600; color:var(--text-muted, #6b7280); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">Instant Demo Account</p>
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:8px;">
          <button type="button" class="tf-btn-outline" style="font-size:11px; padding:6px 4px; text-align:center; justify-content:center;" onclick="TF.fillAndLogin('admin@talentflow.local', 'password')">Admin</button>
          <button type="button" class="tf-btn-outline" style="font-size:11px; padding:6px 4px; text-align:center; justify-content:center;" onclick="TF.fillAndLogin('recruiter1@talentflow.local', 'password')">Recruiter</button>
          <button type="button" class="tf-btn-outline" style="font-size:11px; padding:6px 4px; text-align:center; justify-content:center;" onclick="TF.fillAndLogin('candidate1@talentflow.local', 'password')">Candidate</button>
        </div>
      </div>
    </div>
    <div class="tf-modal-footer">
      <button type="button" class="tf-btn-outline" onclick="TF.logout()">Sign Out</button>
      <button type="button" class="tf-btn-outline" onclick="TF.closeModal('modalAuth')">Close</button>
    </div>
  </div>
</div>
