# Description
This plugin adds an economic system to your PocketMine-MP server!

Easy to use API!

# Features
- Check your or another players balance
- Pay players
- Add, remove and set money (operators only)
- Leaderboard (Top 10 balances)
- ScoreHud support
- Configurable messages. Check out `messages.yml`

# ScoreHud
This plugin supports ScoreHud!

Check out [WavyEconomyScore](https://github.com/WavyCraft/WavyEconomyScore)!

# API
**How to get the instance:**
```php
/** Import one of these classes */
use wavycraft\wavyeconomy\WavyEconomy;

or

use wavycraft\wavyeconomy\api\WavyEconomyAPI;

option 1:
$api = WavyEconomy::getInstance()->getAPI();

option 2:
$api = WavyEconomyAPI::getInstance();
```

**How to get a players balance:**
```php
/** $player is instanceof Player::class */

$api->getBalance($player->getName());
```

**How to add money to a players balance:**
```php
/** $player is instanceof Player::class */

$api->addMoney($player->getName(), 100);
```

**How to remove money from a players balance:**
```php
/** $player is instanceof Player::class */

$api->removeMoney($player->getName(), 100);
```

**How to set a players balance:**
```php
/** $player is instanceof Player::class */

$api->setMoney($player->getName(), 100);
```

**Example usage:**
```php
/** $player is instanceof Player::class */

/** Checks to see if a player has an account */
if (!$api->hasBalance($player->getName()) {
    $player->sendMessage("Player does not own a balance!");
    return;
}

/** If a player has a balance continue to add the money */
$api->addMoney($player->getName(), 100);

Check out AddMoneyCommand.php for a better example!
```
