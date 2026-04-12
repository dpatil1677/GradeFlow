<?php
require_once 'includes/db_connect.php';
require_once 'includes/auth.php';
requireStudent();

$student     = getStudentInfo();
$stuInitials = getInitials($student['full_name']);
$studentId   = $student['id'];

$stuData = $pdo->prepare("SELECT s.*, c.course_name, c.short_name FROM students s JOIN courses c ON s.course_id=c.id WHERE s.id=?");
$stuData->execute([$studentId]);
$stuData = $stuData->fetch();
$semester = $stuData['semester'];

// Use session-backed notifications (persists across page navigations)
initMockNotifications();
$notifications = $_SESSION['mock_notifications'];
$unreadCount   = getUnreadNotificationCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications - GradeFlow</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800&family=Nunito:wght@400;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="css/style.css?v=2.3">
  <link rel="stylesheet" href="css/dashboard.css?v=2.4">
  <style>
    .notifications-header-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .filter-pills { display: flex; gap: 10px; flex-wrap: wrap; }
    .filter-pill {
        padding: 6px 18px;
        border-radius: 20px;
        background: rgba(255,255,255,0.05);
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
        border: 1px solid rgba(255,255,255,0.1);
        user-select: none;
    }
    .filter-pill.active, .filter-pill:hover {
        background: var(--primary-light);
        color: #fff;
        border-color: var(--primary-light);
        box-shadow: 0 4px 12px rgba(108,92,231,0.25);
    }
    .btn-mark-read {
        background: rgba(0,184,148,0.1);
        color: var(--success);
        border: 1px solid rgba(0,184,148,0.25);
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }
    .btn-mark-read:hover {
        background: var(--success);
        color: #fff;
        box-shadow: 0 4px 14px rgba(0,184,148,0.35);
    }
    .btn-mark-read:disabled {
        opacity: 0.45;
        cursor: not-allowed;
    }

    /* ── Notification cards ── */
    .notification-list { display: flex; flex-direction: column; gap: 14px; }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .notification-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-left: 4px solid transparent;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        gap: 18px;
        align-items: flex-start;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease, opacity 0.4s ease;
        animation: slideIn 0.45s ease both;
    }
    .notification-card:nth-child(1){animation-delay:.05s}
    .notification-card:nth-child(2){animation-delay:.12s}
    .notification-card:nth-child(3){animation-delay:.19s}
    .notification-card:nth-child(4){animation-delay:.26s}

    .notification-card::before {
        content:'';
        position:absolute;inset:0;
        background:linear-gradient(135deg,rgba(255,255,255,0.04),transparent);
        pointer-events:none;
    }
    .notification-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 22px rgba(0,0,0,0.22);
        background: rgba(255,255,255,0.055);
    }
    .notification-card.unread {
        background: rgba(255,255,255,0.07);
        border-left-color: var(--primary-light);
    }
    .notification-card.unread .n-title { color: #fff; font-weight: 700; }

    /* read animation */
    .notification-card.marking-read {
        opacity: 0.4;
        transform: scale(0.98);
    }

    .n-icon {
        width: 46px; height: 46px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem; flex-shrink: 0;
    }
    .n-content { flex: 1; min-width: 0; }
    .n-title { font-size: 1.05rem; font-weight: 600; color: var(--text-color); margin: 0 0 6px; }
    .n-message { font-size: 0.88rem; color: var(--text-muted); line-height: 1.6; margin: 0; }
    .n-footer { display:flex; align-items:center; gap:12px; margin-top:10px; flex-wrap:wrap; }
    .n-time { font-size: 0.75rem; color: var(--text-muted); display:flex; align-items:center; gap:5px; }
    .n-tag {
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
        padding: 2px 8px; border-radius: 6px; opacity: .75;
    }

    .n-unread-dot {
        position: absolute; top: 18px; right: 18px;
        width: 9px; height: 9px;
        background: var(--primary-light);
        border-radius: 50%;
        box-shadow: 0 0 8px var(--primary-light);
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    /* Empty state */
    .empty-state {
        text-align:center; padding:60px 20px; color:var(--text-muted);
    }
    .empty-state .empty-icon {
        width:80px; height:80px; border-radius:50%;
        background:rgba(255,255,255,0.04);
        display:flex; align-items:center; justify-content:center;
        margin:0 auto 20px;
        font-size:2rem; color:rgba(255,255,255,0.12);
    }
    .empty-state h3 { font-size:1.15rem; margin-bottom:6px; }

    /* Toast */
    #n-toast {
        position:fixed; bottom:28px; right:28px;
        background:rgba(30,30,50,0.96);
        backdrop-filter:blur(12px);
        border:1px solid rgba(255,255,255,0.1);
        border-radius:10px;
        padding:12px 20px;
        color:#fff;
        font-size:0.88rem;
        font-weight:500;
        display:flex; align-items:center; gap:10px;
        box-shadow:0 8px 30px rgba(0,0,0,0.35);
        transform:translateY(80px);
        opacity:0;
        transition:transform 0.35s cubic-bezier(0.34,1.56,0.64,1), opacity 0.35s ease;
        z-index:9999;
        pointer-events:none;
    }
    #n-toast.show { transform:translateY(0); opacity:1; }
    #n-toast i { color:var(--success); }
  </style>
</head>
<body>

  <div class="dashboard-layout">
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-header">
        <div class="logo-icon">🎓</div>
        <div class="brand-text">GradeFlow<small>Student Portal</small></div>
      </div>
      <nav class="sidebar-nav">
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Main</div>
          <a href="student-dashboard.php" class="sidebar-link"><span class="icon"><i class="fas fa-th-large"></i></span> Dashboard</a>
          <a href="view-result.php"        class="sidebar-link"><span class="icon"><i class="fas fa-file-alt"></i></span> My Results</a>
          <a href="attendance.php"         class="sidebar-link"><span class="icon"><i class="fas fa-calendar-check"></i></span> Attendance</a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Academic</div>
          <a href="student-subjects.php"      class="sidebar-link"><span class="icon"><i class="fas fa-book"></i></span> Subjects</a>
          <a href="student-notifications.php" class="sidebar-link active">
            <span class="icon"><i class="fas fa-bell"></i></span> Notifications
            <?php if ($unreadCount > 0): ?>
            <span class="badge" id="sidebar-badge" style="margin-left:auto;background:var(--danger);color:#fff;padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:700;"><?php echo $unreadCount; ?></span>
            <?php endif; ?>
          </a>
        </div>
        <div class="sidebar-nav-group">
          <div class="sidebar-nav-label">Account</div>
          <a href="student-profile.php" class="sidebar-link"><span class="icon"><i class="fas fa-user-circle"></i></span> My Profile</a>
          <a href="logout.php"          class="sidebar-link"><span class="icon"><i class="fas fa-sign-out-alt"></i></span> Logout</a>
        </div>
      </nav>
      <div class="sidebar-footer">
        <div class="sidebar-user">
          <div class="sidebar-user-avatar" style="background:var(--gradient-primary);"><?php echo $stuInitials; ?></div>
          <div class="sidebar-user-info">
            <div class="name"><?php echo htmlspecialchars($student['full_name']); ?></div>
            <div class="role"><?php echo htmlspecialchars($stuData['short_name']); ?> — Sem <?php echo $semester; ?></div>
          </div>
        </div>
      </div>
    </aside>

    <div class="main-content">
      <header class="topbar">
        <div class="topbar-left">
          <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
          <div class="topbar-title">
            <h2>Notifications</h2>
            <p>Stay updated with your latest alerts</p>
          </div>
        </div>
        <div class="topbar-right" style="display:flex;align-items:center;gap:12px;">
          <?php if ($unreadCount > 0): ?>
          <span style="font-size:0.82rem;color:var(--text-muted);">
            <strong style="color:#fff;" id="unread-label"><?php echo $unreadCount; ?></strong> unread
          </span>
          <?php else: ?>
          <span style="font-size:0.82rem;color:var(--text-muted);" id="unread-label-wrap">You're all caught up!</span>
          <?php endif; ?>
        </div>
      </header>

      <div class="dashboard-content">

        <div class="notifications-header-controls animate-fade-up">
          <div class="filter-pills">
            <div class="filter-pill active" data-filter="all">All <span style="opacity:.55;font-size:.75rem;">(<?php echo count($notifications); ?>)</span></div>
            <div class="filter-pill" data-filter="unread">Unread <?php if($unreadCount>0):?><span style="opacity:.7;font-size:.75rem;">(<?php echo $unreadCount;?>)</span><?php endif;?></div>
            <div class="filter-pill" data-filter="academic">Academic</div>
            <div class="filter-pill" data-filter="event">Events</div>
            <div class="filter-pill" data-filter="system">System</div>
          </div>

          <button class="btn-mark-read" id="btn-mark-all" onclick="markAllAsRead()" <?php echo $unreadCount === 0 ? 'disabled' : ''; ?>>
            <i class="fas fa-check-double"></i> Mark all as read
          </button>
        </div>

        <div class="notification-list" id="notification-container">
          <?php foreach ($notifications as $notif):
            $ts       = strtotime($notif['date']);
            $diff     = time() - $ts;
            $timeStr  = $diff < 3600
                          ? 'Just now'
                          : ($diff < 86400
                              ? round($diff/3600) . ' hours ago'
                              : round($diff/86400) . ' days ago');

            // Tag label & color
            $tagMap   = ['academic'=>'Academic','system'=>'System','event'=>'Event'];
            $tagLabel = $tagMap[$notif['type']] ?? ucfirst($notif['type']);

            // Safe background for icon (replace CSS var with rgba since we can't inline-compute)
            $iconBg = 'rgba(255,255,255,0.1)';
          ?>
          <div class="notification-card <?php echo $notif['read'] ? '' : 'unread'; ?> n-item"
               data-id="<?php echo $notif['id']; ?>"
               data-type="<?php echo $notif['type']; ?>"
               data-read="<?php echo $notif['read'] ? 'true' : 'false'; ?>"
               style="--card-color: <?php echo $notif['color']; ?>">

            <?php if (!$notif['read']): ?>
            <div class="n-unread-dot"></div>
            <?php endif; ?>

            <div class="n-icon" style="color:<?php echo $notif['color']; ?>;background:<?php echo $iconBg; ?>">
              <i class="<?php echo $notif['icon']; ?>"></i>
            </div>

            <div class="n-content">
              <h4 class="n-title"><?php echo htmlspecialchars($notif['title']); ?></h4>
              <p class="n-message"><?php echo htmlspecialchars($notif['message']); ?></p>
              <div class="n-footer">
                <span class="n-time"><i class="far fa-clock"></i> <?php echo $timeStr; ?></span>
                <span class="n-tag" style="background:<?php echo $iconBg; ?>;color:<?php echo $notif['color']; ?>"><?php echo $tagLabel; ?></span>
              </div>
            </div>
          </div>
          <?php endforeach; ?>

          <div class="empty-state" id="empty-state" style="display:none;">
            <div class="empty-icon"><i class="fas fa-bell-slash"></i></div>
            <h3>No notifications here</h3>
            <p>Switch the filter above to see others.</p>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Toast message -->
  <div id="n-toast"><i class="fas fa-check-circle"></i><span id="toast-msg">Done</span></div>

  <script src="js/app.js?v=2.3"></script>
  <script>
  (function () {
    'use strict';

    /* ── helpers ── */
    function toast(msg) {
      const el = document.getElementById('n-toast');
      document.getElementById('toast-msg').textContent = msg;
      el.classList.add('show');
      clearTimeout(el._t);
      el._t = setTimeout(() => el.classList.remove('show'), 2800);
    }

    function apiCall(action, id) {
      return fetch('api-notifications.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ action, id })
      }).then(r => r.json());
    }

    function currentUnreadCount() {
      return document.querySelectorAll('.n-item[data-read="false"]').length;
    }

    function markCardRead(card) {
      if (card.dataset.read === 'true') return Promise.resolve();
      return apiCall('mark_read', parseInt(card.dataset.id)).then(res => {
        if (!res.success) return;
        card.classList.add('marking-read');
        setTimeout(() => {
          card.classList.remove('marking-read', 'unread');
          card.dataset.read = 'true';
          const dot = card.querySelector('.n-unread-dot');
          if (dot) { dot.style.opacity = '0'; dot.style.transform = 'scale(0)'; setTimeout(() => dot.remove(), 300); }
          refreshUI();
        }, 300);
      });
    }

    function refreshUI() {
      const count  = currentUnreadCount();
      const badge  = document.getElementById('sidebar-badge');
      const btn    = document.getElementById('btn-mark-all');
      const label  = document.getElementById('unread-label');
      const wrap   = document.getElementById('unread-label-wrap');

      // Sidebar badge
      if (badge) {
        if (count > 0) badge.textContent = count;
        else { badge.style.opacity = '0'; badge.style.transform = 'scale(0)'; setTimeout(() => badge.remove(), 350); }
      }

      // Mark-all button
      if (btn) btn.disabled = (count === 0);

      // Topbar label
      if (label) {
        if (count > 0) label.textContent = count;
        else { const w = label.closest('span'); if(w) w.style.display='none'; if(wrap) wrap.style.display=''; }
      }

      // Filter pill "Unread" count
      const unreadPill = document.querySelector('.filter-pill[data-filter="unread"]');
      if (unreadPill) {
        unreadPill.innerHTML = 'Unread' + (count > 0 ? `<span style="opacity:.7;font-size:.75rem;">(${count})</span>` : '');
      }

      applyFilter();
    }

    /* ── filtering ── */
    let currentFilter = 'all';

    function applyFilter() {
      const items = document.querySelectorAll('.n-item');
      let visible = 0;
      items.forEach(item => {
        const match = currentFilter === 'all'
          || (currentFilter === 'unread'   && item.dataset.read === 'false')
          || currentFilter === item.dataset.type;
        item.style.display = match ? 'flex' : 'none';
        if (match) visible++;
      });
      document.getElementById('empty-state').style.display = visible === 0 ? 'block' : 'none';
    }

    document.querySelectorAll('.filter-pill').forEach(pill => {
      pill.addEventListener('click', () => {
        document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
        pill.classList.add('active');
        currentFilter = pill.dataset.filter;
        applyFilter();
      });
    });

    /* ── click individual card to mark read ── */
    document.querySelectorAll('.n-item').forEach(card => {
      card.addEventListener('click', () => {
        if (card.dataset.read === 'false') {
          markCardRead(card).then(() => toast('Notification marked as read'));
        }
      });
    });

    /* ── mark all as read ── */
    window.markAllAsRead = function () {
      const unread = [...document.querySelectorAll('.n-item[data-read="false"]')];
      if (unread.length === 0) return;

      document.getElementById('btn-mark-all').disabled = true;

      apiCall('mark_all_read').then(res => {
        if (!res.success) return;
        unread.forEach((card, i) => {
          setTimeout(() => {
            card.classList.add('marking-read');
            setTimeout(() => {
              card.classList.remove('marking-read', 'unread');
              card.dataset.read = 'true';
              const dot = card.querySelector('.n-unread-dot');
              if (dot) { dot.style.opacity = '0'; dot.style.transform = 'scale(0)'; setTimeout(() => dot.remove(), 300); }
            }, 300);
          }, i * 80);
        });
        setTimeout(() => { refreshUI(); toast('All notifications marked as read ✓'); }, unread.length * 80 + 350);
      });
    };
  })();
  </script>
</body>
</html>
