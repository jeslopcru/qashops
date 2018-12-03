<?php

namespace Tests;

use ConvertXmlToCsv;
use PHPUnit\Framework\TestCase;
use function Lambdish\Phunctional\apply;

class ConvertXmlTest extends TestCase
{
    /** @test */
    public function it_should_convert_xml()
    {
        $file = './tests/src/example.xml';

        $service = new ConvertXmlToCsv();

        $newPath = apply(
            $service,
            [
                $file,
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
            ['name_header', 'description_header', 'image_1_header', 'colour_header', 'size_header', 'sleeve_length_header', 'sku_header', 'heel_height_header', 'laces_header'],
            $headers
        );

        $this->assertEquals(
            ['name', 'description', 'image_1', 'colour', 'size','sleeve_length','',''],
            $line1
        );

        $this->assertEquals(
            ['name', 'description', 'image_1', 'colour', '', '', 'sku','heel_height','laces'],
            $line2
        );

        $this->assertEquals(
            ['name', 'description', 'image_1', 'colour', 'size','sleeve_length','sku',''],
            $line3
        );

        $this->assertEquals(
            ['name', 'description', 'image_1', 'colour', 'size','sleeve_length','sku',''],
            $line4
        );
    }
}