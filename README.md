# dummy
A dummy repo for experimenting with composer

- So the purpose of this test is to build a working composer driven build of WordPress and related plugins. I hope to define a site by plugins and git libraries in a composer.json manifest and have a site completely assembled via a composer update type command.

- At this point I have it building the WordPress tree and have setup the iggie file to ignore ALL of WordPress core.

- The order of requirement is critical to successfully install ing all of the moving parts.
  - At this point the wproot is installed first then the WordPress core and finally the mu-plugins, plugins and themes.

- Updated to install the mu-plugins. DONE

- Updated to installed the WordPress common config system. DONE

- Assuming that you already have a DB setup and enter the appropriate details in the respective config file this should yield a working shell site. 

- Still need to hook all of this into a deployment solution like deploybot. TODO

- Need to add a Vagrant conf so that one can eventually run vagrant up to build a working local dev based upon the WordPress tree installed by composer. The Vagrant system would need to setup the db and conf files. PARTIAL

- Add demo to explain how to add plugins and themes from https://wpackagist.org. By adding the following line to the build chain in the required section of the composer.json it will add the appropriate plugin to the installation. You want to ensure that you add this line after the line that directs composer to install wordpress.

```
"wpackagist-plugin/akismet": "dev-trunk",
```