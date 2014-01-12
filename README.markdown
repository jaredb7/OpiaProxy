This project was inspired by translink-opiaproxy (https://github.com/wislon/translink-opiaproxy), who has done a awesome job of making the 
Translink OPIA API easy to use.

I almost exclusively use the CakePHP framework when programming in PHP, so it made sense to make a plugin for it.

Most of the API models we're code-genned using the Swagger CodeGen project as a starting point and then modified to suite.
The proxy component is a 'grown up' version that was used in another project I contributed to (Survive Z Day), it can be extracted and used elsewhere easily with little modification.

Hopefully this project speeds and helps projects for those developing on the Translink OPIA API :).

## INSTALLATION

__1__ - Clone the project into your apps plugins-folder (app/Plugin/)

__2__ - Enable the plugin in your app/Config/bootstrap.php file
```
      CakePlugin::load(array('OpiaProxy' => array('bootstrap' => true, 'routes' => true)));
```

__3__ - Make a copy 'bootstrap_default.php' and name it 'bootstrap.php'

__4__ - Import 'proxy_cache.sql' into your database


## CONFIGURATION

Fill out your OPIA API login details located near the top of the bootstrap.php config file

```
    Configure::write('OpiaProxy.opiaLogin', ""); //Your login as supplied by Translink
    Configure::write('OpiaProxy.opiaPassword', ""); //Your password as supplied by Translink
```

Also configure the caching system to use, valid options are file, mysql or sqlite.
The default option is 'file'
```
    Configure::write('OpiaProxy.perist_type', "file");
```

## USAGE
Choose what API you wish to use and include its 'model' with App::uses, and instantiate it.
Use https://opia.api.translink.com.au/v1/content/swaggerui/index.aspx to find out how the options should be set
```
  App::uses('LocationApi', 'OpiaProxy.Model');
  class MyTestAppController{
  
  private $location_api_client;
  
  //Method to get Stop timetables for a particular date 
    public function api_test(){
        //Instantiate the class
        $this->location_api_client = new NetworkApi();
    
        $obj = $this->location_api_client->resolve('toowong', 2, 1);
  
        //The returned type is a object, in this case a Locations object with an array of locations
        //again please refer to https://opia.api.translink.com.au/v1/content/swaggerui/index.aspx
        //For the object structure and schema.
  
        echo json_encode($obj);
        
        //The result will be something like this, this is exactly what you get if accessing the OPIA API directly
        //{"Locations":[{"Id":"AD:Ivy St, Toowong","Description":"Ivy St,Toowong","Type":"2","Position":{"Lat":-27.483126955414,"Lng":152.97993676781},"LandmarkType":"","__type":"Address:http:\/\/opia.api.translink.com.au\/2012\/04","StreetName":"Ivy","StreetNumber":"","StreetType":"St","Suburb":"Toowong"
        
      }
    }
  }
}
```
