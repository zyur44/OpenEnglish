<?php
    require __DIR__.'/../../connectdb/db.php';  
    $grade = 0; 
    if(isset($_POST['question'])) { 
        foreach($_POST['question'] as $question_id=>$answer_id) { 
            $sql = "SELECT * FROM answers WHERE id=$answer_id AND is_correct=1"; 
            $msql = $pdo->query($sql); 
            if($msql->rowCount()>0) { 
                $grade++; 
                } 
            }
        } 
    echo "Chúc mừng bạn đã hoàn thành bài kiểm tra!"; 
    echo "<br>Số câu đúng: ".$grade; 
    $msql=$pdo->query("SELECT COUNT(*) AS total FROM questions"); 
    $total=$msql->fetch(); 
    $result=$grade/$total['total']*10; 
    echo "<br>Điểm của bạn là: ".round($result,2);
?>
