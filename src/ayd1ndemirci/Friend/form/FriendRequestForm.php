<?php

namespace ayd1ndemirci\Friend\form;

use ayd1ndemirci\Friend\Main;
use dktapps\pmforms\ModalForm;
use pocketmine\player\Player;
use pocketmine\Server;

class FriendRequestForm extends ModalForm
{
    public function __construct(string $playerName)
    {
        parent::__construct(
            $playerName." - Arkadaşlık İsteği",
            "§e{$playerName} §7adlı oyuncu sana bir arkadaşlık isteği gönderdi, kabul etmeyi onaylıyor musun?",
            function (Player $player, bool $data) use ($playerName): void
            {
                if ($data) {
                    $player->sendMessage("§8» §2§o{$playerName} §r§aadlı oyuncunun arkadaşlık isteğini kabul ettin.");
                    Main::getInstance()->getManager()->notePling($player);
                    Main::getInstance()->getManager()->addFriendToPlayer($player->getName(), $playerName);
                    Main::getInstance()->getManager()->addFriendToPlayer($playerName, $player->getName());
                    $playerName = Server::getInstance()->getPlayerExact($playerName);
                    if ($playerName instanceof Player) {
                        $playerName->sendMessage("§8» §2§o{$player->getName()} §r§aadlı oyuncu gönderdiğin arkadaşlık isteğini kabul etti.");
                        Main::getInstance()->getManager()->notePling($playerName);
                    }
                }else {
                    $player->sendMessage("§8» §2§o{$playerName} §r§aadlı oyuncunun isteği reddedildi");
                    Main::getInstance()->getManager()->notePling($player);
                    $playerName = Server::getInstance()->getPlayerExact($playerName);
                    if ($playerName instanceof Player) {
                        $playerName->sendMessage("§8» §4§o{$player->getName()} §r§cadlı oyuncu gönderdiğin arkadaşlık isteğini reddetti.");
                    }
                }
            }
        );
    }
}