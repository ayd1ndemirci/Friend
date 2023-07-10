<?php

namespace ayd1ndemirci\Friend\command;

use ayd1ndemirci\Friend\form\FriendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class FriendCommand extends Command
{

    public function __construct()
    {
        parent::__construct("friend", "Arkadaş menüsü.");
        $this->setAliases(["arkadas"]);
        $this->setPermission("friend.permission");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§cSadece oyunda kullanabilirsin.");
            return;
        }
        $sender->sendForm(new FriendForm());
    }
}