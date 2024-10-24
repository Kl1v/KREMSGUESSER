<?php
if (isset($_POST['name']) && isset($_POST['score'])) {
    $name = htmlspecialchars($_POST['name']);
    $score = (int)$_POST['score'];

    $file = fopen('scores.csv', 'a');
    fputcsv($file, [$name, $score]);
    fclose($file);

    echo 'Score saved successfully';
} else {
    echo 'Invalid data';
}
?>