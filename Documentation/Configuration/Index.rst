.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _configuration:

Configuration Reference
=======================

Target group: **Developers**


.. _configuration-typoscript:

Default configuration
---------------------
* By default a new page type is set for uncached content elements. If you need another page type just change it by using constant editor.
* Make sure static typoscript is included into page template

Varnish Server Configuration
----------------------------
* Best suited set up your varnish server configuration on rootpage or domain start page.
.. image:: ../Images/varnish-configuration-entity.png
* If fully configured, varnish cache is cleared automaticcly for each configured domain on "Clear frontend cache", "Clear current page cache" or even a record has been changed on page.

Default BAN Method for VCL
--------------------------
.. code-block:: python

if (req.request == "BAN") {
    if (req.http.X-Host) {
        ban("req.http.host == " + req.http.X-Host + " && req.url ~ " + req.http.X-Url + "[/]?(\?|&|$)");
        error 200 "OK";
    } else {
        error 400 "Bad Request";
    }
}

Example Varnish VCL config template
--------------------------

.. _Varnish 3 VCL: ../../Resources/Private/Example/default-3.vcl

You need an example VCL configuration file for your Varnish? Feel free to take this one: `Varnish 3 VCL`_
