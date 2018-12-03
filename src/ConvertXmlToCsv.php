<?php

class ConvertXmlToCsv
{
    const PATH = './public/files';

    function __invoke($path)
    {
        $this->checkPath($path);

        $xml = simplexml_load_file($path);

        $newPath = $this->getNewPath();
        $newFile   = fopen($newPath, 'w+');
        $this->setHeaders($xml, $newFile);
        $this->fillCsv($xml, $newFile);

        fclose($newFile);

        return $newPath;
    }
}