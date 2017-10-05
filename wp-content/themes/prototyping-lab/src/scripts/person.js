/*
var Person = function( name ){
  this.name = name;
  this.who = function(){
    return 'My name is ' + this.name;
  }
}
*/

const Person = ( name ) => {
  this.name = name
  this.who = function(){
    return `My name ${this.name}`
  }
}

module.exports = Person;
