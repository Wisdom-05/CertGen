<?php
require_once 'includes/auth.php';
require_login();

$page_title = "Welcome - OCNHS Digital Catalog";
require_once 'includes/header.php';
?>

<div class="welcome-container fade-in">
    <!-- Functional Navigation -->
    <nav class="top-nav-selection no-print">

        <a href="history.php" class="history-btn-top">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            Issuance History
        </a>
    </nav>

    <!-- Branding Hero -->
    <header class="hero-selection">
        <div class="hero-logo-wrapper">
            <img src="assets/img/OCNHS LOGO.png" class="hero-logo-main" alt="OCNHS Seal">
        </div>
        <div class="hero-text">
            <h1>OCNHS Master Certification System</h1>
            <p>Select a certificate catalog below to generate official documents.</p>
        </div>
    </header>

    <!-- Template Selection Grid -->
    <main class="selection-grid">
        <div class="catalog-grid">
            
            <a href="request_form.php?type=CERTIFICATE OF ENROLLMENT" class="template-card">
                <div class="card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><line x1="10" y1="9" x2="8" y2="9"></line></svg>
                </div>
                <div class="card-info">
                    <h3>Enrollment</h3>
                    <p>Current student status verification.</p>
                </div>
            </a>

            <div class="template-card interactive-group">
                <div class="card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <div class="card-info">
                    <h3>Good Moral Character</h3>
                    <div class="sub-options">
                        <a href="request_form.php?type=GOOD MORAL CHARACTER">Standard GMC</a>
                        <a href="request_form.php?type=GOOD MORAL CHARACTER (COLLEGE/SHS ADMISSION)">College/SHS Admission</a>
                        <a href="request_form.php?type=GOOD MORAL CHARACTER (SCHOOL TRANSFER)">School Transfer</a>
                    </div>
                </div>
            </div>

            <a href="request_form.php?type=CERTIFICATE OF GRADUATION" class="template-card">
                <div class="card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                </div>
                <div class="card-info">
                    <h3>Graduation</h3>
                    <p>Academic completion & diploma proof.</p>
                </div>
            </a>

            <a href="request_form.php?type=CERTIFICATE OF COMPLETION" class="template-card">
                <div class="card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <div class="card-info">
                    <h3>Completion</h3>
                    <p>JHS academic completion proof.</p>
                </div>
            </a>

            <a href="request_form.php?type=CERTIFICATE OF RANKING" class="template-card">
                <div class="card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                </div>
                <div class="card-info">
                    <h3>Ranking</h3>
                    <p>Official academic rank certifying.</p>
                </div>
            </a>

            <a href="request_form.php?type=TRANSFER CERTIFICATION" class="template-card">
                <div class="card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"></path><path d="M22 2l-7 20-4-9-9-4 20-7z"></path></svg>
                </div>
                <div class="card-info">
                    <h3>Transfer</h3>
                    <p>School transfer requirement documents.</p>
                </div>
            </a>

            <a href="request_form.php?type=SCHOLARSHIP RECOMMENDATION" class="template-card">
                <div class="card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21v-4a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v4"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <div class="card-info">
                    <h3>Scholarship</h3>
                    <p>Financial aid programs recommendation.</p>
                </div>
            </a>

            <a href="request_form.php?type=LOST ID CERTIFICATION" class="template-card">
                <div class="card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"></rect><circle cx="9" cy="10" r="2"></circle><line x1="15" y1="8" x2="17" y2="8"></line><line x1="15" y1="12" x2="17" y2="12"></line><line x1="7" y1="16" x2="17" y2="16"></line></svg>
                </div>
                <div class="card-info">
                    <h3>Lost ID</h3>
                    <p>Identity verification & replacement proof.</p>
                </div>
            </a>

            <a href="request_form.php?type=CERTIFICATE OF NON-ISSUANCE OF YEARBOOK" class="template-card">
                <div class="card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                </div>
                <div class="card-info">
                    <h3>No Yearbook</h3>
                    <p>Verifies yearbook unavailability status.</p>
                </div>
            </a>

            <a href="request_form.php?type=RECORD DAMAGE CERTIFICATION" class="template-card">
                <div class="card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="2" y1="11" x2="22" y2="11"></line><path d="M12 11l-3 3 3 3 3-3z"></path></svg>
                </div>
                <div class="card-info">
                    <h3>Damaged Record</h3>
                    <p>Certification for record replacements.</p>
                </div>
            </a>

            <a href="request_form.php?type=SCHOOL ACCREDITATION CERTIFICATE" class="template-card">
                <div class="card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                </div>
                <div class="card-info">
                    <h3>Accreditation</h3>
                    <p>Official DepEd school status proof.</p>
                </div>
            </a>

        </div>
    </main>
