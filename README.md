# dummy
A dummy repo for experimenting with composer

- So the purpose of this test is to build a working composer driven build of WordPress and related plugins. I hope to define a site by plugins and git libraries in composer and have a site completely assembled via a composer update type command.

- At this point I have it building the WordPress tree and have setup the iggie file to ignore ALL of WordPress core.

- Updated to install the mu-plugins. DONE

- Updated to installed the WordPress common config system. DONE

- Assuming that you already have a DB setup and enter the appropriate details in the respective config file this should yield a working shell site.   

- Need to add a Vagrant conf so that one can eventually run vagrant up to build a working local dev based upon the WordPress tree installed by composer. The Vagrant system would need to setup the db and conf files.
