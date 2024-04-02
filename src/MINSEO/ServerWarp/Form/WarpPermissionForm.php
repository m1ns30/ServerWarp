<?php

declare(strict_types=1);

namespace MINSEO\ServerWarp\Form;

use MINSEO\ServerWarp\ServerWarp;
use pocketmine\form\Form;
use pocketmine\player\Player;

final class WarpPermissionForm implements Form
{
    public function __construct(private int $name)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => 'custom_form',
            'title' => '§8WARP',
            'content' => [
                [
                    'type' => 'toggle',
                    'text' => '관리자 전용 권한으로 변경 하시겠습니까?',
                    'default' => ServerWarp::getInstance()->getWarp($this->name)->getPermissionBool()
                ]
            ],
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        $warp = ServerWarp::getInstance()->getWarp($this->name);
        if ($data === null) return;

        match ($data[0]) {
            true => $permission = 'warp.op',
            false => $permission = 'warp.user'
        };
        ServerWarp::getInstance()->setWarpPermission($warp->getName(), $permission);
        $player->sendMessage(ServerWarp::ChatPrefix . '해당 워프 권한을 ' . ($data[0] ? '관리자 전용' : '유저 전용') . ' 권한으로 변경 하였습니다.');
    }
}