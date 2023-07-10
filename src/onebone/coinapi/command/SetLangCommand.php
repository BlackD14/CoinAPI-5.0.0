<?php

namespace onebone\coinapi\command;

use pocketmine\command\Command;
use pocketmine\Command\CommandSender;
use pocketmine\utils\TextFormat;

use onebone\coinapi\CoinAPI;

class SetLangCommand extends Command{
	private $plugin;

	public function __construct(CoinAPI $plugin){
		$desc = $plugin->getCommandMessage("setlangcoin");
		parent::__construct("setlangcoin", $desc["description"], $desc["usage"]);

		$this->setPermission("coinapi.command.setlangcoin");

		$this->plugin = $plugin;
	}

	public function execute(CommandSender $sender, string $label, array $params): bool{
		if(!$this->plugin->isEnabled()) return false;
		if(!$this->testPermission($sender)){
			return false;
		}

		$lang = array_shift($params);
		if($lang === null || trim($lang) === ""){
			$sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
			return true;
		}

		if($this->plugin->setPlayerLanguage($sender->getName(), $lang)){
			$sender->sendMessage($this->plugin->getMessage("language-set", [$lang], $sender->getName()));
		}else{
			$sender->sendMessage(TextFormat::RED . "There is no language such as $lang");
		}
		return true;
	}
}
