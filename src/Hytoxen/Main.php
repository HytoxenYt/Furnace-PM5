<?php

namespace Hytoxen;

use pocketmine\plugin\PluginBase;
use Hytoxen\Commands\FurnaceCommand;
use Hytoxen\Commands\FurnaceAllCommand;

class Main extends PluginBase {

    public function onEnable(): void {
        $this->getServer()->getCommandMap()->register("furnace", new FurnaceCommand($this));
        $this->getServer()->getCommandMap()->register("furnaceall", new FurnaceAllCommand($this));

        $this->getLogger()->info("Furnace par Hytoxen activé !");
    }
}
