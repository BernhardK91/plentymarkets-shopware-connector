**Note: This is a fork from the original [repository](https://github.com/plentymarkets/plentymarkets-shopware-connector) because the connector is not updated anymore.**

**This version is compatible to Shopware 5.7.x**

# PlentyConnector

* **License:** MIT
* **Repository:** [Github](https://github.com/bernhardk91/plentymarkets-shopware-connector)
* **Documentation:** [Google Docs](https://docs.google.com/document/d/10mPeV3xqx4We71dYQdPmJK2qvb21Rpym6FG_tKwHKfc/edit?usp=sharing)

## Requirements

* plentymarkets version >= 7
* shopware version >= 5.6
* php >= 7.2
* shell access
* cronjobs
* active plentymarkets webshop
* plentymarkets user with all rest permissions

## Installation Guide

### Git

**Prepare Plugin**
* cd [Shopware-Folder]/custom/plugins
* git clone https://github.com/bernhardk91/plentymarkets-shopware-connector.git PlentyConnector
* cd PlentyConnector
* composer install --no-dev

**Install Plugin**
* cd [Shopware-Folder]
* ./bin/console sw:plugin:refresh
* ./bin/console sw:plugin:install --activate PlentyConnector
* ./bin/console sw:cache:clear

**Configure Plugin**
* visit yourshopwaredomain/backend
* open Settings > PlentyConnector
* add and test api creddentials
* complete all necessary mappings

## Update Guide

**Before you update the plugin, make sure to backup the PlentyConnector-folder (better your whole Shopware installation)!**

You can update via git with the following steps. If you do not wan't to use git, 
you can also download the files from github and paste them all into the 
/custom/plugin/PlentyConnector-Folder (override all files) via FTP or SFTP.

### Git clone

Follow these steps if it is the first time you update from this git repository.

* cd [Shopware-Folder]/custom/plugins/PlentyConnector
* rm -rf ./* (**Attention: this will delete all the plugins files!**)
* git clone https://github.com/bernhardk91/plentymarkets-shopware-connector.git .
* composer install --no-dev
* cd ../../../
* ./bin/console sw:plugin:refresh
* ./bin/console sw:cache:clear
* visit yourshopwaredomain/backend
* open Settings > PlentyConnector
* got to Mappings and click "Save Settings"


### Git pull

If you already cloned the git repository you can seamply update by the following steps:

* cd [Shopware-Folder]/custom/plugins/PlentyConnector
* git pull
