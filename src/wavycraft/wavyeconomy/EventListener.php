<?php

declare(strict_types=1);

namespace wavycraft\wavyeconomy;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use wavycraft\wavyeconomy\api\WavyEconomyAPI;

class EventListener implements Listener {

    public function join(PlayerJoinEvent $event) : void{
        $player = $event->getPlayer();
        $api = WavyEconomyAPI::getInstance();

        if (!$api->hasAccount($player)) {
            $api->createAccount($player);
        }
    }
}