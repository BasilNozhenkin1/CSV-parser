<?php

namespace App\Http\Controllers\v1\csv;

use App\Http\Controllers\Controller;
use App\Repositories\FolderRepository;
use Illuminate\Support\Facades\File;

class FolderManager extends Controller
{
    private $config;
    public function __construct(FolderRepository $config)
    {
        $this->config = $config;
    }

    public function initialize()
    {
        /*
         * Base directory
         */
        File::makeDirectory('import');
        /*
         * Initialize YY and MM directories
         */
        foreach ($this->config->getYears() as $year) {
            File::makeDirectory('import/'.$year);
            foreach ($this->config->getMonths() as $month) {
                File::makeDirectory('import/'.$year.'/'.$month);
            }
        }
        /*
         * Fill all MM folders with DD folders
         */
        foreach ($this->config->getYears() as $year) {
            foreach ($this->config->getDays() as $month => $days) {
                for ($day = 1; $day < $days + 1; $day += 1) {
                    $folder = 'import/' . $year . '/' . $month . '/' . $day;
                    File::makeDirectory($folder);
                }
            }
        }
    }
    public function delete()
    {
        File::deleteDirectory('import');
    }
}
