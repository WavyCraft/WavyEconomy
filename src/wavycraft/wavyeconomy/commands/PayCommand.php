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

class PayCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission("wavyeconomy.pay");

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
            $sender->sendMessage((string) new Messages($config, "pay-usage-message"));
            return;
        }

        $targetName = strtolower($args["player"]);
        $amount = (int)$args["amount"];
        $senderName = strtolower($sender->getName());

        if ($targetName === $senderName) {
            $sender->sendMessage((string) new Messages($config, "cannot-pay-yourself-message"));
            return;
        }

        if ($amount <= 0) {
            $sender->sendMessage((string) new Messages($config, "negative-number-message"));
            return;
        }

        $api = WavyEconomyAPI::getInstance();

        if (!$api->hasAccount($targetName)) {
            $sender->sendMessage((string) new Messages($config, "No-account-message", ["{name}"], [$args["player"]]));
            return;
        }

        if ($api->getBalance($senderName) < $amount) {
            $sender->sendMessage((string) new Messages($config, "not-enough-money-message"));
            return;
        }

        $api->removeMoney($senderName, $amount);
        $api->addMoney($targetName, $amount);

        $sender->sendMessage((string) new Messages($config, "paid-money-message", ["{name}", "{amount}"], [ucfirst($targetName), number_format($amount))]));
        
        $targetPlayer = $sender->getServer()->getPlayerExact($targetName);
        if ($targetPlayer instanceof Player) {
            $targetPlayer->sendMessage((string) new Messages($config, "recieved-money-message", ["{name}", "{amount}", [$sender->getName(), number_format($amount)]]));
        }
    }
}
