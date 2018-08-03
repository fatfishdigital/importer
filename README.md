# importer plugin for Craft CMS 3.x

XML importer

![Screenshot](resources/screenshot/plugin-logo.png)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.


1. Install latest version of curl visit **https://curl.haxx.se/docs/** for details
2. Open your terminal and go to your Craft project:

        cd /path/to/project

3. Then tell Composer to load the plugin:

        composer require github.com/fatfish/importer

4. In the Control Panel, go to Settings → Plugins and click the “Install” button for importer.


## importer Overview
XML Importer allow user to import xml data from api.
     

## Configuring importer

1. Go to settings tab and fill the api url, choose Feed type (XML only supported for now), Choose Primary Element for XML and choose Entry Type.
2. Click on Feeds tab, Map the required field as necessary and click on save configuration. 
3. Click on Run importer this will run import data from api to your nominated Entries.
4. If you like to run cron job then click on cron tab and fill the required field and click on save. This will run the cron job at specified time.


## Using importer

![Screenshot](resources/screenshot/screenshot-1.png) 

![Screenshot](resources/screenshot/screenshot-2.png) 

![Screenshot](resources/screenshot/screenshot-3.png)

## importer Roadmap

More to come on future.

##bug
Please feel free to report bugs or issues you have. For suggestion you can email us on admin@fatfish.com.au

Brought to you by [Fatfish] **https://fatfish.com.au**
