<?php

namespace GHOME\CoreBundle\Entity;

/**
 * Manages the rooms of the home.
 */
class RoomManager extends Manager
{
    /**
	 * {@inheritdoc}
	 */
	protected function initialize()
	{
		$home = new \SimpleXMLElement(file_get_contents($this->dir.'/home.xml'));
		
		foreach ($home->rooms->room as $room)
		{
			$id = (int) $room['id'];
			$name = (string) $room->name;
			
			$this->entities[$id] = new Room($id, $name);
		}
	}
}