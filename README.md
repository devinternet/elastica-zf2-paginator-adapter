Zend Framework 2 Paginator Adapter for Elastica
==============================

##How to Use

```php
$config = array('host' => 127.0.0.1, 'port' => 9200);
$client = new \Elastica\Client($config);

$query = array();

$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\Elastica($client, $query, 'myIndex', 'myType'));
$paginator->setCurrentPageNumber(1);
$paginator->setItemCountPerPage(10);
```