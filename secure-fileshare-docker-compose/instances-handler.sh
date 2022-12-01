#!/bin/bash

usage() {
    echo "usage: $0 [up|down] -i <nr_of_instances>"
    exit 1    
}

# check params
if [[ $# -lt 3 || -z $1 || ( "$1" != "up" && "$1" != "down" ) || ( "$2" != "-i" && "$2" != "-r" ) ]]; then
    usage
fi

get_team_name() {
    i=$1
    TEAM_NAME=$(python3 $SCRIPTPATH/team-name.py "$i")
    echo "[i] TEAM_NAME: ${TEAM_NAME}"
    export TEAM_NAME
}

handle_instance() {
    i=$1
    mkdir $FOLDER/$i
    cp $SCRIPTPATH/docker-compose.yml $FOLDER/$i/
    pushd $FOLDER/$i
    get_team_name "$i"
    export EPORT="88$i"
    podman-compose $ACTION $COMPOSE_OPTIONS
    popd
    
    if [ "$ACTION" == "up" ]; then
        podman exec -it ${i}_mariadb_1 mariadb -u root -prootpwd < <(cat $SQL_INIT_PATH; echo QUIT;)
    fi
}


SCRIPTPATH="$( cd -- "$(dirname "$0")" >/dev/null 2>&1 ; pwd -P )"
ACTION=$1
COMMAND=$2
INSTANCE_COUNT=$3
SQL_INIT_PATH=$SCRIPTPATH/secure-fileshare-backend/db/init.sql

if [ "$1" == "up" ]; then
    COMPOSE_OPTIONS="-d"
fi

# create temp folder
FOLDER=`mktemp -d -t ctf-XXXX`

if [[ "$COMMAND" == "-i" ]]; then
for i in `seq -f '%02g' 1 $INSTANCE_COUNT`; do
    handle_instance $i
done
elif [[ "$COMMAND" == "-r" ]]; then
    INSTANCE_COUNT=`printf '%02g' $INSTANCE_COUNT`
    ACTION='down'
    COMPOSE_OPTIONS=""
    handle_instance $INSTANCE_COUNT
    ACTION='up'
    COMPOSE_OPTIONS="-d"
    handle_instance $INSTANCE_COUNT
fi

# clean up
rm -rf $FOLDER
