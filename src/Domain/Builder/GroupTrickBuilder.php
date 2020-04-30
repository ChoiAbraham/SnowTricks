<?php

namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\GroupTrickBuilderInterface;
use App\Domain\Entity\GroupTrick;

class GroupTrickBuilder implements GroupTrickBuilderInterface
{
    /**
     * @var GroupTrick
     */
    private $groupTrick;

    /**
     * @return GroupTrickBuilder
     */
    public function create(string $groupNumber): self
    {
        $this->groupTrick = new GroupTrick();

        return $this;
    }

    /**
     * @return GroupTrick
     */
    public function getGroupTrick(): GroupTrick
    {
        return $this->groupTrick;
    }
}
