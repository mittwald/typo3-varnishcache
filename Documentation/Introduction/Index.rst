.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _introduction:

Introduction
============


.. _what-it-does:

What does it do?
----------------

Extension provides functionality to use ESI-Tags for configured Varnish Server. Additionally you can add Varnish
Server Configuration to clear Varnish Cache for single pages or full domain cache out of TYPO3 CMS.

By default ESI-Tag rendering is enabled. All COA_INT objects are included as <esi:include>-script. Additionally
you can use ESI-Tags for each Content-Element and set alternative content element, if ESI function is not enabled
by varnish server. Just use tab "Caching" in backend content element form.
