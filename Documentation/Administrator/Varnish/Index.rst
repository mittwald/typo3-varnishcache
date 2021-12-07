.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _admin-varnish:

Varnish Configuration
=====================

The extension sends the following additional headers to configured Varnish servers.

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Header:

   :Description:
         Description:

 - :Field:
         X-Host

   :Description:
         The domain name configured in the Varnish Controlled Domain record.

 - :Field:
         X-Url

   :Description:
         The current url path

Default BAN Method for VCL
--------------------------
.. code-block:: python

    if(req.method == "BAN") {
        if(client.ip ~ allowban) {
            if (req.http.X-Host && req.http.X-Url) {
                ban("req.http.host == " + req.http.X-Host + " && req.url ~ " + req.http.X-Url);
                return (synth(200, "Banned on domain " + req.http.X-Host + " - URL: " + req.http.X-Url)) ;
            } else {
                return (synth(400, "BAD REQUEST"));
            }
        } else {
                return (synth(200, "NOT AUTHORIZED IP: " + req.http.X-Real-IP));
        }
    }

Example Varnish VCL configuration template
------------------------------------------

It is highly recommended to read the Varnish documentation https://varnish-cache.org/docs/ in order to setup
a configuration that suits your needs.

The extension ships with 2 example varnish configurations (vcl 3 and vcl 4) in the `Resources/Private/Example` folder.

Edge Side Includes
------------------

If the extension is configured properly and ESI support is enabled in your Varnish configuration, Varnish will output
uncached by fetching the content through a HTTP request.

.. important::

Note, that the usage of ESI includes may limit the performance gain Varnish delivers. It is therefore recommended to
avoid uncached content as much as posible.
