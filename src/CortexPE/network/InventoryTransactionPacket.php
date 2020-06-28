<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types = 1);

namespace CortexPE\network;

use CortexPE\network\types\NetworkInventoryAction;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket as PMInventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\types\ContainerIds;

class InventoryTransactionPacket extends PMInventoryTransactionPacket {

	/** @var bool */
	public $isCraftingPart;

	/** @var bool */
	public $isFinalCraftingPart;

	protected function decodePayload(): void{
		parent::decodePayload();
		foreach($this->actions as $index => $action){
				$this->actions[$index] = NetworkInventoryAction::cast($action);
			if(
				$action->sourceType === NetworkInventoryAction::SOURCE_CONTAINER and
				$action->windowId === ContainerIds::UI and
				$action->inventorySlot === 50 and
				!$action->oldItem->equalsExact($action->newItem)
			){
				$this->isCraftingPart = true;
				if(!$action->oldItem->isNull() and $action->newItem->isNull()){
					$this->isFinalCraftingPart = true;
				}
			}elseif(
				$action->sourceType === NetworkInventoryAction::SOURCE_TODO and (
					$action->windowId === NetworkInventoryAction::SOURCE_TYPE_CRAFTING_RESULT or
					$action->windowId === NetworkInventoryAction::SOURCE_TYPE_CRAFTING_USE_INGREDIENT
				)
			){
				$this->isCraftingPart = true;
			}
		}
	}
}