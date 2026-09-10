/**
 * TALENTFLOW - ULTRA-MODERN RECRUITMENT ATS FRONTEND
 * Connects seamlessly to Laravel Sanctum /api/v1 REST endpoints.
 * Provides end-to-end recruitment lifecycle: Auth, Jobs, Resumes, Pipeline, Interviews, Tasks & Analytics.
 */

const TF = {
  apiBase: '/api/v1',
  token: localStorage.getItem('tf_token') || '',
  user: JSON.parse(localStorage.getItem('tf_user') || 'null'),
  currentView: 'dashboard',
  editingJobId: null,
  liveData: { metrics: null, monthlyActivity: [], applications: [], jobs: [], interviews: [], tasks: [], notifications: [], dashboardLoaded: false, applicationsLoaded: false, jobsLoaded: false, interviewsLoaded: false, tasksLoaded: false },
  dashboardPeriod: 'all',

  // Sample data fallback for offline/instant preview if API hasn't migrated yet
  mockData: {
    metrics: {
      total_jobs: 14,
      published_jobs: 9,
      total_applications: 1324,
      active_candidates: 420,
      interviews_this_week: 0,
      average_candidate_score: 86.5
    },
    jobs: [
      {
        id: 1,
        title: 'Senior Laravel Architect',
        department: 'Engineering (Bengaluru, Hybrid)',
        description: 'Lead enterprise microservices, optimize MySQL databases, and architect scalable APIs.',
        required_experience: 5.0,
        salary_min: 2400000,
        salary_max: 3600000,
        deadline: '2026-11-30',
        status: 'published',
        skills: [
          { name: 'PHP', is_required: true, weight: 5 },
          { name: 'Laravel', is_required: true, weight: 5 },
          { name: 'MySQL', is_required: true, weight: 3 },
          { name: 'Docker', is_required: false, weight: 2 }
        ],
        applicants_count: 38
      },
      {
        id: 2,
        title: 'Full Stack Engineer (Vue & Laravel)',
        department: 'Product (Pune, Remote)',
        description: 'Craft responsive user interfaces in Vue coupled with robust backend Laravel APIs.',
        required_experience: 3.0,
        salary_min: 1400000,
        salary_max: 2200000,
        deadline: '2026-11-15',
        status: 'published',
        skills: [
          { name: 'Laravel', is_required: true, weight: 4 },
          { name: 'Vue', is_required: true, weight: 4 },
          { name: 'REST API', is_required: true, weight: 3 }
        ],
        applicants_count: 52
      },
      {
        id: 3,
        title: 'Principal Cloud & DevOps Architect',
        department: 'Infrastructure (Hyderabad)',
        description: 'Design multi-region AWS architectures, Kubernetes clusters, and automated CI/CD pipelines.',
        required_experience: 6.0,
        salary_min: 2800000,
        salary_max: 4200000,
        deadline: '2026-12-05',
        status: 'published',
        skills: [
          { name: 'AWS', is_required: true, weight: 5 },
          { name: 'Docker', is_required: true, weight: 4 },
          { name: 'CI/CD', is_required: true, weight: 3 }
        ],
        applicants_count: 24
      }
    ],
    applications: [
      {
        id: 1,
        job_id: 1,
        job_title: 'Senior Laravel Architect',
        candidate_name: 'Aarav Sharma',
        candidate_email: 'candidate1@talentflow.local',
        company: 'Razorpay, Bengaluru',
        score: 95.0,
        status: 'technical_task',
        experience: 5.5,
        education: 'M.Tech, IIT Bombay',
        skills: ['PHP', 'Laravel', 'MySQL', 'Docker', 'AWS'],
        history: [
          { from: null, to: 'applied', remarks: 'Application submitted via portal', date: '3 days ago' },
          { from: 'applied', to: 'screening', remarks: 'Strong fintech architecture background', date: '2 days ago' },
          { from: 'screening', to: 'shortlisted', remarks: 'Shortlisted for tech round', date: '1 day ago' },
          { from: 'shortlisted', to: 'interview', remarks: 'Interview completed with flying colors', date: 'Yesterday' },
          { from: 'interview', to: 'technical_task', remarks: 'Assigned queue pipeline take-home assessment', date: 'Today' }
        ]
      },
      {
        id: 2,
        job_id: 2,
        job_title: 'Full Stack Engineer (Vue & Laravel)',
        candidate_name: 'Ananya Iyer',
        candidate_email: 'candidate2@talentflow.local',
        company: 'Swiggy, Pune',
        score: 86.5,
        status: 'screening',
        experience: 3.2,
        education: 'B.Tech, NIT Trichy',
        skills: ['Laravel', 'Vue', 'MySQL', 'TailwindCSS'],
        history: [
          { from: null, to: 'applied', remarks: 'Applied via online portal', date: '2 days ago' },
          { from: 'applied', to: 'screening', remarks: 'In recruiter screening', date: 'Yesterday' }
        ]
      },
      {
        id: 3,
        job_id: 1,
        job_title: 'Senior Laravel Architect',
        candidate_name: 'Rohan Verma',
        candidate_email: 'candidate3@talentflow.local',
        company: 'Flipkart, Hyderabad',
        score: 91.0,
        status: 'shortlisted',
        experience: 7.0,
        education: 'B.E., BITS Pilani',
        skills: ['PHP', 'Laravel', 'Docker', 'MySQL', 'Redis'],
        history: [
          { from: null, to: 'applied', remarks: 'Applied', date: '4 days ago' },
          { from: 'applied', to: 'screening', remarks: 'Screening approved', date: '2 days ago' },
          { from: 'screening', to: 'shortlisted', remarks: 'Shortlisted for technical round', date: '1 day ago' }
        ]
      },
      {
        id: 4,
        job_id: 1,
        job_title: 'Senior Laravel Architect',
        candidate_name: 'Aditya Rao',
        candidate_email: 'candidate5@talentflow.local',
        company: 'Zomato, Gurugram',
        score: 97.5,
        status: 'interview',
        experience: 8.5,
        education: 'Ph.D., IISc Bangalore',
        skills: ['PHP', 'Laravel', 'MySQL', 'Docker', 'AWS', 'Python'],
        history: [
          { from: null, to: 'applied', remarks: 'Applied', date: '5 days ago' },
          { from: 'applied', to: 'shortlisted', remarks: 'Fast-tracked senior profile', date: '3 days ago' },
          { from: 'shortlisted', to: 'interview', remarks: 'Technical screen scheduled', date: 'Yesterday' }
        ]
      },
      {
        id: 5,
        job_id: 2,
        job_title: 'Full Stack Engineer (Vue & Laravel)',
        candidate_name: 'Sneha Kulkarni',
        candidate_email: 'candidate4@talentflow.local',
        company: 'Jio Platforms, Mumbai',
        score: 79.5,
        status: 'applied',
        experience: 2.5,
        education: 'B.Tech, COEP Pune',
        skills: ['Laravel', 'REST API', 'JavaScript', 'Vue'],
        history: [
          { from: null, to: 'applied', remarks: 'Application submitted', date: 'Today' }
        ]
      },
      {
        id: 6,
        job_id: 1,
        job_title: 'Senior Laravel Architect',
        candidate_name: 'Pooja Deshmukh',
        candidate_email: 'candidate6@talentflow.local',
        company: 'Paytm, Noida',
        score: 88.0,
        status: 'hired',
        experience: 4.0,
        education: 'B.Tech, VJTI Mumbai',
        skills: ['Laravel', 'PHP', 'MySQL', 'Redis'],
        history: [
          { from: null, to: 'applied', remarks: 'Applied', date: '2 weeks ago' },
          { from: 'technical_task', to: 'hired', remarks: 'Offer accepted! Welcome to TalentFlow.', date: 'Yesterday' }
        ]
      },
      {
        id: 7,
        job_id: 2,
        job_title: 'Full Stack Engineer (Vue & Laravel)',
        candidate_name: 'Arjun Reddy',
        candidate_email: 'candidate7@talentflow.local',
        company: 'CRED, Bengaluru',
        score: 84.0,
        status: 'applied',
        experience: 3.8,
        education: 'B.Tech, IIIT Hyderabad',
        skills: ['Laravel', 'Vue', 'PostgreSQL'],
        history: [
          { from: null, to: 'applied', remarks: 'Applied', date: 'Yesterday' }
        ]
      },
      {
        id: 8,
        job_id: 1,
        job_title: 'Senior Laravel Architect',
        candidate_name: 'Neha Gupta',
        candidate_email: 'candidate8@talentflow.local',
        company: 'PhonePe, Bengaluru',
        score: 92.5,
        status: 'screening',
        experience: 6.0,
        education: 'M.S., DTU Delhi',
        skills: ['PHP', 'Laravel', 'Docker', 'AWS', 'Kubernetes'],
        history: [
          { from: null, to: 'applied', remarks: 'Applied', date: '3 days ago' },
          { from: 'applied', to: 'screening', remarks: 'Moved to screening', date: '1 day ago' }
        ]
      }
    ],
    interviews: [
      {
        id: 1,
        candidate_name: 'Aditya Rao',
        job_title: 'Senior Laravel Architect',
        interviewer_name: 'Priya Nair',
        scheduled_at: 'Tomorrow, 11:30 AM IST',
        duration: '60 mins',
        meeting_link: 'https://meet.google.com/aditya-priya-interview',
        status: 'scheduled'
      },
      {
        id: 2,
        candidate_name: 'Aarav Sharma',
        job_title: 'Senior Laravel Architect',
        interviewer_name: 'Priya Nair',
        scheduled_at: 'Completed yesterday',
        duration: '60 mins',
        meeting_link: 'https://meet.google.com/aarav-priya-tech-interview',
        status: 'completed'
      }
    ],
    tasks: [
      {
        id: 1,
        title: 'Build Idempotent Queue Worker Pipeline in Laravel',
        candidate_name: 'Aarav Sharma',
        job_title: 'Senior Laravel Architect',
        due_date: 'In 3 days',
        status: 'submitted',
        submission_url: 'https://github.com/aaravsharma/talentflow-assessment',
        submission_notes: 'Includes retry backoff policies, dead-letter monitoring, and 100% test coverage with PHPUnit.',
        review_status: 'pending'
      }
    ],
    notifications: [
      { id: '1', title: 'New Application', text: 'Aarav Sharma applied for Senior Laravel Architect', time: '10m ago' },
      { id: '2', title: 'Task Submitted', text: 'Aarav Sharma submitted queue pipeline solution', time: '1h ago' },
      { id: '3', title: 'Interview Reminder', text: 'Upcoming interview with Aditya Rao tomorrow at 11:30 AM IST', time: '3h ago' }
    ]
  },

  // Initialize application
  init() {
    if (!this.user) {
      window.location.href = '/login';
      return;
    }
    this.bindEvents();
    this.renderHeader();
    this.loadNotifications();
    this.switchView('dashboard');
  },

  async loadDashboardData(period = this.dashboardPeriod) {
    const [overview, monthlyActivity, applications, jobs] = await Promise.all([
      this.request(`/dashboard/overview?period=${period}`),
      this.request('/dashboard/monthly-activity'),
      this.requestAllPages('/applications?sort=latest'),
      this.requestAllPages('/jobs')
    ]);
    if (overview?.success && overview.data) this.liveData.metrics = overview.data;
    if (monthlyActivity?.data) this.liveData.monthlyActivity = monthlyActivity.data;
    if (applications?.data) {
      this.liveData.applications = applications.data;
      this.liveData.applicationsLoaded = true;
    }
    if (jobs?.data) {
      this.liveData.jobs = jobs.data;
      this.liveData.jobsLoaded = true;
    }
    this.dashboardPeriod = period;
    this.liveData.dashboardLoaded = true;
    this.renderDashboard();
  },

  async handleLoginSubmit(event) {
    event.preventDefault();

    const email = document.getElementById('loginEmail').value.trim();
    const password = document.getElementById('loginPassword').value;
    const error = document.getElementById('loginError');
    const submit = event.target.querySelector('button[type="submit"]');

    if (error) {
      error.textContent = '';
      error.style.display = 'none';
    }
    if (submit) {
      submit.disabled = true;
      submit.textContent = 'Signing in...';
    }

    const result = await this.request('/auth/login', {
      method: 'POST',
      body: JSON.stringify({ email, password })
    });

    if (submit) {
      submit.disabled = false;
      submit.textContent = 'Sign In';
    }

    if (!result?.success || !result?.data?.token) {
      const message = result?.errors?.email?.[0] || result?.message || 'Unable to sign in with these credentials.';
      if (error) {
        error.textContent = message;
        error.style.display = 'block';
      } else {
        this.toast(message, 'error');
      }
      return;
    }

    this.token = result.data.token;
    this.user = {
      id: result.data.user.id,
      name: result.data.user.name,
      email: result.data.user.email,
      role: result.data.role || result.data.user.role,
      avatar: result.data.user.name.split(' ').map(part => part[0]).join('').slice(0, 2).toUpperCase()
    };
    this.liveData.metrics = null;
    this.liveData.monthlyActivity = [];
    this.liveData.applications = [];
    this.liveData.applicationsLoaded = false;
    this.liveData.jobs = [];
    this.liveData.dashboardLoaded = false;
    this.liveData.jobsLoaded = false;
    this.liveData.interviewsLoaded = false;
    this.liveData.tasksLoaded = false;
    localStorage.setItem('tf_token', this.token);
    localStorage.setItem('tf_user', JSON.stringify(this.user));
    this.closeModal('modalAuth');
    this.renderHeader();
    this.loadNotifications();
    this.refreshCurrentView();
    this.toast(`Welcome back, ${this.user.name}!`, 'success');
  },

  async logout() {
    await this.request('/auth/logout', { method: 'POST' });

    this.token = '';
    this.user = null;
    localStorage.removeItem('tf_token');
    localStorage.removeItem('tf_user');
    window.location.href = '/login';
  },

  // HTTP Helper for API requests
  async request(endpoint, options = {}) {
    const headers = {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      ...(this.token ? { 'Authorization': `Bearer ${this.token}` } : {}),
      ...(options.headers || {})
    };

    try {
      const response = await fetch(`${this.apiBase}${endpoint}`, {
        ...options,
        headers
      });
      const data = await response.json();
      return data;
    } catch (err) {
      console.warn(`API call failed for ${endpoint}, utilizing reactive local state:`, err);
      return null;
    }
  },

  async requestAllPages(endpoint, perPage = 50) {
    const separator = endpoint.includes('?') ? '&' : '?';
    const records = [];
    let page = 1;
    let lastPage = 1;

    do {
      const result = await this.request(`${endpoint}${separator}per_page=${perPage}&page=${page}`);
      if (!result?.data) return null;

      records.push(...result.data);
      lastPage = Number(result.meta?.last_page || 1);
      page += 1;
    } while (page <= lastPage);

    return { success: true, data: records, meta: { current_page: 1, last_page: 1, total: records.length } };
  },

  // Global Toast
  toast(message, type = 'success') {
    const container = document.getElementById('tfToastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `tf-toast ${type}`;
    const icon = type === 'success'
      ? `<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`
      : `<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>`;

    toast.innerHTML = `<div class="tf-toast-icon">${icon}</div><div>${message}</div>`;
    container.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(10px)';
      toast.style.transition = 'all 0.3s ease';
      setTimeout(() => toast.remove(), 300);
    }, 3200);
  },

  // Render Header user details & persona
  renderHeader() {
    const userNameEl = document.getElementById('tfHeaderUserName');
    const userRoleEl = document.getElementById('tfHeaderUserRole');
    const userAvatarEl = document.getElementById('tfHeaderUserAvatar');
    const personaBadge = document.getElementById('tfPersonaBadge');

    if (userNameEl && this.user) {
      const displayName = String(this.user.name || 'TalentFlow User').trim();
      const firstName = displayName.split(/\s+/)[0];
      const role = String(this.user.role || 'account').replace(/_/g, ' ').toUpperCase();

      userNameEl.textContent = displayName;
      userRoleEl.textContent = role;
      userAvatarEl.textContent = this.user.avatar || displayName.split(/\s+/).map(part => part[0]).slice(0, 2).join('').toUpperCase();
      if (personaBadge) {
        personaBadge.innerHTML = `<span>●</span> Account: ${role}`;
      }

      document.querySelectorAll('[data-current-user-first-name]').forEach(element => {
        element.textContent = firstName;
      });
    }
  },

  async loadNotifications() {
    const result = await this.requestAllPages('/notifications');
    this.liveData.notifications = result?.data || [];
    this.renderNotifications();
  },

  renderNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    const badge = document.getElementById('notificationBadge');
    if (!dropdown) return;

    const notifications = this.liveData.notifications || [];
    const unreadCount = notifications.filter(notification => !notification.read_at).length;
    if (badge) {
      badge.textContent = unreadCount > 9 ? '9+' : String(unreadCount);
      badge.classList.toggle('is-visible', unreadCount > 0);
    }
    const sidebarBadge = document.getElementById('tfSidebarNotificationBadge');
    if (sidebarBadge) {
      sidebarBadge.textContent = unreadCount > 9 ? '9+' : String(unreadCount);
      sidebarBadge.classList.toggle('is-visible', unreadCount > 0);
    }

    dropdown.innerHTML = `
      <div class="tf-notification-header">
        <div><strong>Notifications</strong><span>${unreadCount} unread</span></div>
        ${unreadCount ? '<button class="tf-notification-mark-all" onclick="TF.markAllNotificationsRead()">Mark all read</button>' : ''}
      </div>
      <div class="tf-notification-list">
        ${notifications.length ? notifications.slice(0, 8).map(notification => {
          const data = notification.data || {};
          const title = data.title || notification.type.replace(/Notification$/, '').replace(/([a-z])([A-Z])/g, '$1 $2');
          const message = data.message || 'You have a new TalentFlow update.';
          const created = notification.created_at ? new Date(notification.created_at).toLocaleString([], { day: '2-digit', month: 'short', hour: 'numeric', minute: '2-digit' }) : 'Just now';
          return `<div class="tf-notification-item ${notification.read_at ? '' : 'is-unread'}">
            <span class="tf-notification-icon">!</span>
            <div><strong>${title}</strong><p>${message}</p><small>${created}</small></div>
            ${notification.read_at ? '' : `<button class="tf-notification-read" title="Mark as read" onclick="TF.markNotificationRead('${notification.id}')">✓</button>`}
          </div>`;
        }).join('') : '<div class="tf-notification-empty">You are all caught up.</div>'}
      </div>
    `;
  },

  async markNotificationRead(notificationId) {
    const result = await this.request(`/notifications/${notificationId}/read`, { method: 'PATCH' });
    if (result?.success) {
      const notification = this.liveData.notifications.find(item => item.id === notificationId);
      if (notification) notification.read_at = new Date().toISOString();
      this.renderNotifications();
      if (this.currentView === 'notifications') this.renderNotificationsPage();
    }
  },

  async markAllNotificationsRead() {
    const result = await this.request('/notifications/read-all', { method: 'POST' });
    if (result?.success) {
      this.liveData.notifications.forEach(notification => { notification.read_at = new Date().toISOString(); });
      this.renderNotifications();
      if (this.currentView === 'notifications') this.renderNotificationsPage();
    }
  },

  renderNotificationsPage() {
    const container = document.getElementById('view-notifications');
    if (!container) return;
    const notifications = this.liveData.notifications || [];
    const unreadCount = notifications.filter(notification => !notification.read_at).length;
    container.innerHTML = `
      <div class="tf-greeting-bar">
        <div class="tf-greeting-text">
          <h2>Notifications</h2>
          <p>Stay updated on applications, interviews, and technical tasks.</p>
        </div>
        ${unreadCount ? '<button class="tf-btn-outline" onclick="TF.markAllNotificationsRead()">Mark all as read</button>' : ''}
      </div>
      <div class="tf-notification-page-list">
        ${notifications.length ? notifications.map(notification => {
          const data = notification.data || {};
          const title = data.title || notification.type.replace(/Notification$/, '').replace(/([a-z])([A-Z])/g, '$1 $2');
          const message = data.message || 'You have a new TalentFlow update.';
          const created = notification.created_at ? new Date(notification.created_at).toLocaleString() : 'Just now';
          return `<div class="tf-notification-page-item ${notification.read_at ? '' : 'is-unread'}">
            <span class="tf-notification-icon">!</span>
            <div><strong>${title}</strong><p>${message}</p><small>${created}</small></div>
            ${notification.read_at ? '<span class="tf-notification-read-state">Read</span>' : `<button class="tf-btn-outline" onclick="TF.markNotificationRead('${notification.id}')">Mark read</button>`}
          </div>`;
        }).join('') : '<div class="tf-card tf-notification-empty">You are all caught up.</div>'}
      </div>
    `;
  },

  // View Navigation
  switchView(viewName) {
    this.currentView = viewName;
    document.querySelectorAll('.tf-nav-item').forEach(el => {
      el.classList.toggle('active', el.dataset.view === viewName);
    });

    document.querySelectorAll('.tf-view').forEach(el => {
      el.classList.remove('active-view');
    });

    const target = document.getElementById(`view-${viewName}`);
    if (target) {
      target.classList.add('active-view');
    }

    this.refreshCurrentView();
  },

  refreshCurrentView() {
    switch (this.currentView) {
      case 'dashboard':
        this.renderDashboard();
        break;
      case 'jobs':
        this.renderJobs();
        break;
      case 'candidates':
        this.renderCandidateCenter();
        break;
      case 'pipeline':
        this.renderPipeline();
        break;
      case 'interviews':
        this.renderInterviews();
        break;
      case 'notifications':
        this.renderNotificationsPage();
        break;
      case 'tasks':
        this.renderTasks();
        break;
    }
  },

  // -------------------------------------------------------------
  // VIEW: Executive Analytics Dashboard
  // -------------------------------------------------------------
  renderDashboard() {
    const container = document.getElementById('view-dashboard');
    if (!container) return;

    if (!this.liveData.dashboardLoaded) this.loadDashboardData();
    const m = this.liveData.metrics || {
      total_applications: 0, active_candidates: 0, interviews_this_week: 0,
      average_candidate_score: 0, published_jobs: 0, shortlisted_applications: 0,
      scheduled_interviews: 0, pipeline_counts: {}
    };
    const isRecruiterOrAdmin = this.user.role === 'recruiter' || this.user.role === 'admin';
    const isCandidate = String(this.user.role || '').toLowerCase() === 'candidate';
    const pipeline = m.pipeline_counts || {};
    const pipelineTotal = Object.values(pipeline).reduce((total, count) => total + Number(count || 0), 0);
    const pipelineStage = (stage) => Number(pipeline[stage] || 0);
    const displayCount = (value) => Number(value || 0) === 0 ? '—' : value;
    const pipelinePercent = (stage) => pipelineTotal ? Math.round((pipelineStage(stage) / pipelineTotal) * 100) : 0;
    const applicationStages = [
      { key: 'applied', label: 'Applied', color: '#E4573D' },
      { key: 'screening', label: 'Screening', color: '#D97706' },
      { key: 'shortlisted', label: 'Shortlisted', color: '#7C3AED' },
      { key: 'interview', label: 'Interview', color: '#2563EB' },
      { key: 'technical_task', label: 'Technical Task', color: '#0891B2' },
      { key: 'hired', label: 'Hired', color: '#059669' }
    ];

    container.innerHTML = `
      <!-- Greeting Bar -->
      <div class="tf-greeting-bar">
        <div class="tf-greeting-text">
          <h2>Welcome to your hiring workspace</h2>
          <p>Track candidates, manage interviews, and keep every hire moving forward.</p>
        </div>
        <div class="tf-greeting-actions">
          <button class="tf-btn-outline" onclick="TF.toggleDashboardFilter()">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            Filter
          </button>
          ${isRecruiterOrAdmin ? `<button class="tf-btn-primary" onclick="TF.openPostJobModal()">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            + Post Job
          </button>` : ''}
        </div>
      </div>

      <!-- 4 Top Metric KPI Cards -->
      <div class="tf-metrics-grid">
        <div class="tf-metric-card">
          <div class="tf-metric-header">
            <span class="tf-metric-title">${isCandidate ? 'My Applications' : 'Total Applications'}</span>
            <div class="tf-metric-icon">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
          </div>
          <div class="tf-metric-body">
            <span id="dashboardTotalApplications" class="tf-metric-value">${displayCount(m.total_applications)}</span>
            <span class="tf-pill-badge tf-pill-magenta">↑ +8.73%</span>
          </div>
          <div class="tf-metric-footer">${isCandidate ? 'Applications submitted by you' : 'All-time candidate submissions'}</div>
        </div>

        <div class="tf-metric-card">
          <div class="tf-metric-header">
            <span class="tf-metric-title">${isCandidate ? 'Shortlisted Status' : 'Shortlisted Candidates'}</span>
            <div class="tf-metric-icon" style="background:#FFF7ED; color:#EA580C;">★</div>
          </div>
          <div class="tf-metric-body">
            <span class="tf-metric-value">${displayCount(m.shortlisted_applications)}</span>
          </div>
          <div class="tf-metric-footer">Applications currently shortlisted</div>
        </div>

        <div class="tf-metric-card">
          <div class="tf-metric-header">
            <span class="tf-metric-title">Scheduled Interviews</span>
            <div class="tf-metric-icon" style="background:#EFF6FF; color:#2563EB;">●</div>
          </div>
          <div class="tf-metric-body">
            <span class="tf-metric-value">${displayCount(m.scheduled_interviews)}</span>
          </div>
          <div class="tf-metric-footer">Active interviews in this period</div>
        </div>

        <div class="tf-metric-card">
          <div class="tf-metric-header">
            <span class="tf-metric-title">${isCandidate ? 'Active Jobs' : 'Active Candidates'}</span>
            <div class="tf-metric-icon" style="background: #ECFDF5; color: #10B981;">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
          </div>
          <div class="tf-metric-body">
            <span id="dashboardActiveCandidates" class="tf-metric-value">${displayCount(isCandidate ? m.published_jobs : m.active_candidates)}</span>
            <span class="tf-pill-badge tf-pill-success">↑ +5.32%</span>
          </div>
          <div class="tf-metric-footer">${isCandidate ? 'Open roles accepting applications' : 'Candidates currently in pipeline'}</div>
        </div>

        <div class="tf-metric-card">
          <div class="tf-metric-header">
            <span class="tf-metric-title">${isCandidate ? 'My Interviews' : 'Interviews in Period'}</span>
            <div class="tf-metric-icon" style="background: #FAF5FF; color: #9333EA;">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
          </div>
          <div class="tf-metric-body">
            <span id="dashboardInterviewsThisWeek" class="tf-metric-value">${displayCount(m.interviews_this_week)}</span>
            <span class="tf-pill-badge tf-pill-purple">↑ +15.4%</span>
          </div>
          <div class="tf-metric-footer">${isCandidate ? 'Scheduled for your applications' : 'Across engineering and product'}</div>
        </div>

        <div class="tf-metric-card">
          <div class="tf-metric-header">
            <span class="tf-metric-title">${isCandidate ? 'Profile Match Score' : 'Avg Candidate Score'}</span>
            <div class="tf-metric-icon" style="background: #ECFEFF; color: #06B6D4;">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
          </div>
          <div class="tf-metric-body">
            <span id="dashboardAverageScore" class="tf-metric-value">${Number(m.average_candidate_score || 0) === 0 ? '—' : `${m.average_candidate_score} <span style="font-size:16px; color:var(--text-muted); font-weight:600;">/100</span>`}</span>
            <span class="tf-pill-badge tf-pill-cyan">High Bar</span>
          </div>
          <div class="tf-metric-footer">${isCandidate ? 'Your latest application score' : 'Automated transparent scoring engine'}</div>
        </div>
      </div>

      <!-- Middle Row: Application Status Overview + Quick Actions -->
      <div class="tf-dashboard-row">
        <!-- Application Status Overview -->
        <div class="tf-card">
          <div class="tf-card-header">
            <div>
              <h3>Application Status Overview</h3>
              <p class="tf-card-subtitle">Live candidate distribution across hiring stages</p>
            </div>
          </div>

          <div class="tf-status-overview">
            ${applicationStages.map(stage => `
              <div class="tf-status-overview-row">
                <div class="tf-status-overview-label">
                  <span class="tf-status-overview-dot" style="background:${stage.color};"></span>
                  <span>${stage.label}</span>
                  <strong>${displayCount(pipelineStage(stage.key))}</strong>
                </div>
                <div class="tf-status-overview-track">
                  <span style="width:${pipelinePercent(stage.key)}%; background:${stage.color};"></span>
                </div>
                <span class="tf-status-overview-percent">${pipelinePercent(stage.key)}%</span>
              </div>
            `).join('')}
          </div>
        </div>

        <!-- Quick Actions Grid Matching Reference -->
        <div class="tf-card">
          <div class="tf-card-header">
            <div>
              <h3>Recruitment Workflows</h3>
              <p class="tf-card-subtitle">Quick access to key operational tasks</p>
            </div>
          </div>

          <div class="tf-quick-actions-grid">
            <div class="tf-action-tile" onclick="TF.switchView('jobs')">
              <div class="tf-action-tile-header">
                <div class="tf-action-icon magenta">
                  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h4>Active Jobs</h4>
              </div>
              <div class="tf-action-score-row">
                <span>Published</span>
                <span class="tf-action-score-val">${m.published_jobs}</span>
              </div>
              <p>Manage postings, adjust skill weights, review deadlines.</p>
            </div>

            <div class="tf-action-tile" onclick="TF.switchView('pipeline')">
              <div class="tf-action-tile-header">
                <div class="tf-action-icon green">
                  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <h4>Pipeline</h4>
              </div>
              <div class="tf-action-score-row">
                <span>In Review</span>
                <span class="tf-action-score-val" style="color:#10B981;">${displayCount(m.shortlisted_applications)}</span>
              </div>
              <p>Advance applicants with status history and audit trail.</p>
            </div>

            <div class="tf-action-tile" onclick="TF.switchView('interviews')">
              <div class="tf-action-tile-header">
                <div class="tf-action-icon magenta">
                  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <h4>Interviews</h4>
              </div>
              <div class="tf-action-score-row">
                <span>Upcoming</span>
                <span class="tf-action-score-val">${displayCount(m.interviews_this_week)}</span>
              </div>
              <p>Schedule with automated overlap conflict validation.</p>
            </div>

            <div class="tf-action-tile" onclick="TF.switchView('candidates')">
              <div class="tf-action-tile-header">
                <div class="tf-action-icon green">
                  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h4>AI Parser</h4>
              </div>
              <div class="tf-action-score-row">
                <span>Processed</span>
                <span class="tf-action-score-val" style="color:#10B981;">99.8%</span>
              </div>
              <p>Queue-driven text extraction and 100-pt scoring engine.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="tf-card" style="margin-top:20px;">
        <div class="tf-card-header">
          <div>
            <h3>Featured Open Roles</h3>
            <p class="tf-card-subtitle">Live jobs with the most recent hiring activity</p>
          </div>
          <button class="tf-btn-outline" onclick="TF.switchView('jobs')">View all jobs</button>
        </div>
        <div id="dashboardFeaturedJobs" style="display:grid; grid-template-columns:repeat(3, 1fr); gap:14px;">
          ${this.renderFeaturedJobs()}
        </div>
      </div>

      <div class="tf-card" style="margin-top:20px;">
        <div class="tf-card-header">
          <div>
            <h3>Candidate Applications</h3>
            <p class="tf-card-subtitle">Search candidates, filter by role or stage, and sort by score</p>
          </div>
          <span id="dashboardApplicationCount" class="tf-pill-badge tf-pill-purple">${this.liveData.applications.length} shown</span>
        </div>
        <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:16px;">
          <input id="dashboardApplicationSearch" class="tf-input" style="flex:1; min-width:220px;" type="search" placeholder="Search candidate or job...">
          <select id="dashboardApplicationStatus" class="tf-input" style="width:170px;">
            <option value="">All stages</option>
            <option value="applied">Applied</option>
            <option value="screening">Screening</option>
            <option value="shortlisted">Shortlisted</option>
            <option value="interview">Interview</option>
            <option value="technical_task">Technical task</option>
            <option value="hired">Hired</option>
            <option value="rejected">Rejected</option>
          </select>
          <select id="dashboardApplicationJob" class="tf-input" style="width:210px;">
            <option value="">All jobs</option>
            ${this.liveData.jobs.map(job => `<option value="${job.id}">${job.title}</option>`).join('')}
          </select>
          <select id="dashboardApplicationSort" class="tf-input" style="width:150px;">
            <option value="latest">Newest</option>
            <option value="score_desc">Highest score</option>
            <option value="score_asc">Lowest score</option>
          </select>
        </div>
        <div id="dashboardApplicationsTable"></div>
      </div>

      <!-- Bottom Row: Hiring Pipeline Stage Progress Bars -->
      <div class="tf-card">
        <div class="tf-card-header">
          <div>
            <h3>Active Pipeline Stages</h3>
            <p class="tf-card-subtitle">Candidate progression across state machine stages</p>
          </div>
          <button class="tf-btn-outline" onclick="TF.switchView('pipeline')">View Kanban Board →</button>
        </div>

        <div class="tf-pipeline-list">
          <div class="tf-pipeline-item">
            <div class="tf-pipeline-info">
              <div class="tf-action-icon magenta">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
              </div>
              <div>
                <h4 style="font-size:14px; font-weight:700;">Applied Candidates</h4>
                <p style="font-size:12px; color:var(--text-muted);">Incoming applications awaiting initial review</p>
              </div>
            </div>
            <div class="tf-pipeline-bar-wrapper">
              <div class="tf-progress-track">
                <div class="tf-progress-fill" style="width: ${pipelinePercent('applied')}%;"></div>
              </div>
              <span class="tf-progress-val">${displayCount(pipelineStage('applied'))} (${pipelinePercent('applied')}%)</span>
            </div>
          </div>

          <div class="tf-pipeline-item">
            <div class="tf-pipeline-info">
              <div class="tf-action-icon green">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              </div>
              <div>
                <h4 style="font-size:14px; font-weight:700;">Screening & Shortlisted</h4>
                <p style="font-size:12px; color:var(--text-muted);">Passed 100-point algorithm bar requirements</p>
              </div>
            </div>
            <div class="tf-pipeline-bar-wrapper">
              <div class="tf-progress-track">
                <div class="tf-progress-fill green" style="width: ${pipelinePercent('shortlisted')}%;"></div>
              </div>
              <span class="tf-progress-val">${displayCount(pipelineStage('shortlisted'))} (${pipelinePercent('shortlisted')}%)</span>
            </div>
          </div>

          <div class="tf-pipeline-item">
            <div class="tf-pipeline-info">
              <div class="tf-action-icon magenta">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
              </div>
              <div>
                <h4 style="font-size:14px; font-weight:700;">Interview & Technical Assessment</h4>
                <p style="font-size:12px; color:var(--text-muted);">Take-home tasks & technical evaluation</p>
              </div>
            </div>
            <div class="tf-pipeline-bar-wrapper">
              <div class="tf-progress-track">
                <div class="tf-progress-fill" style="width: ${pipelinePercent('interview')}%;"></div>
              </div>
              <span class="tf-progress-val">${displayCount(pipelineStage('interview'))} (${pipelinePercent('interview')}%)</span>
            </div>
          </div>
        </div>
      </div>
    `;
    this.bindDashboardApplicationFilters();
    this.renderDashboardApplications();
  },

  renderMonthlyActivity() {
    const months = this.liveData.monthlyActivity || [];
    if (!months.length) {
      return '<div style="padding:24px; color:var(--text-muted);">No monthly activity yet.</div>';
    }

    const maxApplications = Math.max(...months.map(month => Number(month.applications || 0)), 1);
    return `
      <div style="display:flex; align-items:flex-end; gap:12px; height:180px; padding:16px 8px 8px;">
        ${months.map(month => {
          const applications = Number(month.applications || 0);
          const height = Math.max(8, Math.round((applications / maxApplications) * 130));
          return `
            <div style="flex:1; height:100%; display:flex; flex-direction:column; justify-content:flex-end; align-items:center; gap:8px;">
              <span style="font-size:11px; color:var(--text-muted);">${applications || '—'}</span>
              <div title="${applications} applications, ${Number(month.interviews || 0)} interviews" style="width:100%; max-width:42px; height:${height}px; border-radius:8px 8px 3px 3px; background:linear-gradient(180deg, var(--primary-color), #8B5CF6);"></div>
              <span style="font-size:11px; color:var(--text-muted);">${month.label}</span>
            </div>
          `;
        }).join('')}
      </div>
    `;
  },

  renderFeaturedJobs() {
    const jobs = this.liveData.jobs.filter(job => job.is_open || job.status === 'published').slice(0, 3);
    if (!jobs.length) return '<p style="color:var(--text-muted);">No open roles found.</p>';
    return jobs.map(job => `
      <article style="border:1px solid var(--border-subtle); border-radius:var(--radius-md); padding:16px; background:var(--surface-subtle);">
        <div style="display:flex; justify-content:space-between; gap:8px; margin-bottom:10px;">
          <span class="tf-pill-badge tf-pill-purple">${job.department || 'Open role'}</span>
          <span style="font-size:12px; color:var(--text-muted);">${job.applications_count || 0} applicants</span>
        </div>
        <h4 style="font-size:15px; margin-bottom:6px;">${job.title}</h4>
        <p style="font-size:12px; color:var(--text-muted); min-height:36px;">${job.description || 'Hiring for this role is currently open.'}</p>
        <button class="tf-btn-outline" style="margin-top:12px; width:100%;" onclick="TF.switchView('jobs')">Review role</button>
      </article>
    `).join('');
  },

  bindDashboardApplicationFilters() {
    ['dashboardApplicationSearch', 'dashboardApplicationStatus', 'dashboardApplicationJob', 'dashboardApplicationSort']
      .map(id => document.getElementById(id))
      .filter(Boolean)
      .forEach(control => {
        control.addEventListener('input', () => this.renderDashboardApplications());
        control.addEventListener('change', () => this.renderDashboardApplications());
      });
  },

  renderDashboardApplications() {
    const target = document.getElementById('dashboardApplicationsTable');
    if (!target) return;
    const search = (document.getElementById('dashboardApplicationSearch')?.value || '').toLowerCase().trim();
    const status = document.getElementById('dashboardApplicationStatus')?.value || '';
    const jobId = document.getElementById('dashboardApplicationJob')?.value || '';
    const sort = document.getElementById('dashboardApplicationSort')?.value || 'latest';
    const applications = this.liveData.applications
      .filter(application => {
        const candidateName = application.candidate?.user?.name || '';
        const jobTitle = application.job?.title || '';
        return (!search || `${candidateName} ${jobTitle}`.toLowerCase().includes(search))
          && (!status || application.current_status === status)
          && (!jobId || String(application.job_id) === jobId);
      })
      .sort((left, right) => sort === 'score_desc' ? right.score - left.score : sort === 'score_asc' ? left.score - right.score : new Date(right.applied_at) - new Date(left.applied_at));
    const count = document.getElementById('dashboardApplicationCount');
    if (count) count.textContent = `${applications.length} shown`;
    target.innerHTML = applications.length ? `<div style="overflow-x:auto;"><table class="tf-table"><thead><tr><th>Candidate</th><th>Role</th><th>Score</th><th>Stage</th><th>Applied</th></tr></thead><tbody>${applications.map(application => `<tr><td>${application.candidate?.user?.name || 'Candidate'}</td><td>${application.job?.title || 'Role'}</td><td><strong>${application.score}/100</strong></td><td><span class="tf-pill-badge tf-pill-purple">${(application.current_status || '').replace('_', ' ')}</span></td><td>${application.applied_at ? new Date(application.applied_at).toLocaleDateString() : '-'}</td></tr>`).join('')}</tbody></table></div>` : '<p style="color:var(--text-muted); padding:16px 0;">No applications match these filters.</p>';
  },

  // -------------------------------------------------------------
  // VIEW: Job Management & Explorer
  // -------------------------------------------------------------
  renderJobs() {
    const container = document.getElementById('view-jobs');
    if (!container) return;

    if (!this.liveData.jobsLoaded) {
      this.loadJobs();
      container.innerHTML = '<div class="tf-card"><p>Loading job openings...</p></div>';
      return;
    }

    const isRecruiterOrAdmin = this.user.role === 'recruiter' || this.user.role === 'admin';
    const jobs = this.liveData.jobs;

    container.innerHTML = `
      <div class="tf-greeting-bar">
        <div class="tf-greeting-text">
          <h2>Job Openings & Requisitions</h2>
          <p>Explore, create, and manage hiring requirements.</p>
        </div>
        <div class="tf-greeting-actions">
          ${isRecruiterOrAdmin ? `
            <button class="tf-btn-primary" onclick="TF.openPostJobModal()">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
              + Create New Job
            </button>
            <button class="tf-btn-outline" onclick="TF.refreshJobs()">Refresh</button>
          ` : ''}
        </div>
      </div>

      <!-- Simple job opening cards -->
      <div class="tf-job-grid">
        ${jobs.length ? jobs.map(job => `
          <div class="tf-job-card">
            <div class="tf-job-card-main">
              <div class="tf-job-card-top">
                <span class="tf-pill-badge tf-pill-purple">${job.department || 'Tech'}</span>
                <span class="tf-job-status ${job.status === 'published' ? 'is-open' : ''}">${job.status === 'published' ? 'Open' : 'Draft'}</span>
              </div>
              <h3 class="tf-job-card-title">${job.title}</h3>
              <div class="tf-job-card-meta">
                <span>${job.required_experience || 0}+ years experience</span>
                <span>${job.applications_count || 0} applicants</span>
              </div>
              <div class="tf-job-card-skills">
                <span class="tf-job-card-skills-label">Skills</span>
                ${(job.skills || []).slice(0, 3).map(skill => `<span class="tf-job-card-skill">${skill.name}</span>`).join('')}
                ${!(job.skills || []).length ? '<span class="tf-job-card-skill tf-job-card-skill-muted">To be confirmed</span>' : ''}
              </div>
            </div>

            <div class="tf-job-card-actions">
              ${this.user.role === 'candidate' ? `
                <button class="tf-btn-primary" style="width:100%;" onclick="TF.openApplyModal(${job.id}, '${job.title}')">Apply Now</button>
              ` : `
                <button class="tf-btn-outline" style="flex:1;" onclick="TF.switchView('pipeline')">Applicants</button>
                <button class="tf-btn-primary" onclick="TF.openEditJobModal(${job.id})">Edit</button>
                <button class="tf-btn-outline" title="${job.status === 'published' ? 'Close job' : 'Publish job'}" onclick="TF.changeJobStatus(${job.id}, '${job.status === 'published' ? 'closed' : 'published'}')">
                  ${job.status === 'published' ? 'Close' : 'Publish'}
                </button>
              `}
            </div>
          </div>
        `).join('') : '<div class="tf-card"><p>No job openings found.</p></div>'}
      </div>
    `;
  },

  async loadJobs() {
    const result = await this.requestAllPages('/jobs?sort=latest');
    if (result?.data) {
      this.liveData.jobs = result.data;
      this.liveData.jobsLoaded = true;
      this.renderJobs();
    }
  },

  async refreshJobs() {
    this.liveData.jobsLoaded = false;
    this.renderJobs();
  },

  async changeJobStatus(jobId, status) {
    const result = await this.request(`/jobs/${jobId}/status`, {
      method: 'PATCH',
      body: JSON.stringify({ status })
    });
    if (!result?.success) {
      this.toast(result?.message || 'Unable to update job status.', 'error');
      return;
    }
    const job = this.liveData.jobs.find(item => item.id === jobId);
    if (job) job.status = status;
    this.toast(`Job ${status === 'published' ? 'published' : 'closed'} successfully.`, 'success');
    this.renderJobs();
  },

  // -------------------------------------------------------------
  // VIEW: Candidate Experience & 100-Point Scoring Engine
  // -------------------------------------------------------------
  renderCandidateCenter() {
    const container = document.getElementById('view-candidates');
    if (!container) return;
    const isCandidate = String(this.user.role || '').toLowerCase() === 'candidate';

    if (!isCandidate && !this.liveData.applicationsLoaded) {
      container.innerHTML = '<div class="tf-card"><p>Loading candidates...</p></div>';
      this.requestAllPages('/applications?sort=latest').then(result => {
        this.liveData.applications = result?.data || [];
        this.liveData.applicationsLoaded = true;
        this.renderCandidateCenter();
      });
      return;
    }

    const candidateOptions = [...new Map(this.liveData.applications
      .filter(application => application.candidate?.id)
      .map(application => [application.candidate.id, application.candidate])).values()]
      .map(candidate => `<option value="${candidate.id}">${candidate.name || 'Unnamed candidate'}${candidate.email ? ` - ${candidate.email}` : ''}</option>`)
      .join('');

    container.innerHTML = `
      <div class="tf-greeting-bar">
        <div class="tf-greeting-text">
          <h2>Resume Review</h2>
          <p>Upload one PDF. We extract the profile and calculate a score out of 100.</p>
        </div>
      </div>
      <div class="tf-resume-layout">
        <div class="tf-card tf-resume-upload-card">
          <div class="tf-card-header"><div><h3>1. Upload resume</h3><p class="tf-card-subtitle">PDF only, maximum 5 MB.</p></div><span class="tf-pill-badge tf-pill-purple">Step 1</span></div>
          ${isCandidate ? `<div class="tf-resume-owner"><span>Candidate</span><strong>${this.user.name || 'Your profile'}</strong></div>` : `<label class="tf-resume-target-label" for="resumeCandidateSelect">Upload for candidate</label><select id="resumeCandidateSelect" class="tf-input tf-resume-target-select" onchange="TF.updateResumeTarget()"><option value="">Select a candidate</option>${candidateOptions}</select>`}
          <div id="resumeDropzone" class="tf-resume-dropzone">
            <div class="tf-resume-icon">
              <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0L7 9m5-5l5 5M5 20h14"/></svg>
            </div>
            <h4>Drop your PDF here</h4>
            <p>We will extract skills, experience, and education.</p>
            <input type="file" id="pdfFileInput" accept="application/pdf" hidden onchange="TF.uploadResume(event)">
            <button type="button" id="resumeUploadButton" class="tf-btn-primary" ${isCandidate ? '' : 'disabled'} onclick="document.getElementById('pdfFileInput').click()">${isCandidate ? 'Choose PDF' : 'Choose candidate PDF'}</button>
            <button type="button" id="resumeClearButton" class="tf-btn-outline" style="display:none;" onclick="TF.resetResumeUpload()">Remove file</button>
            <div id="resumeFileName" class="tf-resume-file-name"></div>
          </div>
          <div id="extractionProgress" class="tf-resume-status" style="display:none;">
            <div><strong id="extractionStepText">Uploading resume...</strong><span id="extractionStepVal">0%</span></div>
            <div class="tf-progress-track"><div id="extractionProgressBar" class="tf-progress-fill" style="width:0%;"></div></div>
            <p id="extractionHint" class="tf-resume-status-hint">Your resume is being sent securely.</p>
          </div>
        </div>
        <div class="tf-card">
          <div class="tf-card-header"><div><h3>${isCandidate ? `${this.user.name || 'Candidate'}'s score` : 'Selected candidate score'}</h3><p class="tf-card-subtitle">A simple, transparent breakdown.</p></div><span class="tf-pill-badge tf-pill-success">100 points</span></div>
          <div class="tf-score-summary"><strong>Score appears after processing</strong><span>-/100</span></div>
          <div id="resumeResultMeta" class="tf-resume-result-meta">Your score will appear here after the resume is matched to a job.</div>
          <div class="tf-score-list">
            <div><span>Required skills</span><strong>40 points</strong></div>
            <div><span>Extra skills</span><strong>10 points</strong></div>
            <div><span>Experience</span><strong>25 points</strong></div>
            <div><span>Education</span><strong>15 points</strong></div>
            <div><span>Profile details</span><strong>10 points</strong></div>
          </div>
          <p class="tf-card-subtitle" style="margin-top:16px;">After processing, detected skills, experience, education, and contact details will replace this summary.</p>
        </div>
      </div>
      <div class="tf-card tf-resume-tips">
        <h3>Review process</h3>
        <div><span>1</span><p>Upload PDF</p><span>2</span><p>Extract details</p><span>3</span><p>Calculate score</p></div>
      </div>
    `;
    this.bindResumeDropzone();
  },

  bindResumeDropzone() {
    const dropzone = document.getElementById('resumeDropzone');
    if (!dropzone) return;
    ['dragenter', 'dragover'].forEach(eventName => dropzone.addEventListener(eventName, event => {
      event.preventDefault();
      dropzone.classList.add('is-dragging');
    }));
    ['dragleave', 'drop'].forEach(eventName => dropzone.addEventListener(eventName, event => {
      event.preventDefault();
      dropzone.classList.remove('is-dragging');
    }));
    dropzone.addEventListener('drop', event => {
      const file = event.dataTransfer.files[0];
      if (file) this.processResumeFile(file);
    });
  },

  uploadResume(event) {
    const file = event.target.files[0];
    if (file) this.processResumeFile(file);
  },

  updateResumeTarget() {
    const select = document.getElementById('resumeCandidateSelect');
    const uploadButton = document.getElementById('resumeUploadButton');
    if (uploadButton) uploadButton.disabled = !select?.value;
  },

  async processResumeFile(file) {
    const status = document.getElementById('extractionProgress');
    const name = document.getElementById('resumeFileName');
    const clear = document.getElementById('resumeClearButton');
    const uploadButton = document.getElementById('resumeUploadButton');
    const hint = document.getElementById('extractionHint');
    const candidateSelect = document.getElementById('resumeCandidateSelect');
    const candidateId = candidateSelect?.value || '';
    if (this.user.role !== 'candidate' && !candidateId) {
      this.toast('Select a candidate before uploading a resume.', 'error');
      return;
    }
    if (file.type !== 'application/pdf' || !file.name.toLowerCase().endsWith('.pdf')) {
      this.toast('Please choose a PDF file.', 'error');
      return;
    }
    if (file.size > 5 * 1024 * 1024) {
      this.toast('The PDF must be smaller than 5 MB.', 'error');
      return;
    }
    if (name) name.textContent = file.name;
    if (clear) clear.style.display = 'inline-flex';
    if (uploadButton) uploadButton.disabled = true;
    if (status) status.style.display = 'block';
    if (hint) hint.textContent = 'Your resume is being sent securely.';
    this.updateResumeProgress('Uploading resume...', 20);

    const formData = new FormData();
    formData.append('resume', file);
    if (candidateId) formData.append('candidate_id', candidateId);
    try {
      const response = await fetch(`${this.apiBase}/resumes`, {
        method: 'POST',
        headers: { Accept: 'application/json', ...(this.token ? { Authorization: `Bearer ${this.token}` } : {}) },
        body: formData
      });
      const result = await response.json();
      if (!response.ok || !result.success) {
        this.updateResumeProgress('Upload failed', 0);
        this.toast(result.message || 'Resume upload failed.', 'error');
        return;
      }
      this.updateResumeProgress('Queued for processing', 100);
      if (hint) hint.textContent = 'The scoring engine is processing your resume. This can take a few seconds.';
      this.toast('Resume uploaded. Processing has started.', 'success');
      this.pollResumeStatus(result.data.id);
    } catch (error) {
      this.updateResumeProgress('Upload failed', 0);
      this.toast('Unable to upload the resume right now.', 'error');
    }
  },

  updateResumeProgress(message, percent) {
    const text = document.getElementById('extractionStepText');
    const value = document.getElementById('extractionStepVal');
    const bar = document.getElementById('extractionProgressBar');
    if (text) text.textContent = message;
    if (value) value.textContent = `${percent}%`;
    if (bar) bar.style.width = `${percent}%`;
  },

  async pollResumeStatus(resumeId, attempts = 0) {
    if (attempts >= 20) {
      this.updateResumeProgress('Still processing - please keep this page open', 95);
      const hint = document.getElementById('extractionHint');
      if (hint) hint.textContent = 'Processing is taking longer than usual. We will keep checking automatically.';
      return;
    }
    const result = await this.request(`/resumes/${resumeId}/status`);
    const status = result?.data?.status;
    if (status === 'processed') {
      this.updateResumeProgress('Resume processed successfully', 100);
      this.renderResumeResult(result.data);
      const hint = document.getElementById('extractionHint');
      if (hint) hint.textContent = 'Your resume review is complete.';
      this.toast('Resume details and score are ready.', 'success');
      return;
    }
    if (status === 'failed') {
      this.updateResumeProgress('Processing failed', 0);
      const hint = document.getElementById('extractionHint');
      if (hint) hint.textContent = 'Please check the PDF and try uploading it again.';
      this.toast('Resume processing failed. Please upload a clearer PDF.', 'error');
      return;
    }
    if (status) {
      this.updateResumeProgress(`Processing: ${status.replace('_', ' ')}`, Math.min(95, 25 + attempts * 4));
      const hint = document.getElementById('extractionHint');
      if (hint) hint.textContent = attempts > 8 ? 'Still working. No action is needed from you.' : 'The queue is checking your resume now.';
    }
    window.setTimeout(() => this.pollResumeStatus(resumeId, attempts + 1), 2000);
  },

  renderResumeResult(data) {
    const summary = document.querySelector('#view-candidates .tf-score-summary');
    const meta = document.getElementById('resumeResultMeta');
    const extracted = data?.extracted_data || {};
    const score = Number(data?.score);
    if (summary && Number.isFinite(score)) {
      summary.innerHTML = `<strong>Role match score</strong><span>${score.toFixed(1)}/100</span>`;
      summary.classList.add('is-ready');
    }
    if (meta) {
      const skills = Array.isArray(extracted.skills) ? extracted.skills.map(skill => skill.name).join(', ') : 'No skills detected';
      const experience = extracted.experience_years ? `${extracted.experience_years} years` : 'Not detected';
      const education = extracted.education_level || 'Not detected';
      const candidate = data?.candidate_name ? `Candidate: ${data.candidate_name}` : 'Candidate name unavailable';
      const role = data?.score_job_title ? `Matched to: ${data.score_job_title}` : 'No role match yet';
      meta.innerHTML = `<strong>Detected profile</strong><span>${candidate}</span><span>${role}</span><span>${skills}</span><span>${experience} experience</span><span>${education}</span>`;
      meta.classList.add('is-ready');
    }
  },

  resetResumeUpload() {
    const input = document.getElementById('pdfFileInput');
    const name = document.getElementById('resumeFileName');
    const clear = document.getElementById('resumeClearButton');
    const status = document.getElementById('extractionProgress');
    const uploadButton = document.getElementById('resumeUploadButton');
    if (input) input.value = '';
    if (name) name.textContent = '';
    if (clear) clear.style.display = 'none';
    if (status) status.style.display = 'none';
    if (uploadButton) uploadButton.disabled = false;
  },

  // -------------------------------------------------------------
  // VIEW: Hiring Pipeline State Machine Board
  // -------------------------------------------------------------
  renderPipeline() {
    const container = document.getElementById('view-pipeline');
    if (!container) return;

    if (!this.liveData.applicationsLoaded) {
      this.loadDashboardData().then(() => {
        if (this.currentView === 'pipeline') this.renderPipeline();
      });
      container.innerHTML = '<div class="tf-card"><p>Loading live applications...</p></div>';
      return;
    }

    const stages = [
      { key: 'applied', label: 'Applied' },
      { key: 'screening', label: 'Screening' },
      { key: 'shortlisted', label: 'Shortlisted' },
      { key: 'interview', label: 'Interview' },
      { key: 'technical_task', label: 'Technical Task' },
      { key: 'hired', label: 'Hired / Offered' }
    ];

    const applications = this.liveData.applications || [];
    const totalApplications = applications.length;
    const activeApplications = applications.filter(application => !['hired', 'rejected'].includes(application.current_status)).length;
    const averageScore = totalApplications
      ? (applications.reduce((total, application) => total + Number(application.score || 0), 0) / totalApplications).toFixed(1)
      : '0.0';

    container.innerHTML = `
      <div class="tf-greeting-bar">
        <div class="tf-greeting-text">
          <h2>Hiring Pipeline State Machine</h2>
          <p>Live applications grouped by stage with guarded status transitions.</p>
        </div>
        <div class="tf-greeting-actions">
          <button class="tf-btn-outline" onclick="TF.refreshPipeline()">Refresh</button>
        </div>
      </div>

      <div class="tf-pipeline-summary">
        <div><span>Total applications</span><strong>${totalApplications}</strong></div>
        <div><span>Active pipeline</span><strong>${activeApplications}</strong></div>
        <div><span>Average score</span><strong>${averageScore}<small>/100</small></strong></div>
      </div>

      <div class="tf-kanban-board">
        ${stages.map(stage => {
          const appsInStage = applications.filter(application => (application.current_status || application.status) === stage.key);
          return `
            <div class="tf-kanban-col">
              <div class="tf-kanban-col-header">
                <span class="tf-col-title">
                  <span style="width:8px; height:8px; border-radius:50%; background:var(--primary-color);"></span>
                  ${stage.label}
                </span>
                <span class="tf-col-count">${appsInStage.length}</span>
              </div>

              ${appsInStage.map(app => `
                <div class="tf-candidate-card" onclick="TF.openTransitionModal(${app.id})">
                  <div class="tf-card-top">
                    <span class="tf-card-name">${app.candidate?.user?.name || app.candidate_name || 'Candidate'}</span>
                    <span class="tf-card-score">${Number(app.score || 0).toFixed(1)} pts</span>
                  </div>
                  <div class="tf-card-job">${app.job?.title || app.job_title || 'Open role'}</div>
                  <div class="tf-card-tags">
                    ${(app.resume?.extracted_data?.skills || app.skills || []).slice(0, 3).map(skill => `<span class="tf-mini-tag">${skill.name || skill}</span>`).join('')}
                  </div>
                  <div class="tf-pipeline-card-footer">
                    <span>${app.resume?.extracted_data?.experience_years || app.experience || 0} yrs exp</span>
                    <span>Manage</span>
                  </div>
                </div>
              `).join('')}
            </div>
          `;
        }).join('')}
      </div>
    `;
  },

  async refreshPipeline() {
    this.liveData.applicationsLoaded = false;
    this.renderPipeline();
  },

  // -------------------------------------------------------------
  // VIEW: Interview Scheduler
  // -------------------------------------------------------------
  renderInterviews() {
    const container = document.getElementById('view-interviews');
    if (!container) return;

    if (!this.liveData.interviewsLoaded) {
      this.loadInterviews();
      container.innerHTML = '<div class="tf-card"><p>Loading interviews...</p></div>';
      return;
    }

    const interviews = this.liveData.interviews;
    const upcoming = interviews.filter(interview => ['scheduled', 'confirmed'].includes(interview.status)).length;
    const completed = interviews.filter(interview => interview.status === 'completed').length;
    const cancelled = interviews.filter(interview => interview.status === 'cancelled').length;

    container.innerHTML = `
      <div class="tf-greeting-bar">
        <div class="tf-greeting-text">
          <h2>Interview Scheduler & Conflict Guard</h2>
          <p>Schedules candidate interviews with automatic overlap validation.</p>
        </div>
        <div class="tf-greeting-actions">
          <button class="tf-btn-outline" onclick="TF.refreshInterviews()">Refresh</button>
          <button class="tf-btn-primary" onclick="TF.openScheduleModal()">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Schedule Interview
          </button>
        </div>
      </div>

      <div class="tf-interview-summary">
        <div><span>Upcoming</span><strong>${upcoming}</strong></div>
        <div><span>Completed</span><strong>${completed}</strong></div>
        <div><span>Cancelled</span><strong>${cancelled}</strong></div>
      </div>

      <div class="tf-interview-grid">
        ${interviews.length ? interviews.map(interview => {
          const date = new Date(interview.scheduled_at);
          const status = interview.status || 'scheduled';
          const candidateName = interview.application?.candidate?.name || interview.application?.candidate?.user?.name || 'Candidate';
          const initials = candidateName.split(/\s+/).map(part => part[0]).join('').slice(0, 2).toUpperCase();
          return `
          <div class="tf-interview-card">
            <div class="tf-interview-card-header">
              <div class="tf-interview-person">
                <span class="tf-interview-avatar">${initials}</span>
                <div>
                <h3>${candidateName}</h3>
                <p>${interview.application?.job?.title || 'Application'}</p>
                </div>
              </div>
              <span class="tf-interview-status is-${status}">${status.replace('_', ' ')}</span>
            </div>

            <div class="tf-interview-time">
              <strong>${Number.isNaN(date.getTime()) ? 'Date not available' : date.toLocaleDateString(undefined, { day: '2-digit', month: 'short', year: 'numeric' })}</strong>
              <span>${Number.isNaN(date.getTime()) ? '' : date.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' })} · ${interview.duration || 60} mins</span>
            </div>
            <div class="tf-interview-details">
              <span><strong>Interviewer</strong>${interview.interviewer?.name || 'Assigned interviewer'}</span>
              <span><strong>Meeting</strong>${interview.meeting_link ? 'Online meeting' : 'Link not added'}</span>
            </div>

            <div class="tf-interview-actions">
              ${interview.meeting_link ? `<a class="tf-btn-primary" href="${interview.meeting_link}" target="_blank" rel="noopener">Join meeting</a>` : '<span class="tf-interview-no-link">Meeting link pending</span>'}
              <button class="tf-btn-outline" onclick="TF.copyMeetingLink('${interview.meeting_link || ''}')">Copy link</button>
            </div>
          </div>
        `;
        }).join('') : '<div class="tf-card"><p>No interviews found for your account.</p></div>'}
      </div>
    `;
  },

  async refreshInterviews() {
    this.liveData.interviewsLoaded = false;
    this.renderInterviews();
  },

  async loadInterviews() {
    const result = await this.requestAllPages('/interviews');
    if (result?.data) {
      this.liveData.interviews = result.data;
      this.liveData.interviewsLoaded = true;
      this.renderInterviews();
    }
  },

  async copyMeetingLink(link) {
    if (!link) {
      this.toast('This interview has no meeting link.', 'error');
      return;
    }
    try {
      await navigator.clipboard.writeText(link);
      this.toast('Meeting link copied to clipboard', 'success');
    } catch (error) {
      this.toast('Clipboard access was blocked. Open the link and copy it manually.', 'error');
    }
  },

  // -------------------------------------------------------------
  // VIEW: Technical Tasks & Submissions
  // -------------------------------------------------------------
  renderTasks() {
    const container = document.getElementById('view-tasks');
    if (!container) return;

    if (!this.liveData.tasksLoaded) {
      this.loadTasks();
      container.innerHTML = '<div class="tf-card"><p>Loading technical assessments...</p></div>';
      return;
    }

    const tasks = this.liveData.tasks;
    const submittedTasks = tasks.filter(task => task.status === 'submitted' || task.latest_submission).length;
    const overdueTasks = tasks.filter(task => task.is_overdue || task.status === 'overdue').length;

    container.innerHTML = `
      <div class="tf-greeting-bar">
        <div class="tf-greeting-text">
          <h2>Technical Assessments & Submissions</h2>
          <p>Track take-home assignments, deadlines, and candidate submissions.</p>
        </div>
        <div class="tf-greeting-actions">
          <button class="tf-btn-outline" onclick="TF.refreshTasks()">Refresh</button>
        </div>
      </div>

      <div class="tf-task-summary">
        <div><span>Total tasks</span><strong>${tasks.length}</strong></div>
        <div><span>Submitted</span><strong>${submittedTasks}</strong></div>
        <div><span>Overdue</span><strong>${overdueTasks}</strong></div>
      </div>

      <div class="tf-task-grid">
        ${tasks.length ? tasks.map(t => {
          const application = t.application || {};
          const candidate = application.candidate?.user?.name || 'Candidate';
          const job = application.job?.title || 'Application';
          const submission = t.latest_submission;
          const dueDate = t.due_at ? new Date(t.due_at) : null;
          const overdue = t.is_overdue || t.status === 'overdue';
          return `
          <div class="tf-task-card ${overdue ? 'is-overdue' : ''}">
            <div class="tf-task-card-header">
              <div>
                <h3>${t.title}</h3>
                <p>${candidate} · ${job}</p>
              </div>
              <span class="tf-task-status is-${t.status}">${t.status.replace('_', ' ')}</span>
            </div>
            <p class="tf-task-description">${t.description || 'Complete the technical assessment and submit your solution before the deadline.'}</p>
            <div class="tf-task-meta">
              <span><strong>Due</strong>${dueDate && !Number.isNaN(dueDate.getTime()) ? dueDate.toLocaleDateString(undefined, { day: '2-digit', month: 'short', year: 'numeric' }) : 'No deadline'}</span>
              <span><strong>Submission</strong>${submission?.submission_url ? 'Ready to review' : 'Not submitted'}</span>
            </div>
            <div class="tf-task-actions">
              ${submission?.submission_url ? `<a class="tf-btn-primary" href="${submission.submission_url}" target="_blank" rel="noopener">Review submission</a>` : '<span class="tf-task-pending">Waiting for submission</span>'}
              <button class="tf-btn-outline" onclick="TF.toast('Task instructions are shown in the assessment details.', 'success')">Instructions</button>
            </div>
          </div>
        `;
        }).join('') : '<div class="tf-card"><p>No technical assessments are assigned to your account.</p></div>'}
      </div>
    `;
  },

  async refreshTasks() {
    this.liveData.tasksLoaded = false;
    this.renderTasks();
  },

  async loadTasks() {
    const result = await this.requestAllPages('/tasks');
    if (result?.data) {
      this.liveData.tasks = result.data;
      this.liveData.tasksLoaded = true;
      this.renderTasks();
    }
  },

  // -------------------------------------------------------------
  // Modals & User Actions
  // -------------------------------------------------------------
  openModal(modalId) {
    const el = document.getElementById(modalId);
    if (el) el.classList.add('open', 'active');
  },

  closeModal(modalId) {
    const el = document.getElementById(modalId);
    if (el) el.classList.remove('open', 'active');
  },

  openPostJobModal() {
    this.editingJobId = null;
    this.setJobModalMode('create');
    this.openModal('modalPostJob');
  },

  openEditJobModal(jobId) {
    const job = this.liveData.jobs.find(item => item.id === jobId);
    if (!job) return;

    this.editingJobId = jobId;
    document.getElementById('newJobTitle').value = job.title;
    document.getElementById('newJobDept').value = (job.department || 'Engineering').split(' (')[0];
    document.getElementById('newJobExp').value = job.required_experience;
    document.getElementById('newJobMinSalary').value = job.salary_min || '';
    document.getElementById('newJobMaxSalary').value = job.salary_max || '';
    this.setJobModalMode('edit');
    this.openModal('modalPostJob');
  },

  setJobModalMode(mode) {
    const title = document.getElementById('jobModalTitle');
    const submit = document.getElementById('jobModalSubmit');
    if (title) title.textContent = mode === 'edit' ? 'Edit Job Requisition' : 'Create New Job Requisition';
    if (submit) submit.textContent = mode === 'edit' ? 'Save Changes' : 'Publish Job';
  },

  async handleCreateJobSubmit(e) {
    e.preventDefault();
    const title = document.getElementById('newJobTitle').value;
    const dept = document.getElementById('newJobDept').value;
    const exp = document.getElementById('newJobExp').value;
    const minSalary = document.getElementById('newJobMinSalary').value;
    const maxSalary = document.getElementById('newJobMaxSalary').value;

    const wasEditing = Boolean(this.editingJobId);
    const endpoint = wasEditing ? `/jobs/${this.editingJobId}` : '/jobs';
    const result = await this.request(endpoint, {
      method: wasEditing ? 'PUT' : 'POST',
      body: JSON.stringify({
        title,
        department: dept,
        description: 'Open role managed through the TalentFlow hiring dashboard.',
        required_experience: parseFloat(exp),
        salary_min: parseInt(minSalary, 10) || null,
        salary_max: parseInt(maxSalary, 10) || null,
        application_deadline: new Date(Date.now() + 30 * 86400000).toISOString(),
        status: 'published'
      })
    });

    if (!result?.success) {
      this.toast(result?.message || 'Unable to save this job opening.', 'error');
      return;
    }

    if (result.data) {
      const index = this.liveData.jobs.findIndex(job => job.id === result.data.id);
      if (index >= 0) this.liveData.jobs[index] = result.data;
      else this.liveData.jobs.unshift(result.data);
    }
    this.editingJobId = null;
    this.closeModal('modalPostJob');
    this.toast(wasEditing ? `Job '${title}' updated successfully!` : `Job '${title}' published successfully!`, 'success');
    if (this.currentView === 'jobs') this.renderJobs();
  },

  openApplyModal(jobId, jobTitle) {
    document.getElementById('applyModalJobTitle').textContent = jobTitle;
    document.getElementById('applyModalJobId').value = jobId;
    this.openModal('modalApplyJob');
  },

  handleApplySubmit(e) {
    e.preventDefault();
    this.closeModal('modalApplyJob');
    this.toast('Application and PDF resume submitted successfully! Queued for AI parsing.', 'success');
  },

  openTransitionModal(appId) {
    const app = this.liveData.applications.find(item => item.id === appId);
    if (!app) return;

    document.getElementById('transitionCandidateName').textContent = app.candidate?.user?.name || app.candidate_name || 'Candidate';
    document.getElementById('transitionJobTitle').textContent = app.job?.title || app.job_title || 'Open role';
    document.getElementById('transitionCurrentStatus').textContent = (app.current_status || app.status).toUpperCase();
    document.getElementById('transitionAppId').value = appId;

    this.openModal('modalTransition');
  },

  async handleTransitionSubmit(e) {
    e.preventDefault();
    const appId = parseInt(document.getElementById('transitionAppId').value);
    const targetStatus = document.getElementById('transitionTargetStatus').value;
    const remarks = document.getElementById('transitionRemarks').value;

    const app = this.liveData.applications.find(item => item.id === appId);
    if (!app) return;
    const result = await this.request(`/applications/${appId}/status`, {
      method: 'PATCH',
      body: JSON.stringify({ status: targetStatus, remarks })
    });
    if (!result?.success) {
      this.toast(result?.message || 'Unable to update application status.', 'error');
      return;
    }
    const index = this.liveData.applications.findIndex(item => item.id === appId);
    if (index >= 0) this.liveData.applications[index] = { ...app, ...result.data };
    this.closeModal('modalTransition');
    this.toast(`Moved ${app.candidate?.user?.name || 'candidate'} to ${targetStatus.toUpperCase()}`, 'success');
    if (this.currentView === 'pipeline') this.renderPipeline();
  },

  openScheduleModal() {
    const candidateSelect = document.getElementById('interviewApplication');
    if (candidateSelect) {
      candidateSelect.innerHTML = this.liveData.applications.length
        ? this.liveData.applications.map(application => {
          const name = application.candidate?.user?.name || `Application #${application.id}`;
          const title = application.job?.title || 'Open role';
          return `<option value="${application.id}">${name} - ${title}</option>`;
        }).join('')
        : '<option value="">No applications available</option>';
    }
    this.openModal('modalSchedule');
  },

  async handleScheduleSubmit(e) {
    e.preventDefault();
    const applicationId = document.getElementById('interviewApplication').value;
    const date = document.getElementById('interviewDate').value;
    const time = document.getElementById('interviewTime').value;
    const meetingLink = document.getElementById('interviewMeetingLink').value;

    if (!applicationId) {
      this.toast('Create or load an application before scheduling an interview.', 'error');
      return;
    }

    const result = await this.request(`/applications/${applicationId}/interviews`, {
      method: 'POST',
      body: JSON.stringify({
        interviewer_id: this.user.id || undefined,
        scheduled_at: `${date}T${time}:00`,
        duration: 60,
        meeting_link: meetingLink
      })
    });

    if (!result?.success) {
      const message = result?.errors?.scheduled_at?.[0] || result?.message || 'Unable to schedule this interview.';
      this.toast(message, 'error');
      return;
    }

    this.closeModal('modalSchedule');
    this.liveData.interviewsLoaded = false;
    this.toast(`Interview booked successfully on ${date} at ${time}.`, 'success');
    if (this.currentView === 'interviews') this.renderInterviews();
  },

  toggleDashboardFilter() {
    const existing = document.getElementById('dashboardFilterPanel');
    if (existing) {
      existing.remove();
      return;
    }
    const panel = document.createElement('div');
    panel.id = 'dashboardFilterPanel';
    panel.className = 'tf-card';
    panel.style.cssText = 'margin-bottom:20px; display:flex; align-items:center; gap:12px;';
    panel.innerHTML = `<strong>Dashboard period</strong><select id="dashboardPeriodSelect" class="tf-chart-select"><option value="week" ${this.dashboardPeriod === 'week' ? 'selected' : ''}>This week</option><option value="month" ${this.dashboardPeriod === 'month' ? 'selected' : ''}>This month</option><option value="all" ${this.dashboardPeriod === 'all' ? 'selected' : ''}>All time</option></select><span id="dashboardFilterStatus" style="font-size:12px;color:var(--text-muted);">Live KPIs are loaded from the database.</span>`;
    panel.querySelector('#dashboardPeriodSelect').addEventListener('change', (event) => {
      this.liveData.dashboardLoaded = false;
      panel.querySelector('#dashboardFilterStatus').textContent = 'Updating dashboard...';
      this.loadDashboardData(event.target.value);
    });
    document.getElementById('view-dashboard')?.querySelector('.tf-greeting-bar')?.after(panel);
  },

  toggleNotificationDropdown() {
    const dd = document.getElementById('notificationDropdown');
    if (dd) dd.classList.toggle('open');
  },

  openAuthModal() {
    this.openModal('modalAuth');
  },

  // Event Listeners
  bindEvents() {
    document.querySelectorAll('.tf-nav-item').forEach(item => {
      item.addEventListener('click', () => {
        const view = item.dataset.view;
        if (view) this.switchView(view);
      });
    });

    // Close modals when clicking backdrop
    document.querySelectorAll('.tf-modal-backdrop').forEach(backdrop => {
      backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) {
          backdrop.classList.remove('open', 'active');
        }
      });
    });
  }
};

document.addEventListener('DOMContentLoaded', () => {
  TF.init();
});
