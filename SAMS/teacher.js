// ✅ SAMS - TEACHER JAVASCRIPT
// Parehong itsura at galaw sa student

// Dark Mode - Naaalala kahit lumipat ng page o mag-refresh
const darkToggle = document.getElementById('darkmode');

// I-check kung naka-dark mode na dati
if(localStorage.getItem('darkMode') === 'enabled') {
    document.body.classList.add('dark-mode');
    if(darkToggle) darkToggle.checked = true;
}

// Toggle kapag pinindot
if(darkToggle) {
    darkToggle.addEventListener('change', function() {
        if(this.checked) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'enabled');
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'disabled');
        }
    });
}

// ✅ Confirm Logout
function confirmLogout() {
    if(confirm('⚠️ Are you sure you want to log out of your account?')) {
        window.location.href = 'logout.php';
    }
}

// ✅ Smooth Transition
document.body.style.transition = 'all 0.3s ease';

// ✅ Karagdagang Functions para sa Teacher (Grades, Students, Announcements)
function updateFinalGrade(input) {
    const row = input.closest('tr');
    const q1 = parseFloat(row.querySelector('input:nth-child(1)').value) || 0;
    const q2 = parseFloat(row.querySelector('input:nth-child(2)').value) || 0;
    const final = ((q1 + q2) / 2).toFixed(2);
    const remarks = final >= 75 ? 'Passed' : 'Failed';
    
    row.querySelector('.final-grade').textContent = final;
    row.querySelector('.remarks').textContent = remarks;
}