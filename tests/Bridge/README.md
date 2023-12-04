# PHPUnit Bridge

This bridge exists because the tearDown function defined in PHPUnit 10 is incompatible with PHPUnit 5
as it includes a defined return type.

The bridge gets around this issue by selectively loading the appropriate foundational class
based on the detected PHP runtime environment via the `autoload.php` file that is loaded
via Composer.

Once PHP 5.6 support is not needed, then this bridge functionality can be entirely removed and
the functionality moved to the normal `TestCase` class.
