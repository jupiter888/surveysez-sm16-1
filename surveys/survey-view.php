<?php
/**      This DOC BLOCK is explaining what is ocurring:
survey-view.php along with index.php create a list/view application
 * 
 * @package SM16
 * @author Daniel Tracy <visual.eyes.success@gmail.com>
 * @version 0.1 2016/07/25
 * @link http://bigdatascience.xyz/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see index.php
 * @see Pager.php
 *
 * @todo none
*/
# '../' works for a sub-folder.  use './' for the root  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
 
# check variable of item passed in - if invalid data, forcibly redirect back to ice-cream-list.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "surveys/index.php");
}

//sql statement to select individual item
//$sql = "select * from sm16_IceCream where IceCreamID = " . $myID
//$sql = "select * from sm16_surveys where SurveyID = " . $myID;
//---end config area --------------------------------------------------


#the query string delivers a number(isset above), and then we can create an object of the survey class

$mySurvey = new Survey($myID);

    if($mySurvey->isValid)
    { 
      //load survey title in title tag
      $config->titleTag = $mySurvey->Title;
    }
    else
    {
        //sorry no survey?
        $config->titleTag = 'Sorry, no such survey';
    }




//use this if there is a problem, to see objects
//dumpDie($mySurvey);

# END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to theme header or header_inc.php
?>
<h3 align="center"><?=$config->titleTag?></h3>







 
<?php
get_footer(); #defaults to theme footer or footer_inc.php



#make survey object
class Survey
{ 
    #build properties outside the function, then use $this->
    public $Title='';
    public $Description='';
    public $SurveyID=0;
    public $isValid= false;
    

    
    
        #id is a declared variable that will die at the end of the constructor,
        #this is a variable apart of a function and the curly braces contain the variable,
        #so its not the same as myID as above.
        #the names have to be in order
    
    public function __construct($id) 
        #survey class creates objects, so the method needs a parameter, a unique object of this class
        {  
          //forcibly cast to integer
          //eliminates the possibility of sql being passed (compromising security) 
          //this is a precaution that is commonly used
          $id= (int)$id;
          $sql = "select * from sm16_surveys where SurveyID = " . $id;
           
        $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

        if(mysqli_num_rows($result) > 0)
            {
               #records exist - process
               $this->SurveyID= $id;
               $this->isValid = true;	
                    while ($row = mysqli_fetch_assoc($result))
                        {   //make the ice cream data
                            $this->Title = dbOut($row['Title']);
			                $this->Description = dbOut($row['Description']);
                        }
            }

         @mysqli_free_result($result); # We're done with the data!

        
        
        
        
        
        
        
        
        
        }#end survey constructor
}











































