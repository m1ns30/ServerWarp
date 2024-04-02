<?php

declare(strict_types=1);

namespace MINSEO\ServerWarp\Command;

use MINSEO\ServerWarp\Form\WarpListForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;

final class WarpListCommand extends Command
{
    public function __construct()
    {
        parent::__construct('워프', '워프 목록 명령어 입니다');
        $this->setPermission('warp.user');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if(!$sender instanceof Player) return;
        if(!$this->testPermission($sender)) return;
        $sender->sendForm(new WarpListForm());
    }
}