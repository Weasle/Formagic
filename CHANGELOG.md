Formagic 1.0
============
* Introducing v1.0 (API version 2.0). A lot changed since 0.2, although the
  basic mechanism of creating and adding form items still the same.
* UnitTest suite provided
* Stable release. All releases within major revision 1 will be backward
  compatible to API 2.0.

Formagic 1.0.1
==============
* Fix for Formagic Rule ItemDependecy: Rule phrasing was inverted
  http://sourceforge.net/tracker/?func=detail&aid=3330583&group_id=202176&atid=980533

Formagic 1.0.2
==============
* Formagic construct option "filters" and "rules" now accept arrays (again)
* Fixed filtering of fields containing integer values
* Fixed non-mandatory numeric rule validation with no request value
* Fixed bug for chained filters: Last output is passed to the next filter
  correctly now.

Formagic 1.0.3
==============
* Removed redundant attribute Formagic::_attributes()

Formagic 1.1
============
* Added XSRF protection item
* Added Formagic Session API
* Switched to PHPDocumentor 2.0
* Fixed link to license agreement
* Fixed documentation issues

Formagic 1.5
============
* Switched to Github
* Introduced composer/packagist compatibility
* Introduced autoloader
* Removed all require statements
* Introduced value object for file uploads, allowing to attach common filters and rules without breaking (even those
  common filters and rules would probably not be very useful for uploaded files)

Formagic 1.5.3
==============
* Bugfix issue "Missing closing label-tag" #10
* Bugfix issue "Formagic_Item_Container does not aggregate the rule violations for contained items" #8
* Bugfix issue "Formagic needs an accesor for its name." #14
* Added Formagic_Translator_Interface

Formagic 1.5.3-pl1
==================
* Bugfix issue #24

Formagic 1.5.4
==============
* New feature #13
* Bugfix #23
