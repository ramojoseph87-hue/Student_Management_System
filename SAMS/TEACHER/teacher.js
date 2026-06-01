// ==============================================
// SAMS TEACHER SCRIPT
// Folder: /TEACHER/teacher.js
// By: Joseph - Sogod, Southern Leyte | STAC
// ==============================================

// ✅ DARK MODE FUNCTION
const toggle = document.getElementById('darkmode');

// Tandaan ang settings ng user
if(localStorage.getItem('darkMode') === 'true') { 
    document.body.classList.add('dark-mode'); 
    if(toggle) toggle.checked = true; 
} else {
    document.body.classList.remove('dark-mode');
    if(toggle) toggle.checked = false;
}

// Kapag pinindot ang switch
if(toggle) {
    toggle.addEventListener('change', function() { 
        if (this.checked) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'true');
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'false');
        }
    });
}

// ✅ LOGOUT CONFIRMATION
function confirmLogout() {
    if(confirm("⚠️ Are you sure you want to log out?")) {
        window.location.href = "../logout.php"; // tama ang daan pabalik
    }
}