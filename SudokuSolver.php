<?php
    // LOADING PART
    require 'Sudoku.php';

    // initializing sudoku
    $mySudoku = new Sudoku;

    // loading sudoku from file
    $mySudoku->loadInfo('input/01.txt');

    // checking out if loaded sudoku is valid
    $isValidSudoku = $mySudoku->isValidSudoku();

    //var_dump($mySudoku);

    if ($isValidSudoku) {
        echo 'SUDOKU IS VALID' . PHP_EOL;
    } else { 
        echo 'SUDOKU IS NOT VALID' . PHP_EOL;
    }

?>