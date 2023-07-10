<?php

namespace ayd1ndemirci\Friend;

use ayd1ndemirci\Friend\command\FriendCommand;
use ayd1ndemirci\Friend\listener\PlayerListener;
use ayd1ndemirci\Friend\manager\FriendManager;
use ayd1ndemirci\Friend\provider\SQLite;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{
    public static Main $main;

    private SQLite $sqlite;

    private FriendManager $manager;

    public function onLoad(): void
    {
        self::$main = $this;
        $this->sqlite = new SQLite();
        $this->manager = new FriendManager();
    }

    public function onEnable(): void
    {
        $this->getLogger()->info("Friend actived - @ayd1ndemirci");

        $this->getServer()->getCommandMap()->register("friend", new FriendCommand());

        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);
    }

    public function getSQLite(): SQLite
    {
        return $this->sqlite;
    }

    public function getManager(): FriendManager
    {
        return $this->manager;
    }

    public static function getInstance(): self
    {
        return self::$main;
    }
}