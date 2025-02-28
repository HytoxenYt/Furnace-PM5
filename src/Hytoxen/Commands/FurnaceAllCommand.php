<?php

declare(strict_types=1);

namespace Hytoxen\Commands;

use pocketmine\block\VanillaBlocks;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;

class FurnaceAllCommand extends Command {


    public function __construct(PluginBase $plugin) {
        parent::__construct("furnaceall", "Cuit tous les items de l'inventaire", "/furnaceall");
        $this->setPermission("furnaceall.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Cette commande réservée aux joueurs !");
            return;
        }

        if (!$this->testPermission($sender)) {
            return;
        }

        $inventory = $sender->getInventory();
        $items = $inventory->getContents();
        $smeltedCount = 0;

        foreach ($items as $slot => $item) {
            $smeltedItem = $this->getSmeltedItem($item);
            if ($smeltedItem !== null) {
                $smeltedItem->setCount($item->getCount());
                $inventory->setItem($slot, $smeltedItem);
                $smeltedCount += $item->getCount();
            }
        }

        if ($smeltedCount > 0) {
            $sender->sendMessage("Vous avez cuit §e{$smeltedCount} §aitems !");
        } else {
            $sender->sendMessage("Aucun item à cuire !");
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
