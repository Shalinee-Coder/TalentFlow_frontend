<!-- Top Application Header -->
<header class="tf-header">
  <!-- Search -->
  <div class="tf-search-bar">
    <svg width="18" height="18" fill="none" stroke="#8E8DA0" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    <input type="text" placeholder="Search candidate, job, skills...">
  </div>

  <!-- Header Actions -->
  <div class="tf-header-actions">
    <!-- Persona 1-Click Switcher -->
    <div class="tf-role-switcher" onclick="TF.openAuthModal()" title="Switch account">
      <span id="tfPersonaBadge">● Account</span>
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
    </div>

    <!-- Notification Bell -->
    <div class="tf-notification-wrap">
      <div class="tf-icon-btn" onclick="TF.toggleNotificationDropdown()" title="Notifications">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        <span id="notificationBadge" class="tf-notification-badge">0</span>
      </div>
      <div id="notificationDropdown" class="tf-notification-dropdown"></div>
    </div>

    <!-- User Profile Pill -->
    <div class="tf-user-pill" onclick="TF.openAuthModal()">
      <div id="tfHeaderUserAvatar" class="tf-user-avatar">TF</div>
      <div class="tf-user-info">
        <span id="tfHeaderUserName" class="tf-user-name">TalentFlow User</span>
        <span id="tfHeaderUserRole" class="tf-user-role">ACCOUNT</span>
      </div>
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
    </div>
  </div>
</header>
