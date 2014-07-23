<?php
{{License}}
/**
 * {{EntityLabel}} tab on product edit form
 *
 * @category	{{Namespace}}
 * @package		{{Namespace}}_{{Module}}
 * {{qwertyuiop}}
 */
class {{Namespace}}_{{Module}}_Block_Adminhtml_Catalog_Product_Edit_Tab_{{Entity}} extends {{Namespace}}_{{Module}}_Block_Adminhtml_{{Entity}}_Tree{
	protected $_{{entity}}Ids = null;
	protected $_selectedNodes = null;
	/**
	 * constructor
	 * Specify template to use
	 * @access public
	 * @return void
	 * {{qwertyuiop}}
	 */
	public function __construct(){
		parent::__construct();
		$this->setTemplate('{{namespace}}_{{module}}/catalog/product/edit/tab/{{entity}}.phtml');
	}
	/**
	 * Retrieve currently edited product
	 * @access public
	 * @return Mage_Catalog_Model_Product
	 * {{qwertyuiop}}
	 */
	public function getProduct(){
		return Mage::registry('current_product');
	}
	/**
	 * Return array with {{entityLabel}} IDs which the product is assigned to
	 * @access public
	 * @return array
	 * {{qwertyuiop}}
	 */
	public function get{{Entity}}Ids(){
		if (is_null($this->_{{entity}}Ids)){
			$selected{{Entities}} = Mage::helper('{{module}}/product')->getSelected{{Entities}}($this->getProduct());
			$ids = array();
			foreach ($selected{{Entities}} as ${{entity}}){
				$ids[] = ${{entity}}->getId();
			}
			$this->_{{entity}}Ids = $ids; 
		}
		return $this->_{{entity}}Ids;
	}
	/**
	 * Forms string out of get{{Entity}}Ids()
	 * @access public
	 * @return string
	 * {{qwertyuiop}}
	 */
	public function getIdsString(){
		return implode(',', $this->get{{Entity}}Ids());
	}
	/**
	 * Returns root node and sets 'checked' flag (if necessary)
	 * @access public
	 * @return Varien_Data_Tree_Node
	 * {{qwertyuiop}}
	 */
	public function getRootNode(){
		$root = $this->getRoot();
		if ($root && in_array($root->getId(), $this->get{{Entity}}Ids())) {
			$root->setChecked(true);
		}
		return $root;
	}
	/**
	 * Returns root node
	 *
	 * @param {{Namespace}}_{{Module}}_Model_{{Entity}}|null $parentNode{{Entity}}
	 * @param int  $recursionLevel
	 * @return Varien_Data_Tree_Node
	 * {{qwertyuiop}}
	 */
	public function getRoot($parentNode{{Entity}} = null, $recursionLevel = 3){
		if (!is_null($parentNode{{Entity}}) && $parentNode{{Entity}}->getId()) {
			return $this->getNode($parentNode{{Entity}}, $recursionLevel);
		}
		$root = Mage::registry('{{entity}}_root');
		if (is_null($root)) {
			$rootId = Mage::helper('{{module}}/{{entity}}')->getRoot{{Entity}}Id();
		
			$ids = $this->getSelectedF{{Entity}}PathIds($rootId);
			$tree = Mage::getResourceSingleton('{{module}}/{{entity}}_tree')
				->loadByIds($ids, false, false);
			if ($this->get{{Entity}}()) {
				$tree->loadEnsuredNodes($this->get{{Entity}}(), $tree->getNodeById($rootId));
			}
			$tree->addCollectionData($this->get{{Entity}}Collection());
			$root = $tree->getNodeById($rootId);
			Mage::register('{{entity}}_root', $root);
		}
		return $root;
	}
	/**
	 * Returns array with configuration of current node
	 * @access protected
	 * @param Varien_Data_Tree_Node $node
	 * @param int $level How deep is the node in the tree
	 * @return array
	 * {{qwertyuiop}}
	 */
	protected function _getNodeJson($node, $level = 1){
		$item = parent::_getNodeJson($node, $level);
		if ($this->_isParentSelected{{Entity}}($node)) {
			$item['expanded'] = true;
		}
		if (in_array($node->getId(), $this->get{{Entity}}Ids())) {
			$item['checked'] = true;
		}
		return $item;
	}

