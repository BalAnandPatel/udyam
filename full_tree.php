<?php
require 'config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Please login first.");
}

// Logged-in user
$loggedInUserId = $_SESSION['user_id'];

// If user clicks another ID, show that genealogy
$userId = isset($_GET['uid']) ? (int)$_GET['uid'] : $loggedInUserId;

/* ==========================
   Recursive Tree Builder
========================== */
function buildFullTree($userId, $conn) {
    $user = $conn->query("SELECT id, name, position FROM users WHERE id='$userId'")->fetch_assoc();

    echo '<li>';
    echo '<div class="node">';
    echo "<strong>ID: {$user['id']}</strong>";
    echo "<small class='user-name'>" . htmlspecialchars($user['name']) . "</small>";
    echo "<div class='node-details'>(" . ucfirst($user['position']) . ")</div>";
    echo '</div>';

    // Fetch children
    $res = $conn->query("SELECT id FROM users WHERE parent_id='$userId' ORDER BY position");
    if ($res->num_rows > 0) {
        echo '<ul>';
        while ($row = $res->fetch_assoc()) {
            buildFullTree($row['id'], $conn);
        }
        echo '</ul>';
    }
    echo '</li>';
}

// Count total team
function countTeam($userId, $conn) {
    $count = 0;
    $queue = [$userId];
    while (!empty($queue)) {
        $current = array_shift($queue);
        $res = $conn->query("SELECT id FROM users WHERE parent_id='$current'");
        while ($row = $res->fetch_assoc()) {
            $count++;
            $queue[] = $row['id'];
        }
    }
    return $count;
}

$totalTeam = countTeam($userId, $conn);

include 'includes/header.php';
?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="text-primary">🌳 Full Genealogy of User ID <?= $userId ?></h3>
    <?php if ($userId != $loggedInUserId): ?>
      <a href="?uid=<?= $loggedInUserId ?>" class="btn btn-sm btn-secondary">⬅ Back to My Tree</a>
    <?php endif; ?>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Complete Binary Tree</h5>
      <p><strong>Total Team Members:</strong> <?= $totalTeam ?></p>

      <div class="tree-container">
        <div class="tree">
          <ul>
            <?php buildFullTree($userId, $conn); ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<style>
.tree-container {
  width: 100%;
  overflow-x: auto;
  overflow-y: hidden;
  -webkit-overflow-scrolling: touch;
  padding-bottom: 10px;
}

/* Tree base */
.tree ul {
  padding-top: 20px;
  position: relative;
  display: flex;
  justify-content: center;
}

.tree li {
  list-style-type: none;
  text-align: center;
  position: relative;
  padding: 20px 5px 0 5px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* Connector lines */
.tree li::before, .tree li::after {
  content: '';
  position: absolute;
  top: 0;
  width: 50%;
  height: 20px;
  border-top: 2px solid #ccc;
}

.tree li::before { right: 50%; }
.tree li::after  { left: 50%; border-left: 2px solid #ccc; }

.tree li:only-child::before, 
.tree li:only-child::after { display: none; }

.tree li:only-child { padding-top: 0; }
.tree li:first-child::before, 
.tree li:last-child::after { border: 0 none; }

.tree li:last-child::before {
  border-right: 2px solid #ccc;
  border-radius: 0 5px 0 0;
}
.tree li:first-child::after {
  border-radius: 5px 0 0 0;
}

/* Child wrapper ensures equal spacing */
.tree ul ul {
  display: flex;
  justify-content: center;
  padding-left: 0;
}

/* Node */
.tree .node {
  border: 1px solid #0d6efd;
  border-radius: 8px;
  padding: 6px 10px;
  background: #f8f9fa;
  color: #0d6efd;
  font-size: 12px;
  min-width: 100px;
  margin: auto;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  transition: transform 0.2s, background 0.2s;
  position: relative;
}

.tree .node strong { font-size: 13px; }

/* Hover */
.tree .node:hover {
  background: #0d6efd;
  color: #fff;
  transform: scale(1.05);
}

/* Responsive */
@media (max-width: 768px) {
  .tree .node {
    font-size: 11px;
    min-width: 70px;
    padding: 4px 6px;
  }
  .tree .node strong { font-size: 11px; }
}
</style>
