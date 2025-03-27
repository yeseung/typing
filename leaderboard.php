<?php
$servername = "localhost";  // 데이터베이스 서버 주소
$username = "";         // 데이터베이스 사용자명
$password = "";             // 데이터베이스 비밀번호
$dbname = "";    // 데이터베이스 이름  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("연결 실패: " . $conn->connect_error);
}

// 상위 10명의 기록을 가져옴
$sql = "SELECT username, wpm FROM leaderboard ORDER BY wpm DESC LIMIT 10";
$result = $conn->query($sql);

$leaderboard = array();
while ($row = $result->fetch_assoc()) {
    $leaderboard[] = $row;
}

echo json_encode($leaderboard);

$conn->close();
?>
