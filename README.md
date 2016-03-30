# Director

## Installation

Firstly, this is a composer package. So to get up an running, you'll need to add this repo to the composer.json file.


## Sample Usage

Here is an example of how you might use this...

```php
	
	//autoloader
	require_once('vendor/autoload.php');

	//what we are using
	use Dao\Director;
	use Dao\Url;

	try{

		//get the data - see sample data below
		$data = file_get_contents('redirects.json');
		$data = json_decode($data);

		//find the current URL
		$url = new Url();
		$from = $url->get('path');

		//add data to the director instance
		$director = new Director($data, array(
			'defaultLocation' 	=> '/',
			'testMode'			=> true,

			//not in use at the moment
			'permanent'			=> true,
			'preserveQuery'		=> true
		));

		//find a match from the data
		if($director->findMatch($from)){

			//point to it
			$director->pointToMatch();
		}

	}catch(Exception $e) {
		echo $e->getMessage();
	}

```


## Sample data

Please note the options are not in use as of yet. This will be implemented later on.

```json
	[
		{
			"from": "/old/url/",
			"to": "/new/url/"
		},
		{
			"from": "/another/old/url/",
			"to": "/another/new/url/",
			"options" : {
				"preserveQuery" : true,
				"permanent": true
			}
		}
	]
```