<?php

declare(strict_types=1);

namespace MINSEO\ServerWarp\Command;

use MINSEO\ServerWarp\Form\WarpForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;

final class WarpAdminCommand extends Command
{
    public function __construct()
    {
        parent::__construct('워프관리', '워프 관리 관련 명령어 입니다');
        $this->setPermission('warp.op');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender instanceof Player) return;
        if(!$this->testPermission($sender)) return;
        $sender->sendForm(new WarpForm());
    }
}