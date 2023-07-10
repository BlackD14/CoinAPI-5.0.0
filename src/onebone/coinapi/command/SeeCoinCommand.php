<?php

namespace onebone\coinapi\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;
use pocketmine\Server;

use onebone\coinapi\CoinAPI;

class SeeCoinCommand extends Command{
    private $plugin;

    public function __construct(CoinAPI $plugin){
        $desc = $plugin->getCommandMessage("seecoin");
        parent::__construct("seecoin", $desc["description"], $desc["usage"]);

        $this->setPermission("coinapi.command.seecoin");

        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $label, array $params): bool{
        if(!$this->plugin->isEnabled()) return false;
        if(!$this->testPermission($sender)){
            return false;
        }

        $player = array_shift($params);
        if($player === null || trim($player) === ""){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
            return true;
        }

        if(($p = $this->plugin->getServer()->getPlayerByPrefix($player)) instanceof Player){
            $player = $p->getName();
        }

        $coin = $this->plugin->myCoin($player);
        if($coin !== false){
            $sender->sendMessage($this->plugin->getMessage("seecoin-seecoin", [$player, $coin], $sender->getName()));
        }else{
            $sender->sendMessage($this->plugin->getMessage("player-never-connected", [$player], $sender->getName()));
        }
        return true;
    }
}
