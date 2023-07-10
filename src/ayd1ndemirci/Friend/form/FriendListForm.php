<?php

namespace ayd1ndemirci\Friend\form;

use ayd1ndemirci\Friend\Main;
use dktapps\pmforms\FormIcon;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class FriendListForm extends MenuForm
{

    /**
     * @param Player $player
     */
    public function __construct(\pocketmine\player\Player $player)
    {
        $options = [];
        foreach (Main::getInstance()->getManager()->getPlayerFriends($player->getName()) as $friend) {
            foreach ($friend as $friends) {
                $friendExact = Server::getInstance()->getPlayerExact($friends);
                $options[] = new MenuOption($friends, new FormIcon($friendExact instanceof Player ? "textures/ui/heart_new" : "textures/ui/heart_background", FormIcon::IMAGE_TYPE_PATH));
            }
        }
        parent::__construct(
            "Arkadaşların",
            "\n§7İşlem yapacağın arkadaşını seç",
            $options,
            function (Player $player, int $data): void
            {
                $text = TextFormat::clean($this->getOption($data)->getText());
                $player->sendForm(new SelectedFriendForm($text));
            }
        );
    }
}