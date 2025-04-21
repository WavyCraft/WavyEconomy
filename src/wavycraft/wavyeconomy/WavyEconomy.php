<?php

declare(strict_types=1);

namespace wavycraft\wavyeconomy;

use pocketmine\plugin\PluginBase;

use wavycraft\wavyeconomy\api\WavyEconomyAPI;
use wavycraft\wavyeconomy\commands\BalanceCommand;
use wavycraft\wavyeconomy\commands\SeeBalanceCommand;
use wavycraft\wavyeconomy\commands\PayCommand;
use wavycraft\wavyeconomy\commands\AddMoneyCommand;
use wavycraft\wavyeconomy\commands\RemoveMoneyCommand;
use wavycraft\wavyeconomy\commands\SetMoneyCommand;

use CortexPE\Commando\PacketHooker;

class WavyEconomy extends PluginBase {

    protected static self $instance;

    protected function onLoad() : void{
        self::$instance = $this;
    }

    protected function onEnable() : void{
        $this->saveDefaultConfig();
        $this->saveResource("messages.yml");

        if (!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }

        $this->getServer()->getCommandMap()->registerAll("WavyEconomy", [
            new BalanceCommand($this, "balance", "Check your balance"),
            new SeeBalanceCommand($this, "seebalance", "Check a players balance"),
            new PayCommand($this, "pay", "Pay a player"),
            new AddMoneyCommand($this, "addmoney", "Add money to a players balance"),
            new RemoveMoneyCommand($this, "removemoney", "Remove money from a players balance"),
            new SetMoneyCommand($this, "setmoney", "Set a players balance")
        ]);

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }

    public static function getInstance() : self{
        return self::$instance;
    }

    public function getAPI() : WavyEconomyAPI{
        return WavyEconomyAPI::getInstance();
    }
}
