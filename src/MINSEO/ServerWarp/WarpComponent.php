<?php

declare(strict_types=1);

namespace MINSEO\ServerWarp;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;

final class WarpComponent
{
    private string $name;

    private string $permission;

    private string $position;

    private array $scripts;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->permission = $data['permission'];
        $this->scripts = $data['scripts'];
        $this->position = $data['position'];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPermission(): string
    {
        return $this->permission;
    }

    public function getPermissionBool(): bool
    {
        return $this->permission === 'warp.op';
    }

    public function getScripts(): array
    {
        return $this->scripts;
    }

    public function teleport(Player $player): void
    {
        $player->teleport($this->getPosition());
    }

    public function getPosition(): Position
    {
        $position = explode(':', $this->position);
        return new Position((float) $position[1], (float) $position[2], (float) $position[3], Server::getInstance()->getWorldManager()->getWorldByName($position[0]));
    }
}