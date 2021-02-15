<?php

namespace App\Repositories;

class FolderRepository
{
    private $years;
    private $months;
    private $days;

    public function __construct()
    {
        $this->years = [2018, 2019, 2020, 2021];
        $this->months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        foreach($this->months as $m) {
            $this->days[$m] = $this->daysPerMonth($m);
        }
    }
    /*
     * Опустим высокосные года (29 дней в феврале)
     * case 2
     */
    private function daysPerMonth($month) {
        switch ($month) {
            case 1: case 3: case 5: case 7: case 8: case 10: case 12:
                return 31;
                break;
            case 4: case 6 :case 9 :case 11:
                return 30;
                break;
            case 2:
                return 28;
                break;
        }
    }
    public function getYears(): array {
        return $this->years;
    }

    public function getMonths():array {
        return $this->months;
    }

    public function getDays(): array {
        return $this->days;
    }
}
