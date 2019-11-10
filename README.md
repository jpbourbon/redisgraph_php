# redisgraph_php
A client for RedisGraph aiming to track its development and implementation of OpenCypher.
The goal of this library is providing a standard way of sending queries to the RedisGraph engine and encapsulate the results in a clean object.

## Installation
```
composer require jpbourbon/redisgraph_php:"0.5.6"
```

## Usage
There are 2 ways of using this library.
- Using the **Client**, an instantiable class;
- Using **RedisGraph**, a static wrapper that loads the Client as a singleton;

### Connection
#### Client
```
...
use RedisGraphPhp\Client;
...
$options = [
        "host"  => "127.0.0.1",
        "port"  => "6379",
        "graph" => "test"
    ];
$client = new Client($options);
```
#### RedisGraph
```
...
use RedisGraphPhp\RedisGraph;
...
$options = [
        "host"  => "127.0.0.1",
        "port"  => "6379",
        "graph" => "test"
    ];
RedisGraph::setOptions($options);
```

### Queries
This library supports only raw queries. However, we encapsulate the query in a **Cypher** class that can be used for validation in future iterations. This class also provides tagging and runtime changing of graphs.
```
...
use RedisGraphPha\Cypher;
...
$cypher = new Cypher("MATCH (f:Foo) RETURN f", "MyTast");
```

### Running queries
#### Client
```
...
use RedisGraphPhp\Client;
use RedisGraphPha\Cypher;
...
$options = [
        "host"  => "127.0.0.1",
        "port"  => "6379",
        "graph" => "test"
    ];
$client = new Client($options);
$cypher = new Cypher("MATCH (f:Foo) RETURN f", "MyTast");
$result = $client->run($cypher);
```
#### RedisGraph
```
...
use RedisGraphPhp\RedisGraph;
use RedisGraphPha\Cypher;
...
$options = [
        "host"  => "127.0.0.1",
        "port"  => "6379",
        "graph" => "test"
    ];
RedisGraph::setOptions($options);
$cypher = new Cypher("MATCH (f:Foo) RETURN f", "MyTast");
$result = RedisGraph::run($cypher);
```

### Results
The response from the ***run*** method will return an instance of the ***Result*** class. This class consists of a structure that holds an array of query return ***keys***, and array of ***RecordSets*** with ***Records****, the ***Cypher*** object and the ***Statistics*** object.

#### Return keys
A Cypher query that contains a ***RETURN*** clause will always return a key:value pair, except when the query generates no returnable values.
Consider the following Cypher query:
```
MATCH (f:Foo) RETURN f
```
This returns the key ***f***.
To obtain an array with the returned keys, use the ***getKeys*** method:
```
$result->getKeys();
```

#### Getting the RecordSet and Records

The ***Result** and ***RecordSet*** objects provide methods to lookup their child classes.
```
$result->getRecordSets(); // Returns all RecordSet objects returnes (rows)
$result->getRecordSet(N); // Returns the RecordSet at position N or null
$result->firstRecordSet(); // Returns the first RecordSet or null
$result->size(); // Returns the size of the recordSets array

$result->firstRecordSet()->getRecords(); // Returns all Records from the first RecordSet
$result->firstRecordSet()->getRecord(N); // Returns the Record at position N or null
$result->firstRecordSet()->firstRecord(); // Returns the first Records from the RecordSet
```

#### Types of Records

The ***Records*** can be either ***Nodes***, ***Relationships*** or ***Scalar values***. Each of them has its own characteristics, reflected in the returned object.

#### Scalar

Scalar values are single values. Those can be either the result of an internal cypher function (such as ***SUM(), COUNT()*** etc) or the value from a node or relationship property.
```
MATCH (n) RETURN COUNT(n) AS total // "total" is a scalar value
MATCH (n) RETURN n.key // "n.key" is a scalar value
```
To access the value you would first need to know the key, and use the ***getValue()*** method on the record:
```
$result->firstRecordSet()->getRecord("total")->getValue(); // Returns the single value
```
However, as we often need to get a list of scalar values, there is a special method for that:
```
$result->getAllScalar("n.key"); // Returns an array of all values from all recordsets
```

#### Nodes and Relationships

***Nodes*** and ***Relationships*** share some common methods, since they can both hold properties, but have their specific ones.
Common
```
$result->firstRecordSet()->firstRecord()->getId(); // returns the internal id of the record
$result->firstRecordSet()->firstRecord()-getProperties(); // Gets an array with existing properties;
$result->firstRecordSet()->firstRecord()->getValue($property); // Gets the value for the given property name
```
Nodes
```
$result->firstRecordSet()->firstRecord()->getLabels(); // Gets the array of labels
```
Relationship
```
$result->firstRecordSet()->firstRecord()->getType(); // Returns the type as a string
$result->firstRecordSet()->firstRecord()->getLinkedNodes(); // Gets an array with the source and target node ids
```

## TBC...
