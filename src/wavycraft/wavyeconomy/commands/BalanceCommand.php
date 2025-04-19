<?php

declare(strict_types=1);

namespace wavycraft\wavyeconomy\commands;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use pocketmine\utils\Config;

use wavycraft\wavyeconomy\WavyEconomy;
use wavycraft\wavyeconomy\api\WavyEconomyAPI;

use terpz710\messages\Messages;

use CortexPE\Commando\BaseCommand;

class BalanceCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission("wavyeconomy.balance");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        $config = new Config(WavyEconomy::getInstance()->getDataFolder() . "messages.yml");

        if ($sender instanceof Player) {
            $balance = WavyEconomyAPI::getInstance()->getBalance($sender);
            $sender->sendMessage((string) new Messages($config, "balance-message", ["{balance}"], [number_format($balance)]));
            return;
        }

        $sender->sendMessage("This command can only be used in-game!");
    }
}