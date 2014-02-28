#!/bin/bash
 
# Install dictionaries
mkdir -p /etc/elasticsearch/hunspell/cs_CZ
yes | \cp -rf /vagrant/puphpet/files/elasticsearch-dictionaries/* /etc/elasticsearch/hunspell/cs_CZ
chown -R elasticsearch:elasticsearch /etc/elasticsearch/*
