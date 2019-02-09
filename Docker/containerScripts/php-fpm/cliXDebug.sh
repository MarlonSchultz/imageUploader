#!/usr/bin/env bash
# serverName is the your IDE has a mapping for
# i.e: PHPSTORM: Languages & Frameworks > PHP > Servers
export PHP_IDE_CONFIG="serverName=_"

# Check if port and ip are correct
# get ip from host ON host with
# export DOCKER_HOST_IP=$(ip -4 addr show docker0 | grep -Po 'inet \K[\d.]+')
export XDEBUG_CONFIG="remote_enable=1 remote_mode=req remote_port=9001 remote_host=172.17.0.1 remote_connect_back=0"