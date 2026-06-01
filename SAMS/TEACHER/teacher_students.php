<?php
session_start();

// ✅ DETALYE NG GURO
if (!isset($_SESSION['teacher_id'])) {
    $_SESSION['teacher_id'] = 'TCH-001';
    $_SESSION['teacher_name'] = 'Mr. Joseph';
    $_SESSION['department'] = "Computer Studies Department";
}

// ✅ LISTAHAN NG MGA ESTUDYANTE - TAMA NA ANG KURSO NIYO DITO
$student_list = [
    [
        'id' => 'STU-2023-0045',
        'name' => 'Dela Cruz, Juan A.',
        'course' => 'BS Information System',
        'year' => '1st Year',
        'section' => '1-A',
        'status' => 'Enrolled',
        'contact' => '0912 345 6789',
        'email' => 'juan.delacruz@stac.edu.ph'
    ],
    [
        'id' => 'STU-2023-0122',
        'name' => 'Santos, Maria B.',
        'course' => 'BEEd - Bachelor of Elementary Education',
        'year' => '1st Year',
        'section' => '1-C',
        'status' => 'Enrolled',
        'contact' => '0998 765 4321',
        'email' => 'maria.santos@stac.edu.ph'
    ],
    [
        'id' => 'STU-2022-0078',
        'name' => 'Reyes, Jose C.',
        'course' => 'BSCrim - BS Criminology',
        'year' => '2nd Year',
        'section' => '2-B',
        'status' => 'Enrolled',
        'contact' => '0917 123 4567',
        'email' => 'jose.reyes@stac.edu.ph'
    ],
    [
        'id' => 'STU-2023-0091',
        'name' => 'Bautista, Ana D.',
        'course' => 'BSOA - BS Office Administration',
        'year' => '1st Year',
        'section' => '1-D',
        'status' => 'Enrolled',
        'contact' => '0915 987 6543',
        'email' => 'ana.bautista@stac.edu.ph'
    ],
    [
        'id' => 'STU-2022-0104',
        'name' => 'Torres, Mark E.',
        'course' => 'BSBA - BS Business Administration',
        'year' => '2nd Year',
        'section' => '2-A',
        'status' => 'Dropped',
        'contact' => '0920 456 7890',
        'email' => 'mark.torres@stac.edu.ph'
    ],
    [
        'id' => 'STU-2023-0156',
        'name' => 'Garcia, Liza F.',
        'course' => 'BS Information System',
        'year' => '1st Year',
        'section' => '1-A',
        'status' => 'Enrolled',
        'contact' => '0918 234 5678',
        'email' => 'liza.garcia@stac.edu.ph'
    ],
    [
        'id' => 'STU-2022-0201',
        'name' => 'Villanueva, Carlo G.',
        'course' => 'BSBA - BS Business Administration',
        'year' => '2nd Year',
        'section' => '2-B',
        'status' => 'Enrolled',
        'contact' => '0919 876 5432',
        'email' => 'carlo.villanueva@stac.edu.ph'
    ],
    [
        'id' => 'STU-2023-0215',
        'name' => 'Castro, Sarah H.',
        'course' => 'BSCrim - BS Criminology',
        'year' => '1st Year',
        'section' => '1-C',
        'status' => 'Enrolled',
        'contact' => '0921 123 9876',
        'email' => 'sarah.castro@stac.edu.ph'
    ],
    [
        'id' => 'STU-2022-0302',
        'name' => 'Mendoza, Leo I.',
        'course' => 'BEEd - Bachelor of Elementary Education',
        'year' => '2nd Year',
        'section' => '2-A',
        'status' => 'Enrolled',
        'contact' => '0922 456 7890',
        'email' => 'leo.mendoza@stac.edu.ph'
    ],
    [
        'id' => 'STU-2023-0410',
        'name' => 'Romero, Kate J.',
        'course' => 'BSOA - BS Office Administration',
        'year' => '1st Year',
        'section' => '1-D',
        'status' => 'Dropped',
        'contact' => '0923 789 0123',
        'email' => 'kate.romero@stac.edu.ph'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List | SAMS - STAC</title>

    <!-- ✅ SAME STYLE -->
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">

    <style>
        /* ✅ DAGDAG NA DESIGN */
        .table-container {
            overflow-x: auto;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: rgba(37, 99, 235, 0.08);
            color: var(--primary);
            padding: 14px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        td {
            padding: 14px 12px;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
            color: var(--text-color);
        }
        tr:hover {
            background-color: rgba(37, 99, 235, 0.04);
        }
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-enrolled { background: var(--success); color: white; }
        .status-dropped { background: var(--danger); color: white; }
        .action-buttons {
            display: flex;
            gap: 6px;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.75rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            border: none;
            cursor: pointer;
        }
        .filter-area {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 15px 20px;
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .search-box {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background: var(--bg-color);
            color: var(--text-color);
            min-width: 250px;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: var(--gray);
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="app-container">

        <!-- ✅ SIDEBAR (AKTIBO NGAYON ANG STUDENTS) -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="../Untitled.png" alt="STAC Logo">
                <h2>SAMS | TEACHER</h2>
                <p>Faculty Portal</p>
                <hr>
                <p class="teacher-info">👨‍🏫 <?= $_SESSION['teacher_name'] ?></p>
                <p style="font-size: 11px; opacity: 0.7; margin-top: 4px;"><?= $_SESSION['department'] ?></p>
            </div>

            <ul class="nav-links">
                <li><a href="teacher_dashboard.php">🏠 Dashboard</a></li>
                <li><a href="teacher_classes.php">🏫 My Classes</a></li>
                <li><a href="teacher_students.php" class="active">👨‍🎓 Students</a></li>
                <li><a href="teacher_grades.php">📝 Grades Management</a></li>
                <li><a href="teacher_attendance.php">📋 Attendance</a></li>
                <li><a href="teacher_announcements.php">📢 Announcements</a></li>
                <li><a href="teacher_settings.php">⚙️ Settings</a></li>
            </ul>

            <div class="logout-btn">
                <a href="#" onclick="confirmLogout(); return false;">🚪 Logout</a>
            </div>
        </div>

        <!-- ✅ MAIN CONTENT -->
        <div class="main-content">

            <!-- ✅ DARK MODE SWITCH -->
            <div class="mode-switch">
                <span>☀️</span>
                <label class="switch">
                    <input type="checkbox" id="darkmode">
                    <span class="slider"></span>
                </label>
                <span>🌙</span>
            </div>

            <!-- ✅ PAGE HEADER -->
            <div class="welcome-card">
                <h1>👨‍🎓 Student Master List</h1>
                <div class="info-row">
                    <span>Complete list of students under your advisory and subjects</span>
                    <span class="badge">Total: <span id="totalCount"><?= count($student_list) ?></span> Students</span>
                </div>
            </div>

            <!-- ✅ FILTER & SEARCH - GUMAGANA NA! -->
            <div class="filter-area">
                <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                    <div>
                        <label style="font-size:0.85rem; margin-right:5px;">Filter by Course:</label>
                        <select id="filterCourse" style="padding:6px 10px; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-color); color:var(--text-color);">
                            <option value="">All Courses</option>
                            <option value="BS Information System">BS Information System</option>
                            <option value="BEEd">BEEd</option>
                            <option value="BSCrim">BSCrim</option>
                            <option value="BSOA">BSOA</option>
                            <option value="BSBA">BSBA</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:0.85rem; margin-right:5px;">Filter by Section:</label>
                        <select id="filterSection" style="padding:6px 10px; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-color); color:var(--text-color);">
                            <option value="">All Sections</option>
                            <option value="1-A">1-A</option>
                            <option value="1-C">1-C</option>
                            <option value="1-D">1-D</option>
                            <option value="2-A">2-A</option>
                            <option value="2-B">2-B</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:0.85rem; margin-right:5px;">Filter by Status:</label>
                        <select id="filterStatus" style="padding:6px 10px; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-color); color:var(--text-color);">
                            <option value="">All Status</option>
                            <option value="Enrolled">Enrolled</option>
                            <option value="Dropped">Dropped</option>
                        </select>
                    </div>
                </div>
                <input type="text" id="searchInput" class="search-box" placeholder="🔍 Search by Name or ID...">
            </div>

            <!-- ✅ STUDENTS TABLE -->
            <div class="table-container">
                <table id="studentTable">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Course / Year</th>
                            <th>Section</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($student_list as $stud): ?>
                        <tr class="student-row" 
                            data-course="<?= $stud['course'] ?>" 
                            data-section="<?= $stud['section'] ?>" 
                            data-status="<?= $stud['status'] ?>"
                            data-name="<?= strtolower($stud['name']) ?>"
                            data-id="<?= strtolower($stud['id']) ?>">
                            <td><strong><?= $stud['id'] ?></strong></td>
                            <td>
                                <div><?= $stud['name'] ?></div>
                                <div style="font-size: 0.75rem; opacity: 0.7;"><?= $stud['email'] ?></div>
                            </td>
                            <td>
                                <div><?= $stud['course'] ?></div>
                                <div style="font-size: 0.75rem; opacity: 0.7;"><?= $stud['year'] ?></div>
                            </td>
                            <td><?= $stud['section'] ?></td>
                            <td>
                                <span class="status-badge status-<?= strtolower($stud['status']) ?>"><?= $stud['status'] ?></span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="teacher_view_student.php?id=<?= $stud['id'] ?>" class="btn-sm action-btn btn-primary">View</a>
                                    <a href="teacher_grades.php?stud=<?= $stud['id'] ?>" class="btn-sm action-btn">Grades</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr id="noDataRow" class="no-data" style="display: none;">
                            <td colspan="6">No students found matching your filter or search.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- ✅ SAME SCRIPT -->
    <script src="teacher.js"></script>

    <!-- ✅ SCRIPT PARA GUMANA ANG SEARCH AT FILTER -->
    <script>
        const searchInput = document.getElementById('searchInput');
        const filterCourse = document.getElementById('filterCourse');
        const filterSection = document.getElementById('filterSection');
        const filterStatus = document.getElementById('filterStatus');
        const rows = document.querySelectorAll('.student-row');
        const totalCount = document.getElementById('totalCount');
        const noDataRow = document.getElementById('noDataRow');

        // Function para i-filter ang listahan
        function filterStudents() {
            let searchVal = searchInput.value.toLowerCase();
            let courseVal = filterCourse.value.toLowerCase();
            let sectionVal = filterSection.value.toLowerCase();
            let statusVal = filterStatus.value.toLowerCase();
            let visibleCount = 0;

            rows.forEach(row => {
                let matchSearch = row.dataset.name.includes(searchVal) || row.dataset.id.includes(searchVal);
                let matchCourse = courseVal === "" || row.dataset.course.toLowerCase().includes(courseVal);
                let matchSection = sectionVal === "" || row.dataset.section.toLowerCase() === sectionVal;
                let matchStatus = statusVal === "" || row.dataset.status.toLowerCase() === statusVal;

                if (matchSearch && matchCourse && matchSection && matchStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Palitan ang bilang at ipakita kung walang nahanap
            totalCount.innerText = visibleCount;
            noDataRow.style.display = (visibleCount === 0) ? '' : 'none';
        }

        // Ikabit ang mga pangyayari
        searchInput.addEventListener('keyup', filterStudents);
        filterCourse.addEventListener('change', filterStudents);
        filterSection.addEventListener('change', filterStudents);
        filterStatus.addEventListener('change', filterStudents);
    </script>
</body>
</html>