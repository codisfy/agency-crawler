## AgencyCrawler

### Tech Stack 
* Laravel 8 
* InertiaJS
* Vue3
* Bootstrap 5


### Code structure

The majority of the code is inside app/Modules directory. 

* Crawler - Processes a particular URL and returns an array of CrawlResult objects
* CrawlResult - Is a Data Object - used to retrieve data
* Parser - extracts various information from the retrieved results
* Fizzle - Name inspired from Guzzle - does a curl request and returns a FizzleResponse Object
* FizzleResponse - Data Object stores the content and request info. 
* CrawlerResponse - Arrayable response object

### JS

Stats calculation is mostly done on the frontend which is Vue3. 

The JS code is inside `resources/js`

### Possible Improvements

* Support more than 10 URLs
* Add parallel processing or Queues
* Use database to store requests and ability to view past data.
* Test Cases
