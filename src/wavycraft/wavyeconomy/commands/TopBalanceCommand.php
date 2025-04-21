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

class TopBalanceCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission("wavyeconomy.topbalance");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        $config = new Config(WavyEconomy::getInstance()->getDataFolder() . "messages.yml");
        
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game!");
            return;
        }

        $balances = WavyEconomyAPI::getInstance()->getAllBalances();

        arsort($balances);

        $sender->sendMessage((string) new Messages($config, "top-balance-message-1"));
        $i = 1;
        foreach ($balances as $player => $balance) {
            $sender->sendMessage((string) new Messages($config, "top-balance-message-2", ["{position}", "{name}", "{balance}"], [$i, ucfirst($player), number_format($balance)]);
            if (++$i > 10) break;
        }
    }
}