</div>

<style>
    /* Welcome Specific Styles - Integration with Global Tokens */
    .welcome-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
    
    .top-nav-selection { display: flex; justify-content: space-between; margin-bottom: 50px; }
    .registry-btn, .history-btn-top { 
        display: flex; align-items: center; gap: 10px; 
        padding: 12px 25px; border-radius: 40px; 
        text-decoration: none; font-weight: 800; font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .registry-btn { background: var(--primary-color); color: white; }
    .history-btn-top { background: white; color: var(--primary-color); border: 2px solid var(--primary-color); }
    .registry-btn:hover, .history-btn-top:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

    .hero-selection { text-align: center; margin-bottom: 60px; }
    .hero-logo-main { width: 150px; filter: drop-shadow(0 0 30px rgba(0,45,114,0.2)); }
    .hero-text h1 { font-size: 3.5rem; color: #fff; margin: 20px 0 10px; font-weight: 900; letter-spacing: -2px; text-shadow: 0 4px 20px rgba(0,0,0,0.3); }
    .hero-text p { font-size: 1.2rem; color: rgba(255,255,255,0.85); font-weight: 500; }

    .catalog-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px; }
    .template-card { 
        background: white; padding: 30px; border-radius: 20px; 
        text-decoration: none; color: inherit; 
        display: flex; flex-direction: column; 
        gap: 15px;
        transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        height: 100%;
        box-sizing: border-box;
    }
    .template-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); border-color: var(--primary-color); }
    .card-icon { 
        background: #f8f9fa; 
        width: 60px; height: 60px; 
        display: flex; align-items: center; justify-content: center; 
        border-radius: 15px;
        color: var(--primary-color);
        flex-shrink: 0;
    }
    .card-info { flex-grow: 1; display: flex; flex-direction: column; }
    .card-info h3 { margin: 0 0 8px 0; font-size: 1.25rem; color: var(--primary-color); border: none; padding: 0; background: none; font-weight: 800; }
    .card-info p { margin: 0; font-size: 0.95rem; color: #777; line-height: 1.5; }
    
    /* Interactive Group Refinement */
    .interactive-group { cursor: pointer; position: relative; }
    .interactive-group::after {
        content: '⌄'; position: absolute; top: 30px; right: 30px;
        font-size: 1.5rem; color: var(--primary-color); opacity: 0.5;
        transition: transform 0.3s;
    }
    .interactive-group.active::after { transform: rotate(180deg); }
    .sub-options { 
        margin-top: 20px; display: none; flex-direction: column; gap: 10px; width: 100%; 
        border-top: 1px solid #eee; padding-top: 15px;
        animation: slideDown 0.3s ease-out;
    }
    .interactive-group.active .sub-options { display: flex; }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .sub-options a { padding: 12px 15px; background: #f8f9fa; border-radius: 12px; font-size: 0.9rem; text-decoration: none; color: var(--primary-color); font-weight: 700; transition: all 0.2s; border: 1px solid transparent; }
    .sub-options a:hover { background: var(--primary-color); color: white; border-color: var(--primary-color); }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const interactiveCards = document.querySelectorAll('.interactive-group');
        
        interactiveCards.forEach(card => {
            card.addEventListener('click', function(e) {
                // If the user clicks a sub-option link, don't toggle the card
                if (e.target.closest('.sub-options a')) return;
                
                this.classList.toggle('active');
            });
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>
