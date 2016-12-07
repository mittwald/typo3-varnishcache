.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _admin-manual:

Administrator Manual
====================

Target group: **Administrators**


.. _admin-installation:

Installation
------------

.. _Configuration Reference: ../Configuration/Index.rst 

- Install "varnishcache" extension through extension manager
   - "Admin Tools" > "Extensions" > "Get Extensions" > "varnishcache"
- Include "varnishcache" in static typo template 
   - "Template" > "Root-Template" > "Info/Modify" > "Edit the whole template reord" > "Includes" > "Include static (from extensions)" > "varnishcache"
- Enable cURL via "Install Tool"
   - "System" > "Install" > "All configuration" > "curlUse"
- Make sure you have at least one domain record configured
   - "List" > "Create new record" > "System Records" > "Domain"
- Create varnish server configuration
   - "List" > "Create new record" > "Varnishcache" > "Varnish server configuration" 
   - See `Configuration Reference`_ for default configuration  
   
Flush varnish cache by TYPO3
----------------------------
In order to automatically clear varnish cache by hitting "Clear frontend cache" or "Clear single page cache" you need a configured domain record as well as a varnish server configuration. These conditions are already fulfilled if you followed our "Installation"-Guide. 

Logging
----------------------------
All requests to the varnish server are logged in backend module "Log".
