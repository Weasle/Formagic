FORMAGIC README
===============

Thank you for your interest in Formagic.
The source files within are subject to the new BSD license that is bundled
with this package in the file LICENSE.

This is a beta release of Formagic. Please check http://formagic.sourceforge.net
for updates of Formagic. There will be a final release as soon as all intended
features are implemented and fully tested.


SYSTEM REQUIREMENTS
-------------------
There are not many requirements for using Formagic, any PHP 5 will do (PHP 4 is
not supported). Formagic will run with any php.ini settings. It is designed to
run without errors or warnings down to E_STRICT level.


GETTING STARTED
---------------
Just unpack the ZIP-File into a directory of your choice and include
formagic.php in your code.

Please visit http://www.formagic-php.net for examples and How-Tos.
Here is a very short example of Formagic to get you started:

<?php
    include('Formagic.php');
    $form = new Formagic();
    $form
        ->addItem('input', 'myInput', array('label' => 'My first input'))
        ->addItem('submit', 'mySubmit', array('label' => 'Send'));

    if ($form->validate()) {
        echo "submitted and ok<br />";
        $form->setReadonly(true);
    }
    echo $form->render();
?>

Of course there is much more to Formagic than this, but perhaps this will get
you a picture how easy it is to create HTML forms with Formagic.


API DOCUMENTATION
-----------------
The API documentation is created using phpDocumentor.
You will find the current API documentation here:
http://www.formagic-php.net/docs/api/


FORMAGIC LINKS
--------------
You will find the official Formagic project site and documentation here:
http://www.formagic-php.net

Formagic Sourceforge page:
http://sourceforge.net/projects/formagic/

Please feel free to report any feature request or bugs here:
http://sourceforge.net/tracker/?group_id=202176
