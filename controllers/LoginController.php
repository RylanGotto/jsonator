<?php	
include('Controller.php');
include(dirname(__FILE__) . '/../db_utils/JSONator.php');
include(dirname(__FILE__) . '/../models/User.php');

class LoginController extends Controller
{
	public function __construct($request){
		$configs = include(dirname(__FILE__) . '/../db_utils/settings.php');
		$this->userDB = new JSONator($configs['userCollectionFilePath'], User::jsonSchema());  
		$this->requestHandler($request);
    }

    
    protected function requestHandler($request)
    {
    	if ($request->method == 'POST'){
            $email = json_decode($request->post_data, true);
            $email = $email[0]['email'];

            $foundUser = false;

            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $records = $this->userDB->read();
                foreach($records as $key => $val)
                {

                    if(trim($email) == $val['email'])
                    {
                        $this->render($val);
                        $foundUser = true;
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
