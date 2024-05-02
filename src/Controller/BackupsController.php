<?php

namespace App\Controller;

use App\Service\BackupsService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BackupsController extends AbstractController
{
    #[Route('/admin/backup/create', name: 'admin_backup_create')]
    public function createBackup(BackupsService $backupsService): Response
    {
        $backupsService->createBackup();
        $this->addFlash('success','Backup created successfully.');
        return $this->redirectToRoute('adminpanel_adminpanel');
    }

    #[Route('/admin/backup/restore', name: 'admin_backup_restore')]
    public function restoreBackup(): Response
    {
        return new Response(sprintf('Backup restored  successfully.'));
    }
}
