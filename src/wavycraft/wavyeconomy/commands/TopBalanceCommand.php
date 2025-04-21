<?php

declare(strict_types=1);

namespace wavycraft\wavyeconomy\commands;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use wavycraft\wavyeconomy\WavyEconomy;
use wavycraft\wavyeconomy\api\WavyEconomyAPI;

use CortexPE\Commando\BaseCommand;

class TopBalanceCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission("wavyeconomy.topbalance");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game!");
            return;
        }

        $balances = WavyEconomyAPI::getInstance()->getAllBalances();

        arsort($balances);

        $sender->sendMessage(TextFormat::GOLD . "--- Top 10 Balances ---");
        $i = 1;
        foreach ($balances as $player => $balance) {
            $sender->sendMessage("§7" . $i . ". §f" . ucfirst($player) . " - " . "§a$" . number_format($balance));
            if (++$i > 10) break;
        }
    }
}
