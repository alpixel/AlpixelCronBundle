CRONBundle
===========

The CRONBundle provides a Symfony Bundle capable of saving cronjob and running them at given intervals.



## Installation

1. Install the package

```
bash
$ composer require 'alpixel/cronbundle:~1.0'
```

If the package can't be found, please check that the alpixel packages repository has been added to the project :

```
    "repositories": [
        {
            "type": "composer",
            "url": "http://packages.alpixel.fr/"
        }
    ],
```

2. Update DB Schema

```
php app/console doctrine:schema:update
```

3. Start using the bundle

```
//analyze all the cron task available and register them
php app/console cron:scan 

//Run the cron analyzer
php app/console cron:run
```

4. CRON setup

In order to run the symfony cron:run task, you should setup a real cronjob on the server just as follows. We added a rule for flushing the logs every day.

```
*/5 * * * * /path/to/symfony/install/app/console cron:run
0 0 * * * /path/to/symfony/install/app/console cron:run
```

5. Deploy.rb setup

```
after "deploy:restart" do
...
    run "php #{current_path}/app/console cron:scan"
...
end
```

## Creating a new task

Creating your own tasks with CronBundle couldn't be easier - all you have to do is create a normal Symfony2 Command (or ContainerAwareCommand) and tag it with the @CronJob annotation, as demonstrated below:

```php
/**
 * @CronJob("PT1H")
 */
class DemoCommand extends Command
{
    public function configure()
    {
        // Must have a name configured
        // ...
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        // Your code here
    }
}
```

The interval spec ("PT1H" in the above example) is documented on the [DateInterval](http://php.net/dateinterval) documentation page, and can be modified whenever you choose. For your CronJob to be scanned and included in future runs, you must first run app/console cron:scan - it will be scheduled to run the next time you run app/console cron:run
