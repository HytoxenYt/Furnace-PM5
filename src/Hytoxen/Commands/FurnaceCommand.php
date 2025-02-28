<?php

declare(strict_types=1);

namespace Hytoxen\Commands;

use pocketmine\block\VanillaBlocks;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\item\VanillaItems;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;

class FurnaceCommand extends Command {

    public function __construct(PluginBase $plugin) {
        parent::__construct("furnace", "Cuit l'item tenu en main", "/furnace");
        $this->setPermission("furnace.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Commande réservée aux joueurs !");
            return;
        }

        if (!$this->testPermission($sender)) {
            return;
        }

        $item = $sender->getInventory()->getItemInHand();
        $smeltedItem = $this->getSmeltedItem($item);

        if ($smeltedItem !== null) {
            $smeltedItem->setCount($item->getCount());
            $sender->getInventory()->setItemInHand($smeltedItem);
            $sender->sendMessage("L'item en main a été cuit !");
        } else {
            $sender->sendMessage("Cet item ne peut pas être cuit !");
        }
    }

    private function getSmeltedItem($item): ?Item {
        return match (true) {
            $item->equals(VanillaItems::RAW_IRON()) => VanillaItems::IRON_INGOT(),
            $item->equals(VanillaItems::RAW_GOLD()) => VanillaItems::GOLD_INGOT(),
            $item->equals(VanillaItems::RAW_COPPER()) => VanillaItems::COPPER_INGOT(),
            $item->equals(VanillaItems::POTATO()) => VanillaItems::BAKED_POTATO(),
            $item->equals(VanillaItems::RAW_BEEF()) => VanillaItems::STEAK(),
            $item->equals(VanillaItems::RAW_PORKCHOP()) => VanillaItems::COOKED_PORKCHOP(),
            $item->equals(VanillaItems::RAW_CHICKEN()) => VanillaItems::COOKED_CHICKEN(),
            $item->equals(VanillaItems::RAW_MUTTON()) => VanillaItems::COOKED_MUTTON(),
            $item->equals(VanillaItems::RAW_SALMON()) => VanillaItems::COOKED_SALMON(),
            default => null,
        };
    }
}
