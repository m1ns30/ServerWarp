<?php

declare(strict_types=1);

namespace MINSEO\ServerWarp;

use MINSEO\ServerWarp\WarpComponent;
use MINSEO\ServerWarp\Command\WarpAdminCommand;
use MINSEO\ServerWarp\Command\WarpCommand;
use MINSEO\ServerWarp\Command\WarpListCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\world\Position;

final class ServerWarp extends PluginBase
{
    public const ChatPrefix = '§l§b[워프] §r§7';
    private static ?self $instance = null;
    private Config $data;
    private array $db;

    public static function getInstance(): self
    {
        return self::$instance;
    }

    public function createWarp(string $warp, string $permission, string $position): bool
    {
        foreach($this->db as $item => $value) {
            if($value['name'] === $warp) {
                return false;
            }
        }
        $this->db[] = [
            'name' => $warp,
            'permission' => $permission,
            'position' => $position,
            'scripts' => []
        ];
        return true;
    }

    public function removeWarp(int $index): void
    {
        unset($this->db[$index]);
        $this->db = array_values($this->db);
    }

    public function getWarp(int $index): WarpComponent
    {
        return new WarpComponent($this->db[$index]);
    }

    public function getWarpByName(string $name): ?WarpComponent
    {
        foreach($this->db as $item => $value) {
            if($value['name'] === $name) {
                return new WarpComponent($value);
            }
        }
        return null;
    }

    public function setWarpPermission(string $name, string $permission): void
    {
        foreach($this->db as $item => $value) {
            if($value['name'] === $name) {
                $command = $this->getServer()->getCommandMap()->getCommand($name);
                $this->getServer()->getCommandMap()->unregister($command);
                $this->getServer()->getCommandMap()->register('warp', new WarpCommand($name, $name.'(으)로 워프합니다', $permission));
                $this->db[$item]['permission'] = $permission;
                return;
            }
        }
    }

    public function positionHash(Position $pos): string
    {
        return $pos->getWorld()->getFolderName().':'.$pos->getX().':'.$pos->getY().':'.$pos->getZ();
    }

    protected function onLoad(): void
    {
        self::$instance = $this;
    }

    protected function onEnable(): void
    {
        $this->data = new Config($this->getDataFolder().'data.json', Config::JSON);
        $this->db = $this->data->getAll();
        $this->getServer()->getCommandMap()->register('warpManage', new WarpAdminCommand());
        $this->getServer()->getCommandMap()->register('warpList', new WarpListCommand());
        foreach($this->getWarps() as $warp) {
            $this->getServer()->getCommandMap()->register('warp', new WarpCommand($warp->getName(), $warp->getName().'(으)로 워프합니다', $warp->getPermission()));
        }
    }

    public static function makeButton(string $title, string $subtitle): array
    {
        return ['text' => "§l$title\n§r§8▶ $subtitle §r§8◀"];
    }

    public function getWarps(): array
    {
        $warps = [];
        foreach($this->db as $warp) {
            $warps[] = new WarpComponent($warp);
        }
        return $warps;
    }

    protected function onDisable(): void
    {
        $this->data->setAll($this->db);
        $this->data->save();
    }
}