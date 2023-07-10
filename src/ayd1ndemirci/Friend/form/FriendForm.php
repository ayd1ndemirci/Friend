<?php

namespace ayd1ndemirci\Friend\form;

use ayd1ndemirci\Friend\Main;
use dktapps\pmforms\FormIcon;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\player\Player;
use pocketmine\Server;

class FriendForm extends MenuForm
{

    public function __construct()
    {
        parent::__construct(
            "Arkadaş Menüsü",
            "",
            [
                new MenuOption("Arkadaş Ekle", new FormIcon("textures/ui/dressing_room_customization", FormIcon::IMAGE_TYPE_PATH)),
                new MenuOption("Arkadaşların", new FormIcon("textures/ui/FriendsIcon", FormIcon::IMAGE_TYPE_PATH))
            ],
            function (Player $player, int $data) : void
            {
                if ($data === 0) {
                    if (count(Server::getInstance()->getOnlinePlayers()) > 1) {
                        $player->sendForm(new AddFriendForm($player));
                        Main::getInstance()->getManager()->notePling($player);
                    }else $player->sendMessage("§8» §cSunucuda sadece sen varsın.");
                }elseif ($data === 1) {
                    $player->sendForm(new FriendListForm($player));
                }
            }
        );
    }
}