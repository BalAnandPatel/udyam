<?php
session_start();
require 'config/db.php';

// Ensure we have a mysqli connection in $conn
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
?>
<div class="container mt-4">
    <h3>👤 Your Direct Members & Income</h3>
    <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Member Name</th>
                    <th>Email</th>
                    <th>Join Date</th>
                    <th>Total Direct Income (₹)</th>
                    <th>From User</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query direct members with summed referral income
                $sql = "
                    SELECT u.id, u.name, u.email, u.created_at,
                           COALESCE(SUM(e.amount),0) AS total_income,
                           GROUP_CONCAT(e.meta SEPARATOR '||') AS all_meta
                    FROM users u
                    LEFT JOIN earnings e 
                        ON e.user_id = {$user_id}
                        AND e.type = 'referral'
                        AND e.meta LIKE CONCAT('%user #', u.id, '%')
                    WHERE u.sponsor_id = {$user_id}
                    GROUP BY u.id, u.name, u.email, u.created_at
                    ORDER BY u.created_at DESC
                ";
                $res = mysqli_query($conn, $sql);

                if ($res === false) {
                    echo "<tr><td colspan='7' class='text-danger'>Database query failed: " . htmlspecialchars(mysqli_error($conn)) . "</td></tr>";
                } else {
                    if (mysqli_num_rows($res) > 0) {
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($res)) {
                            // Extract source user IDs from meta
                            $from_users = [];
                            if (!empty($row['all_meta'])) {
                                preg_match_all('/user\s+#(\d+)/i', $row['all_meta'], $matches);
                                if (!empty($matches[1])) {
                                    $from_users = array_unique($matches[1]);
                                }
                            }

                            echo "<tr>
                                    <td>{$i}</td>
                                    <td>" . htmlspecialchars($row['id']) . "</td>
                                    <td>" . htmlspecialchars($row['name']) . "</td>
                                    <td>" . htmlspecialchars($row['email']) . "</td>
                                    <td>" . date('d M Y H:i', strtotime($row['created_at'])) . "</td>
                                    <td>₹" . number_format($row['total_income'], 2) . "</td>
                                    <td>" . (!empty($from_users) ? implode(', ', $from_users) : '-') . "</td>
                                  </tr>";
                            $i++;
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No direct members found.</td></tr>";
                    }
                    mysqli_free_result($res);
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
