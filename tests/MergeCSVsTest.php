<?php

namespace Tests;

use CsvMerge;
use PHPUnit\Framework\TestCase;
use function Lambdish\Phunctional\apply;

class MergeCSVsTest extends TestCase
{
    /** @test */
    public function it_should_merge_two_fields()
    {
        $file1 = './tests/src/file1.csv';
        $file2 = './tests/src/file2.csv';

        $service = new CsvMerge();

        $newPath = apply(
            $service,
            [
                $file1,
                $file2,
            ]
        );

        $newFile = fopen($newPath, 'r');

        $headers = fgetcsv($newFile, ',');
        $line1   = fgetcsv($newFile, ',');
        $line2   = fgetcsv($newFile, ',');
        $line3   = fgetcsv($newFile, ',');
        $line4   = fgetcsv($newFile, ',');

        fclose($newFile);

        $this->assertEquals(
            ['Header_1', 'Header_2', 'Header_3', 'Header_4', 'Header_5', 'Header_6', 'Header_7'],
            $headers
        );

        $this->assertEquals(
            [1, 2, 3, 4, '', '', ''],
            $line1
        );

        $this->assertEquals(
            [4, 5, 6, 7, '', '', ''],
            $line2
        );

        $this->assertEquals(
            ['', 9, '', '', 8, 10, 11],
            $line3
        );

        $this->assertEquals(
            ['', 13, '', '', 12, 14, 15],
            $line4
        );
    }
}