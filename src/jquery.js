// Diese Datei führt dazu, dass npm/yarn kein jQuery herunterlädt und mit in das Javascript einkompiliert.
// Stattdessen wird ein jQuery verwendet, dass zuvor bereits geladen wurde (von Wordpress).
// Das ist nach meiner Erfahrung weniger fehleranfällig, als das jQuery von Wordpress zu blockieren.

module.exports = window.jQuery;
