<!-- View: Resume Processing & 100-Point Scoring Engine -->
<div id="view-candidates" class="tf-view">
  <div class="tf-greeting-bar">
    <div class="tf-greeting-text">
          <h2>Resume Review & Candidate Score</h2>
          <p>Upload a PDF to extract skills, experience, education, and a transparent score out of 100.</p>
    </div>
  </div>

  <div style="display:grid; grid-template-columns: 1fr 1.2fr; gap:24px;">
    <!-- Upload Box -->
    <div class="tf-card">
      <h3 style="font-size:16px; font-weight:700; margin-bottom:14px;">Upload Candidate Resume (PDF)</h3>
      
      <div id="resumeDropzone" style="border: 2px dashed var(--border-focus); border-radius:var(--radius-lg); background:var(--surface-subtle); padding:40px 24px; text-align:center; cursor:pointer; transition:all 0.2s ease;">
        <div style="width:50px; height:50px; margin:0 auto 16px; border-radius:50%; background:var(--primary-light); color:var(--primary-color); display:flex; align-items:center; justify-content:center;">
          <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
        </div>
        <h4 style="font-size:15px; font-weight:700; margin-bottom:6px;">Drop a candidate PDF here</h4>
        <p style="font-size:12px; color:var(--text-muted); margin-bottom:16px;">The system extracts details and scores the profile automatically.</p>
        <input type="file" id="pdfFileInput" accept="application/pdf" style="display:none;" onchange="TF.simulateResumeUpload(event)">
        <button class="tf-btn-outline" onclick="document.getElementById('pdfFileInput').click()">Browse Files</button>
      </div>

      <!-- Extraction Progress State -->
      <div id="extractionProgress" style="display:none; margin-top:20px; padding:16px; border-radius:var(--radius-md); background:var(--primary-light); border:1px solid rgba(147,51,234,0.15);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
          <span id="extractionStepText" style="font-size:13px; font-weight:700; color:var(--primary-color);">Simulating Queue: Extracting Text...</span>
          <span id="extractionStepVal" style="font-size:12px; font-weight:800; color:var(--primary-color);">45%</span>
        </div>
        <div class="tf-progress-track" style="height:6px; background:#E9D5FF;">
          <div id="extractionProgressBar" class="tf-progress-fill" style="width:45%;"></div>
        </div>
      </div>
    </div>

    <!-- Score explanation -->
    <div class="tf-card">
      <div class="tf-card-header">
        <div>
          <h3>How the 100-point score is calculated</h3>
          <p class="tf-card-subtitle">A processed resume will show its candidate and role here.</p>
        </div>
        <div style="text-align:right;">
          <span style="font-size:28px; font-weight:800; color:var(--primary-color);">100</span>
          <span style="font-size:14px; font-weight:700; color:var(--text-muted);">/ 100</span>
        </div>
      </div>

      <div style="display:flex; flex-direction:column; gap:14px;">
        <!-- Required Skills: 40 pts -->
        <div style="border:1px solid var(--border-subtle); padding:14px 18px; border-radius:var(--radius-md); background:var(--surface-subtle);">
          <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
            <strong style="font-size:13px;">1. Required Skills Match (Max 40 Pts)</strong>
            <span style="font-weight:800; color:var(--primary-color);">40.0 / 40.0</span>
          </div>
          <p style="font-size:12px; color:var(--text-muted); margin-bottom:8px;">Required skills contribute up to 40 points.</p>
          <div class="tf-progress-track">
            <div class="tf-progress-fill" style="width:100%;"></div>
          </div>
        </div>

        <!-- Optional Skills: 10 pts -->
        <div style="border:1px solid var(--border-subtle); padding:14px 18px; border-radius:var(--radius-md); background:var(--surface-subtle);">
          <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
            <strong style="font-size:13px;">2. Optional / Bonus Skills (Max 10 Pts)</strong>
            <span style="font-weight:800; color:var(--primary-color);">9.5 / 10.0</span>
          </div>
          <p style="font-size:12px; color:var(--text-muted); margin-bottom:8px;">Additional skills contribute up to 10 points.</p>
          <div class="tf-progress-track">
            <div class="tf-progress-fill" style="width:95%;"></div>
          </div>
        </div>

        <!-- Experience: 25 pts -->
        <div style="border:1px solid var(--border-subtle); padding:14px 18px; border-radius:var(--radius-md); background:var(--surface-subtle);">
          <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
            <strong style="font-size:13px;">3. Experience Match (Max 25 Pts)</strong>
            <span style="font-weight:800; color:var(--primary-color);">25.0 / 25.0</span>
          </div>
          <p style="font-size:12px; color:var(--text-muted); margin-bottom:8px;">Relevant experience contributes up to 25 points.</p>
          <div class="tf-progress-track">
            <div class="tf-progress-fill green" style="width:100%;"></div>
          </div>
        </div>

        <!-- Education: 15 pts -->
        <div style="border:1px solid var(--border-subtle); padding:14px 18px; border-radius:var(--radius-md); background:var(--surface-subtle);">
          <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
            <strong style="font-size:13px;">4. Education Level (Max 15 Pts)</strong>
            <span style="font-weight:800; color:var(--primary-color);">13.0 / 15.0</span>
          </div>
          <p style="font-size:12px; color:var(--text-muted); margin-bottom:8px;">Education level contributes up to 15 points.</p>
          <div class="tf-progress-track">
            <div class="tf-progress-fill" style="width:86%;"></div>
          </div>
        </div>

        <!-- Bonus Profile: 10 pts -->
        <div style="border:1px solid var(--border-subtle); padding:14px 18px; border-radius:var(--radius-md); background:var(--surface-subtle);">
          <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
            <strong style="font-size:13px;">5. Bonus / Verified Contacts (Max 10 Pts)</strong>
            <span style="font-weight:800; color:var(--primary-color);">10.0 / 10.0</span>
          </div>
          <p style="font-size:12px; color:var(--text-muted); margin-bottom:8px;">Verified contact and profile details contribute up to 10 points.</p>
          <div class="tf-progress-track">
            <div class="tf-progress-fill green" style="width:100%;"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
