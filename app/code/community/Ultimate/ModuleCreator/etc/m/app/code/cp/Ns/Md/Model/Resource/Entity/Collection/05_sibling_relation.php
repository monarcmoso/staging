	/**
	 * add the {{sibling}} filter to collection
	 * @access public
	 * @param mixed ({{Namespace}}_{{Module}}_Model_{{Sibling}}|int) ${{sibling}}
	 * @return {{Namespace}}_{{Module}}_Model_Resource_{{Entity}}_Collection
	 * {{qwertyuiop}}
	 */
	public function add{{Sibling}}Filter(${{sibling}}){
		if (${{sibling}} instanceof {{Namespace}}_{{Module}}_Model_{{Sibling}}){
			${{sibling}} = ${{sibling}}->getId();
		}
		if (!isset($this->_joinedFields['{{sibling}}'])){
			$this->getSelect()->join(
				array('related_{{sibling}}' => $this->getTable('{{module}}/{{entity}}_{{sibling}}')),
				'related_{{sibling}}.{{entity}}_id = main_table.entity_id',
				array('position')
			);
			$this->getSelect()->where('related_{{sibling}}.{{sibling}}_id = ?', ${{sibling}});
			$this->_joinedFields['{{sibling}}'] = true;
		}
		return $this;
	}
