
# Installation

1. Download the [whybug.phar](https://github.com/whybug/whybug-php/raw/master/dist/whybug.phar) file
and put it somewhere on your system, for example `/usr/local/lib/`.

2. Add the phar file to the `auto_prepend_file` setting in your `php.ini`.

```
# Add to php.ini
auto_prepend_file = /usr/local/lib/whybug.phar
```
