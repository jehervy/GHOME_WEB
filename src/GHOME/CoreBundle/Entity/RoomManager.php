<?php

namespace GHOME\CoreBundle\Entity;

class RoomManager
{
	private $dir;
	private $rooms = array();
	
	public function __construct($dir)
	{
		$this->dir = $dir;

		$this->initialize();
	}
	
	public function findAll()
	{
		return $this->rooms;
	}
	
	public function find($index)
	{
		return isset($this->rooms[$index]) ? $this->rooms[$index] : null;
	}

	private function initialize()
	{
		$home = new \SimpleXMLElement(file_get_contents($this->dir.'/home.xml'));
		
		foreach ($home->rooms->room as $room)
		{
			$id = (int) $room['id'];
			$name = (string) $room->name;
			
			$this->rooms[$id] = new Room($id, $name);
		}
	}
}