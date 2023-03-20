<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class FileService
{
    public function createFile($res_array)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $stuff = $serializer->encode($res_array, 'csv');
        $filesystem = new Filesystem();
        $filesystem->dumpFile('report.csv', $stuff);

        return 'report.csv';
    }
}
