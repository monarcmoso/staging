		//related {{siblings}}
		$result['{{siblings}}'] = array();
		$related{{Siblings}}Collection = ${{entity}}->getSelected{{Siblings}}Collection();
		foreach ($related{{Siblings}}Collection as ${{sibling}}) {
			$result['{{siblings}}'][${{sibling}}->getId()] = ${{sibling}}->getPosition();
		}
