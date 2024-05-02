<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
class  BackupsService
{
    private $backupFolder;
    private $databaseName;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->backupFolder = $parameterBag->get('app.backupsdir');
        $this->databaseName = $parameterBag->get('app.databasename');
    }

    public function createBackup(): void
    {
        if (!is_dir($this->backupFolder)) {
            mkdir($this->backupFolder, 0777, true);
        }

        $timestamp = date('Y-m-d_H-i-s');
        $backupFilename = sprintf('%s_%s.sql.gz', $this->databaseName, $timestamp);
        $backupPath = $this->backupFolder . '/' . $backupFilename;
        //it will be not completed till app is not on server, there is much better way to create and upload dump to our database
        //somethimes there are problems on windows and you can recive dump with 0Kb size. I prefer using DBeaver
    }
}
