<?php

namespace ayd1ndemirci\Friend\form;

use ayd1ndemirci\Friend\Main;
use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Dropdown;
use dktapps\pmforms\element\Label;
use pocketmine\player\Player;
use pocketmine\Server;

class AddFriendForm extends CustomForm
{

    /**
     * @param Player $player
     */
    public array $playerList = [];

    public function __construct(\pocketmine\player\Player $player)
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            if ($player->getName() !== $onlinePlayer->getName()) {
                $this->playerList[] = $onlinePlayer->getName();
            }
        }
        sort($this->playerList);

        parent::__construct(
            "Arkadaş Ekleme Menüsü",
            [
                new Label("info", "\n§7Bu menüden sunucudaki oyunculara arkadaşlık isteği atabilirsin.\n"),
                new Dropdown("player", "\nArkadaşlık isteği göndereceğin oyuncuyu seç", $this->playerList),
                new Label("\n", "\n")
            ],
            function (Player $player, CustomFormResponse $response): void {
                $selectedPlayer = $this->playerList[$response->getInt("player")];

                if ($player->getName() === $selectedPlayer) {
                    $player->sendMessage("§8» §cKendine arkadaşlık isteği gönderemezsin.");
                    return;
                }

                $selectedPlayerExact = Server::getInstance()->getPlayerExact($selectedPlayer);

                if (!$selectedPlayerExact instanceof Player) {
                    $player->sendMessage("§8» §4§o{$selectedPlayer} §r§cadlı oyuncu oyundan ayrılmış.");
                    return;
                }
                $friends = [];
                foreach (Main::getInstance()->getSQLite()->getPlayer($player->getName()) as $data) {
                    $friend = json_decode($data["friends"], true);
                    foreach ($friend as $friendList) {
                        $friends[] = $friendList;
                    }
                }
                if (in_array($selectedPlayer, $friends)) {
                    $player->sendMessage("§8» §4§o{$selectedPlayer} §r§cadlı oyuncu zaten senin arkadaşın.");
                    return;
                }
                $player->sendMessage("§8» §2§o{$selectedPlayer} §r§aadlı oyuncuya arkadaşlık isteği gönderildi.");
                $selectedPlayerExact->sendForm(new FriendRequestForm($player->getName()));
                Main::getInstance()->getManager()->notePling($player);
            }
        );
    }
}