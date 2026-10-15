
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ระบบเช็คอิน-เช็คเอาต์พนักงาน</title>

    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Kanit', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        aside {
            width: 245px;
            background: #14213d;
            color: white;
            padding: 25px 16px;
            flex-shrink: 0;
        }

        .brand {
            font-size: 21px;
            font-weight: 600;
            padding: 8px 12px 25px;
            border-bottom: 1px solid #ffffff25;
        }

        .menu-title {
            color: #94a3b8;
            font-size: 12px;
            margin: 25px 12px 10px;
        }

        .menu {
            display: block;
            width: 100%;
            padding: 13px 12px;
            margin: 5px 0;
            border: 0;
            border-radius: 9px;
            background: transparent;
            color: #e2e8f0;
            text-align: left;
            font: inherit;
            cursor: pointer;
        }

        .menu:hover { background: #ffffff15; }

        .menu.active {
            background: #2563eb;
            color: white;
        }

        main {
            flex: 1;
            min-width: 0;
            padding: 30px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        h1 { font-size: 26px; margin: 0; }
        h2 { font-size: 20px; margin: 0; }
        .muted { color: #64748b; font-size: 13px; }

        .welcome {
            background: linear-gradient(120deg, #1d4ed8, #3b82f6);
            color: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 22px;
        }

        .welcome p { margin-bottom: 0; color: #dbeafe; }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 22px;
        }

        .card {
            background: white;
            padding: 22px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px #0f172a08;
            margin-bottom: 20px;
        }

        .stat-number {
            font-size: 30px;
            font-weight: 600;
            margin-top: 12px;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            background: #dcfce7;
            color: #15803d;
            font-size: 12px;
            white-space: nowrap;
        }

        .status.working {
            background: #fef3c7;
            color: #92400e;
        }

        .status.error {
            background: #fee2e2;
            color: #b91c1c;
        }

        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            min-width: 650px;
            border-collapse: collapse;
        }

        th, td {
            padding: 13px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
        }

        th {
            color: #64748b;
            background: #f8fafc;
            font-weight: 500;
        }

        .btn {
            border: 0;
            padding: 9px 13px;
            border-radius: 8px;
            cursor: pointer;
            font: inherit;
            font-size: 13px;
        }

        .btn-primary { background: #2563eb; color: white; }
        .btn-light { background: #e2e8f0; color: #334155; }
        .btn:disabled { opacity: .5; cursor: not-allowed; }

        .empty {
            text-align: center;
            padding: 25px;
            color: #94a3b8;
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 13px;
            display: none;
        }

        .login-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            margin-top: 15px;
        }

        input {
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font: inherit;
            min-width: 0;
        }

        [hidden] { display: none !important; }

        @media (max-width: 800px) {
            .layout { display: block; }
            aside { width: 100%; }
            main { padding: 18px 12px; }
            .stats { grid-template-columns: 1fr; gap: 10px; }
            header { align-items: flex-start; flex-direction: column; }
        }
    </style>
</head>

<body>
<div class="layout">

    <aside>
        <div class="brand">◷ WorkTime</div>

        <div class="menu-title">เมนูหลัก</div>

        <button class="menu active" data-page="dashboard">
            ▦ &nbsp; ภาพรวมระบบ
        </button>

        <button class="menu" data-page="attendance">
            ◷ &nbsp; เช็คอิน / เช็คเอาต์
        </button>

        <button class="menu" data-page="history">
            ▤ &nbsp; ประวัติการทำงาน
        </button>
    </aside>

    <main>
        <header>
            <div>
                <h1 id="pageTitle">ภาพรวมระบบ</h1>
                <div class="muted">ระบบบันทึกเวลาเข้า-ออกงานพนักงาน</div>
            </div>

            <div>
                <span id="apiBadge" class="status error">ยังไม่เชื่อมต่อ API</span>
            </div>
        </header>

        <div id="message" class="message"></div>

        <!-- หน้า Login -->
        <section class="card">
            <h2>เข้าสู่ระบบ</h2>
            <div class="muted">กรอกบัญชีผู้ใช้ที่มีอยู่ในระบบ</div>

            <form id="loginForm" class="login-form">
                <input id="email" type="email"
                       placeholder="อีเมล" required>

                <input id="password" type="password"
                       placeholder="รหัสผ่าน" required>

                <button class="btn btn-primary" type="submit">
                    Login
                </button>

                <button class="btn btn-light" type="button" id="logoutBtn">
                    Logout
                </button>
            </form>
        </section>

        <!-- หน้า 1: ภาพรวม -->
        <section id="dashboardPage" class="page">
            <div class="welcome">
                <h2>ยินดีต้อนรับสู่ระบบลงเวลาทำงาน</h2>
                <p>ตรวจสอบการเข้างานและออกงานของพนักงานได้ในที่เดียว</p>
            </div>

            <div class="stats">
                <div class="card">
                    <div class="muted">รายการเช็คอินทั้งหมด</div>
                    <div class="stat-number" id="totalCount">0</div>
                </div>

                <div class="card">
                    <div class="muted">รายการที่กำลังทำงาน</div>
                    <div class="stat-number" id="workingCount">0</div>
                </div>

                <div class="card">
                    <div class="muted">สถานะ API</div>
                    <div class="stat-number" id="apiText"
                         style="font-size:20px">ยังไม่เชื่อมต่อ</div>
                </div>
            </div>

            <div class="card">
                <h2>รายการลงเวลาล่าสุด</h2>
                <p class="muted">แสดงข้อมูลจากระบบ</p>
                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>พนักงาน</th>
                            <th>วันที่</th>
                            <th>เวลาเข้า</th>
                            <th>เวลาออก</th>
                            <th>สถานะ</th>
                        </tr>
                        </thead>
                        <tbody id="dashboardRows">
                        <tr><td colspan="5" class="empty">กรุณา Login ก่อน</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- หน้า 2: เช็คอิน / เช็คเอาต์ -->
        <section id="attendancePage" class="page" hidden>
            <div class="card">
                <h2>รายชื่อพนักงานเช็คอิน / เช็คเอาต์</h2>
                <p class="muted">รายการเรียงตามวันที่และเวลาเข้างานล่าสุด</p>

                <button class="btn btn-light" id="refreshAttendance">
                    รีเฟรชข้อมูล
                </button>

                <div class="table-wrap" style="margin-top:15px">
                    <table>
                        <thead>
                        <tr>
                            <th>พนักงาน</th>
                            <th>วันที่</th>
                            <th>เวลาเข้า</th>
                            <th>เวลาออก</th>
                            <th>สถานะ</th>
                            <th>ดำเนินการ</th>
                        </tr>
                        </thead>
                        <tbody id="attendanceRows">
                        <tr><td colspan="6" class="empty">กรุณา Login ก่อน</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- หน้า 3: ประวัติการทำงาน -->
        <section id="historyPage" class="page" hidden>
            <div class="card">
                <h2>ประวัติการทำงาน</h2>
                <p class="muted">ตรวจสอบประวัติการลงเวลาและสถานะของพนักงาน</p>

                <button class="btn btn-light" id="refreshHistory">
                    รีเฟรชประวัติ
                </button>

                <div class="table-wrap" style="margin-top:15px">
                    <table>
                        <thead>
                        <tr>
                            <th>พนักงาน</th>
                            <th>วันที่</th>
                            <th>เวลาเข้า</th>
                            <th>เวลาออก</th>
                            <th>สถานะการทำงาน</th>
                            <th>หมายเหตุ</th>
                        </tr>
                        </thead>
                        <tbody id="historyRows">
                        <tr><td colspan="6" class="empty">กรุณา Login ก่อน</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>
</div>

<script>
    const API = '/api';
    let token = '';
    let attendanceData = [];

    const $ = id => document.getElementById(id);

    function showMessage(text, success = false) {
        const box = $('message');
        box.textContent = text;
        box.style.display = 'block';
        box.style.background = success ? '#dcfce7' : '#fee2e2';
        box.style.color = success ? '#166534' : '#991b1b';
    }

    function escapeHtml(value) {
        return String(value ?? '-').replace(/[&<>"']/g, char => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        }[char]));
    }

    function formatTime(value) {
        return value ? String(value).slice(0, 8) : '-';
    }

    function getStatus(item) {
        return item.check_out
            ? '<span class="status">ออกงานแล้ว</span>'
            : '<span class="status working">กำลังทำงาน</span>';
    }

    function authHeaders() {
        return {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        };
    }

    async function apiRequest(url, options = {}) {
        const response = await fetch(url, {
            ...options,
            headers: {
                ...authHeaders(),
                ...(options.headers || {})
            }
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            const errors = data.errors
                ? Object.values(data.errors).flat().join(' ')
                : '';

            throw new Error(errors || data.message || 'เกิดข้อผิดพลาดจาก API');
        }

        return data;
    }

    function renderTables() {
        const rows = [...attendanceData].sort((a, b) =>
            String(b.work_date || '').localeCompare(String(a.work_date || '')) ||
            String(b.check_in || '').localeCompare(String(a.check_in || ''))
        );

        $('totalCount').textContent = rows.length;
        $('workingCount').textContent =
            rows.filter(item => !item.check_out).length;

        const tableRow = item => `
            <tr>
                <td>${escapeHtml(item.employee_name)}</td>
                <td>${escapeHtml(item.work_date)}</td>
                <td>${escapeHtml(formatTime(item.check_in))}</td>
                <td>${escapeHtml(formatTime(item.check_out))}</td>
                <td>${getStatus(item)}</td>
            </tr>`;

        $('dashboardRows').innerHTML = rows.length
            ? rows.slice(0, 5).map(tableRow).join('')
            : '<tr><td colspan="5" class="empty">ยังไม่มีรายการลงเวลา</td></tr>';

        $('attendanceRows').innerHTML = rows.length
            ? rows.map(item => `
                <tr>
                    <td>${escapeHtml(item.employee_name)}</td>
                    <td>${escapeHtml(item.work_date)}</td>
                    <td>${escapeHtml(formatTime(item.check_in))}</td>
                    <td>${escapeHtml(formatTime(item.check_out))}</td>
                    <td>${getStatus(item)}</td>
                    <td>
                        ${!item.check_out
                            ? `<button class="btn btn-primary checkoutBtn"
                                       data-id="${Number(item.id)}">
                                   เช็คเอาต์
                               </button>`
                            : '—'}
                    </td>
                </tr>
            `).join('')
            : '<tr><td colspan="6" class="empty">ยังไม่มีรายการลงเวลา</td></tr>';

        $('historyRows').innerHTML = rows.length
            ? rows.map(item => `
                <tr>
                    <td>${escapeHtml(item.employee_name)}</td>
                    <td>${escapeHtml(item.work_date)}</td>
                    <td>${escapeHtml(formatTime(item.check_in))}</td>
                    <td>${escapeHtml(formatTime(item.check_out))}</td>
                    <td>${getStatus(item)}</td>
                    <td>${escapeHtml(item.note || '-')}</td>
                </tr>
            `).join('')
            : '<tr><td colspan="6" class="empty">ยังไม่มีประวัติการทำงาน</td></tr>';

        document.querySelectorAll('.checkoutBtn').forEach(button => {
            button.addEventListener('click', () => checkout(button.dataset.id));
        });
    }

    async function loadAttendances() {
        if (!token) {
            showMessage('กรุณา Login ก่อนใช้งานระบบ');
            return;
        }

        try {
            const result = await apiRequest(`${API}/work-attendances`);
            attendanceData = Array.isArray(result)
                ? result
                : (result.data || []);

            $('apiBadge').textContent = 'เชื่อมต่อ API แล้ว';
            $('apiBadge').className = 'status';
            $('apiText').textContent = 'เชื่อมต่อแล้ว';
            $('apiText').style.color = '#15803d';

            renderTables();
        } catch (error) {
            $('apiBadge').textContent = 'API มีปัญหา';
            $('apiBadge').className = 'status error';
            $('apiText').textContent = 'เชื่อมต่อไม่สำเร็จ';
            $('apiText').style.color = '#b91c1c';
            showMessage(error.message);
        }
    }

    // Login: POST /api/login
    $('loginForm').addEventListener('submit', async event => {
        event.preventDefault();

        try {
            const response = await fetch(`${API}/login`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: $('email').value.trim(),
                    password: $('password').value
                })
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok || !data.token) {
                throw new Error(data.message || 'เข้าสู่ระบบไม่สำเร็จ');
            }

            token = data.token;
            showMessage('เข้าสู่ระบบสำเร็จ', true);
            await loadAttendances();
        } catch (error) {
            showMessage(error.message);
        }
    });

    // Logout: POST /api/logout
    $('logoutBtn').addEventListener('click', async () => {
        if (!token) {
            showMessage('กรุณา Login ก่อนออกจากระบบ');
            return;
        }

        try {
            await apiRequest(`${API}/logout`, { method: 'POST' });
            token = '';
            attendanceData = [];
            renderTables();

            $('apiBadge').textContent = 'ออกจากระบบแล้ว';
            $('apiBadge').className = 'status error';
            $('apiText').textContent = 'ออกจากระบบแล้ว';
            $('password').value = '';

            showMessage('ออกจากระบบสำเร็จ', true);
        } catch (error) {
            showMessage(error.message);
        }
    });

    // Checkout: POST /api/work-attendances/{id}/checkout
    async function checkout(id) {
        if (!confirm('ยืนยันบันทึกเวลาออกงานหรือไม่?')) return;

        try {
            await apiRequest(`${API}/work-attendances/${id}/checkout`, {
                method: 'POST'
            });

            showMessage('บันทึกเวลาออกงานสำเร็จ', true);
            await loadAttendances();
        } catch (error) {
            showMessage(error.message);
        }
    }

    // Switch between the 3 pages
    const pageNames = {
        dashboard: ['dashboardPage', 'ภาพรวมระบบ'],
        attendance: ['attendancePage', 'เช็คอิน / เช็คเอาต์'],
        history: ['historyPage', 'ประวัติการทำงาน']
    };

    document.querySelectorAll('[data-page]').forEach(button => {
        button.addEventListener('click', () => {
            const page = button.dataset.page;

            document.querySelectorAll('.page').forEach(section => {
                section.hidden = true;
            });

            $(pageNames[page][0]).hidden = false;
            $('pageTitle').textContent = pageNames[page][1];

            document.querySelectorAll('[data-page]').forEach(menu => {
                menu.classList.toggle('active', menu === button);
            });

            if (token) loadAttendances();
        });
    });

    $('refreshAttendance').addEventListener('click', loadAttendances);
    $('refreshHistory').addEventListener('click', loadAttendances);
</script>
</body>
</html>
