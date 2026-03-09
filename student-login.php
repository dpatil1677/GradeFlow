<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Login - GradeFlow</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/validation.css">
</head>
<body>

  <!-- Toast Notification Container -->
  <div id="toast" class="toast-notification"></div>

  <div class="auth-page">
    <!-- Left Visual Panel -->
    <div class="auth-visual">
      <div class="hero-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
      </div>
      <div class="hero-grid"></div>
      <div class="auth-visual-content animate-fade-up">
        <div class="auth-visual-icon">🎓</div>
        <h2>Welcome Back, <span class="text-gradient">Student!</span></h2>
        <p>Access your academic dashboard, view results, check attendance, and track your academic progress all in one place.</p>

        <div style="display: flex; gap: 24px; justify-content: center; margin-top: 40px;">
          <div class="hero-stat">
            <div class="number text-gradient" style="font-size:1.6rem;">24/7</div>
            <div class="label">Access</div>
          </div>
          <div class="hero-stat">
            <div class="number text-gradient" style="font-size:1.6rem;">Instant</div>
            <div class="label">Results</div>
          </div>
          <div class="hero-stat">
            <div class="number text-gradient" style="font-size:1.6rem;">Secure</div>
            <div class="label">Platform</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Form Panel -->
    <div class="auth-form-section">
      <div class="auth-form-wrapper animate-fade-left">
        <div class="auth-form-header">
          <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Home
          </a>
          <h1>Student <span class="text-gradient">Login</span></h1>
          <p>Enter your credentials to access your academic portal</p>
        </div>

        <form id="studentLoginForm" action="#" method="POST" novalidate>
          <!-- Roll Number Field -->
          <div class="form-group">
            <label class="form-label" for="roll">Roll Number</label>
            <div class="form-input-wrapper">
              <span class="input-icon" id="rollIcon"><i class="fas fa-id-badge"></i></span>
              <input type="text" id="roll" name="roll_number" class="form-input" placeholder="Enter your roll number" autocomplete="username" inputmode="numeric">
              <span class="validation-icon" id="rollValidIcon"><i class="fas fa-check-circle"></i></span>
            </div>
            <div class="v-error-msg" id="rollError"><i class="fas fa-exclamation-circle"></i> <span></span></div>
            <div class="char-count" id="rollCount">&nbsp;</div>
          </div>

          <!-- Password Field -->
          <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="form-input-wrapper">
              <span class="input-icon" id="passwordIcon"><i class="fas fa-lock"></i></span>
              <input type="password" id="password" name="password" class="form-input" placeholder="Enter your password" autocomplete="current-password" style="padding-right:48px;">
              <button type="button" class="password-toggle" id="togglePassword" tabindex="-1" aria-label="Toggle password visibility">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <div class="caps-warning" id="capsWarning"><i class="fas fa-exclamation-triangle"></i> Caps Lock is ON</div>
            <div class="v-error-msg" id="passError"><i class="fas fa-exclamation-circle"></i> <span></span></div>
            <div class="strength-meter" id="strengthMeter">
              <div class="strength-bar" id="bar1"></div>
              <div class="strength-bar" id="bar2"></div>
              <div class="strength-bar" id="bar3"></div>
              <div class="strength-bar" id="bar4"></div>
            </div>
            <div class="strength-text" id="strengthText"></div>
          </div>

          <!-- Remember & Forgot -->
          <div class="form-actions">
            <div class="form-checkbox">
              <input type="checkbox" id="remember">
              <label for="remember">Remember me</label>
            </div>
            <a href="#" class="form-link">Forgot Password?</a>
          </div>

          <!-- Submit Button -->
          <button type="submit" id="submitBtn" class="btn btn-primary btn-lg submit-btn" style="width:100%;">
            <span class="btn-text"><i class="fas fa-sign-in-alt"></i> Sign In</span>
            <span class="spinner"><span class="spinner-circle"></span> Authenticating...</span>
          </button>

          <!-- Login Attempts Warning -->
          <div class="login-attempts" id="loginAttempts">
            <i class="fas fa-info-circle"></i> <span id="attemptCount">5</span> attempts remaining
          </div>

          <div class="form-divider">or</div>

          <a href="result-search.php" class="btn btn-ghost btn-lg" style="width:100%;">
            <i class="fas fa-search"></i> Quick Result Search
          </a>

          <div class="form-footer" style="margin-top: 28px;">
            New student? Contact your <a href="#">administrator</a> for account setup.
          </div>
        </form>
      </div>
    </div>
  </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('studentLoginForm');
  const rollInput = document.getElementById('roll');
  const passInput = document.getElementById('password');
  const submitBtn = document.getElementById('submitBtn');
  const togglePasswordBtn = document.getElementById('togglePassword');

  const rollError = document.getElementById('rollError');
  const passError = document.getElementById('passError');

  const rollIcon = document.getElementById('rollIcon');
  const passwordIcon = document.getElementById('passwordIcon');
  const rollValidIcon = document.getElementById('rollValidIcon');

  const strengthMeter = document.getElementById('strengthMeter');
  const strengthText = document.getElementById('strengthText');
  const bars = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3'), document.getElementById('bar4')];
  const rollCount = document.getElementById('rollCount');
  const capsWarning = document.getElementById('capsWarning');
  const loginAttemptsEl = document.getElementById('loginAttempts');
  const attemptCountEl = document.getElementById('attemptCount');
  let maxAttempts = 5, currentAttempts = 0;

  /* ---- Toast ---- */
  function showToast(message, type) {
    const toast = document.getElementById('toast');
    const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-times-circle' : 'fa-exclamation-triangle';
    toast.className = 'toast-notification toast-' + type;
    toast.innerHTML = '<i class="fas ' + icon + '"></i> ' + message;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3500);
  }

  /* ---- Error helpers ---- */
  function showError(input, errorEl, iconEl, validIconEl, msg) {
    errorEl.querySelector('span').textContent = msg;
    errorEl.classList.add('show');
    input.classList.add('input-error');
    input.classList.remove('input-success');
    if (iconEl) { iconEl.classList.add('icon-error'); iconEl.classList.remove('icon-success'); }
    if (validIconEl) { validIconEl.classList.remove('show','valid'); validIconEl.classList.add('show','invalid'); validIconEl.querySelector('i').className='fas fa-times-circle'; }
  }
  function clearError(input, errorEl, iconEl, validIconEl) {
    errorEl.classList.remove('show');
    input.classList.remove('input-error');
    if (iconEl) iconEl.classList.remove('icon-error');
  }
  function showSuccess(input, iconEl, validIconEl) {
    input.classList.remove('input-error'); input.classList.add('input-success');
    if (iconEl) { iconEl.classList.remove('icon-error'); iconEl.classList.add('icon-success'); }
    if (validIconEl) { validIconEl.classList.remove('invalid'); validIconEl.classList.add('show','valid'); validIconEl.querySelector('i').className='fas fa-check-circle'; }
  }

  /* ---- Validators ---- */
  function validateRoll() {
    const v = rollInput.value.trim();
    if (!v) { showError(rollInput, rollError, rollIcon, rollValidIcon, 'Roll number is required'); return false; }
    if (!/^\d+$/.test(v)) { showError(rollInput, rollError, rollIcon, rollValidIcon, 'Roll number must contain only digits'); return false; }
    if (v.length < 4) { showError(rollInput, rollError, rollIcon, rollValidIcon, 'Roll number must be at least 4 digits'); return false; }
    if (v.length > 10) { showError(rollInput, rollError, rollIcon, rollValidIcon, 'Roll number must not exceed 10 digits'); return false; }
    clearError(rollInput, rollError, rollIcon, rollValidIcon);
    showSuccess(rollInput, rollIcon, rollValidIcon);
    return true;
  }

  function validatePassword() {
    const v = passInput.value;
    if (!v) { showError(passInput, passError, passwordIcon, null, 'Password is required'); resetStrength(); return false; }
    if (v.length < 6) { showError(passInput, passError, passwordIcon, null, 'Password must be at least 6 characters'); return false; }
    if (v.length > 50) { showError(passInput, passError, passwordIcon, null, 'Password must not exceed 50 characters'); return false; }
    clearError(passInput, passError, passwordIcon, null);
    showSuccess(passInput, passwordIcon, null);
    return true;
  }

  /* ---- Password Strength ---- */
  function calcStrength(p) { let s=0; if(p.length>=6)s++; if(p.length>=10)s++; if(/[A-Z]/.test(p)&&/[a-z]/.test(p))s++; if(/\d/.test(p))s++; if(/[^a-zA-Z0-9]/.test(p))s++; return Math.min(s,4); }
  function updateStrength(p) {
    if (!p.length) { resetStrength(); return; }
    strengthMeter.classList.add('show');
    const s = calcStrength(p);
    const lvl = ['','Weak','Fair','Good','Strong'], cls = ['','weak','medium','medium','strong'];
    bars.forEach((b,i) => { b.className='strength-bar'; if(i<s) b.classList.add('active',cls[s]); });
    if (s>0) { strengthText.textContent=lvl[s]; strengthText.className='strength-text show '+cls[s]; }
    else strengthText.className='strength-text';
  }
  function resetStrength() { bars.forEach(b=>b.className='strength-bar'); strengthMeter.classList.remove('show'); strengthText.className='strength-text'; }

  /* ---- Toggle Password ---- */
  togglePasswordBtn.addEventListener('click', function() {
    const t = passInput.type==='password'?'text':'password';
    passInput.type = t;
    this.querySelector('i').className = t==='password'?'fas fa-eye':'fas fa-eye-slash';
  });

  /* ---- Caps Lock ---- */
  passInput.addEventListener('keyup', function(e) {
    capsWarning.classList.toggle('show', e.getModifierState && e.getModifierState('CapsLock'));
  });

  /* ---- Real-time validation ---- */
  rollInput.addEventListener('input', function() {
    // Allow only digits
    this.value = this.value.replace(/[^0-9]/g, '');
    rollCount.textContent = this.value.length > 0 ? this.value.length + ' digits' : '\u00A0';
    rollCount.classList.toggle('show', this.value.length > 0);
    if (this.value.trim()) validateRoll();
    else { clearError(this, rollError, rollIcon, rollValidIcon); rollValidIcon.classList.remove('show'); this.classList.remove('input-success'); }
  });

  passInput.addEventListener('input', function() {
    updateStrength(this.value);
    if (this.value) validatePassword();
    else { clearError(this, passError, passwordIcon, null); this.classList.remove('input-success'); resetStrength(); }
  });

  /* ---- Blur validation ---- */
  rollInput.addEventListener('blur', function() { if(this.value.trim()) validateRoll(); });
  passInput.addEventListener('blur', function() { capsWarning.classList.remove('show'); if(this.value) validatePassword(); });

  /* ---- Focus micro-animation ---- */
  [rollInput, passInput].forEach(inp => {
    inp.addEventListener('focus', function() { this.parentElement.style.transform='scale(1.01)'; this.parentElement.style.transition='transform 0.2s ease'; });
    inp.addEventListener('blur', function() { this.parentElement.style.transform='scale(1)'; });
  });

  /* ---- Submit ---- */
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const r = validateRoll(), p = validatePassword();
    if (!r || !p) {
      if (!r) rollInput.focus(); else passInput.focus();
      showToast('Please fix the errors before proceeding', 'error');
      form.style.animation = 'shake 0.4s ease'; setTimeout(() => form.style.animation = '', 400);
      return;
    }
    currentAttempts++;
    if (currentAttempts >= maxAttempts) { showToast('Too many attempts. Please try again later.', 'error'); submitBtn.disabled = true; submitBtn.style.opacity = '0.5'; return; }
    const rem = maxAttempts - currentAttempts;
    attemptCountEl.textContent = rem;
    if (currentAttempts >= 2) loginAttemptsEl.classList.add('show');

    submitBtn.classList.add('loading');
    setTimeout(function() {
      submitBtn.classList.remove('loading');
      showToast('Login successful! Redirecting to dashboard...', 'success');
      submitBtn.style.background = 'var(--gradient-secondary)';
      submitBtn.style.animation = 'successPulse 0.6s ease';
      setTimeout(() => window.location.href = 'student-dashboard.php', 1200);
    }, 1500);
  });

  /* ---- Enter key navigation ---- */
  rollInput.addEventListener('keydown', function(e) { if(e.key==='Enter'){e.preventDefault(); if(validateRoll()) passInput.focus();} });
});
</script>

</body>
</html>
