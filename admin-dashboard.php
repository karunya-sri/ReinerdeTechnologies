<?php

include 'db.php';

$result = $conn->query("SELECT * FROM contacts ORDER BY id DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard — Reinerde</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --navy:    #012b5e;
      --green:   #2d6a4f;
      --mint:    #52b788;
      --gold:    #FFD700;
      --light:   #f0f7f4;
      --sidebar: #071a10;
      --border:  #dde8f0;
      --text:    #1a2e3d;
      --sub:     #6b8a9e;
      --unread-bg: #fff8e1;
      --unread-border: #ffe082;
      --danger:  #e74c3c;
      --success: #27ae60;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: #eef4f8;
      color: var(--text);
      min-height: 100vh;
      display: flex;
    }

    /* ══ SIDEBAR ═══════════════════════════════════════════════ */
    .sidebar {
      width: 250px;
      flex-shrink: 0;
      background: var(--sidebar);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0; left: 0;
      z-index: 100;
      transition: transform 0.3s;
    }
    .sidebar-brand {
      padding: 28px 24px 20px;
      border-bottom: 1px solid rgba(255,255,255,0.07);
    }
    .brand-row {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .brand-icon {
      width: 40px; height: 40px;
      background: linear-gradient(135deg, var(--navy), var(--green));
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--gold);
      font-size: 20px;
    }
    .brand-text strong {
      display: block;
      font-size: 16px;
      font-weight: 700;
      color: #fff;
    }
    .brand-text span {
      font-size: 10px;
      color: rgba(255,255,255,0.4);
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    .sidebar-nav {
      padding: 20px 12px;
      flex: 1;
    }
    .nav-label {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: rgba(255,255,255,0.25);
      padding: 0 12px;
      margin-bottom: 8px;
      margin-top: 16px;
    }
    .nav-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 11px 14px;
      border-radius: 10px;
      color: rgba(255,255,255,0.6);
      font-size: 13.5px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s;
      text-decoration: none;
      margin-bottom: 3px;
      position: relative;
    }
    .nav-item:hover { background: rgba(255,255,255,0.06); color: #fff; }
    .nav-item.active {
      background: linear-gradient(135deg, rgba(45,106,79,0.6), rgba(82,183,136,0.3));
      color: #fff;
    }
    .nav-item i { font-size: 18px; }
    .nav-badge {
      margin-left: auto;
      background: var(--gold);
      color: #333;
      font-size: 10px;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 20px;
      min-width: 20px;
      text-align: center;
      display: none;
    }
    .nav-badge.visible { display: block; }

    .sidebar-footer {
      padding: 16px 12px;
      border-top: 1px solid rgba(255,255,255,0.07);
    }
    .logout-btn {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 11px 14px;
      border-radius: 10px;
      color: rgba(255,100,100,0.8);
      font-size: 13.5px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s;
      width: 100%;
      background: none;
      border: none;
      font-family: 'Poppins', sans-serif;
    }
    .logout-btn:hover { background: rgba(231,76,60,0.1); color: #e74c3c; }
    .logout-btn i { font-size: 18px; }

    /* ══ MAIN ═══════════════════════════════════════════════════ */
    .main {
      margin-left: 250px;
      flex: 1;
      padding: 0;
      min-height: 100vh;
    }

    /* Topbar */
    .topbar {
      background: #fff;
      padding: 16px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 1px 0 var(--border);
      position: sticky;
      top: 0;
      z-index: 50;
    }
    .topbar-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .hamburger {
      display: none;
      background: none;
      border: none;
      cursor: pointer;
      color: var(--text);
      font-size: 22px;
    }
    .page-title {
      font-size: 17px;
      font-weight: 600;
      color: var(--text);
    }
    .topbar-right {
      display: flex;
      align-items: center;
      gap: 14px;
    }
    .notif-bell {
      position: relative;
      cursor: pointer;
      width: 38px; height: 38px;
      background: var(--light);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--navy);
      font-size: 20px;
      transition: background 0.2s;
    }
    .notif-bell:hover { background: #d4eee3; }
    .notif-dot {
      position: absolute;
      top: 6px; right: 6px;
      width: 9px; height: 9px;
      background: var(--gold);
      border-radius: 50%;
      border: 2px solid #fff;
      display: none;
    }
    .notif-dot.show { display: block; }
    .admin-chip {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 6px 12px;
      background: var(--light);
      border-radius: 20px;
      font-size: 13px;
      font-weight: 500;
      color: var(--navy);
    }
    .admin-chip i { font-size: 16px; color: var(--green); }

    /* ══ CONTENT ════════════════════════════════════════════════ */
    .content { padding: 28px; }

    /* Stat cards */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
      gap: 16px;
      margin-bottom: 28px;
    }
    .stat-card {
      background: #fff;
      border-radius: 16px;
      padding: 20px 22px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.05);
      border: 1px solid var(--border);
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .stat-icon {
      width: 48px; height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      flex-shrink: 0;
    }
    .stat-icon.blue   { background: #e8f0fe; color: #1a73e8; }
    .stat-icon.green  { background: #e6f4ea; color: var(--green); }
    .stat-icon.gold   { background: #fff8e1; color: #f9a825; }
    .stat-icon.red    { background: #fce8e6; color: #e74c3c; }
    .stat-val  { font-size: 24px; font-weight: 700; color: var(--text); line-height: 1; }
    .stat-label { font-size: 12px; color: var(--sub); margin-top: 3px; font-weight: 500; }

    /* Panel */
    .panel {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.05);
      border: 1px solid var(--border);
      overflow: hidden;
    }
    .panel-header {
      padding: 18px 24px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
    }
    .panel-header h2 {
      font-size: 16px;
      font-weight: 600;
      color: var(--text);
    }
    .panel-header p {
      font-size: 12px;
      color: var(--sub);
      margin-top: 2px;
    }

    /* Filter/search bar */
    .toolbar {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }
    .search-box {
      position: relative;
    }
    .search-box i {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      color: #aab8c4;
      font-size: 16px;
    }
    .search-box input {
      padding: 8px 12px 8px 32px;
      border: 1.5px solid var(--border);
      border-radius: 8px;
      font-family: 'Poppins', sans-serif;
      font-size: 13px;
      outline: none;
      width: 200px;
      transition: border-color 0.2s;
    }
    .search-box input:focus { border-color: var(--mint); }

    .filter-select {
      padding: 8px 12px;
      border: 1.5px solid var(--border);
      border-radius: 8px;
      font-family: 'Poppins', sans-serif;
      font-size: 13px;
      outline: none;
      background: #fff;
      color: var(--text);
      cursor: pointer;
      transition: border-color 0.2s;
    }
    .filter-select:focus { border-color: var(--mint); }

    .btn-mark-all {
      padding: 8px 14px;
      background: var(--light);
      border: 1.5px solid var(--border);
      border-radius: 8px;
      font-family: 'Poppins', sans-serif;
      font-size: 12px;
      font-weight: 600;
      color: var(--green);
      cursor: pointer;
      transition: all 0.2s;
    }
    .btn-mark-all:hover { background: #d0eedd; border-color: var(--mint); }

    /* ── Message table ── */
    .msg-table { width: 100%; border-collapse: collapse; }
    .msg-table th {
      padding: 12px 16px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: var(--sub);
      background: #f8fafc;
      border-bottom: 1px solid var(--border);
      text-align: left;
    }
    .msg-table td {
      padding: 14px 16px;
      border-bottom: 1px solid #f0f5f8;
      font-size: 13px;
      vertical-align: middle;
    }
    .msg-row { transition: background 0.15s; cursor: pointer; }
    .msg-row:hover td { background: #f7fbfd; }
    .msg-row.unread td { background: var(--unread-bg); }
    .msg-row.unread td:first-child {
      border-left: 3px solid var(--gold);
    }
    .msg-row:last-child td { border-bottom: none; }

    .sender-info { display: flex; flex-direction: column; gap: 2px; }
    .sender-name { font-weight: 600; color: var(--text); }
    .sender-email { font-size: 11px; color: var(--sub); }

    .source-badge {
      display: inline-block;
      padding: 3px 9px;
      border-radius: 20px;
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 0.3px;
    }
    .source-badge.main    { background: #e8f0fe; color: #1a73e8; }
    .source-badge.contact { background: #e6f4ea; color: var(--green); }

    .status-dot {
      width: 8px; height: 8px;
      border-radius: 50%;
      display: inline-block;
      margin-right: 6px;
    }
    .status-dot.unread { background: var(--gold); }
    .status-dot.read   { background: #c0cdd8; }

    .date-cell { color: var(--sub); font-size: 12px; white-space: nowrap; }

    .msg-preview {
      max-width: 200px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      color: var(--sub);
    }

    /* Action buttons */
    .actions { display: flex; align-items: center; gap: 6px; }
    .action-btn {
      width: 30px; height: 30px;
      border-radius: 7px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 15px;
      border: none;
      cursor: pointer;
      transition: all 0.2s;
    }
    .action-btn.read-toggle { background: #e6f4ea; color: var(--green); }
    .action-btn.read-toggle:hover { background: #c8e6c9; }
    .action-btn.delete { background: #fce8e6; color: var(--danger); }
    .action-btn.delete:hover { background: #f5c6c3; }

    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 60px 24px;
      color: var(--sub);
    }
    .empty-state i { font-size: 48px; opacity: 0.3; display: block; margin-bottom: 12px; }
    .empty-state p { font-size: 14px; }

    /* ── Message Detail Modal ── */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(7,26,16,0.6);
      z-index: 200;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .modal-overlay.open { display: flex; }
    .modal {
      background: #fff;
      border-radius: 20px;
      width: 100%;
      max-width: 520px;
      box-shadow: 0 30px 80px rgba(1,43,94,0.2);
      animation: modalIn 0.3s cubic-bezier(0.34,1.56,0.64,1) both;
      max-height: 90vh;
      overflow-y: auto;
    }
    @keyframes modalIn {
      from { opacity: 0; transform: scale(0.92) translateY(20px); }
      to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-header {
      padding: 20px 24px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .modal-header h3 { font-size: 16px; font-weight: 600; }
    .modal-close {
      background: none; border: none; cursor: pointer;
      color: var(--sub); font-size: 22px; transition: color 0.2s;
    }
    .modal-close:hover { color: var(--text); }
    .modal-body { padding: 24px; }
    .detail-row { margin-bottom: 18px; }
    .detail-label {
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--sub);
      margin-bottom: 5px;
    }
    .detail-value {
      font-size: 14px;
      color: var(--text);
      line-height: 1.6;
      word-break: break-word;
    }
    .detail-message {
      background: #f7fbfd;
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 14px;
      font-size: 14px;
      color: var(--text);
      line-height: 1.7;
      white-space: pre-wrap;
    }
    .modal-footer {
      padding: 16px 24px;
      border-top: 1px solid var(--border);
      display: flex;
      gap: 10px;
      justify-content: flex-end;
    }
    .modal-btn {
      padding: 9px 18px;
      border-radius: 8px;
      font-family: 'Poppins', sans-serif;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      border: none;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 7px;
    }
    .modal-btn.delete { background: #fce8e6; color: var(--danger); }
    .modal-btn.delete:hover { background: #e74c3c; color: #fff; }
    .modal-btn.toggle { background: var(--light); color: var(--green); }
    .modal-btn.toggle:hover { background: #c8e6c9; }
    .modal-btn.close  { background: #f0f5f8; color: var(--sub); }
    .modal-btn.close:hover { background: #dde8f0; }

    /* Toast */
    .toast {
      position: fixed;
      bottom: 28px;
      right: 28px;
      background: var(--navy);
      color: #fff;
      padding: 12px 20px;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 8px;
      z-index: 999;
      transform: translateY(80px);
      opacity: 0;
      transition: all 0.35s cubic-bezier(0.34,1.56,0.64,1);
      box-shadow: 0 8px 24px rgba(1,43,94,0.25);
    }
    .toast.show { transform: translateY(0); opacity: 1; }
    .toast i { font-size: 16px; color: var(--gold); }

    /* Delete confirm */
    .confirm-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(7,26,16,0.5);
      z-index: 300;
      align-items: center;
      justify-content: center;
    }
    .confirm-overlay.open { display: flex; }
    .confirm-box {
      background: #fff;
      border-radius: 16px;
      padding: 28px;
      max-width: 340px;
      width: 90%;
      text-align: center;
      box-shadow: 0 20px 60px rgba(0,0,0,0.15);
      animation: modalIn 0.25s ease both;
    }
    .confirm-box i { font-size: 40px; color: var(--danger); margin-bottom: 12px; }
    .confirm-box h4 { font-size: 16px; font-weight: 600; margin-bottom: 8px; }
    .confirm-box p { font-size: 13px; color: var(--sub); margin-bottom: 20px; }
    .confirm-btns { display: flex; gap: 10px; justify-content: center; }
    .confirm-btns button {
      padding: 9px 22px;
      border-radius: 8px;
      font-family: 'Poppins', sans-serif;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      border: none;
      transition: all 0.2s;
    }
    .btn-cancel-del { background: #f0f5f8; color: var(--sub); }
    .btn-cancel-del:hover { background: #dde8f0; }
    .btn-confirm-del { background: var(--danger); color: #fff; }
    .btn-confirm-del:hover { background: #c0392b; }

    /* Responsive */
    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.open { transform: translateX(0); }
      .main { margin-left: 0; }
      .hamburger { display: flex; }
      .stats-row { grid-template-columns: 1fr 1fr; }
      .msg-table th:nth-child(4),
      .msg-table td:nth-child(4),
      .msg-table th:nth-child(5),
      .msg-table td:nth-child(5) { display: none; }
    }
    @media (max-width: 480px) {
      .content { padding: 16px; }
      .topbar  { padding: 14px 16px; }
      .stats-row { grid-template-columns: 1fr; }
      .toolbar { flex-direction: column; align-items: stretch; }
      .search-box input { width: 100%; }
    }
  </style>
</head>
<body>

  <!-- ══ SIDEBAR ══════════════════════════════════════════════ -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
      <div class="brand-row">
        <img src="K:\templatemo_486_new_event\templatemo_486_new_event\images\logo1.png" 
             alt="BioCulture logo" 
             class="logoname" 
             style="margin-left: -200px;height: 50px;">
        <div class="brand-text">
          <strong>Reinerde</strong>
          <span>Admin Panel</span>
        </div>
      </div>
    </div>

    <nav class="sidebar-nav">
      <div class="nav-label">Main</div>
      <a class="nav-item active" href="#">
        <i class='bx bx-message-square-dots'></i>
        Messages
        <span class="nav-badge" id="sidebarBadge">0</span>
      </a>
      <a class="nav-item" href="index.html" target="_blank">
        <i class='bx bx-globe'></i>
        View Website
      </a>
      <a class="nav-item" href="map.html" target="_blank">
        <i class='bx bx-envelope'></i>
        Contact Page
      </a>
    </nav>

    <div class="sidebar-footer">
      <button class="logout-btn" onclick="handleLogout()">
        <i class='bx bx-log-out'></i>
        Logout
      </button>
    </div>
  </aside>

  <!-- ══ MAIN ══════════════════════════════════════════════════ -->
  <div class="main">

    <!-- Topbar -->
    <div class="topbar">
      <div class="topbar-left">
        <button class="hamburger" onclick="toggleSidebar()"><i class='bx bx-menu'></i></button>
        <div>
          <div class="page-title">Contact Messages</div>
        </div>
      </div>
      <div class="topbar-right">
        <div class="notif-bell" onclick="markAllRead()" title="Mark all as read">
          <i class='bx bx-bell'></i>
          <div class="notif-dot" id="notifDot"></div>
        </div>
        <div class="admin-chip"><i class='bx bxs-user-check'></i> Admin</div>
      </div>
    </div>

    <!-- Content -->
    <div class="content">

      <!-- Stats -->
      <div class="stats-row" id="statsRow">
        <div class="stat-card">
          <div class="stat-icon blue"><i class='bx bx-message-dots'></i></div>
          <div>
            <div class="stat-val" id="statTotal">0</div>
            <div class="stat-label">Total Messages</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon gold"><i class='bx bx-envelope'></i></div>
          <div>
            <div class="stat-val" id="statUnread">0</div>
            <div class="stat-label">Unread</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon green"><i class='bx bx-check-circle'></i></div>
          <div>
            <div class="stat-val" id="statRead">0</div>
            <div class="stat-label">Read</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon red"><i class='bx bx-globe'></i></div>
          <div>
            <div class="stat-val" id="statSources">0</div>
            <div class="stat-label">Sources</div>
          </div>
        </div>
      </div>

      <!-- Messages panel -->
      <div class="panel">
        <div class="panel-header">
          <div>
            <h2>All Submissions</h2>
            <p>Click a row to view full message details</p>
          </div>
          <div class="toolbar">
            <div class="search-box">
              <i class='bx bx-search'></i>
              <input type="text" id="searchInput" placeholder="Search…" oninput="renderTable()">
            </div>
            <select class="filter-select" id="filterStatus" onchange="renderTable()">
              <option value="all">All Messages</option>
              <option value="unread">Unread</option>
              <option value="read">Read</option>
            </select>
            <select class="filter-select" id="filterSource" onchange="renderTable()">
              <option value="all">All Sources</option>
              <option value="Main Page">Main Page</option>
              <option value="Contact Page">Contact Page</option>
            </select>
            <button class="btn-mark-all" onclick="markAllRead()">
              <i class='bx bx-check-double'></i> Mark All Read
            </button>
          </div>
        </div>

        <div style="overflow-x:auto;">
          <table class="msg-table">
            <thead>
              <tr>
                <th>Status</th>
                <th>Sender</th>
                <th>Subject</th>
                <th>Preview</th>
                <th>Source</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>

<td>
<?php if($row['is_read']==0){ ?>
<span class="status-dot unread"></span> New
<?php } else { ?>
<span class="status-dot read"></span> Read
<?php } ?>
</td>

<td>
<div class="sender-info">
<span class="sender-name">
<?php echo $row['first_name']." ".$row['last_name']; ?>
</span>

<span class="sender-email">
<?php echo $row['email']; ?>
</span>
</div>
</td>

<td>
<?php echo $row['company']; ?>
</td>

<td class="msg-preview">
<?php echo $row['message']; ?>
</td>

<td>
<span class="source-badge">
<?php echo $row['source']; ?>
</span>
</td>

<td class="date-cell">
<?php echo $row['created_at']; ?>
</td>

<td>

<a href="delete.php?id=<?php echo $row['id']; ?>">
<button class="action-btn delete">
<i class='bx bx-trash'></i>
</button>
</a>

</td>

</tr>

<?php } ?>

</tbody>
          </table>
        </div>

        <div class="empty-state" id="emptyState" style="display:none;">
          <i class='bx bx-inbox'></i>
          <p>No messages found.</p>
        </div>

      </div>
    </div>
  </div>

  <!-- ══ MESSAGE DETAIL MODAL ══════════════════════════════════ -->
  <div class="modal-overlay" id="modalOverlay" onclick="closeModal(event)">
    <div class="modal">
      <div class="modal-header">
        <h3 id="modalSubject">—</h3>
        <button class="modal-close" onclick="closeModalDirect()"><i class='bx bx-x'></i></button>
      </div>
      <div class="modal-body">
        <div class="detail-row">
          <div class="detail-label">From</div>
          <div class="detail-value" id="modalName">—</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">Email</div>
          <div class="detail-value" id="modalEmail">—</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">Source</div>
          <div class="detail-value" id="modalSource">—</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">Received</div>
          <div class="detail-value" id="modalDate">—</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">Message</div>
          <div class="detail-message" id="modalMessage">—</div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="modal-btn delete" id="modalDeleteBtn" onclick="modalDelete()">
          <i class='bx bx-trash'></i> Delete
        </button>
        <button class="modal-btn toggle" id="modalToggleBtn" onclick="modalToggleRead()">
          <i class='bx bx-envelope-open'></i> <span id="modalToggleText">Mark as Read</span>
        </button>
        <button class="modal-btn close" onclick="closeModalDirect()">
          Close
        </button>
      </div>
    </div>
  </div>

  <!-- ══ DELETE CONFIRM ════════════════════════════════════════ -->
  <div class="confirm-overlay" id="confirmOverlay">
    <div class="confirm-box">
      <i class='bx bx-trash'></i>
      <h4>Delete Message?</h4>
      <p>This action cannot be undone.</p>
      <div class="confirm-btns">
        <button class="btn-cancel-del" onclick="closeConfirm()">Cancel</button>
        <button class="btn-confirm-del" onclick="confirmDelete()">Yes, Delete</button>
      </div>
    </div>
  </div>

  <!-- ══ TOAST ═════════════════════════════════════════════════ -->
  <div class="toast" id="toast">
    <i class='bx bx-check-circle'></i>
    <span id="toastText">Done!</span>
  </div>

  <!-- Load shared DB module -->
  



</body>
</html>
