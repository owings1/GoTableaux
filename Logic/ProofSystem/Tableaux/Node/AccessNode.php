<?php
/**
 * Defines the AccessNode class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link Node} parent class.
 */
require_once dirname( __FILE__) . '/../Node.php';

/**
 * Loads the {@link ModalNode} interface.
 */
require_once dirname( __FILE__) . '/ModalNode.php';

/**
 * Represents a modal logic access relation node.
 * @package Tableaux
 * @author Douglas Owings
 */
class AccessNode extends Node implements ModalNode
{
	/**
	 * Holds a reference to the seeing world index.
	 * @var integer
	 * @access private
	 */
	protected $i;
	
	/**
	 * Holds a reference to the seen world index.
	 * @var integer
	 * @access private
	 */
	protected $j;
	
	/**
	 * Constructor.
	 * 
	 * Sets the indexes of the node.
	 *
	 * @param integer $i The first index.
	 * @param integer $j The second index.
	 */
	public function __construct( $i, $j )
	{
		$this->setI( $i )->setJ( $j ); 
	}
	
	/**
	 * Sets the first index.
	 *
	 * @param integer $i The index.
	 * @return AccessNode Current instance
	 */
	public function setI( $i )
	{
		$this->i = (int) $i;
		return $this;
	}
	
	/**
	 * Gets the first index
	 * 
	 * @return integer The first index.
	 */
	public function getI()
	{
		return $this->i;
	}
	
	/**
	 * Sets the second index.
	 *
	 * @param integer $j The second index.
	 * @return AccessNode Current instance.
	 */
	public function setJ( $j )
	{
		$this->j = (int) $j;
		return $this;
	}
	
	/**
	 * Gets the second index.
	 *
	 * @return integer The second index.
	 */
	public function getJ()
	{
		return $this->j;
	}
}