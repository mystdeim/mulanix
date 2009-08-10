<?php
$orgdoc = new DOMDocument;
$orgdoc->formatOutput = true;
$orgdoc->loadXML("<root><element><child>text in child</child></element></root>");

// Create a new document
$newdoc = new DOMDocument;
$newdoc->formatOutput = true;
$newdoc->load('Mulanix/boot/config.xml');

// The node we want to import to a new document
//$node = $newdoc->getElementsByTagName("config")->item(0);
$node = $newdoc->documentElement;

echo "The 'org document' before copying nodes into it:\n";
var_dump($orgdoc->saveXML());

// Import the node, and all its children, to the document
$node = $orgdoc->importNode($node, true);
// And then append it to the "<root>" node
$orgdoc->documentElement->appendChild($node);

echo "\nThe 'new document' after copying the nodes into it:\n";
var_dump($orgdoc->saveXML());