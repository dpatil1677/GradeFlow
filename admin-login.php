<?php
require_once 'includes/db_connect.php';
require_once 'includes/auth.php';

$error = '';
$success = '';

// If already logged in, redirect
if (isset($_SESSION['admin_id'])) {
    header('Location: admin-dashboard.php');
    exit;
}

// Handle login POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $security_code = trim($_POST['security_code'] ?? '');

    if (empty($username) || empty($password) || empty($security_code)) {
        $error = 'All fields are required';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        // One-time upgrade: If password is plain text (like from db_setup.sql), update to hash
        $valid_password = false;
        if ($admin) {
            if (password_verify($password, $admin['password'])) {
                $valid_password = true;
            } elseif ($admin['password'] === $password) { // Plain text fallback
                $valid_password = true;
                $new_hash = password_hash($password, PASSWORD_DEFAULT);
                $update_stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
                $update_stmt->execute([$new_hash, $admin['id']]);
            }
        }

        if ($valid_password && $admin['security_code'] === $security_code) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $success = 'Authentication successful! Redirecting...';
        } else {
            $error = 'Invalid credentials. Please check username, password and security code.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - GradeFlow</title>
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
    <div class="auth-visual" style="background: linear-gradient(135deg, #1a0a2e, #16213e, #0f0f1a);">
      <div class="hero-bg">
        <div class="orb" style="width:400px;height:400px;background:var(--accent);top:-80px;right:-80px;filter:blur(100px);opacity:0.3;animation:float 7s ease-in-out infinite;"></div>
        <div class="orb" style="width:350px;height:350px;background:var(--primary);bottom:-80px;left:-80px;filter:blur(100px);opacity:0.3;animation:float 9s ease-in-out infinite;"></div>
      </div>
      <div class="hero-grid"></div>
      <div class="auth-visual-content animate-fade-up">
        <div class="auth-visual-icon" style="font-size: 2.5rem;">🛡️</div>
        <h2>Admin <span class="text-gradient-accent">Portal</span></h2>
        <p>Secure access to manage students, process results, track attendance, and oversee the complete academic management system.</p>

        <div style="margin-top: 40px; text-align: left;">
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <div style="width:36px;height:36px;border-radius:8px;background:rgba(253,121,168,0.15);display:flex;align-items:center;justify-content:center;color:var(--accent);"><i class="fas fa-users-cog"></i></div>
            <span style="font-size:0.9rem;color:var(--text-secondary);">Manage student records & enrollment</span>
          </div>
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <div style="width:36px;height:36px;border-radius:8px;background:rgba(108,92,231,0.15);display:flex;align-items:center;justify-content:center;color:var(--primary-light);"><i class="fas fa-chart-bar"></i></div>
            <span style="font-size:0.9rem;color:var(--text-secondary);">Process & publish examination results</span>
          </div>
          <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:36px;height:36px;border-radius:8px;background:rgba(0,206,201,0.15);display:flex;align-items:center;justify-content:center;color:var(--secondary);"><i class="fas fa-clipboard-check"></i></div>
            <span style="font-size:0.9rem;color:var(--text-secondary);">Track & manage attendance reports</span>
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
          <h1>Admin <span class="text-gradient-accent">Login</span></h1>
          <p>Authorized personnel only. Enter your admin credentials.</p>
        </div>

        <?php if ($error): ?>
        <div class="alert alert-danger" style="margin-bottom: 20px;">
          <i class="fas fa-exclamation-circle"></i>
          <span><?php echo htmlspecialchars($error); ?></span>
        </div>
        <?php endif; ?>

        <form id="adminLoginForm" action="" method="POST" novalidate>
          <!-- Username Field -->
          <div class="form-group">
            <label class="form-label" for="admin-username">Admin Username</label>
            <div class="form-input-wrapper">
              <span class="input-icon" id="usernameIcon"><i class="fas fa-user-shield"></i></span>
              <input type="text" id="admin-username" name="username" class="form-input" placeholder="Enter admin username" autocomplete="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
              <span class="validation-icon" id="usernameValidIcon"><i class="fas fa-check-circle"></i></span>
            </div>
            <div class="v-error-msg" id="usernameError"><i class="fas fa-exclamation-circle"></i> <span></span></div>
          </div>

          <!-- Password Field -->
          <div class="form-group">
            <label class="form-label" for="admin-password">Password</label>
            <div class="form-input-wrapper">
              <span class="input-icon" id="passwordIcon"><i class="fas fa-lock"></i></span>
              <input type="password" id="admin-password" name="password" class="form-input" placeholder="Enter admin password" autocomplete="current-password" style="padding-right:48px;">
              <button type="button" class="password-toggle" id="togglePassword" tabindex="-1" aria-label="Toggle password visibility">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <div class="caps-warning" id="capsWarning"><i class="fas fa-exclamation-triangle"></i> Caps Lock is ON</div>
            <div class="v-error-msg" id="passwordError"><i class="fas fa-exclamation-circle"></i> <span></span></div>
            <div class="strength-meter" id="strengthMeter">
              <div class="strength-bar" id="bar1"></div>
              <div class="strength-bar" id="bar2"></div>
              <div class="strength-bar" id="bar3"></div>
              <div class="strength-bar" id="bar4"></div>
            </div>
            <div class="strength-text" id="strengthText"></div>
          </div>

          <!-- Security Code Field -->
          <div class="form-group">
            <label class="form-label" for="security-code">Security Code</label>
            <div class="form-input-wrapper">
              <span class="input-icon" id="codeIcon"><i class="fas fa-key"></i></span>
              <input type="text" id="security-code" name="security_code" class="form-input" placeholder="Enter 6-digit security code" maxlength="6" autocomplete="off" inputmode="numeric">
              <span class="validation-icon" id="codeValidIcon"><i class="fas fa-check-circle"></i></span>
            </div>
            <div class="v-error-msg" id="codeError"><i class="fas fa-exclamation-circle"></i> <span></span></div>
            <div class="char-count" id="codeCount">0 / 6</div>
          </div>

          <!-- Remember Device -->
          <div class="form-actions">
            <div class="form-checkbox">
              <input type="checkbox" id="remember-admin">
              <label for="remember-admin">Remember this device</label>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" id="submitBtn" class="btn btn-accent btn-lg submit-btn" style="width:100%;">
            <span class="btn-text"><i class="fas fa-sign-in-alt"></i> Access Admin Panel</span>
            <span class="spinner"><span class="spinner-circle"></span> Verifying...</span>
          </button>

          <div class="alert alert-warning" style="margin-top: 24px;">
            <i class="fas fa-exclamation-triangle"></i>
            <span>This portal is restricted to authorized administrators only. All login attempts are logged.</span>
          </div>

          <div class="form-footer" style="margin-top: 20px;">
            Need access? Contact <a href="#">IT Support</a>
          </div>
        </form>
      </div>
    </div>
  </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('adminLoginForm');
  const usernameInput = document.getElementById('admin-username');
  const passwordInput = document.getElementById('admin-password');
  const codeInput = document.getElementById('security-code');
  const submitBtn = document.getElementById('submitBtn');
  const togglePasswordBtn = document.getElementById('togglePassword');

  const usernameError = document.getElementById('usernameError');
  const passwordError = document.getElementById('passwordError');
  const codeError = document.getElementById('codeError');

  const usernameIcon = document.getElementById('usernameIcon');
  const passwordIcon = document.getElementById('passwordIcon');
  const codeIcon = document.getElementById('codeIcon');
  const usernameValidIcon = document.getElementById('usernameValidIcon');
  const codeValidIcon = document.getElementById('codeValidIcon');

  const strengthMeter = document.getElementById('strengthMeter');
  const strengthText = document.getElementById('strengthText');
  const bars = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3'), document.getElementById('bar4')];
  const codeCount = document.getElementById('codeCount');
  const capsWarning = document.getElementById('capsWarning');

  <?php if ($success): ?>
  // Auto-redirect on success
  showToast('<?php echo $success; ?>', 'success');
  setTimeout(function() { window.location.href = 'admin-dashboard.php'; }, 1200);
  <?php endif; ?>

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
  function validateUsername() {
    const v = usernameInput.value.trim();
    if (!v) { showError(usernameInput, usernameError, usernameIcon, usernameValidIcon, 'Username is required'); return false; }
    if (v.length < 3) { showError(usernameInput, usernameError, usernameIcon, usernameValidIcon, 'Username must be at least 3 characters'); return false; }
    clearError(usernameInput, usernameError, usernameIcon, usernameValidIcon);
    showSuccess(usernameInput, usernameIcon, usernameValidIcon);
    return true;
  }

  function validatePassword() {
    const v = passwordInput.value;
    if (!v) { showError(passwordInput, passwordError, passwordIcon, null, 'Password is required'); resetStrength(); return false; }
    if (v.length < 6) { showError(passwordInput, passwordError, passwordIcon, null, 'Password must be at least 6 characters'); return false; }
    clearError(passwordInput, passwordError, passwordIcon, null);
    showSuccess(passwordInput, passwordIcon, null);
    return true;
  }

  function validateCode() {
    const v = codeInput.value.trim();
    if (!v) { showError(codeInput, codeError, codeIcon, codeValidIcon, 'Security code is required'); return false; }
    if (!/^\d+$/.test(v)) { showError(codeInput, codeError, codeIcon, codeValidIcon, 'Security code must contain only digits'); return false; }
    if (v.length !== 6) { showError(codeInput, codeError, codeIcon, codeValidIcon, 'Security code must be exactly 6 digits'); return false; }
    clearError(codeInput, codeError, codeIcon, codeValidIcon);
    showSuccess(codeInput, codeIcon, codeValidIcon);
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
    const t = passwordInput.type==='password'?'text':'password';
    passwordInput.type = t;
    this.querySelector('i').className = t==='password'?'fas fa-eye':'fas fa-eye-slash';
  });

  /* ---- Caps Lock ---- */
  passwordInput.addEventListener('keyup', function(e) {
    capsWarning.classList.toggle('show', e.getModifierState && e.getModifierState('CapsLock'));
  });

  /* ---- Real-time validation ---- */
  usernameInput.addEventListener('input', function() {
    if (this.value.trim()) validateUsername();
    else { clearError(this, usernameError, usernameIcon, usernameValidIcon); usernameValidIcon.classList.remove('show'); this.classList.remove('input-success'); }
  });
  passwordInput.addEventListener('input', function() {
    updateStrength(this.value);
    if (this.value) validatePassword();
    else { clearError(this, passwordError, passwordIcon, null); this.classList.remove('input-success'); resetStrength(); }
  });
  codeInput.addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g,'');
    codeCount.textContent = this.value.length + ' / 6';
    codeCount.classList.add('show');
    if (this.value) validateCode();
    else { clearError(this, codeError, codeIcon, codeValidIcon); codeValidIcon.classList.remove('show'); this.classList.remove('input-success'); }
  });

  /* ---- Blur validation ---- */
  usernameInput.addEventListener('blur', function() { if(this.value.trim()) validateUsername(); });
  passwordInput.addEventListener('blur', function() { capsWarning.classList.remove('show'); if(this.value) validatePassword(); });
  codeInput.addEventListener('blur', function() { if(this.value.trim()) validateCode(); });

  /* ---- Focus micro-animation ---- */
  [usernameInput, passwordInput, codeInput].forEach(inp => {
    inp.addEventListener('focus', function() { this.parentElement.style.transform='scale(1.01)'; this.parentElement.style.transition='transform 0.2s ease'; });
    inp.addEventListener('blur', function() { this.parentElement.style.transform='scale(1)'; });
  });

  /* ---- Submit ---- */
  form.addEventListener('submit', function(e) {
    const u=validateUsername(), p=validatePassword(), c=validateCode();
    if (!u||!p||!c) {
      e.preventDefault();
      if(!u) usernameInput.focus(); else if(!p) passwordInput.focus(); else codeInput.focus();
      showToast('Please fix the errors before proceeding','error');
      form.style.animation='shake 0.4s ease'; setTimeout(()=>form.style.animation='',400);
      return;
    }
    // Show loading state while form submits
    submitBtn.classList.add('loading');
  });

  /* ---- Paste restriction on security code ---- */
  codeInput.addEventListener('paste', function(e) {
    const txt = (e.clipboardData||window.clipboardData).getData('text');
    if(!/^\d{1,6}$/.test(txt)) { e.preventDefault(); showToast('Only numeric values can be pasted','warning'); }
  });

  /* ---- Enter key navigation ---- */
  usernameInput.addEventListener('keydown', function(e) { if(e.key==='Enter'){e.preventDefault(); if(validateUsername()) passwordInput.focus();} });
  passwordInput.addEventListener('keydown', function(e) { if(e.key==='Enter'){e.preventDefault(); if(validatePassword()) codeInput.focus();} });
});
</script>

</body>
</html>
