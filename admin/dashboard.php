<?php
include 'includes/header.php';


// ✅ Total users
// ✅ Total users
$sqlUsers = "SELECT COUNT(*) AS total_users FROM users";
$resUsers = mysqli_query($conn, $sqlUsers);
$totalUsers = mysqli_fetch_assoc($resUsers)['total_users'];

// ✅ Today's joined users
$todayDate = date("Y-m-d");
$sqlTodayUsers = "SELECT COUNT(*) AS today_users FROM users WHERE DATE(created_at) = '$todayDate'";
$resTodayUsers = mysqli_query($conn, $sqlTodayUsers);
$todayJoined = mysqli_fetch_assoc($resTodayUsers)['today_users'];


// ✅ Total videos
$sqlVideos = "SELECT COUNT(*) as total_videos FROM videos";
$resVideos = mysqli_query($conn, $sqlVideos);
$totalVideos = mysqli_fetch_assoc($resVideos)['total_videos'];

// ✅ Total earnings (all types)
$sqlEarnings = "SELECT SUM(amount) as total_earnings FROM earnings";
$resEarnings = mysqli_query($conn, $sqlEarnings);
$totalEarnings = mysqli_fetch_assoc($resEarnings)['total_earnings'];
$totalEarnings = $totalEarnings ? number_format($totalEarnings, 2) : "0.00";

// ✅ Today's earnings
$today = date("Y-m-d");
$sqlToday = "SELECT SUM(amount) as today_earnings FROM earnings WHERE DATE(created_at)='$today'";
$resToday = mysqli_query($conn, $sqlToday);
$todayEarnings = mysqli_fetch_assoc($resToday)['today_earnings'];
$todayEarnings = $todayEarnings ? number_format($todayEarnings, 2) : "0.00";

// ✅ Founder Club Payment Pool (25% of base_amount)
$sqlFounderPayment = "SELECT SUM(base_amount) AS total_base FROM bills";
$resFounderPayment = mysqli_query($conn, $sqlFounderPayment);
$totalBase = mysqli_fetch_assoc($resFounderPayment)['total_base'];

$founderPool = $totalBase ? ($totalBase * 0.25) : 0;
$founderPool = number_format($founderPool, 2);

// Today's Joined Users
$todayDate = date("Y-m-d");

$sqlTodayUsers = "SELECT COUNT(*) AS today_users FROM users WHERE DATE(created_at) = '$todayDate'";
$resTodayUsers = mysqli_query($conn, $sqlTodayUsers);
$todayJoined = mysqli_fetch_assoc($resTodayUsers)['today_users'];



// ✅ Founder Club Achievers (64 direct members)
$sqlFounder = "
    SELECT COUNT(*) AS total_founders FROM (
        SELECT sponsor_id
        FROM users
        GROUP BY sponsor_id
        HAVING COUNT(*) >= 64
    ) AS founder_users
";
$resFounder = mysqli_query($conn, $sqlFounder);
$totalFounders = mysqli_fetch_assoc($resFounder)['total_founders'];

?>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h5>Total Users</h5>
                <h3 class="text-primary"><?= $totalUsers ?></h3>
                <a href="users.php" class="btn btn-sm btn-outline-primary mt-2">View Users</a>
            </div>
        </div>
    </div>
<div class="col-md-3 mb-4">
    <div class="card shadow-sm text-center border-primary">
        <div class="card-body">
            <h5>Today's Joined Users</h5>
            <h3 class="text-primary"><?= $todayJoined ?></h3>
            <a href="users.php?filter=today" class="btn btn-sm btn-outline-primary mt-2">
                View Today’s Users
            </a>
        </div>
    </div>
</div>


    <div class="col-md-3 mb-4">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h5>Total Videos</h5>
                <h3 class="text-info"><?= $totalVideos ?></h3>
                <a href="videos.php" class="btn btn-sm btn-outline-info mt-2">Manage Videos</a>
            </div>
        </div>
    </div>
<div class="col-md-3 mb-4">
    <div class="card shadow-sm text-center border-success">
        <div class="card-body">
            <h5>🏆 Founder Club Member</h5>
            <h3 class="text-success"><?= $totalFounders ?></h3>
            <a href="founder_club.php" class="btn btn-sm btn-outline-success mt-2">
                View Members
            </a>
        </div>
    </div>
</div>


<div class="col-md-3 mb-4">
    <div class="card shadow-sm text-center border-primary">
        <div class="card-body">
            <h5>💰 Founder Club Pool Income</h5>
            <h3 class="text-primary">₹<?= $founderPool ?></h3>
            <a href="founder_income.php" class="btn btn-sm btn-outline-primary mt-2">
                View Details
            </a>
        </div>
    </div>
</div>


    <div class="col-md-3 mb-4">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h5>Total Earnings</h5>
                <h3 class="text-success">₹<?= $totalEarnings ?></h3>
                <a href="earnings.php" class="btn btn-sm btn-outline-success mt-2">View All</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h5>Today's Earnings</h5>
                <h3 class="text-warning">₹<?= $todayEarnings ?></h3>
                <a href="earnings.php" class="btn btn-sm btn-outline-warning mt-2">View Details</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm p-3">
            <h5>Quick Actions</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><a href="videos.php">Upload / Manage Videos</a></li>
                <li class="list-group-item"><a href="users.php">View Users</a></li>
                <li class="list-group-item"><a href="watch_history.php">Watch History</a></li>
                <li class="list-group-item"><a href="earnings.php">All Earnings</a></li>
            </ul>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
