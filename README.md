# json
An easy way for using json encoded strings in php.
## Install
1. Composer : ``composer require estaheri/json ``
2. manually : Download it from release page of github and require the class file ``json.php`` in your code.
## Examples
```php
<?php
// validate is a string json
$is_json = json::_is($string);
// json decode or encode string
$json_decode = json::_in($json_string);
$json_encode = json::_out($data);
// get or update a key/keys from a json string
$json_string = json::update($json_string,['firstName'=>'Will','lastName'=>'Smith']);
$first_name = json::get($json_string,'firstName');
$name = json::get($json_string,['firstName','lastName']);
// remove a key/keys from a json string
$json_string = json::remove($json_string,'firstName');
$json_string = json::remove($json_string,['lastName','age']);
// convert array or object or json string to these three types
$array = json::to_array($data);
$json = json::to_json($data);
$object = json::to_object($data);
```
## Questions :
### Why should we use this class while php has json methods in it?
I just wrote this class for making codes more clean and shorter.\
For example :
```php
<?php
$json_string='{"id": "1","firstName": "Tom","lastName": "Cruise"}';
####################################################
// using php itself
// update a key from json string
$decoded=json_decode($json_string);
$decoded->firstName='Will';
$decoded->lastName='Smith';
$json_string = json_encode($decoded);
####################################################
// using this library
// update a key from json string
$json_string = json::update($json_string,['firstName'=>'Will','lastName'=>'Smith']);
```
By the way I wrote it for self use but I think that maybe someone need it!
### Where is the document?
All methods have a phpDoc document so there is no need to write a document for now.
* Also it's just class with simple methods!