	/**
	 * Returns whether $node is a parent (not exactly direct) of a selected node
	 * @access protected
	 * @param Varien_Data_Tree_Node $node
	 * @return bool
	 * {{qwertyuiop}}
	 */
	protected function _isParentSelected{{Entity}}($node){
		$result = false;
		// Contains string with all {{entityLabel}} IDs of children (not exactly direct) of the node
		$allChildren = $node->getAllChildren();
		if ($allChildren) {
			$selected{{Entity}}Ids = $this->get{{Entity}}Ids();
			$allChildrenArr = explode(',', $allChildren);
			for ($i = 0, $cnt = count($selected{{Entity}}Ids); $i < $cnt; $i++) {
				$isSelf = $node->getId() == $selected{{Entity}}Ids[$i];
				if (!$isSelf && in_array($selected{{Entity}}Ids[$i], $allChildrenArr)) {
					$result = true;
					break;
				}
			}
		}
		return $result;
	}

	/**
	 * Returns array with nodes those are selected (contain current product)
	 * @access protected
	 * @return array
	 * {{qwertyuiop}}
	 */
	protected function _getSelectedNodes(){
		if ($this->_selectedNodes === null) {
			$this->_selectedNodes = array();
			$root = $this->getRoot();
			foreach ($this->get{{Entity}}Ids() as ${{entity}}Id) {
				if ($root) {
					$this->_selectedNodes[] = $root->getTree()->getNodeById(${{entity}}Id);
				}
			}
		}
		return $this->_selectedNodes;
	}

	/**
	 * Returns JSON-encoded array of {{entityLabel}} children
	 * @access public
	 * @param int ${{entity}}Id
	 * @return string
	 * {{qwertyuiop}}
	 */
	public function get{{Entity}}ChildrenJson(${{entity}}Id){
		${{entity}} = Mage::getModel('{{module}}/{{entity}}')->load(${{entity}}Id);
		$node = $this->getRoot(${{entity}}, 1)->getTree()->getNodeById(${{entity}}Id);
		if (!$node || !$node->hasChildren()) {
			return '[]';
		}
		
		$children = array();
		foreach ($node->getChildren() as $child) {
			$children[] = $this->_getNodeJson($child);
		}
		return Mage::helper('core')->jsonEncode($children);
	}

	/**
	 * Returns URL for loading tree
	 * @access public
	 * @param null $expanded
	 * @return string
	 * {{qwertyuiop}}
	 */
	public function getLoadTreeUrl($expanded = null){
		return $this->getUrl('*/*/{{entities}}Json', array('_current' => true));
	}

	/**
	 * Return distinct path ids of selected {{entitiesLabel}}
	 * @access public
	 * @param mixed $rootId Root {{entityLabel}} Id for context
	 * @return array
	 * {{qwertyuiop}}
	 */
	public function getSelected{{Entity}}PathIds($rootId = false){
		$ids = array();
		${{entity}}Ids = $this->get{{Entity}}Ids();
		if (empty(${{entity}}Ids)) {
			return array();
		}
		$collection = Mage::getResourceModel('{{module}}/{{entity}}_collection');
		
		if ($rootId) {
			$collection->addFieldToFilter('parent_id', $rootId);
		} 
		else {
			$collection->addFieldToFilter('entity_id', array('in'=>${{entity}}Ids));
		}
		
		foreach ($collection as $item) {
			if ($rootId && !in_array($rootId, $item->getPathIds())) {
				continue;
			}
			foreach ($item->getPathIds() as $id) {
				if (!in_array($id, $ids)) {
					$ids[] = $id;
				}
			}
		}
		return $ids;
	}
}