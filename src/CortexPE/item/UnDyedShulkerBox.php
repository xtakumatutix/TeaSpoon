<?php
declare(strict_types=1);

namespace CortexPE\item;


use pocketmine\item\Item;
use pocketmine\nbt\tag\NamedTag;

class UnDyedShulkerBox extends ShulkerBox{

	public function __construct(?string $name = null, ?NamedTag $inventory = null){
		if($name === null){
			$name = "Shulker Box";
		}
		Item::__construct(self::UNDYED_SHULKER_BOX, 0, $name);
		if($inventory !== null){
			$this->getNamedTag()->setTag($inventory);
		}
	}

}