<?php

namespace ayd1ndemirci\Friend\form;

use ayd1ndemirci\Friend\Main;
use dktapps\pmforms\FormIcon;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use dktapps\pmforms\ModalForm;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\sound\EndermanTeleportSound;

class SelectedFriendForm extends MenuForm
{

    /**
     * @param string $text
     */
    public function __construct(string $text)
    {
        $options = [];
        $selectedFriend = Server::getInstance()->getPlayerExact($text);

        if ($selectedFriend instanceof Player) {
            $options = [
                new MenuOption("Arkadaşına Işınlan", new FormIcon("textures/items/ender_pearl", FormIcon::IMAGE_TYPE_PATH)),
                new MenuOption("Arkadaşlıktan Çıkar", new FormIcon("textures/ui/wither_effect", FormIcon::IMAGE_TYPE_PATH)),
                new MenuOption("Geri", new FormIcon("textures/ui/arrow_left", FormIcon::IMAGE_TYPE_PATH))
            ];
        }else {
            $options = [
                new MenuOption("Arkadaşlıktan Çıkar", new FormIcon("textures/ui/wither_effect", FormIcon::IMAGE_TYPE_PATH)),
                new MenuOption("Geri", new FormIcon("textures/ui/arrow_left", FormIcon::IMAGE_TYPE_PATH))
            ];
        }
        parent::__construct(
            $text." - Arkadaş İşlemleri",
            "",
            $options,
            function (Player $player, int $data) use ($text, $selectedFriend): void
            {
                $selected = TextFormat::clean($this->getOption($data)->getText());
                if ($selected === "Arkadaşına Işınlan") {
                    if ($selectedFriend instanceof Player) {
                        $player->teleport($selectedFriend->getPosition());
                        $player->sendMessage("§8» §2§o{$selectedFriend->getName()} §r§aadlı arkadaşına ışınlandın.");
                        $player->getWorld()->addSound($player->getPosition(), new EndermanTeleportSound(), [$player]);
                        $selectedFriend->sendMessage("§8» §2§o{$player->getName()} §r§aadlı arkadaşın sana ışınlandı.");
                    }else $player->sendMessage("§8» §4§o{$text} §r§cadlı arkadaşın oyundan ayrılmış.");
                }elseif ($selected === "Arkadaşlıktan Çıkar") {
                    $player->sendForm(new ModalForm(
                        $text." - Arkadaşlıktan Çıkarma",
                        "§c{$text} §7adlı arkadaşını arkadaşlıktan çıkarmayı onaylıyor musun?",
                        function (Player $player, bool $data) use ($text): void
                        {
                            if ($data) {
                                Main::getInstance()->getManager()->removeFriendToPlayer($player->getName(), $text);
                                Main::getInstance()->getManager()->removeFriendToPlayer($text, $player->getName());
                                $player->sendMessage("§8» §4§o{$text} §r§cadlı kişi arkadaş listenden çıkarıldı.");
                                Main::getInstance()->getManager()->notePling($player);
                                $text = Server::getInstance()->getPlayerExact($text);

                                if ($text instanceof Player) {
                                    $text->sendMessage("§8» §4§o{$player->getName()} §r§cadlı arkadaşın seni arkadaşlık listesinden sildi.");
                                }
                            }
                        }
                    ));
                }elseif ($selected === "Geri") $player->sendForm(new FriendListForm($player));
            }
        );
    }
}