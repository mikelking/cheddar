#### Start of the repo documentation

To this point the project has been largely experimental. Fortunately I have succeeded in actually deploying a production WordPress MultisSite with the project code. In addition I have successfully executed phpunit based unit tests against an mu-plugin and a regular plugin.

With regards to unit testing the plan is to use a library like WP_Mock to facilitate encapsulated unit testing in lieu of the often adopted WordPress integration tests. I plan on investigating the latter once this project steps up to a build server like Jenkins.  

I have added an update to the build script to auto set the revision using `git rev-parse --short HEAD` and will incorporate this into the wpcfg in the future. if the revision file is found read teh contents and place it in a $scf attribute. This will make the site version a property one can use for enqueuing assets or just reference.


