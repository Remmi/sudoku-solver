<?php
/**
 * Sudoku class
 */
 class Sudoku {
    // contains symbol for indicating empty cells
    private const EMPTY_CELL = '.';

    // contains all elements
    private $field = array();

    // contains potential solves for all empty cells
    private $potentialSolves = array();

    // contains coordinates for cubes
    private $cubes = array(
        array(0, 0, 2, 2), // 1
        array(0, 3, 2, 5), // 2
        array(0, 6, 2, 8), // 3
        array(3, 0, 5, 2), // 4
        array(3, 3, 5, 5), // 5
        array(3, 6, 5, 8), // 6
        array(6, 0, 8, 2), // 7
        array(6, 3, 8, 5), // 8
        array(6, 6, 8, 8), // 9
    );

    // returns array [1..9] with zeros in values
    private function getHash() {
        return array(
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,
                9 => 0
            );
    }

    // checking if rows and cols are correct
    private function checkRowsCols() {
        for ($i = 0; $i < 9; $i++) {
            $hashRow = $this->getHash();
            $hashCol = $this->getHash();

            for ($j = 0; $j < 9; $j++) {
                $currValRow = $this->getFieldValue($i, $j);
                $currValCol = $this->getFieldValue($j, $i);

                if (!array_key_exists($currValRow, $hashRow) && ($currValRow <> $this::EMPTY_CELL)) {
                    echo 'BREAK AT I: ' . $i . '; J: ' . $j . PHP_EOL;
                    return false;
                } else {
                    if ($currValRow <> $this::EMPTY_CELL) {
                        $hashRow[$currValRow]++;

                        if ($hashRow[$currValRow] > 1) {
                            echo 'BREAK AT I: ' . $i . '; J: ' . $j . PHP_EOL;
                            return false;
                        }
                    }

                }

                if (!array_key_exists($currValCol, $hashCol) && ($currValCol <> $this::EMPTY_CELL)) {
                    echo 'BREAK AT I: ' . $i . '; J: ' . $j . PHP_EOL;
                    return false;
                } else {
                    if ($currValCol <> $this::EMPTY_CELL) {
                        $hashCol[$currValCol]++;

                        if ($hashCol[$currValCol] > 1) {
                            echo 'BREAK AT I: ' . $i . '; J: ' . $j . PHP_EOL;
                            return false;
                        } 
                    }
                }
                
            }

        }

        return true;
    }

    // checking if cubes are correct
    function checkCubes() {
        foreach ($this->cubes as $currCube) {
            $hashCube = $this->getHash();

            for ($i = $currCube[0]; $i <= $currCube[2]; $i++) {
                for ($j = $currCube[1]; $j <= $currCube[3]; $j++) {
                    $currVal = $this->getFieldValue($i, $j);
                    if (!array_key_exists($currVal, $hashCube) && ($currVal <> $this::EMPTY_CELL)) {
                        echo 'BREAK AT I: ' . $i . '; J: ' . $j . PHP_EOL;
                        return false;
                    } else {
                        if ($currVal <> $this::EMPTY_CELL) {
                            $hashCube[$currVal]++;

                            if ($hashCube[$currVal] > 1) {
                                echo 'BREAK AT I: ' . $i . '; J: ' . $j . PHP_EOL;
                                return false;
                            }
                        }
                
                    }
                }
            }

        }
        
        return true;
    }

    public function isValidSudoku() {
        $resCheckCubes = $this->checkCubes();
        $resCheckRowsCols = $this->checkRowsCols();    

        return ($resCheckCubes && $resCheckRowsCols);
    }

    // filling potential solves with all variants
    // for fields with '.' value
    private function fillPotentialSolves() {
        for ($i = 0; $i < 9; $i++) {
            for ($j = 0; $j < 9; $j++) {
                if ($this->getFieldValue($i, $j) == $this::EMPTY_CELL) {
                    $this->potentialSolves[$i][$j] = array(
                        1 => 1, 
                        2 => 1, 
                        3 => 1, 
                        4 => 1, 
                        5 => 1, 
                        6 => 1, 
                        7 => 1, 
                        8 => 1, 
                        9 => 1
                    );
                }
            }
        }
        return true;
    }

    // checking if sudoku is solved
    private function isSolved() {
        return (count($this->potentialSolves) == 0);
    }

    // get cube coordinates for cell with address [M,N]
    private function getCube($m, $n) {
        foreach ($this->cubes as $currCube) {
            if ($m >= $currCube[0] && 
                $m <= $currCube[2] && 
                $n >= $currCube[1] && 
                $n <= $currCube[3]) {
                return $currCube;
            }
        }

        return null;
    }

    // returns value of FIELD[x][y]
    // or returns null, if element doesn't exist
    public function getFieldValue($x, $y) {
        if (array_key_exists($x, $this->field) && array_key_exists($y, $this->field[$x])) {
            return $this->field[$x][$y];
        }
        return null;
    }

    // public function getFieldSolveValue($x, $y) {
    //     if (array_key_exists($x, $this->fieldSolve) && array_key_exists($y, $this->fieldSolve[$x])) {
    //         return $this->fieldSolve[$x][$y];
    //     }
    //     return null;
    // }

    // sets FIELD[X][Y] value
    public function setFieldValue($x, $y, $value) {
        if (array_key_exists($x, $this->field) && array_key_exists($y, $this->field[$x])) {
            $this->field[$x][$y] = $value;
            return true;
        }
        return false;
    }

    // public function setFieldSolveValue($x, $y, $value) {
    //     if (array_key_exists($x, $this->fieldSolve) && array_key_exists($y, $this->fieldSolve[$x])) {
    //         $this->fieldSolve[$x][$y] = $value;
    //         return true;
    //     }
    //     return false;
    // }

    // returns all field
    public function getField() {
        return $this->field;
    }

    // public function getFieldSolve() {
    //     return $this->fieldSolve;
    // }

    // setting potential solve's final value
    /**
    private function setPotentialSolve($x, $y, $value) {
        // at first, we clear the exact point PS[X][Y]
        if (array_key_exists($y, $this->potentialSolves[$x])) {
            unset($this->potentialSolves[$x][$y]);
        }
        
        // at second, we check if it was the last one in this row
        if (array_key_exists($x, $this->potentialSolves)) {
            // if it's so, clear all row PS[X]
            if (count($this->potentialSolves[$x]) == 0) {
                unset($this->potentialSolves[$x]);
            }
        } elseif (!is_null($value)) {
            // otherwise, we go through all PS[X] row 
            foreach ($this->potentialSolves[$x] as $key => $val) {
                
                // and remove this value from all solves (if they have it)
                $currPos = array_search($value, $val);

                if ($currPos !== false) {
                    unset($this->potentialSolves[$m][$key][$currPos]);
                }
            }

            // then we go through all PS[0..8][Y] col values
            // except PS[X][Y], cause we're already checked it
            foreach ($this->potentialSolves as $key => $val) {
                if ($key <> $x) {
                    if (array_key_exists($y, $this->potentialSolves[$key])) {
                        $currPos = array_search($value, $this->potentialSolves[$key][$y]);
                        if ($currPos !== false) {
                            unset($this->potentialSolves[$key][$y][$currPos]);    
                        }
                    }

                    if (count($this->potentialSolves[$key]) == 0) {
                        unset($this->potentialSolves[$key]);
                    }
                }
            }

            // and finally we check all cube values
            // array(a1, b1, a2, b2)
            $currCube = $this->getCube($x, $y);
            for ($i = $currCube[0]; $i <= $currCube[2]; $i++) {
                if (array_key_exists($i, $this->potentialSolves)) {

                    for ($j = $currCube[1]; $j <= $currCube[3]; $j++) {

                        if (array_key_exists($j, $this->potentialSolves[$i])) {
                            $currPos = array_search($value, $this->potentialSolves[$i][$j]);
                            if ($currPos !== false) {
                            unset($this->potentialSolves[$i][$j]);
                            }
                        }
                        
                    }

                    if (count($this->potentialSolves[$i]) == 0) {
                        unset($this->potentialSolves[$i]);
                    }
                }
            }

        } else {
            // do nothing
        }

        //

        return true;
    }
    */

    private function putSolve($m, $n, $value) {
        $bRes1 = $this->setFieldValue($m, $n, $value);
        $bRes2 = $this->setPotentialSolve($m, $n, $value);
        return ($bRes1 && $bRes2);
    }

    // trying to solve sudoku
    public function makeSolution() {
        if ($this->isValidSudoku()) {
            
            return true;
        }

        return false;
    }

    // loading sudoku data from $filepath
    public function loadInfo($filepath) {

        // we can load only existing file
        if (file_exists($filepath)) {

            // reading stuff and put it into array
            $input = json_decode(
                file_get_contents($filepath)
            );

            // putting array into sudoku
            foreach ($input as $x => $xval) {
                foreach ($xval as $y => $yval) {
                    if ($yval <> $this::EMPTY_CELL) {
                        $yval = (int)$yval;
                    }
        
                    $this->setFieldValue((int)$x, (int)$y, $yval);
                }
            }

            
            // initializing potential solves
            $this->fillPotentialSolves();
        } else {
            echo 'FILE NOT FOUND IN PATH: ' . $filepath . PHP_EOL;
        }
    }

    // initializing sudoku class
    public function __construct() {
        // initializing field
        // we're not using setField because it won't work at this stage
        // (cause arrays don't exist in this moment)
        for ($i = 0; $i <= 8; $i++) {
            if (!array_key_exists($i, $this->field)) {
                $this->field[$i] = array();
                // $this->fieldSolve[$i] = array();
            }
            for ($j = 0; $j <= 8; $j++) {
                $this->field[$i][$j] = null;
                // $this->fieldSolve[$i][$j] = null;
            }
        }
    }

 }
 ?>