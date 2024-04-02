<?php

declare(strict_types=1);

namespace MINSEO\ServerWarp\Form;

use MINSEO\ServerWarp\ServerWarp;
use pocketmine\form\Form;
use pocketmine\player\Player;

final class WarpRemoveForm implements Form
{
    public function jsonSerialize(): array
    {
        $buttons = [];
        foreach(ServerWarp::getInstance()->getWarps() as $warp) {
            $buttons[] = ServerWarp::makeButton($warp->getName(), '삭제하시고 싶은 워프를 선택하세요.');
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
        ServerWarp::getInstance()->removeWarp($data);
        $player->sendMessage(ServerWarp::ChatPrefix . '해당 워프를 정상적으로 삭제 하였습니다.');
    }
}