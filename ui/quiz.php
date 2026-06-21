<?php 
    $title =" BàiTest"; 
    define("year", 2026); 
    const KH = "Cơ bản"; 

    require __DIR__.'/../connectdb/db.php';

    $quiz_id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : 1;
    $sql = "SELECT * FROM questions WHERE quiz_id = $quiz_id";
    $msql = $pdo->query($sql);
    $question = $msql->fetchAll(); 
?> 
<!DOCTYPE html> 
<html> 
    <head> 
        <title>English Basic</title> 
    </head> 
    <body> 
        <h2>
            <?php 
            echo "<span style='color:green'>". $title. " ".year."</span>"; echo "<br>Khóa học: ".KH; 
            ?> 
        </h2> 
        <h3>Good Luck!</h3> 
        <form method ="post" action="http://localhost/OpenEnglish/backend/quizzes/submit_quiz.php"> 
            <?php 
                foreach ($question as $k => $v){ 
                    echo "<h3>Question".($k + 1).": ".$v['question_text']."</h3>"; 
                    $sqlAnswers = "SELECT * FROM answers WHERE question_id=".$v['id']; 
                    $msqlAnswers = $pdo->query($sqlAnswers); 
                    $answers = $msqlAnswers->fetchAll(); 
                    foreach ($answers as $a){ 
                        echo "<p>"; 
                        echo "<input type='radio' name='question[".$v['id']."]' value='".$a['id']."'> "; 
                        echo $a['answer_text']; echo "</p>"; 
                    } 
                }
            ?> 
            <button type="submit">Complete</button>
        </form> 
    </body> 
</html>
