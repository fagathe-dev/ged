<?php
namespace App\Service;

use App\Repository\FolderRepository;

final class FolderService
{

    public function __construct(
        private FolderRepository $repository
    ) {
    }

}