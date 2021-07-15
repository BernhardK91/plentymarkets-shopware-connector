**Note: This is a fork from the original [repository](https://github.com/plentymarkets/plentymarkets-shopware-connector) because the connector is not updated anymore.**

# PlentyConnector

* **License:** MIT
* **Repository:** [Github](https://github.com/bernhardk91/plentymarkets-shopware-connector)
* **Documentation:** [Google Docs](https://docs.google.com/document/d/10mPeV3xqx4We71dYQdPmJK2qvb21Rpym6FG_tKwHKfc/edit?usp=sharing)

## Requirements

* plentymarkets version >= 7
* shopware version >= 5.5
* php >= 7.1
* shell access
* cronjobs
* active plentymarkets webshop
* plentymarkets user with all rest permissions

## Installation Guide

### Git

**Prepare Plugin**
* cd Shopware/custom/plugins
* git clone https://github.com/bernhardk91/plentymarkets-shopware-connector.git PlentyConnector
* cd PlentyConnector
* composer install --no-dev

**Install Plugin**
* cd Shopware
* ./bin/console sw:plugin:refresh
* ./bin/console sw:plugin:install --activate PlentyConnector
* ./bin/console sw:cache:clear

**Configure Plugin**
* visit yourshopwaredomain/backend
* open Settings > PlentyConnector
* add and test api creddentials
* complete all necessary mappings

