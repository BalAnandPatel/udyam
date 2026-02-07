<?php
session_start();
require 'config/db.php';

// Ensure we have a mysqli connection in $conn (fallback to common vars)
if (!isset($conn) || !($conn instanceof mysqli)) {
    if (isset($mysqli) && ($mysqli instanceof mysqli)) {
        $conn = $mysqli;
    } elseif (isset($db) && ($db instanceof mysqli)) {
        $conn = $db;
    } else {
        die("Database connection not found. Check config/db.php (expected \$conn).");
    }
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = (int) $_SESSION['user_id'];

include 'includes/header.php';

/*
 * BFS traversal to collect team members up to 12 levels.
 * Using plain mysqli_query() and casting IDs to int (no bind_param).
 */
$max_level = 12;
$team = [];            // collected members
$queue = [];           // queue of arrays: [parent_id, level]
$queue[] = [$user_id, 1];

while (!empty($queue)) {
    list($parent, $level) = array_shift($queue);
    $parent = (int) $parent;
    if ($level > $max_level) continue;

    $sql = "SELECT id, name, email, sponsor_id, created_at FROM users WHERE sponsor_id = $parent";
    $res = mysqli_query($conn, $sql);
    if ($res === false) {
        die("DB query failed: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($res)) {
        $row['level'] = (int)$level;
        $team[] = $row;
        // enqueue this user's children for next level
        $queue[] = [(int)$row['id'], $level + 1];
    }
    mysqli_free_result($res);
}

// Sponsor name cache to avoid repeated queries
$sponsorCache = [];

?>
<div class="container mt-4">
    <h3>👥 Your Total Team</h3>
    <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Member Name</th>
                    <th>Email</th>
                    <th>Sponsor</th>
                    <th>Level</th>
                    <th>Join Date</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (!empty($team)) {
                $i = 1;
                foreach ($team as $member) {
                    $sponsorName = "N/A";
                    $sId = isset($member['sponsor_id']) ? (int)$member['sponsor_id'] : 0;
                    if ($sId > 0) {
                        if (isset($sponsorCache[$sId])) {
                            $sponsorName = $sponsorCache[$sId];
                        } else {
                            // fetch sponsor name with a simple query (cast id to int)
                            $sSql = "SELECT name FROM users WHERE id = " . (int)$sId . " LIMIT 1";
                            $sRes = mysqli_query($conn, $sSql);
                            if ($sRes) {
                                $sRow = mysqli_fetch_assoc($sRes);
                                if ($sRow && !empty($sRow['name'])) {
                                    $sponsorName = $sRow['name'];
                                }
                                mysqli_free_result($sRes);
                            }
                            $sponsorCache[$sId] = $sponsorName;
                        }
                    }

                    echo "<tr>";
                    echo "<td>" . $i . "</td>";
                    echo "<td>" . htmlspecialchars($member['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($member['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($sponsorName) .  "--". htmlspecialchars($member['sponsor_id']) ."</td>";
                    echo "<td>Level " . (int)$member['level'] . "</td>";
                    echo "<td>" . date('d M Y H:i', strtotime($member['created_at'])) . "</td>";
                    echo "</tr>";

                    $i++;
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No team members found.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
