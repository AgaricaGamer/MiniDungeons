<?php

namespace Agamer\MiniDungeon;

use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

    private $config;

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML);
    }

    public function onBlockBreak(BlockBreakEvent $event){
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if($block->getLevel()->getName() === $this->config->get("levelname")){
            if($block->getId() === Block::COAL_ORE && $this->config->get("coal_ore") === true){
                foreach ($event->getDrops() as $drop) {
                    $event->getPlayer()->getInventory()->addItem($drop);
                }
                $event->setDrops([]);
                $event->setCancelled();
                $event->setXpDropAmount(0);
                $player->addXp($event->getXpDropAmount());
                $block->getLevelNonNull()->setBlock($block->asVector3(), Block::get(Block::BEDROCK));
                $this->getScheduler()->scheduleDelayedTask(new DelayTask($this, $block), 20 * $this->config->get("delay"));
              elseif($block->getId() === Block::IRON_ORE && $this->config->get("iron_ore") === true){
      
