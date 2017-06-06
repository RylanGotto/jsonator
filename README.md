# jsonator

JSOnator is a micro MVC server and lightweight noSQL document storage system with no dependancies. 
The purpose of this project is to demonstrate different skills, 
and methodlogies in order to create a REST service from scratch.
The DBMS is complete with schema validation and has been implemneted in a noSQL style. 
The DBMS currently is only offering the most basic of CRUD controls any references have to be done manually.
Addtionally tests were implmeneted using phpUnit. Give execution permissions to run_tests.sh and run accordingly.

The project is easily deployed due to there being no dependancies and all files were authored by me (except /client/bower_components). 
Simply drop into any apache or nginx server and test with the given client or Postman.

The project offers a dummy client which can control all end points in the entire system,
currently the client is set up as a reservation system,
but the system is completely decoupled and can be modifyed very quickly to service any type of data like a tradtional MVC framework. 
