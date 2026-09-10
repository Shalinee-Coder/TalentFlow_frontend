<!-- View: Dashboard & Analytics -->
<div id="view-dashboard" class="tf-view active-view">
  <!-- Greeting Bar -->
  <div class="tf-greeting-bar">
    <div class="tf-greeting-text">
      <h2>Welcome to your hiring workspace</h2>
      <p>Track candidates, manage interviews, and keep every hire moving forward.</p>
    </div>
    <div class="tf-greeting-actions">
      <select class="tf-chart-select" style="padding:9px 16px; font-size:13px;">
        <option>This week</option>
        <option>This month</option>
        <option>All time</option>
      </select>
      <button class="tf-btn-primary" onclick="TF.openPostJobModal()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        + Post Job
      </button>
    </div>
  </div>

  <!-- Row 1: 4 Top KPI Cards (1 Hero Blue + 3 Crisp White with Sparklines) -->
  <div class="tf-metrics-grid">
    <!-- Card 1: Hero Royal Blue -->
    <div class="tf-hero-kpi-card">
      <div class="tf-hero-top">
        <span class="tf-hero-title">Active Candidates</span>
        <div class="tf-hero-icon">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
      </div>
      <div>
        <div class="tf-hero-val">0</div>
        <div class="tf-hero-badge">↑ +12.4% vs last week</div>
      </div>
      <!-- SVG Translucent Area Wave -->
      <svg class="tf-hero-wave" viewBox="0 0 300 60" preserveAspectRatio="none">
        <defs>
          <linearGradient id="heroWaveGrad" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.25"/>
            <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0.0"/>
          </linearGradient>
        </defs>
        <path d="M0,45 C50,20 100,55 150,25 C200,5 250,35 300,15 L300,60 L0,60 Z" fill="url(#heroWaveGrad)"/>
        <path d="M0,45 C50,20 100,55 150,25 C200,5 250,35 300,15" fill="none" stroke="#FFFFFF" stroke-width="2.5"/>
      </svg>
    </div>

    <!-- Card 2: Crisp White (Total Applications) -->
    <div class="tf-spark-kpi-card">
      <div class="tf-spark-top">
        <span class="tf-spark-title">Total Applications</span>
        <div class="tf-spark-icon" style="background:#EFF6FF; color:#2563EB;">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
      </div>
      <div>
        <div class="tf-spark-val">0</div>
        <div class="tf-spark-badge" style="background:#FEF2F2; color:#DC2626;">↓ -4.2% vs last week</div>
      </div>
      <!-- SVG Blue Sparkline Wave -->
      <svg class="tf-spark-wave" viewBox="0 0 300 50" preserveAspectRatio="none">
        <path d="M0,35 C60,45 110,15 160,30 C210,45 260,20 300,25" fill="none" stroke="#3B82F6" stroke-width="2.5" stroke-linecap="round"/>
      </svg>
    </div>

    <!-- Card 3: Crisp White (Shortlisted Talents) -->
    <div class="tf-spark-kpi-card">
      <div class="tf-spark-top">
        <span class="tf-spark-title">Shortlisted Talents</span>
        <div class="tf-spark-icon" style="background:#F5F3FF; color:#7C3AED;">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
      </div>
      <div>
        <div class="tf-spark-val">0</div>
        <div class="tf-spark-badge" style="background:#ECFDF5; color:#059669;">↑ +16.7% vs last week</div>
      </div>
      <!-- SVG Purple Sparkline Wave -->
      <svg class="tf-spark-wave" viewBox="0 0 300 50" preserveAspectRatio="none">
        <path d="M0,40 C60,20 120,38 180,18 C240,32 270,12 300,20" fill="none" stroke="#8B5CF6" stroke-width="2.5" stroke-linecap="round"/>
      </svg>
    </div>

    <!-- Card 4: Crisp White (Interviews Scheduled) -->
    <div class="tf-spark-kpi-card">
      <div class="tf-spark-top">
        <span class="tf-spark-title">Interviews Scheduled</span>
        <div class="tf-spark-icon" style="background:#ECFEFF; color:#0284C7;">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
      </div>
      <div>
        <div class="tf-spark-val">0</div>
        <div class="tf-spark-badge" style="background:#ECFDF5; color:#059669;">↑ +5.1% vs last week</div>
      </div>
      <!-- SVG Green/Sky Sparkline Wave -->
      <svg class="tf-spark-wave" viewBox="0 0 300 50" preserveAspectRatio="none">
        <path d="M0,30 C50,40 100,18 170,35 C230,22 260,10 300,18" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round"/>
      </svg>
    </div>
  </div>

  <!-- Row 2: Bar Chart & Donut Chart -->
  <div class="tf-charts-grid">
    <!-- Left: Vertical Bar Chart (Recruitment Pipeline Growth) -->
    <div class="tf-chart-card">
      <div class="tf-chart-header">
        <h3>Recruitment Pipeline Growth</h3>
        <select class="tf-chart-select">
          <option>Last 7 days</option>
          <option>Last 30 days</option>
        </select>
      </div>

      <div class="tf-bar-chart-container">
        <!-- 7 Days Bars -->
        <div class="tf-bar-col">
          <div class="tf-bar-bar" style="height: 48px;" title="Mon: 18 applications"></div>
          <span class="tf-bar-day">Mon</span>
        </div>
        <div class="tf-bar-col">
          <div class="tf-bar-bar" style="height: 75px;" title="Tue: 32 applications"></div>
          <span class="tf-bar-day">Tue</span>
        </div>
        <div class="tf-bar-col">
          <div class="tf-bar-bar" style="height: 62px;" title="Wed: 26 applications"></div>
          <span class="tf-bar-day">Wed</span>
        </div>
        <div class="tf-bar-col">
          <div class="tf-bar-bar" style="height: 115px; background: #2563EB;" title="Thu: 54 applications (Peak)"></div>
          <span class="tf-bar-day">Thu</span>
        </div>
        <div class="tf-bar-col">
          <div class="tf-bar-bar" style="height: 85px;" title="Fri: 38 applications"></div>
          <span class="tf-bar-day">Fri</span>
        </div>
        <div class="tf-bar-col">
          <div class="tf-bar-bar" style="height: 130px; background: #3B5BFD;" title="Sat: 62 applications"></div>
          <span class="tf-bar-day">Sat</span>
        </div>
        <div class="tf-bar-col">
          <div class="tf-bar-bar" style="height: 40px;" title="Sun: 15 applications"></div>
          <span class="tf-bar-day">Sun</span>
        </div>
      </div>
    </div>

    <!-- Right: Donut Chart (Department & Skill Breakdown) -->
    <div class="tf-chart-card">
      <div class="tf-chart-header">
        <h3>Skill & Role Distribution</h3>
      </div>

      <div class="tf-donut-layout">
        <!-- SVG Donut -->
        <svg width="150" height="150" viewBox="0 0 42 42">
          <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#F1F5F9" stroke-width="5.5"></circle>
          <!-- Slice 1: Engineering (40%) -->
          <circle cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#3B5BFD" stroke-width="5.5" stroke-dasharray="40 60" stroke-dashoffset="25"></circle>
          <!-- Slice 2: Product (30%) -->
          <circle cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#38BDF8" stroke-width="5.5" stroke-dasharray="30 70" stroke-dashoffset="85"></circle>
          <!-- Slice 3: DevOps / Cloud (20%) -->
          <circle cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#FB923C" stroke-width="5.5" stroke-dasharray="20 80" stroke-dashoffset="55"></circle>
          <!-- Slice 4: Others / QA (10%) -->
          <circle cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#34D399" stroke-width="5.5" stroke-dasharray="10 90" stroke-dashoffset="35"></circle>
        </svg>

        <!-- Legend -->
        <div class="tf-donut-legend">
          <div class="tf-legend-item">
            <span class="tf-legend-dot" style="background:#3B5BFD;"></span>
            <span>Engineering</span>
            <span class="tf-legend-pct">40%</span>
          </div>
          <div class="tf-legend-item">
            <span class="tf-legend-dot" style="background:#38BDF8;"></span>
            <span>Product</span>
            <span class="tf-legend-pct">30%</span>
          </div>
          <div class="tf-legend-item">
            <span class="tf-legend-dot" style="background:#FB923C;"></span>
            <span>DevOps</span>
            <span class="tf-legend-pct">20%</span>
          </div>
          <div class="tf-legend-item">
            <span class="tf-legend-dot" style="background:#34D399;"></span>
            <span>QA & Data</span>
            <span class="tf-legend-pct">10%</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Row 3: 3 Mini Horizontal Metric Cards -->
  <div class="tf-mini-metrics-row">
    <div class="tf-mini-card">
      <div class="tf-mini-icon" style="background:#EFF6FF; color:#2563EB;">
        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/></svg>
      </div>
      <div class="tf-mini-content">
        <span class="tf-mini-label">Resumes Under Screening</span>
        <div class="tf-mini-val-wrap">
          <span class="tf-mini-val">27</span>
          <span class="tf-mini-diff" style="color:#059669;">↑ +3 vs yesterday</span>
        </div>
      </div>
    </div>

    <div class="tf-mini-card">
      <div class="tf-mini-icon" style="background:#ECFDF5; color:#059669;">
        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div class="tf-mini-content">
        <span class="tf-mini-label">Offer Acceptance Rate</span>
        <div class="tf-mini-val-wrap">
          <span class="tf-mini-val">92%</span>
          <span class="tf-mini-diff" style="color:#059669;">↑ +1.5% vs last month</span>
        </div>
      </div>
    </div>

    <div class="tf-mini-card">
      <div class="tf-mini-icon" style="background:#FFFBEB; color:#D97706;">
        <svg width="22" height="22" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
      </div>
      <div class="tf-mini-content">
        <span class="tf-mini-label">Hiring Satisfaction</span>
        <div class="tf-mini-val-wrap">
          <span class="tf-mini-val">4.8/5</span>
          <span class="tf-mini-diff" style="color:#059669;">↑ +0.2 vs last quarter</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Row 4: Candidate Applications & Management Table -->
  <div class="tf-table-card">
    <div class="tf-table-top-bar">
      <h3>Candidate Applications & Pipeline</h3>
      <button class="tf-btn-primary" onclick="TF.openPostJobModal()">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        + Post New Job
      </button>
    </div>

    <!-- Filter Pills & Search Bar -->
    <div class="tf-table-filters-row">
      <div class="tf-status-pills-wrap">
        <div class="tf-status-pill-item" style="background:#EFF6FF; color:#2563EB; border:1px solid #BFDBFE;">
          <span>Applied: 18</span>
          <span style="font-size:10px; color:#059669;">(+2%)</span>
        </div>
        <div class="tf-status-pill-item" style="background:#FFFBEB; color:#D97706; border:1px solid #FDE68A;">
          <span>Screening: 15</span>
          <span style="font-size:10px; color:#059669;">(+1%)</span>
        </div>
        <div class="tf-status-pill-item" style="background:#F5F3FF; color:#7C3AED; border:1px solid #DDD6FE;">
          <span>Interview: 34</span>
          <span style="font-size:10px; color:#059669;">(+5%)</span>
        </div>
        <div class="tf-status-pill-item" style="background:#ECFDF5; color:#059669; border:1px solid #A7F3D0;">
          <span>Hired: 74</span>
          <span style="font-size:10px; color:#059669;">(+3%)</span>
        </div>
      </div>

      <div class="tf-table-search-group">
        <div class="tf-table-search-input">
          <svg width="15" height="15" fill="none" stroke="#94A3B8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input type="text" placeholder="Search candidate, job...">
        </div>
        <button class="tf-btn-outline" style="padding:7px 14px; font-size:12px;">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
          Filters
        </button>
        <select class="tf-chart-select" style="padding:7px 12px; font-size:12px;">
          <option>Sort by date</option>
          <option>Sort by score</option>
        </select>
      </div>
    </div>

    <!-- The Data Table -->
    <div class="tf-table-responsive">
      <table class="tf-table">
        <thead>
          <tr>
            <th>Candidate</th>
            <th>Applied Role</th>
            <th>Current Company & Location</th>
            <th>ATS Score</th>
            <th>Applied Date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Candidate 1 -->
          <tr>
            <td>
              <div class="tf-cand-info">
                <div class="tf-cand-avatar">AS</div>
                <div>
                  <span class="tf-cand-name">Aarav Sharma</span>
                  <span class="tf-cand-email">candidate1@talentflow.local</span>
                </div>
              </div>
            </td>
            <td><strong>Senior Laravel Architect</strong></td>
            <td>Razorpay • Bengaluru, KA</td>
            <td><span class="tf-score-pill">95.0%</span></td>
            <td>05/09/2026</td>
            <td><span class="tf-pill-badge tf-pill-purple">Technical Task</span></td>
            <td>
              <button class="tf-btn-outline" style="padding:5px 10px; font-size:11px;" onclick="TF.openTransitionModal(1, 'Aarav Sharma', 'technical_task')">Manage →</button>
            </td>
          </tr>

          <!-- Candidate 2 -->
          <tr>
            <td>
              <div class="tf-cand-info">
                <div class="tf-cand-avatar" style="background:#F5F3FF; color:#7C3AED;">AI</div>
                <div>
                  <span class="tf-cand-name">Ananya Iyer</span>
                  <span class="tf-cand-email">candidate2@talentflow.local</span>
                </div>
              </div>
            </td>
            <td><strong>Full Stack Engineer (Vue & Laravel)</strong></td>
            <td>Swiggy • Pune, MH</td>
            <td><span class="tf-score-pill" style="color:#2563EB; background:#EFF6FF;">86.5%</span></td>
            <td>06/09/2026</td>
            <td><span class="tf-pill-badge tf-pill-amber">Screening</span></td>
            <td>
              <button class="tf-btn-outline" style="padding:5px 10px; font-size:11px;" onclick="TF.openTransitionModal(2, 'Ananya Iyer', 'screening')">Manage →</button>
            </td>
          </tr>

          <!-- Candidate 3 -->
          <tr>
            <td>
              <div class="tf-cand-info">
                <div class="tf-cand-avatar" style="background:#ECFEFF; color:#0284C7;">RV</div>
                <div>
                  <span class="tf-cand-name">Rohan Verma</span>
                  <span class="tf-cand-email">candidate3@talentflow.local</span>
                </div>
              </div>
            </td>
            <td><strong>Senior Laravel Architect</strong></td>
            <td>Flipkart • Hyderabad, TS</td>
            <td><span class="tf-score-pill">91.0%</span></td>
            <td>04/09/2026</td>
            <td><span class="tf-pill-badge tf-pill-blue">Shortlisted</span></td>
            <td>
              <button class="tf-btn-outline" style="padding:5px 10px; font-size:11px;" onclick="TF.openTransitionModal(3, 'Rohan Verma', 'shortlisted')">Manage →</button>
            </td>
          </tr>

          <!-- Candidate 4 -->
          <tr>
            <td>
              <div class="tf-cand-info">
                <div class="tf-cand-avatar" style="background:#FFFBEB; color:#D97706;">AR</div>
                <div>
                  <span class="tf-cand-name">Aditya Rao</span>
                  <span class="tf-cand-email">candidate5@talentflow.local</span>
                </div>
              </div>
            </td>
            <td><strong>Principal Cloud Architect</strong></td>
            <td>Zomato • Gurugram, HR</td>
            <td><span class="tf-score-pill">97.5%</span></td>
            <td>03/09/2026</td>
            <td><span class="tf-pill-badge tf-pill-blue">Interview</span></td>
            <td>
              <button class="tf-btn-outline" style="padding:5px 10px; font-size:11px;" onclick="TF.openScheduleModal(4, 'Aditya Rao')">Schedule</button>
            </td>
          </tr>

          <!-- Candidate 5 -->
          <tr>
            <td>
              <div class="tf-cand-info">
                <div class="tf-cand-avatar" style="background:#ECFDF5; color:#059669;">PD</div>
                <div>
                  <span class="tf-cand-name">Pooja Deshmukh</span>
                  <span class="tf-cand-email">candidate6@talentflow.local</span>
                </div>
              </div>
            </td>
            <td><strong>Senior Laravel Architect</strong></td>
            <td>Paytm • Noida, UP</td>
            <td><span class="tf-score-pill">88.0%</span></td>
            <td>28/08/2026</td>
            <td><span class="tf-pill-badge tf-pill-green">Hired</span></td>
            <td>
              <button class="tf-btn-outline" style="padding:5px 10px; font-size:11px;" onclick="TF.toast('Candidate already onboarded!', 'success')">Profile</button>
            </td>
          </tr>

          <!-- Candidate 6 -->
          <tr>
            <td>
              <div class="tf-cand-info">
                <div class="tf-cand-avatar" style="background:#EFF6FF; color:#2563EB;">NG</div>
                <div>
                  <span class="tf-cand-name">Neha Gupta</span>
                  <span class="tf-cand-email">candidate8@talentflow.local</span>
                </div>
              </div>
            </td>
            <td><strong>Senior Cloud Infrastructure</strong></td>
            <td>PhonePe • Bengaluru, KA</td>
            <td><span class="tf-score-pill">92.5%</span></td>
            <td>05/09/2026</td>
            <td><span class="tf-pill-badge tf-pill-amber">Screening</span></td>
            <td>
              <button class="tf-btn-outline" style="padding:5px 10px; font-size:11px;" onclick="TF.openTransitionModal(8, 'Neha Gupta', 'screening')">Manage →</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="tf-pagination-bar">
      <span>Showing 1 to 6 of 141 candidates</span>
      <div class="tf-page-nav">
        <button class="tf-page-btn">‹</button>
        <button class="tf-page-btn active">1</button>
        <button class="tf-page-btn">2</button>
        <button class="tf-page-btn">3</button>
        <button class="tf-page-btn">…</button>
        <button class="tf-page-btn">20</button>
        <button class="tf-page-btn">›</button>
      </div>
    </div>
  </div>
</div>
