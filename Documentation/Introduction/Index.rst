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

By default ESI-Tag rendering is enabled. You can use ESI-Tags for each Content-Element and set alternative content
element. Just use tab "Caching" in backend content element form. When caching is disabled for a content element,
this element will be included as <esi:include>-script.
