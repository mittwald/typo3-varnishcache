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
- Just install extension through extension manager
- Include static typoscript settings
- curl needs to be enabled

Flush varnish cache by TYPO3
----------------------------
- To enable automatic cache clearing if you hit "Clear frontend cache" or "Clear single page cache" you have to configure a varnish server configuration on root page in backend.
- Additionally you have to configure at least one domain record.
- Requests to varnish server are logged in backend module "Log"
