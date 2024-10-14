#!/bin/bash
echo "Startando Backup"
cd docker-entrypoint-initdb.d
SQL=$(date -d "today" +"%Y%m%d%H%M").sql
mysqldump -u root -pCu2JBpchOBmA artesp >${SQL}
PATH="$(pwd)/${SQL}"
echo ${PATH}
