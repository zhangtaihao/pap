
 Pap: Minimal PHP application framework
===========================================
Author: Taihao Zhang
Email: jason@zth.me

The Pap framework is a PHP application framework for building configurable &
easily deployed web applications. This system intentionally only provides an
application handler architecture, without any external database libraries, so
that the application has no unnecessary dependencies. It is the responsibility
of the result application to specify how to define the configuration, beyond a
basic XML configuration structure to leverage the built-in automatic object
factory for initializing an application.


 System requirements
------------------------
The default framework requires the following components to function:

 - Web server (e.g. Apache)
 - PHP 5.2 or later
 - PHP extensions
   - SPL (on by default, always on in PHP 5.3)


 License
------------
This package is released under the General Public License, version 2. See
LICENSE.txt for license details.
