<?php
namespace App\Service;

use App\Repository\TeamRepository;

class TeamService{
    private $repository;

    public function __construct(TeamRepository $repository)
    {
        $this->repository = $repository;
    }
    public function getTeam(): array{
        return $this->repository->findAll();
    }
}
