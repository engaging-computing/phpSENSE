#!/bin/bash
mkdir $HOME/PINCushion
cp .files/* $HOME/PINCushion -r
cp .files/Goldeneye.sh $HOME/Desktop
sudo cp .files/librxtxSerial.so /usr/lib/jvm/java-6*/jre/lib/i386
sudo cp .files/RXTX* /usr/lib/jvm/java-6*/jre/lib
