<?php
require 'config/db.php';
//ession_start();

if (!isset($_SESSION['user_id'])) {
    die("Please login first.");
}

// Logged-in user
$loggedInUserId = $_SESSION['user_id'];

// If user clicks on another ID, load that genealogy
$userId = isset($_GET['uid']) ? (int)$_GET['uid'] : $loggedInUserId;

// ----------- Helper: Count Levels (for summary) -----------
function countLevels($userId, $maxLevel, $conn) {
    $result = [];
    $currentLevel = [$userId];

    for ($level = 1; $level <= $maxLevel; $level++) {
        $nextLevel = [];
        $count = 0;

        foreach ($currentLevel as $uid) {
            $res = $conn->query("SELECT id FROM users WHERE parent_id='$uid'");
            while ($row = $res->fetch_assoc()) {
                $count++;
                $nextLevel[] = $row['id'];
            }
        }

        $result[$level] = $count;
        $currentLevel = $nextLevel;

        if (empty($currentLevel)) break;
    }

    return $result;
}

// ----------- Recursive Tree Builder -----------
function buildTree($userId, $conn, $depth = 0, $maxDepth = 2) {
    $user = $conn->query("SELECT id, name FROM users WHERE id='$userId'")->fetch_assoc();

    echo '<li>';
    echo '<div class="node">';
    echo "<strong>ID: {$user['id']}</strong>";
    echo "<small class='user-name'>" . htmlspecialchars($user['name']) . "</small>";

    echo "<div class='node-details'>";
    $check = $conn->query("SELECT COUNT(*) as cnt FROM users WHERE parent_id='$userId'");
    $row = $check->fetch_assoc();
    if ($row['cnt'] > 0) {
        echo "<a href='?uid={$user['id']}' class='btn btn-sm btn-outline-light mt-2'>View Next Level</a>";
    }
    echo "</div>"; 
    echo '</div>';

    if ($depth < $maxDepth) {
        $res = $conn->query("SELECT id FROM users WHERE parent_id='$userId' ORDER BY position");
        if ($res->num_rows > 0) {
            echo '<ul>';
            while ($row = $res->fetch_assoc()) {
                buildTree($row['id'], $conn, $depth + 1, $maxDepth);
            }
            echo '</ul>';
        }
    }

    echo '</li>';
}

// ----------- Data for Current User -----------
$levels = countLevels($userId, 12, $conn);
$totalTeam = array_sum($levels);

// include header
include 'includes/header.php';
?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="text-primary">👥 Genealogy of User ID <?= $userId ?></h3>
    <?php if ($userId != $loggedInUserId): ?>
      <a href="?uid=<?= $loggedInUserId ?>" class="btn btn-sm btn-secondary">⬅ Back to My Tree</a>
    <?php endif; ?>
  </div>

  <div class="row">
    <div class="col-md-4">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="card-title">Level Summary</h5>
          <ul class="list-group">
            <?php foreach ($levels as $level => $count): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Level <?= $level ?>
                <span class="badge bg-primary rounded-pill"><?= $count ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
          <p class="mt-3"><strong>Total Team Members:</strong> <?= $totalTeam ?></p>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Genealogy Tree (2 levels)</h5>

          <!-- ✅ Scrollable container for mobile -->
          <div class="tree-container">
            <div class="tree">
              <ul>
                <?php buildTree($userId, $conn, 0, 2); ?>
              </ul>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<style>
/* ✅ Tree scrollable container for mobile */
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
