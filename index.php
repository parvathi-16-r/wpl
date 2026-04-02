<?php
require_once 'config.php';
$message = '';
$edit_data = null;
if (isset($_POST['insert'])) {
    $name     = mysqli_real_escape_string($conn, trim($_POST['name']));
    $role     = mysqli_real_escape_string($conn, trim($_POST['role']));
    $email    = mysqli_real_escape_string($conn, trim($_POST['email']));
    $stardate = mysqli_real_escape_string($conn, trim($_POST['stardate']));
    $status   = mysqli_real_escape_string($conn, trim($_POST['status']));
    if ($name && $role && $email && $stardate) {
        $sql = "INSERT INTO crew_members (name, role, email, stardate, status)
                VALUES ('$name', '$role', '$email', '$stardate', '$status')";
        if (mysqli_query($conn, $sql)) {
            $message = '<div class="alert success">✦ Crew member launched into the database!</div>';
        } else {
            $message = '<div class="alert error">✕ Launch failed: ' . mysqli_error($conn) . '</div>';
        }
    } else {
        $message = '<div class="alert warning">⚠ All fields are required for launch.</div>';
    }
}
if (isset($_POST['update'])) {
    $id       = (int) $_POST['id'];
    $name     = mysqli_real_escape_string($conn, trim($_POST['name']));
    $role     = mysqli_real_escape_string($conn, trim($_POST['role']));
    $email    = mysqli_real_escape_string($conn, trim($_POST['email']));
    $stardate = mysqli_real_escape_string($conn, trim($_POST['stardate']));
    $status   = mysqli_real_escape_string($conn, trim($_POST['status']));
    if ($id && $name && $role && $email && $stardate) {
        $sql = "UPDATE crew_members SET name='$name', role='$role', email='$email',
                stardate='$stardate', status='$status' WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            $message = '<div class="alert success">✦ Crew record patched successfully!</div>';
        } else {
            $message = '<div class="alert error">✕ Patch failed: ' . mysqli_error($conn) . '</div>';
        }
    } else {
        $message = '<div class="alert warning">⚠ All fields are required for patch.</div>';
    }
}
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $sql = "DELETE FROM crew_members WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        $message = '<div class="alert success">✦ Crew member removed from database.</div>';
    } else {
        $message = '<div class="alert error">✕ Destroy failed: ' . mysqli_error($conn) . '</div>';
    }
}
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM crew_members WHERE id=$id");
    $edit_data = mysqli_fetch_assoc($result);
}
$search = '';
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
    $fetch_sql = "SELECT * FROM crew_members WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR role LIKE '%$search%' ORDER BY id DESC";
} else {
    $fetch_sql = "SELECT * FROM crew_members ORDER BY id DESC";
}
$result = mysqli_query($conn, $fetch_sql);
$total  = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ORION DATABASE · Mission Control</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;600;700&family=Rajdhani:wght@400;600;700&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Exo 2', sans-serif;
    background: #020818;
    color: #e0e7ff;
    min-height: 100vh;
    overflow-x: hidden;
}
body::before {
    content: '';
    position: fixed; inset: 0; z-index: 0;
    background-image:
        radial-gradient(1px 1px at 5% 8%,  #fff 0%, transparent 100%),
        radial-gradient(1px 1px at 12% 30%, rgba(255,255,255,0.7) 0%, transparent 100%),
        radial-gradient(1px 1px at 18% 60%, #fff 0%, transparent 100%),
        radial-gradient(1px 1px at 22% 80%, rgba(255,255,255,0.5) 0%, transparent 100%),
        radial-gradient(1px 1px at 30% 20%, #fff 0%, transparent 100%),
        radial-gradient(1px 1px at 35% 50%, rgba(255,255,255,0.6) 0%, transparent 100%),
        radial-gradient(1px 1px at 40% 90%, #fff 0%, transparent 100%),
        radial-gradient(1px 1px at 48% 15%, rgba(255,255,255,0.8) 0%, transparent 100%),
        radial-gradient(1px 1px at 55% 40%, #fff 0%, transparent 100%),
        radial-gradient(1px 1px at 60% 70%, rgba(255,255,255,0.4) 0%, transparent 100%),
        radial-gradient(1px 1px at 67% 25%, #fff 0%, transparent 100%),
        radial-gradient(1px 1px at 72% 55%, rgba(255,255,255,0.6) 0%, transparent 100%),
        radial-gradient(1px 1px at 78% 85%, #fff 0%, transparent 100%),
        radial-gradient(1px 1px at 83% 10%, rgba(255,255,255,0.9) 0%, transparent 100%),
        radial-gradient(1px 1px at 88% 45%, #fff 0%, transparent 100%),
        radial-gradient(1px 1px at 93% 75%, rgba(255,255,255,0.5) 0%, transparent 100%),
        radial-gradient(1px 1px at 97% 35%, #fff 0%, transparent 100%),
        radial-gradient(1.5px 1.5px at 15% 95%, rgba(180,180,255,0.9) 0%, transparent 100%),
        radial-gradient(1.5px 1.5px at 44% 62%, rgba(180,180,255,0.7) 0%, transparent 100%),
        radial-gradient(2px 2px at 76% 6%,  rgba(200,200,255,0.8) 0%, transparent 100%),
        radial-gradient(2px 2px at 90% 92%, rgba(200,200,255,0.6) 0%, transparent 100%);
    pointer-events: none;
}
body::after {
    content: '';
    position: fixed; inset: 0; z-index: 0;
    background:
        radial-gradient(ellipse 70% 50% at 85% 15%, rgba(88,28,220,0.20) 0%, transparent 70%),
        radial-gradient(ellipse 60% 40% at 5%  70%, rgba(20,80,200,0.18) 0%, transparent 70%),
        radial-gradient(ellipse 50% 60% at 50% 100%, rgba(180,20,120,0.12) 0%, transparent 70%);
    pointer-events: none;
}
.wrapper { position: relative; z-index: 1; max-width: 1100px; margin: 0 auto; padding: 24px 20px 60px; }
header {
    display: flex; align-items: center; gap: 16px;
    border-bottom: 1px solid rgba(99,179,237,0.2);
    padding-bottom: 18px; margin-bottom: 28px;
}
.logo {
    width: 48px; height: 48px; border-radius: 50%;
    background: linear-gradient(135deg, #4f46e5, #7c3aed, #2563eb);
    border: 2px solid rgba(147,197,253,0.5);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    box-shadow: 0 0 20px rgba(99,102,241,0.5);
}
.header-text h1 {
    font-family: 'Orbitron', monospace;
    font-size: 22px; font-weight: 700;
    color: #ffffff; letter-spacing: 3px;
}
.header-text p {
    font-family: 'Rajdhani', sans-serif;
    font-size: 13px; color: rgba(147,197,253,0.75);
    letter-spacing: 3px; text-transform: uppercase; margin-top: 2px;
}
.header-status { margin-left: auto; display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
.status-row { display: flex; align-items: center; gap: 6px; }
.status-dot { width: 7px; height: 7px; border-radius: 50%; background: #34d399; box-shadow: 0 0 8px #34d399; animation: pulse 2s infinite; }
.status-text { font-family: 'Rajdhani', sans-serif; font-size: 13px; color: #34d399; letter-spacing: 2px; }
.db-info { font-family: 'Orbitron', monospace; font-size: 11px; color: rgba(147,197,253,0.55); letter-spacing: 1px; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }
.alert {
    padding: 10px 16px; border-radius: 8px; margin-bottom: 20px;
    font-family: 'Rajdhani', sans-serif; font-size: 15px; font-weight: 600;
    letter-spacing: 1px;
}
.alert.success { background: rgba(52,211,153,0.12); border: 1px solid rgba(52,211,153,0.35); color: #34d399; }
.alert.error   { background: rgba(248,113,113,0.12); border: 1px solid rgba(248,113,113,0.35); color: #f87171; }
.alert.warning { background: rgba(251,191,36,0.12);  border: 1px solid rgba(251,191,36,0.35);  color: #fbbf24; }
.panel {
    background: rgba(10,15,50,0.7);
    border: 1px solid rgba(99,179,237,0.2);
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 22px;
    backdrop-filter: blur(8px);
}
.panel-title {
    font-family: 'Orbitron', monospace;
    font-size: 13px; font-weight: 700;
    color: rgba(200,220,255,0.85);
    letter-spacing: 3px; text-transform: uppercase;
    margin-bottom: 18px;
    display: flex; align-items: center; gap: 8px;
}
.panel-title::before {
    content: '';
    display: inline-block; width: 3px; height: 14px;
    background: linear-gradient(180deg, #7c3aed, #2563eb);
    border-radius: 2px;
}
.form-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; margin-bottom: 16px; }
.field { display: flex; flex-direction: column; gap: 5px; }
.field label {
    font-family: 'Rajdhani', sans-serif;
    font-size: 12px; color: rgba(200,220,255,0.75);
    letter-spacing: 2px; text-transform: uppercase;
}
.field input, .field select {
    background: rgba(15,23,60,0.85);
    border: 1px solid rgba(99,179,237,0.25);
    border-radius: 7px;
    padding: 9px 12px;
    font-family: 'Exo 2', sans-serif;
    font-size: 15px;
    color: #e0e7ff;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    width: 100%;
}
.field input:focus, .field select:focus {
    border-color: rgba(99,179,237,0.7);
    box-shadow: 0 0 0 3px rgba(99,179,237,0.1);
}
.field select option { background: #0a1040; color: #e0e7ff; }
.btn-row { display: flex; gap: 10px; flex-wrap: wrap; }
.btn {
    font-family: 'Rajdhani', sans-serif;
    font-size: 13px; font-weight: 700;
    letter-spacing: 1.5px; text-transform: uppercase;
    padding: 9px 20px; border-radius: 7px;
    cursor: pointer; border: none;
    transition: opacity 0.15s, transform 0.1s;
    text-decoration: none; display: inline-block;
}
.btn:hover { opacity: 0.85; transform: translateY(-1px); }
.btn:active { transform: scale(0.97); }
.btn-launch  { background: linear-gradient(135deg,#4f46e5,#7c3aed); color:#ffffff; box-shadow:0 0 14px rgba(99,102,241,0.4); }
.btn-patch   { background: transparent; color:#fbbf24; border:1px solid rgba(251,191,36,0.5); }
.btn-cancel  { background: transparent; color:rgba(200,220,255,0.75); border:1px solid rgba(99,179,237,0.2); }
.btn-scan    { background: transparent; color:#34d399; border:1px solid rgba(52,211,153,0.4); }
.btn-reset   { background: transparent; color:rgba(200,220,255,0.6); border:1px solid rgba(99,179,237,0.15); font-size:12px; padding:7px 14px; }
.search-row { display: flex; gap: 10px; margin-bottom: 0; }
.search-row input {
    flex: 1;
    background: rgba(15,23,60,0.85);
    border: 1px solid rgba(99,179,237,0.25);
    border-radius: 7px;
    padding: 9px 14px;
    font-family: 'Exo 2', sans-serif;
    font-size: 15px; color: #e0e7ff;
    outline: none;
}
.search-row input:focus { border-color: rgba(99,179,237,0.6); }
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
thead tr {
    background: rgba(30,40,100,0.5);
    border-bottom: 1px solid rgba(99,179,237,0.2);
}
th {
    font-family: 'Rajdhani', sans-serif;
    font-size: 12px; color: rgba(200,220,255,0.8);
    letter-spacing: 2px; text-transform: uppercase;
    padding: 10px 14px; text-align: left; font-weight: 700;
}
td {
    font-family: 'Exo 2', sans-serif;
    font-size: 15px; color: #e0e7ff;
    padding: 11px 14px;
    border-bottom: 1px solid rgba(99,179,237,0.08);
    vertical-align: middle;
}
tbody tr:hover { background: rgba(99,102,241,0.07); }
.td-id {
    font-family: 'Orbitron', monospace;
    font-size: 11px; color: rgba(200,220,255,0.55);
}
.badge {
    font-family: 'Rajdhani', sans-serif;
    font-size: 12px; font-weight: 700; letter-spacing: 1px;
    padding: 3px 9px; border-radius: 20px; display: inline-block;
}
.badge-active  { background:rgba(52,211,153,0.12); color:#34d399; border:1px solid rgba(52,211,153,0.3); }
.badge-offline { background:rgba(248,113,113,0.12); color:#f87171; border:1px solid rgba(248,113,113,0.3); }
.actions { display: flex; gap: 8px; }
.action-link {
    font-family: 'Rajdhani', sans-serif;
    font-size: 12px; font-weight: 700; letter-spacing: 1px;
    padding: 4px 10px; border-radius: 5px; cursor: pointer;
    text-decoration: none; transition: opacity 0.15s;
    border: 1px solid;
}
.edit-link   { color:#fbbf24; border-color:rgba(251,191,36,0.4); }
.edit-link:hover { background:rgba(251,191,36,0.1); }
.delete-link { color:#f87171; border-color:rgba(248,113,113,0.3); }
.delete-link:hover { background:rgba(248,113,113,0.1); }
.stats-row { display: flex; gap: 12px; margin-bottom: 22px; }
.stat-card {
    flex: 1;
    background: rgba(10,15,50,0.7);
    border: 1px solid rgba(99,179,237,0.15);
    border-radius: 10px; padding: 14px 18px;
    backdrop-filter: blur(8px);
}
.stat-label { font-family:'Rajdhani',sans-serif; font-size:12px; color:rgba(200,220,255,0.65); letter-spacing:2px; text-transform:uppercase; }
.stat-value { font-family:'Orbitron',monospace; font-size:26px; font-weight:700; color:#ffffff; margin-top:4px; }
.stat-value.green { color:#34d399; }
.stat-value.red   { color:#f87171; }
.empty {
    text-align: center; padding: 40px 20px;
    font-family: 'Rajdhani', sans-serif;
    color: rgba(200,220,255,0.5); letter-spacing: 2px;
    font-size: 15px;
}
footer {
    text-align: center; margin-top: 30px;
    font-family: 'Orbitron', monospace;
    font-size: 11px; color: rgba(200,220,255,0.4);
    letter-spacing: 2px;
}
</style>
</head>
<body>
<div class="wrapper">
  <header>
    <div class="logo">🪐</div>
    <div class="header-text">
      <h1>ORION DATABASE</h1>
      <p>Mission Control · PHP + MySQL CRUD System</p>
    </div>
    <div class="header-status">
      <div class="status-row">
        <div class="status-dot"></div>
        <div class="status-text">CONNECTED</div>
      </div>
      <div class="db-info">DB: orion_db · PHP <?= PHP_VERSION ?></div>
    </div>
  </header>
  <?= $message ?>
  <?php
  $all     = mysqli_query($conn, "SELECT COUNT(*) as c FROM crew_members");
  $active  = mysqli_query($conn, "SELECT COUNT(*) as c FROM crew_members WHERE status='ACTIVE'");
  $offline = mysqli_query($conn, "SELECT COUNT(*) as c FROM crew_members WHERE status='OFFLINE'");
  $tc = mysqli_fetch_assoc($all)['c'];
  $ac = mysqli_fetch_assoc($active)['c'];
  $oc = mysqli_fetch_assoc($offline)['c'];
  ?>
  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-label">Total Crew</div>
      <div class="stat-value"><?= $tc ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Active</div>
      <div class="stat-value green"><?= $ac ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Offline</div>
      <div class="stat-value red"><?= $oc ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Stardate</div>
      <div class="stat-value" style="font-size:14px;margin-top:6px;"><?= date('Y.m.d') ?></div>
    </div>
  </div>
  <div class="panel">
    <div class="panel-title">
      <?= $edit_data ? 'Patch Crew Record' : 'Launch New Crew Member' ?>
    </div>
    <form method="POST" action="">
      <?php if ($edit_data): ?>
        <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
      <?php endif; ?>
      <div class="form-grid">
        <div class="field">
          <label>Crew Name</label>
          <input type="text" name="name" placeholder="Enter full name..."
                 value="<?= $edit_data ? htmlspecialchars($edit_data['name']) : '' ?>" required>
        </div>
        <div class="field">
          <label>Sector / Role</label>
          <input type="text" name="role" placeholder="e.g. Navigator, Engineer..."
                 value="<?= $edit_data ? htmlspecialchars($edit_data['role']) : '' ?>" required>
        </div>
        <div class="field">
          <label>Signal Frequency (Email)</label>
          <input type="email" name="email" placeholder="crew@orion.space"
                 value="<?= $edit_data ? htmlspecialchars($edit_data['email']) : '' ?>" required>
        </div>
        <div class="field">
          <label>Stardate</label>
          <input type="date" name="stardate"
                 value="<?= $edit_data ? $edit_data['stardate'] : date('Y-m-d') ?>" required>
        </div>
        <div class="field">
          <label>Status</label>
          <select name="status">
            <option value="ACTIVE"  <?= ($edit_data && $edit_data['status']=='ACTIVE')  ? 'selected' : '' ?>>ACTIVE</option>
            <option value="OFFLINE" <?= ($edit_data && $edit_data['status']=='OFFLINE') ? 'selected' : '' ?>>OFFLINE</option>
          </select>
        </div>
      </div>
      <div class="btn-row">
        <?php if ($edit_data): ?>
          <button type="submit" name="update" class="btn btn-patch">✦ Patch Record</button>
          <a href="index.php" class="btn btn-cancel">✕ Cancel</a>
        <?php else: ?>
          <button type="submit" name="insert" class="btn btn-launch">⬆ Launch Record</button>
          <a href="index.php" class="btn btn-reset">↺ Reset</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
  <div class="panel">
    <div class="panel-title">Crew Manifest · <?= $total ?> Record<?= $total != 1 ? 's' : '' ?></div>
    <form method="GET" action="" style="margin-bottom:18px;">
      <div class="search-row">
        <input type="text" name="search" placeholder="◎  Scan crew by name, role or signal..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-scan">◎ Scan</button>
        <?php if ($search): ?>
          <a href="index.php" class="btn btn-reset">✕ Clear</a>
        <?php endif; ?>
      </div>
    </form>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Crew Name</th>
            <th>Role / Sector</th>
            <th>Signal Frequency</th>
            <th>Stardate</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($total > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td class="td-id">#<?= str_pad($row['id'], 3, '0', STR_PAD_LEFT) ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['role']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= date('Y.m.d', strtotime($row['stardate'])) ?></td>
              <td>
                <span class="badge <?= $row['status'] === 'ACTIVE' ? 'badge-active' : 'badge-offline' ?>">
                  <?= $row['status'] ?>
                </span>
              </td>
              <td>
                <div class="actions">
                  <a href="?edit=<?= $row['id'] ?>" class="action-link edit-link">✦ Patch</a>
                  <a href="?delete=<?= $row['id'] ?>"
                     class="action-link delete-link"
                     onclick="return confirm('Destroy crew member #<?= $row['id'] ?> from database?')">✕ Destroy</a>
                </div>
              </td>
            </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="7" class="empty">
              <?= $search ? '◎ No crew members match your scan.' : '⬆ No crew members launched yet. Add your first record above.' ?>
            </td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <footer>
    ORION DATABASE SYSTEM · PHP <?= PHP_VERSION ?> · MySQL · phpMyAdmin · XAMPP &nbsp;|&nbsp; <?= date('Y') ?>
  </footer>
</div>
<?php mysqli_close($conn); ?>
</body>
</html>
