# Amazon Price Tracker
This project was created to track the prices of various items on Amazon. It made use of various Amazon API's as well as screen scraping at regular intervals to get the updated prices as to avoid API rate limits.

# How To Run (Sort Of)

So this application's backend was written in PHP/MySQL. Unfortunately when I started this proejct and even made it public I never really planned on making it easy to install. Forgive me, I was naive. So these instructions will walk you through what to modify aside from setting up the database schema (someone feel free to write a script to generate the tables, and raise a pull request).

You'll want to modify these values found in [/includes/model/AmazonUtility.class.php](https://github.com/CodyEngel/Amazon-Price-Tracker/blob/master/includes/model/AmazonUtility.class.php), you can find details on generating these keys from the [Amazon Product Advertising API](https://affiliate-program.amazon.com/gp/advertising/api/detail/main.html).

```
private static $AWSAccessKey = "YOURACCESSKEY";
private static $AWSSecretKey = "YOURSECRETKEY";
private static $AWSAssociateTag = "YOURASSOCIATETAG";
```

Then in order to crawl items from the wishlist you'll need to run the [wishlist-crawler](https://github.com/CodyEngel/Amazon-Price-Tracker/blob/master/includes/wishlist-crawler.html) which can be done manually or through a cron job (or other script).

And that's it, aside from the MySQL database setup. One day I may revisit this project and do it in a more modern language such as Ruby or Node. If I do then I'll make it easier to setup, and again you are free to add the scripts to create the tables and raise a pull request and I'll pull it into the project.
