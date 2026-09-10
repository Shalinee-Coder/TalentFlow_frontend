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

    </div>
    <div class="tf-modal-footer">
      <button type="button" class="tf-btn-outline" onclick="TF.logout()">Sign Out</button>
      <button type="button" class="tf-btn-outline" onclick="TF.closeModal('modalAuth')">Close</button>
    </div>
  </div>
</div>
