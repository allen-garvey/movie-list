#!/usr/bin/env bash

#create db.php by copying example if it doesn't exist

if [ ! -f './inc/db.php' ]; then
	echo -e "\nInitializing ./inc/db.php\n"; 
	cp './inc/db-example' './inc/db.php'; 
fi