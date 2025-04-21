<?php

declare(strict_types=1);

namespace wavycraft\wavyeconomy\api;

use pocketmine\player\Player;

use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

use wavycraft\wavyeconomy\WavyEconomy;
use wavycraft\wavyeconomy\event\BalanceChangeEvent;

final class WavyEconomyAPI {
    use SingletonTrait;

    protected Config $config;

    protected int $startingAmount;

    public function __construct() {
        $dataFolder = WavyEconomy::getInstance()->getDataFolder();

        @mkdir($dataFolder . "database/");
        $this->config = new Config($dataFolder . "database/balances.json");

        $this->startingAmount = WavyEconomy::getInstance()->getConfig()->get("starting-amount");
    }

    public function hasAccount($player) : bool{
        if ($player instanceof Player) {
            $player = $player->getName();
        }

        $player = strtolower($player);

        return $this->config->exists($player);
    }

    public function createAccount($player) : void{
        if ($player instanceof Player) {
            $player = $player->getName();
        }

        $player = strtolower($player);

        if (!$this->hasAccount($player)) {
            $this->config->set($player, $this->startingAmount);
            $this->config->save();
        }
    }

    public function getBalance($player) : int{
        if ($player instanceof Player) {
            $player = $player->getName();
        }

        $player = strtolower($player);

        return (int) $this->config->get($player);
    }

    public function getAllBalances() : array{
        return $this->config->getAll();
    }

    public function addMoney($player, int $amount) : void{
        if ($player instanceof Player) {
            $player = $player->getName();
        }

        $player = strtolower($player);

        if (!$this->hasAccount($player)) {
            return;
        }

        $current = $this->getBalance($player);
        $event = new BalanceChangeEvent($player);

        $this->config->set($player, $current + $amount);
        $this->config->save();
        $event->call();
    }

    public function removeMoney($player, int $amount) : void{
        if ($player instanceof Player) {
            $player = $player->getName();
        }

        $player = strtolower($player);

        if (!$this->hasAccount($player)) {
            return;
        }

        $current = $this->getBalance($player);
        $event = new BalanceChangeEvent($player);

        $this->config->set($player, $current - $amount);
        $this->config->save();
        $event->call();
    }

    public function setMoney($player, int $amount) : void{
        if ($player instanceof Player) {
            $player = $player->getName();
        }

        $player = strtolower($player);

        if (!$this->hasAccount($player)) {
            return;
        }

        $event = new BalanceChangeEvent($player);

        $this->config->set($player, $amount);
        $this->config->save();
        $event->call();
    }
}
