<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Create Account | TalentFlow</title>
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
        <span class="tf-login-eyebrow">CANDIDATE PORTAL</span>
        <h1>Bring your next opportunity into focus.</h1>
        <p>Create your profile once, upload your resume, and follow every step of your application.</p>
      </div>
      <div class="tf-login-signal">
        <span class="tf-login-signal-dot"></span>
        <span>Candidate workspace online</span>
      </div>
    </section>

    <section class="tf-login-panel" aria-labelledby="registerHeading">
      <div class="tf-login-panel-inner">
        <div class="tf-login-heading">
          <span class="tf-login-eyebrow">GET STARTED</span>
          <h2 id="registerHeading">Create your account</h2>
          <p>Build your candidate profile in less than a minute.</p>
        </div>

        <form id="registerPageForm" class="tf-login-form">
          <div class="tf-form-group">
            <label for="registerName">Full Name</label>
            <input id="registerName" class="tf-input" type="text" placeholder="Your full name" autocomplete="name" required>
          </div>
          <div class="tf-form-group">
            <label for="registerEmail">Email Address</label>
            <input id="registerEmail" class="tf-input" type="email" placeholder="you@example.com" autocomplete="email" required>
          </div>
          <div class="tf-form-group">
            <label for="registerPassword">Password</label>
            <input id="registerPassword" class="tf-input" type="password" placeholder="At least 8 characters" minlength="8" autocomplete="new-password" required>
          </div>
          <div class="tf-form-group">
            <label for="registerPasswordConfirmation">Confirm Password</label>
            <input id="registerPasswordConfirmation" class="tf-input" type="password" placeholder="Repeat your password" minlength="8" autocomplete="new-password" required>
          </div>
          <p id="registerError" class="tf-login-error" role="alert"></p>
          <button id="registerSubmit" class="tf-btn-primary tf-login-submit" type="submit">Create Account</button>
        </form>

        <p class="tf-login-switch">Already have an account? <a href="/login">Sign in</a></p>
      </div>
    </section>
  </main>

  <script>
    const registerForm = document.getElementById('registerPageForm');
    const registerName = document.getElementById('registerName');
    const registerEmail = document.getElementById('registerEmail');
    const registerPassword = document.getElementById('registerPassword');
    const registerConfirmation = document.getElementById('registerPasswordConfirmation');
    const registerError = document.getElementById('registerError');
    const registerSubmit = document.getElementById('registerSubmit');

    registerForm.addEventListener('submit', async event => {
      event.preventDefault();
      registerError.textContent = '';
      registerSubmit.disabled = true;
      registerSubmit.textContent = 'Creating account...';

      if (registerPassword.value !== registerConfirmation.value) {
        registerError.textContent = 'Passwords do not match.';
        registerSubmit.disabled = false;
        registerSubmit.textContent = 'Create Account';
        return;
      }

      try {
        const response = await fetch('/api/v1/auth/register', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            name: registerName.value.trim(),
            email: registerEmail.value.trim(),
            password: registerPassword.value,
            password_confirmation: registerConfirmation.value
          })
        });
        const result = await response.json();

        if (!response.ok || !result.success || !result.data?.token) {
          throw new Error(result.errors?.email?.[0] || result.errors?.password?.[0] || result.message || 'Unable to create account.');
        }

        const user = result.data.user;
        const initials = user.name.split(' ').map(part => part[0]).join('').slice(0, 2).toUpperCase();
        localStorage.setItem('tf_token', result.data.token);
        localStorage.setItem('tf_user', JSON.stringify({
          name: user.name,
          email: user.email,
          role: result.data.role || 'candidate',
          avatar: initials
        }));
        window.location.href = '/';
      } catch (error) {
        registerError.textContent = error.message;
        registerSubmit.disabled = false;
        registerSubmit.textContent = 'Create Account';
      }
    });
  </script>
</body>
</html>
