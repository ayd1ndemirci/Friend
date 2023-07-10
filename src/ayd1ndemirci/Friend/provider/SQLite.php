<?php

namespace ayd1ndemirci\Friend\provider;

use ayd1ndemirci\Friend\Main;

class SQLite
{
    public $database;

    public function __construct()
    {
        $this->database = new \SQLite3(Main::getInstance()->getDataFolder()."friend.db");
        $this->database->exec("CREATE TABLE IF NOT EXISTS friend(playerName VARCHAR(20), friends Text)");
    }

    public function addPlayer(string $playerName, string $friends = "{}"): void
    {
        $data = $this->database->prepare("INSERT INTO friend(playerName, friends) VALUES (:playerName, :friends)");
        $data->bindParam(":playerName", $playerName);
        $data->bindParam(":friends", $friends);
        $data->execute();
    }

    public function removePlayer(string $playerName): void
    {
        $data = $this->database->prepare("DELETE FROM friend WHERE playerName = :playerName");
        $data->bindParam(":playerName", $playerName);
        $data->execute();
    }

    public function updatePlayer(string $playerName, string $friends): void
    {
        $this->removePlayer($playerName);
        $this->addPlayer($playerName, $friends);
    }

    public function getPlayer(string $playerName): array
    {
        $result = [];

        $data = $this->database->prepare("SELECT * FROM friend WHERE playerName = :playerName");
        $data->bindParam(":playerName", $playerName);
        $control = $data->execute();

        while ($row = $control->fetchArray(SQLITE3_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }
}