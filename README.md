WP CTF 2022 Challenge
=====================

This repo contains the challenge of WP CTF 2022.

Start the challenge
-------------------

1. build images
```
   $ cd secure-fileshare-backend
   $ podman build . -t ctf-backend --no-cache
   $ /cd ../secure-fileshare-frontend
   $ podman build . -t ctf-frontend --no-cache
   $ cd ..
```
2. start containers
```
   $ cd ../secure-fileshare-docker-compose
   $ bash -x instance-handler.sh up -i 01
```

3. instance should be reachable @ http://localhost:8801/

Writeup
-------
https://github.com/CyberSp3ck/ctf-writeups/tree/main/wpctf22
