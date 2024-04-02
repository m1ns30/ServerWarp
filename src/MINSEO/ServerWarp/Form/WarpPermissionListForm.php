<?php

declare(strict_types=1);

namespace MINSEO\ServerWarp\Form;

use MINSEO\ServerWarp\ServerWarp;
use pocketmine\form\Form;
use pocketmine\player\Player;

final class WarpPermissionListForm implements Form
{
    public function jsonSerialize(): array
    {
        $buttons = [];
        foreach(ServerWarp::getInstance()->getWarps() as $warp) {
            $buttons[] = ServerWarp::makeButton($warp->getName(), '클릭시 권한 변경');
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
        $player->sendForm(new WarpPermissionForm($data));
    }
}