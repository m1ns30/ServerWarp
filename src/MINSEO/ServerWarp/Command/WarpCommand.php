<?php

declare(strict_types=1);

namespace MINSEO\ServerWarp\Command;

use MINSEO\ServerWarp\ServerWarp;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

final class WarpCommand extends Command
{
    public function __construct(private string $name, string $description, string $permission)
    {
        $this->setPermission($permission);
        $this->setPermissionMessage(ServerWarp::ChatPrefix . '해당 워프에 대한 권한이 없습니다');
        parent::__construct($this->name, $description);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if(!$sender instanceof Player) return;
        if(!$this->testPermission($sender)) return;
        if(ServerWarp::getInstance()->getWarpByName($this->name) === null) {
            $sender->sendTitle('ERROR WARP');
            return;
        }
        ServerWarp::getInstance()->getWarpByName($this->name)->teleport($sender);
        $sender->sendMessage(ServerWarp::ChatPrefix . $this->name . ' (으)로 워프 했습니다');
    }
}