<?php
/**
 * @author Erol Soyöz <erol@devinternet.com>
 */
namespace Zend\Paginator\Adapter;

use Zend\Paginator\Adapter\AdapterInterface;
use Zend\Paginator\Exception\InvalidArgumentException;
use Elastica\Query;
use Elastica\Search;
use Elastica\ResultSet;

class Elastica implements AdapterInterface {
	/**
	 * Elastica Client
	 * 
	 * @var \Elastica\Client
	 */
	protected $_client = null;

	/**
	 * Query
	 * 
	 * @var array
	 */
	protected $_query = null;

	/**
	 * Index
	 * 
	 * @var string
	 */
	protected $_index = null;

	/**
	 * Type
	 * 
	 * @var string
	 */
	protected $_type = null;

	/**
	 * Total count
	 *
	 * @var integer
	 */
	protected $_count = null;

	/**
	 * Result
	 * 
	 * @var \Elastica\ResultSet
	 */
	protected $_result = null;

	/**
	 * Set the parameters
	 * 
	 * @param \Elastica\Client $client
	 * @param array $query
	 * @param string $index
	 * @param string $type
	 */
	public function __construct($client, $query, $index, $type) {
		$this->setClient($client);
		$this->setQuery($query);
		$this->setIndex($index);
		$this->setType($type);
	}

	/**
	 * @see \Zend\Paginator\Adapter\AdapterInterface::getItems()
	 */
	public function getItems($offset, $itemCountPerPage) {
		$query = new \Elastica\Query($this->getQuery());
		$query->setFrom($offset);
		$query->setSize($itemCountPerPage);

		$search = new \Elastica\Search($this->getClient());
		$result = $search->addIndex($this->getIndex())->addType($this->getType())->search($query);
		
		$this->setResult($result);
		$this->setCount($result->getTotalHits());

		return $result;
	}

	/**
	 * @see Countable::count()
	 */
	public function count() {
		if ($this->getCount() !== null) {
			return $this->getCount();
		}
	}

	/**
	 * Set the _client property
	 * 
	 * @param \Elastica\Client $client
	 * @throws Exception\InvalidArgumentException
	 */
	public function setClient($client) {
		if (!$client instanceof \Elastica\Client) {
			throw new \Zend\Paginator\Exception\InvalidArgumentException('$client must be an instance of Elastica\Client');
		} else {
			$this->_client = $client;
		}
	}

	/**
	 * Get the _client property
	 * 
	 * @return \Elastica\Client
	 */
	public function getClient() {
		return $this->_client;
	}

	/**
	 * Set the _query property
	 * 
	 * @param array $query
	 */
	public function setQuery($query) {
	    if (!is_array($query)) {
	        throw new \Zend\Paginator\Exception\InvalidArgumentException('$query must be an array');
	    } else {
	        $this->_query = $query;
	    }
	}

	/**
	 * Get the _query property
	 * 
	 * @return array
	 */
	public function getQuery() {
		return $this->_query;
	}

	/**
	 * Set the _index property
	 * 
	 * @param string $index
	 */
	public function setIndex($index) {
		$this->_index = $index;
	}

	/**
	 * Get the _index property
	 * 
	 * @return string
	 */
	public function getIndex() {
		return $this->_index;
	}

	/**
	 * Set the _type property
	 * 
	 * @param string $type
	 */
	public function setType($type) {
		$this->_type = $type;
	}

	/**
	 * Get the _type property
	 * 
	 * @return string
	 */
	public function getType() {
		return $this->_type;
	}

	/**
	 * Set the _count property
	 * 
	 * @param number $count
	 */
	public function setCount($count) {
		$this->_count = $count;
	}

	/**
	 * Get the _count property
	 * 
	 * @return number
	 */
	public function getCount() {
		return $this->_count;
	}
	
	/**
	 * Set the _result property
	 */
	public function setResult($result) {
	    if (!$result instanceof \Elastica\ResultSet) {
	        throw new \Zend\Paginator\Exception\InvalidArgumentException('$result must be an instance of Elastica\ResultSet');
	    } else {
	        $this->_result = $result;
	    }
	}
	
	/**
	 * Get the _result property
	 */
	public function getResult() {
	    return $this->_result;
	}
}
