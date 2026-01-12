<?php

/**
 * Fichier pour avoir une vue plus propre des exos
 */

function section($titre) {
    echo "<hr><h2>$titre</h2>";
}

function field($label, $value) {
    echo "<strong>$label :</strong> " . htmlspecialchars($value) . "<br>";
}

function subsection($titre) {
    echo "<h3>$titre</h3>";
}

function listItems($items, $callback) {
    if (count($items) === 0) return;
    echo "<ul>";
    foreach ($items as $item) {
        echo "<li>" . $callback($item) . "</li>";
    }
    echo "</ul>";
}

function noResult($message) {
    echo $message . "<br>";
}

function setProp($object, $propName, $value) {
    $reflection = new ReflectionClass($object);
    $prop = $reflection->getProperty($propName);
    $prop->setAccessible(true);
    $prop->setValue($object, $value);
}

function getPropSafe($object, $propName) {
    $reflection = new ReflectionClass($object);
    $prop = $reflection->getProperty($propName);
    $prop->setAccessible(true);
    return $prop->isInitialized($object) ? $prop->getValue($object) : null;
}
