<?php

declare(strict_types=1);

namespace MINSEO\ServerWarp\Form;

use MINSEO\ServerWarp\ServerWarp;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\Server;

final class WarpListForm implements Form
{
    public function __construct()
    {
    }

    public function jsonSerialize(): array
    {
        $buttons = [];
        foreach(ServerWarp::getInstance()->getWarps() as $warp) {
            $buttons[] = ServerWarp::makeButton($warp->getName(), '클릭시 워프합니다');
        }
        return [
            'type' => 'form',
            'title' => '§8WARP',
            'content' => '워프를 선택해주세요',
            'buttons' => $buttons
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        if($data === null) return;
        if(ServerWarp::getInstance()->getWarp($data)->getPermissionBool()) {
            if(!Server::getInstance()->isOp($player->getName())) {
                $player->sendMessage(ServerWarp::ChatPrefix . ServerWarp::getInstance()->getWarp($data)->getName() . ' 해당 워프에 대한 권한이 없습니다');
                return;
            }
        }
        ServerWarp::getInstance()->getWarp($data)->teleport($player);
        $warpname = ServerWarp::getInstance()->getWarp($data)->getName();
        $player->sendMessage(ServerWarp::ChatPrefix . $warpname . ' (으)로 워프 했습니다');
    }
}