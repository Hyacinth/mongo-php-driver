--TEST--
Test for PHP-339: Segfault on insert timeout.
--SKIPIF--
<?php if (getenv('SKIP_SLOW_TESTS')) die('skip slow tests excluded by request'); ?>
<?php require_once "tests/utils/bridge.inc" ?>
--FILE--
<?php
require_once "tests/utils/server.inc";

$dsn = MongoShellServer::getBridgeInfo();
$m = new MongoClient($dsn);
$c = $m->selectDB(dbname())->selectCollection("collection");

try {
    $foo = array("foo" => time());
    $result = $c->insert($foo, array("safe" => true, "timeout" => 1));
} catch(Exception $e) {
    var_dump(get_class($e), $e->getMessage());
    var_dump($foo);
}
?>
===DONE===
--EXPECTF--
string(27) "MongoCursorTimeoutException"
string(%d) "%Scursor timed out (timeout: 1, time left: 0:1000, status: 0)"
array(2) {
  ["foo"]=>
  int(%d)
  ["_id"]=>
  object(MongoId)#%d (1) {
    ["$id"]=>
    string(24) "%s"
  }
}
===DONE===

