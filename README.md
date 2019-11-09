# redisgraph_php
A client for RedisGraph aiming to track its development and implementation of OpenCypher.
The goal of this library is providing a standard way of sending queries to the RedisGraph engine and encapsulate the results in a clean object.

## Installation
```
composer require jpbourbon/redisgraph_php:"0.5.3"
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
