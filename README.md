# Twitch Channel Status Check

### Archived

This is no longer maintained and should not be utilized as it is terribly slow, resource intensive, would require constantly checking for updates to the Twitch UI - thus making it unreliable, and there are far more efficient ways to obtain the status of a Twitch stream by way of the Twitch API or GraphQL interface. It was a project undertaken at the time as more of a "what if I could", but is not something that should ever be used in a production environment. No further changes will be made to this repository.

### Overview

This was written on a whim to determine the status of a Twitch channel - namely, to determine whether or not it was Online or Offline.

----

### Requirements:

 * [PHP](https://www.php.net/) 7.3 (or newer)
 * [PhantomJS](https://phantomjs.org/)

This supports depedency checking, and will check for the installation of PhantomJS. If it is not present, then the script will exit and inform accordingly.

This was developed in a Linux environment (specifically, Fedora Linux); and while it has not been tested under Windows nor macOS, it was designed with cross-platform compatibility in mind. As such, while it does rely on external libraries, if such is not discoverable via `$PATH` (or, in the context of Windows, `where`), then the location of such will need to be specified in [res/config.inc.php](res/config.inc.php).

----

### Configuration:

The URL to the Twitch Channel to check should be defined in the parameter "`["config"]["twitch"]["url"]`" in [res/config.inc.php](res/config.inc.php).

Further configuration options are present in the same file. Review all available configuration options for more information.

#### Configuration Notes: 

 * Process locking is available in the event that this script is called in a fashion that should require it.
 * If process locking is enabled, the script will attempt to discover and write a lock file to the default system temporary directory. If "`["process_lock"]["deletion"]`" is set to a boolean value of `true` (default value), then said lock file will be cleared upon the script's completion, or in the event of an exception error .
 * Ideally, neither the PhantomJS script itself, nor the parameters present in [res/config.inc.php](res/config.inc.php) (specified under `["phantomjs"]`) should need to be modified. However, adjustment to the timeout period may be needed in the script itself dependent upon network conditions.
 * In the configuration file, there is a parameter under "`["phantomjs"]`" named "`["putenv"]`". In some environments that this script was tested in, the following error would occur:
 ```
Auto configuration failed
140338006058816:error:25066067:DSO support routines:DLFCN_LOAD:could not load the shared library:dso_dlfcn.c:185:filename(libssl_conf.so): libssl_conf.so: cannot open shared object file: No such file or directory
140338006058816:error:25070067:DSO support routines:DSO_load:could not load the shared library:dso_lib.c:244:
140338006058816:error:0E07506E:configuration file routines:MODULE_LOAD_DSO:error loading dso:conf_mod.c:285:module=ssl_conf, path=ssl_conf
140338006058816:error:0E076071:configuration file routines:MODULE_RUN:unknown module name:conf_mod.c:222:module=ssl_conf
```
 This error would continue to occur in differing environments. To counteract it, setting this value to `true` will cause PHP to export the following environmental variable: `export OPENSSL_CONF=/etc/ssl/`
 
----

### Notes:

 * In direct correlation to the size of the Twitch page that will be retrieved, PHP may exhaust the set memory limit. Configuring the parameter `memory_limit` in `php.ini` accordingly may be necessary.
 * Although PhantomJS is open source, it has not seen a new release since [2.1.1 - dated January 24, 2016](https://github.com/ariya/phantomjs/releases/tag/2.1.1). This script relies upon PhantomJS as Javascript support is a required function of Twitch. As a result, usage of cURL is not a viable option for this project. Should Twitch break this in some way that PhantomJS no longer supports it, this script can be considered to be functionally unusable - barring an update that utilizes an alternative method such as [Symfony Panther](https://github.com/symfony/panther).
 * Concurrently, although PhantomJS is open source, and although the [configuration file](res/config.inc.php) makes reference to it being in the path [./bin](bin/), PhantomJS is not distributed as a part of this project. Binaries should instead be retrieved from [PhantomJS' official source](https://phantomjs.org/). Additionally, specifying the path to PhantomJS is optional as the script will look for it on the default system `$PATH` (see: Requirements); though the default specification in [res/config.inc.php](res/config.inc.php) will look for it at `./bin/phantomjs`, that parameter can be simply blanked out (rely on `$PATH`), or pointed to the full path of the binary.
 * This project makes use of [simplehtmldom](https://github.com/simplehtmldom/simplehtmldom) for parsing through the DOM of the retrieved page.

----

### Standard Fare:

This is provided with no warranty nor a gaurantee of any kind.
