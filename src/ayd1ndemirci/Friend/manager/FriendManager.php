<?php

namespace ayd1ndemirci\Friend\manager;

use ayd1ndemirci\Friend\Main;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

class FriendManager
{
    public function getPlayerFriends(string $playerName): array
    {
        $friends = [];

        foreach (Main::getInstance()->getSQLite()->getPlayer($playerName) as $data) {
            $friends[] = json_decode($data["friends"], true);
        }
        return $friends;
    }

    public function addFriendToPlayer(string $playerName, string $friendName): void
    {
        foreach (Main::getInstance()->getSQLite()->getPlayer($playerName) as $data) {
            $friends = json_decode($data["friends"], true);
            $friends[] = $friendName;
            Main::getInstance()->getSQLite()->updatePlayer($playerName, json_encode($friends));
        }
    }

    public function removeFriendToPlayer(string $playerName, string $friendName): void
    {
        foreach (Main::getInstance()->getSQLite()->getPlayer($playerName) as $data) {
            $friends = json_decode($data["friends"], true);
            unset($friends[array_search($friendName, $friends)]);
            Main::getInstance()->getSQLite()->updatePlayer($playerName, json_encode($friends));
        }
    }

    public function notePling(Player $player): void
    {
        $player->getNetworkSession()->sendDataPacket(PlaySoundPacket::create("note.pling", $player->getPosition()->getX(), $player->getPosition()->getY(), $player->getPosition()->getZ(), 1, 1));
    }
}