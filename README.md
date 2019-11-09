# redisgraph_php
A client for RedisGraph aiming to track its development and implementation of OpenCypher.
The goal of this library is providing a standard way of sending queries to the RedisGraph engine and encapsulate the results in a clean object.

## Installation
```
composer require jpbourbon/redisgraph_php:"0.5.5"
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
To obtain an array witht the returned keys, use the ***getKeys*** method:
```
$result->getKeys();
```

#### Getting the RecorSet and Records

The ***Result** and ***RecordSet*** objects provide methods to lookup their child classes.
```
$result->getRecordSets(); // Returns all RecordSet objects returnes (rows)
$result->getRecordSet(N); // Returns the RecordSet at position N or null
$result->firstRecordSet(); // Returns the first RecordSet or null
$result->size(); // Returns the size of the recordSets array

$result->firstRecordSet()->getRecords(); // Returns all Records from the first RecordSet
$result->firstRecordSet()->getRecord(N); // Returns the Record at position N or null
$result->firstRecordSet()->firstRecord(); // Returns the first Records from the RecordSet
$result->firstRecordSet()->size(); // Returns the size of a records array
```

#### Types of Records

The ***Records*** can be either ***Nodes***, ***Relationships*** or ***Sinlge*** (scalar) values. Each of them has its own characteristics, reflected in the returned object.

## TBC...
