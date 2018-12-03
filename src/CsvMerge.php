<?php

use Exceptions\FileIsEmptyException;
use Exceptions\InvalidFileException;
use Exceptions\NonExistOrInvalidPathException;

class CsvMerge
{
    const PATH = './public/files';

    private $newHeaders       = [];
    private $totalItemsInLine = 0;

    public function __invoke($path1, $path2)
    {
        $this->checkPaths($path1, $path2);

        $file1 = $this->openFile($path1);
        $file2 = $this->openFile($path2);

        $file1Header = $this->getHeaders($file1, $path1);
        $file2Header = $this->getHeaders($file2, $path2);

        $this->newHeaders       = $this->mergeHeaders($file1Header, $file2Header);
        $this->totalItemsInLine = count($this->newHeaders);

        $newPath = $this->getNewPath();
        $newFile = $this->createNewFile($newPath);

        $this->fillFromFile($newFile, $file1, $file1Header);
        $this->fillFromFile($newFile, $file2, $file2Header);

        fclose($newFile);

        return $newPath;
    }

    private function checkPaths($path1, $path2)
    {
        if (!file_exists($path1)) throw new NonExistOrInvalidPathException($path1);
        if (!file_exists($path2)) throw new NonExistOrInvalidPathException($path2);
    }

    private function openFile($path)
    {
        $file = fopen($path, 'r');

        if (!$file) throw new InvalidFileException($path);

        return $file;
    }

    private function getHeaders($file, $path)
    {
        if (feof($file)) throw new FileIsEmptyException($path);

        return fgetcsv($file, ',');
    }

    private function mergeHeaders($headers1, $headers2)
    {
        // Merge headers, remove duplicates and reset keys
        return array_values(array_unique(array_merge($headers1, $headers2)));
    }

    private function getNewPath()
    {
        $fileName = sprintf('merge_%s.csv', time());
        $path     = sprintf('%s/%s',
            self::PATH,
            $fileName
        );

        return $path;
    }

    private function createNewFile($newPath)
    {
        $file = fopen($newPath, 'w');

        fputcsv(
            $file,
            $this->newHeaders,
            ','
        );

        return $file;
    }

    private function getData($file, $headers)
    {
        $line     = array_fill(0, $this->totalItemsInLine, '');
        $fileLine = fgetcsv($file, ',');


        foreach ($this->newHeaders as $index => $value) {
            $indexInFileHeader = array_search($value, $headers);

            if ($indexInFileHeader !== false)
                $line[$index] = $fileLine[$indexInFileHeader];
        }

        return $line;
    }

    private function fillFromFile($file, $fromFile, $headers): void
    {
        while (!feof($fromFile)) {
            fputcsv(
                $file,
                $this->getData($fromFile, $headers),
                ','
            );
        }

        fclose($fromFile);
    }

}