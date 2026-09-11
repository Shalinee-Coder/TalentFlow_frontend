<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Sign In | TalentFlow</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/talentflow-theme.css">
</head>
<body class="tf-login-page">
  <main class="tf-login-shell">
    <section class="tf-login-intro">
      <div class="tf-login-brand">
        <span class="tf-login-brand-mark">TF</span>
        <span>TalentFlow</span>
      </div>
      <div class="tf-login-copy">
        <span class="tf-login-eyebrow">RECRUITMENT OPERATIONS</span>
        <h1>Hire with a clearer view of talent.</h1>
        <p>Move every candidate from first application to final offer with one focused workspace.</p>
      </div>
      <div class="tf-login-signal">
        <span class="tf-login-signal-dot"></span>
        <span>Pipeline workspace online</span>
      </div>
    </section>

    <section class="tf-login-panel" aria-labelledby="loginHeading">
      <div class="tf-login-panel-inner">
        <div class="tf-login-heading">
          <span class="tf-login-eyebrow">WELCOME BACK</span>
          <h2 id="loginHeading">Sign in to TalentFlow</h2>
          <p>Use your account credentials to continue.</p>
        </div>

        <form id="loginPageForm" class="tf-login-form">
          <div class="tf-form-group">
            <label for="pageLoginEmail">Email Address</label>
            <input id="pageLoginEmail" class="tf-input" type="email" placeholder="you@example.com" autocomplete="email" required>
          </div>
          <div class="tf-form-group">
            <div class="tf-login-label-row">
              <label for="pageLoginPassword">Password</label>
              <span>Minimum 8 characters</span>
            </div>
            <input id="pageLoginPassword" class="tf-input" type="password" placeholder="Enter your password" autocomplete="current-password" required>
          </div>
          <p id="pageLoginError" class="tf-login-error" role="alert"></p>
          <button id="pageLoginSubmit" class="tf-btn-primary tf-login-submit" type="submit">Sign In</button>

          <!-- 1-Click Demo Accounts -->
          <div style="margin-top:16px; padding-top:14px; border-top:1px solid var(--border-subtle, #e5e7eb);">
            <p style="font-size:11px; font-weight:600; color:var(--text-muted, #6b7280); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">Instant Demo Login</p>
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:8px;">
              <button type="button" class="tf-btn-outline" style="font-size:11px; padding:6px 4px; text-align:center; justify-content:center;" onclick="quickLogin('admin@talentflow.local', 'password')">Admin</button>
              <button type="button" class="tf-btn-outline" style="font-size:11px; padding:6px 4px; text-align:center; justify-content:center;" onclick="quickLogin('recruiter1@talentflow.local', 'password')">Recruiter</button>
              <button type="button" class="tf-btn-outline" style="font-size:11px; padding:6px 4px; text-align:center; justify-content:center;" onclick="quickLogin('candidate1@talentflow.local', 'password')">Candidate</button>
            </div>
          </div>
        </form>

        <p class="tf-login-switch">New to TalentFlow? <a href="/register">Create an account</a></p>
        <a class="tf-login-back" href="/">Back to dashboard preview</a>
      </div>
    </section>
  </main>

  <script>
    const loginForm = document.getElementById('loginPageForm');
    const emailInput = document.getElementById('pageLoginEmail');
    const passwordInput = document.getElementById('pageLoginPassword');
    const errorMessage = document.getElementById('pageLoginError');
    const submitButton = document.getElementById('pageLoginSubmit');

    function quickLogin(email, password) {
      emailInput.value = email;
      passwordInput.value = password;
      loginForm.dispatchEvent(new Event('submit', { cancelable: true }));
    }

    loginForm.addEventListener('submit', async event => {
      event.preventDefault();
      errorMessage.textContent = '';
      submitButton.disabled = true;
      submitButton.textContent = 'Signing in...';

      try {
        const response = await fetch('/api/v1/auth/login', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            email: emailInput.value.trim(),
            password: passwordInput.value
          })
        });
        const result = await response.json();

        if (!response.ok || !result.success || !result.data?.token) {
          throw new Error(result.errors?.email?.[0] || result.message || 'Unable to sign in.');
        }

        const user = result.data.user;
        const initials = user.name.split(' ').map(part => part[0]).join('').slice(0, 2).toUpperCase();
        localStorage.setItem('tf_token', result.data.token);
        localStorage.setItem('tf_user', JSON.stringify({
          id: user.id,
          name: user.name,
          email: user.email,
          role: result.data.role || user.role,
          avatar: initials
        }));
        window.location.href = '/';
      } catch (error) {
        errorMessage.textContent = error.message;
        submitButton.disabled = false;
        submitButton.textContent = 'Sign In';
      }
    });
  </script>
</body>
</html>
