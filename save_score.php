<?php
$servername = "localhost";  // 데이터베이스 서버 주소
$username = "";         // 데이터베이스 사용자명
$password = "";             // 데이터베이스 비밀번호
$dbname = "";    // 데이터베이스 이름

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("연결 실패: " . $conn->connect_error);
}

// 사용자명과 WPM 데이터 받기
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST["username"];
    $wpm = intval($_POST["wpm"]);

    if (!empty($user) && $wpm > 0) {
        // 기존 사용자 점수가 존재하는지 확인
        $sql = "SELECT * FROM leaderboard WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // 기존 점수보다 높으면 업데이트
            $row = $result->fetch_assoc();
            if ($wpm > $row["wpm"]) {
                $update_sql = "UPDATE leaderboard SET wpm = ? WHERE username = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("is", $wpm, $user);
                $update_stmt->execute();
                $update_stmt->close();
            }
        } else {
            // 새로운 사용자 추가
            $insert_sql = "INSERT INTO leaderboard (username, wpm) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("si", $user, $wpm);
            $insert_stmt->execute();
            $insert_stmt->close();
        }
        $stmt->close();
    }
}

$conn->close();
?>
