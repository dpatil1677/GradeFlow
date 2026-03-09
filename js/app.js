   //Sidebar, Tabs, Modals, Interactivity

// ---- Sidebar Toggle (Mobile) ----
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('open');
}

// Close sidebar on clicking outside (mobile)
document.addEventListener('click', function (e) {
  const sidebar = document.getElementById('sidebar');
  const toggle = document.querySelector('.sidebar-toggle');
  if (sidebar && sidebar.classList.contains('open') && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
    sidebar.classList.remove('open');
  }
});

// ---- Tab Switching ----
function switchTab(event, tabId) {
  // Remove active from all tab buttons
  document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
  // Remove active from all tab contents
  document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

  // Activate clicked tab buttone
  event.currentTarget.classList.add('active');
  // Activate corresponding content
  const targetContent = document.getElementById(tabId);
  if (targetContent) {
    targetContent.classList.add('active');
  }
}

// ---- Modal ----
function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.remove('active');
    document.body.style.overflow = '';
  }
}

// Close modal on overlay click
document.addEventListener('click', function (e) {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('active');
    document.body.style.overflow = '';
  }
});

// Close modal on Escape key
document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-overlay.active').forEach(modal => {
      modal.classList.remove('active');
    });
    document.body.style.overflow = '';
  }
});

// ---- Navbar Scroll Effect (for non-dashboard pages) ----
if (document.getElementById('navbar')) {
  window.addEventListener('scroll', function () {
    const navbar = document.getElementById('navbar');
    if (navbar) {
      navbar.classList.toggle('scrolled', window.scrollY > 50);
    }
  });
}

// ---- Mobile Nav Toggle ----
function toggleNav() {
  const navMenu = document.getElementById('navMenu');
  if (navMenu) {
    navMenu.classList.toggle('open');
  }
}

// ---- Smooth scroll for anchor links ----
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    const href = this.getAttribute('href');
    if (href !== '#') {
      e.preventDefault();
      const target = document.querySelector(href);
      if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        // Close mobile nav if open
        const navMenu = document.getElementById('navMenu');
        if (navMenu && navMenu.classList.contains('open')) {
          navMenu.classList.remove('open');
        }
      }
    }
  });
});

// ---- Intersection Observer for Animations ----
document.addEventListener('DOMContentLoaded', function () {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -40px 0px'
  };

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.style.animationPlayState = 'running';
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  // Observe all animated elements
  document.querySelectorAll('.animate-fade-up, .animate-fade-down, .animate-fade-left, .animate-fade-right, .animate-scale').forEach(function (el) {
    // Only pause if not already visible
    const rect = el.getBoundingClientRect();
    if (rect.top > window.innerHeight) {
      el.style.animationPlayState = 'paused';
    }
    observer.observe(el);
  });
});

// ---- Auto-calculate totals in marks entry ----
document.querySelectorAll('input[type="number"]').forEach(function (input) {
  input.addEventListener('input', function () {
    const row = this.closest('tr');
    if (row) {
      const inputs = row.querySelectorAll('input[type="number"]');
      if (inputs.length === 2) {
        const internal = parseInt(inputs[0].value) || 0;
        const external = parseInt(inputs[1].value) || 0;
        const total = internal + external;
        const totalCell = row.querySelector('td:nth-child(6) strong');
        if (totalCell) {
          totalCell.textContent = total;
          totalCell.style.color = total >= 40 ? 'var(--success)' : 'var(--danger)';
        }
      }
    }
  });
});

// ---- Mark All Present (Attendance) ----
function markAll(status) {
  const radios = document.querySelectorAll('input[type="radio"]');
  radios.forEach(function (radio) {
    if (status === 'present' && radio.parentElement.cellIndex === 4) {
      radio.checked = true;
    }
  });
}

// ---- Tooltip and hover effects for table rows ----
document.querySelectorAll('.data-table tbody tr').forEach(function (row) {
  row.addEventListener('mouseenter', function () {
    this.style.transition = 'background 0.2s ease';
  });
});

// Console branding
console.log(
  '%c🎓 GradeFlow - Student Academic & Result Management System',
  'background: linear-gradient(135deg, #6c5ce7, #a29bfe); color: white; padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: bold;'
);
console.log(
  '%cPowered by professional UI design',
  'color: #a29bfe; font-size: 11px;'
);

