<?php
require 'config/db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'], $_GET['video_id'])) {
    echo json_encode(["status"=>"error","message"=>"Unauthorized"]);
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$video_id = (int) $_GET['video_id'];

// ✅ Check if already rewarded
$stmt = $conn->prepare("SELECT id FROM video_views WHERE user_id=? AND video_id=?");
$stmt->bind_param("ii",$user_id,$video_id);
$stmt->execute();
$stmt->store_result();
if($stmt->num_rows > 0){
    echo json_encode(["status"=>"info","message"=>"✅ You already earned from this video."]);
    exit;
}
$stmt->close();

// ✅ Fetch reward amount
$stmt = $conn->prepare("SELECT earn_amount FROM videos WHERE id=?");
$stmt->bind_param("i",$video_id);
$stmt->execute();
$video = $stmt->get_result()->fetch_assoc();
$reward = isset($video['earn_amount']) ? (float)$video['earn_amount'] : 1.0;
$stmt->close();

// ✅ Insert reward transaction
$conn->begin_transaction();
try{
    // Insert into video_views
    $ins = $conn->prepare("INSERT INTO video_views(user_id,video_id) VALUES(?,?)");
    $ins->bind_param("ii",$user_id,$video_id);
    if(!$ins->execute()) throw new Exception("Insert video_views failed: ".$ins->error);
    $ins->close();

    // Update wallet
    $upd = $conn->prepare("UPDATE users SET wallet_balance=wallet_balance+? WHERE id=?");
    $upd->bind_param("di",$reward,$user_id);
    if(!$upd->execute()) throw new Exception("Update wallet failed: ".$upd->error);
    $upd->close();

    // Insert into earnings
    $earn = $conn->prepare("INSERT INTO earnings(user_id,type,amount,meta) VALUES(?,?,?,?)");
    $type = 'watch';
    $meta = 'Video ID: '.$video_id;
    $earn->bind_param("isds",$user_id,$type,$reward,$meta);
    if(!$earn->execute()) throw new Exception("Insert earnings failed: ".$earn->error);
    $earn->close();

    $conn->commit();
    echo json_encode(["status"=>"success","message"=>"🎉 You have successfully earned ₹$reward for watching this video."]);
}catch(Exception $e){
    $conn->rollback();
    echo json_encode(["status"=>"error","message"=>$e->getMessage()]);
}
