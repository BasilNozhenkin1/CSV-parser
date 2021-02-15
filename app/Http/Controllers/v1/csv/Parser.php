<?php

namespace App\Http\Controllers\v1\csv;

use App\Http\Controllers\Controller;
use App\Http\Controllers\v1\csv\Logger;
use App\Http\Controllers\v1\csv\FolderManager;
use App\Repositories\FolderRepository;
use Illuminate\Support\Facades\File;
use App\Models\Import;

class Parser extends Controller
{
    private $config;
    public function __construct(FolderRepository $config)
    {
        $this->config = $config;
    }
    public function run() {
        Logger::log('Начинаем работу');
        foreach ($this->config->getYears() as $year) {
            foreach ($this->config->getDays() as $month => $days) {
                for ($day = 1; $day < $days + 1; $day += 1) {
                    $folder = 'import/' . $year . '/' . $month . '/' . $day;
                    $files =  File::files($folder);
                    foreach ($files as $file) {
                        $fileHandled = $this->openFile($file);
                        $fileRows = $this->extractDataFromFile($fileHandled);
                        $this->closeFile($fileHandled);
                    }
                }
            }
        }
        Logger::log('Заканчиваем работу');
        return \App::call('App\Http\Controllers\v1\csv\FolderManager@delete');
    }
    private function openFile($path) {
        Logger::log('Пробуем открыть файл по пути '.$path);
        try {
            $fileHandled = fopen($path, 'r');
            Logger::log('Файл по пути '.$path.' удалось открыть');
            return $fileHandled;
        } catch (Exception $e) {
            Logger::log('Файл по пути '.$path.' не удалось открыть');
        }
    }
    private function extractDataFromFile($fileHandle) {
        Logger::log('Из файла  '.$fileHandle.' пробуем получить данные');
        $header = true;
        $rows = array();
        while ($row = fgetcsv($fileHandle, 10000, ";")) {
            if ($header)
            {
                $header = false;
            }
            else
            {
                $currentRow = ['registration_number' => $row[0],
                                'name' => $row[1],
                                'url' => $row[2],
                                'phone' => $row[3],
                                'email' => $row[4]];
                $validated = $this->validateData($currentRow);
                if ($validated) {
                    Import::create($currentRow);
                }
            }
        }
        Logger::log('Из файла  '.$fileHandle.' удалось получить данные');
        return $rows;
    }
    private function validateData($row) {
        Logger::log('Начинаем валидацию');
        if (!is_numeric ($row['registration_number'])) {
            Logger::log('Валидация прошла неудачно: Рег. номер не является числом');
            return false;
        }
        if (strlen($row['name']) < 3 && strlen($row['name']) > 50) {
            Logger::log('Валидация прошла неудачно: Ошибка в имени');
            return false;
        }
        if (filter_var($row['url'], FILTER_VALIDATE_URL) === FALSE) {
            Logger::log('Валидация прошла неудачно: URL ошибка');
            return false;
        }
        if(!preg_match("/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/", $row['phone'])) {
            Logger::log('Валидация прошла неудачно: Ошибка в номере телефона');
            return false;
        }
        if (filter_var($row['email'], FILTER_VALIDATE_EMAIL) === FALSE) {
            Logger::log('Валидация прошла неудачно: Ошибка в email');
            return false;
        }
        Logger::log('Валидация прошла успешно');
        return true;
    }
    private function closeFile($fileHandle) {
        Logger::log('Начинаем закрытие файла');
        fclose($fileHandle);
        Logger::log('Файл закрыт');
    }
}
