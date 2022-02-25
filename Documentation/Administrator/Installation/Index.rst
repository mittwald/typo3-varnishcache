.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _admin-install:

Installation
============

- Install "varnishcache" extension through extension manager or using composer
- Include "varnishcache" in static typoscipt template
- Create at least one domain record
- Create varnish server configuration

See :ref:`admin-configuration` for setup instructions for the domain record and varnish server.

.. important::

In order to automatically clear the varnish cache by hitting "Flush frontend caches" or "Flush all caches" you need a
configured domain record as well as a varnish server configuration.

