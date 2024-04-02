<?php

declare(strict_types=1);

namespace MINSEO\ServerWarp\Form;

use MINSEO\ServerWarp\ServerWarp;
use pocketmine\form\Form;
use pocketmine\player\Player;

final class WarpForm implements Form
{
    public function jsonSerialize(): array
    {
        return [
            'type' => 'form',
            'title' => '§8WARP',
            'content' => '기능을 선택해주세요',
            'buttons' => [
                ServerWarp::makeButton('워프 추가 하기', '서있는 위치에 워프를 추가 합니다'),
                ServerWarp::makeButton('워프 삭제 하기', '워프를 삭제 합니다'),
                ServerWarp::makeButton('워프 권한 수정', '워프를 삭제 합니다'),
            ]
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null) return;

        match ($data) {
            0 => $player->sendForm(new WarpCreateForm()),
            1 => $player->sendForm(new WarpRemoveForm()),
            2 => $player->sendForm(new WarpPermissionListForm())
        };
    }
}