require("./styles/main.css");

var Person = require("./scripts/person.js")

var adam = new Person("Adam");
var tom = new Person("Tommy");

document.write("Who goes there?");
document.write(adam.who());
document.write(tom.who());
