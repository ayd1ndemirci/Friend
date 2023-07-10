<?php

namespace ayd1ndemirci\Friend\listener;

use ayd1ndemirci\Friend\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class PlayerListener implements Listener
{

    public function onJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();

        if (empty(Main::getInstance()->getSQLite()->getPlayer($player->getName()))) {
            Main::getInstance()->getSQLite()->addPlayer($player->getName(), "{}");
        }
    }
}