<?php

declare(strict_types=1);

namespace MINSEO\ServerWarp\Form;

use MINSEO\ServerWarp\Command\WarpCommand;
use MINSEO\ServerWarp\ServerWarp;
use pocketmine\form\Form;
use pocketmine\player\Player;

final class WarpCreateForm implements Form
{
    public function jsonSerialize(): array
    {
        return [
            'type' => 'custom_form',
            'title' => '§8WARP',
            'content' => [
                [
                    'type' => 'input',
                    'text' => '워프 이름을 입력해주세요'
                ],
                [
                    'type' => 'label',
                    'text' => '관리자 권한으로 만드시겠습니까?'
                ],
                [
                    'type' => 'toggle',
                    'text' => '',
                    'default' => false,
                ],
            ]
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        if($data === null) return;
        $name = trim($data[0]);
        if($name === '') {
            $player->sendMessage(ServerWarp::ChatPrefix . '워프 이름을 입력해주세요');
            return;
        }
        $permission = $data[2] ? 'warp.op' : 'warp.user';
        if(!ServerWarp::getInstance()->createWarp($name, $permission, ServerWarp::getInstance()->positionHash($player->getPosition()))) {
            $player->sendMessage(ServerWarp::ChatPrefix . '이미 해당 워프가 있습니다');
            return;
        }
        $player->getServer()->getCommandMap()->register('warp', new WarpCommand($name, $name.'(으)로 워프합니다', $permission));
        $player->sendMessage(ServerWarp::ChatPrefix . '워프를 만들었습니다');
    }
}