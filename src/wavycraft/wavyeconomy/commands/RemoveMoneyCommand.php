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
use CortexPE\Commando\args\IntegerArgument;

class RemoveMoneyCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission("wavyeconomy.removemoney");
        
        $this->registerArgument(0, new RawStringArgument("player"));
        $this->registerArgument(1, new IntegerArgument("amount"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        $config = new Config(WavyEconomy::getInstance()->getDataFolder() . "messages.yml");

        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game!");
            return;
        }

        if (!isset($args["player"], $args["amount"])) {
            $sender->sendMessage((string) new Messages($config, "remove-money-usage-message"));
            return;
        }

        $targetName = strtolower($args["player"]);
        $amount = (int)$args["amount"];

        if ($amount <= 0) {
            $sender->sendMessage((string) new Messages($config, "negative-number-message"));
            return;
        }

        $api = WavyEconomyAPI::getInstance();

        if (!$api->hasAccount($targetName)) {
            $sender->sendMessage((string) new Messages($config, "no-account-message", ["{name}"], [$args["player"]]));
            return;
        }

        if ($api->getBalance($targetName) < $amount) {
            $sender->sendMessage((string) new Messages($config, "not-enough-money-to-remove-message", ["{name}"], [ucfirst($targetName)]));
            return;
        }

        $api->removeMoney($targetName, $amount);
        $sender->sendMessage((string) new Messages($config, "removed-money-message", ["{name}", "{amount}"], [ucfirst($targetName), number_format($amount)]));
    }
}
