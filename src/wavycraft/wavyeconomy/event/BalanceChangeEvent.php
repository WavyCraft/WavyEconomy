<?php

declare(strict_types=1);

namespace wavycraft\wavyeconomy\event;

use pocketmine\event\Event;

class BalanceChangeEvent extends Event {

    public function __construct(protected $player) {
        $this->player = $player;
    }

    /** This returns the playername */
    public function getPlayer(){
        return $this->player;
    }
}
