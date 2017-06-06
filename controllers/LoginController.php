<?php	
include('Controller.php');
include(dirname(__FILE__) . '/../db_utils/JSONator.php');
include(dirname(__FILE__) . '/../models/User.php');


/**
* LoginController handles all request to end point:
* http://localhost/jsonator/api.php/login
*
* @author Rylan Gotto <rgotto2@gmail.com>
*/
class LoginController extends Controller
{
    /**
     * Construct LoginController Object and establish connection to DB. Invoke respective request
     * handler for login controller
     * @param Request $request The Request that contains the method and data required.
     */
	public function __construct($request){
		$configs = include(dirname(__FILE__) . '/../db_utils/settings.php'); 
		$this->userDB = new JSONator($configs['userCollectionFilePath'], User::jsonSchema());  
		$this->requestHandler($request);
    }

    /**
     * Handles a request coming into the login page. Determines if HTTP method is allow  
     * and looks up to see if email exist in user collection, if user exists return JSON of 
     * user info or 404 if email does not exist.
     * @param Request $request The Request that contains the method and data required.
     */
    protected function requestHandler($request)
    {
    	if ($request->method == 'POST'){
            $email = json_decode($request->post_data, true);
            $email = $email[0]['email'];

            $foundUser = false;

            if(filter_var($email, FILTER_VALIDATE_EMAIL)) { //check if email is valid
                $records = $this->userDB->read();
                foreach($records as $key => $val)
                {

                    if(trim($email) == $val['email']) //if user email is found render response as JSON 
                    {
                        $this->render($val);
                        $foundUser = true;
                        break;
                    }
                }
                if(!$foundUser)
                {
                    graceful404('Invalid Email Address');
                }
                
            }
            else {
                graceful404('Invalid Email Address');
            }

    	}else{
            graceful404('Method type is not supported');
        }
    }
}
