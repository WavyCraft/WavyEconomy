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
use CortexPE\Commando\args\RawStringArgument;

class SeeBalanceCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission("wavyeconomy.seebalance");

        $this->registerArgument(0, new RawStringArgument("player"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        $config = new Config(WavyEconomy::getInstance()->getDataFolder() . "messages.yml");

        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game!");
            return;
        }

        if (!isset($args["player"])) {
            $sender->sendMessage((string) new Messages($config, "see-balance-usage-message"));
            return;
        }

        $targetName = strtolower($args["player"]);

        $api = WavyEconomyAPI::getInstance();

        if (!$api->hasAccount($targetName)) {
            $sender->sendMessage((string) new Messages($config, "no-account-message", ["{name}"], [$args["player"]]));
            return;
        }

        $balance = $api->getBalance($targetName);

        $sender->sendMessage((string) new Messages($config, "see-balance-message", ["{name}", "{balance}"], [ucfirst($targetName), number_format($balance)]));
    }
}