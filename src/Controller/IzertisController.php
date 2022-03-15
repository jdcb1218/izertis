<?php
/**
 * @file
 * @author Juan Ceballos
 * Contains \Drupal\izertis\Controller\izertisController.
 */
namespace Drupal\izertis\Controller;
/**
 * Provides route responses for the izertis module.
 */
class IzertisController {

  /**
   * Returns a info Token Api(izertis).
   *
   * @return array
   *   A simple renderable array.
   */

		public function get_storage(){
			$storage = array();
			$storage['data']['domain_api_key'] =  \Drupal::state()->get('domain_api_key');
			$storage['data']['ts'] =  \Drupal::state()->get('ts');
			$storage['data']['public_key'] =  \Drupal::state()->get('public_key');
			$storage['data']['private_key'] =  \Drupal::state()->get('private_key');
			$storage['data']['endpoint_comics'] =  \Drupal::state()->get('endpoint_comics');
			$storage['data']['endpoint_characters'] =  \Drupal::state()->get('endpoint_characters');
		  return $storage['data'];
		}

    public function getToken(){
    	$data = IzertisController::get_storage();
     	$hash_config = $data['ts'] . $data['private_key'] . $data['public_key'];
		  $hash = md5($hash_config);
			return $hash;
   	}

   	public function get_comics(){
   		$data = IzertisController::get_storage();
   		$hash = IzertisController::getToken();
   		
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $data['domain_api_key'].$data['endpoint_comics'].'?ts='.$data['ts'].
			  							 '&apikey='.$data['public_key'].'&hash='.$hash.'',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'GET',
			));

			$response = curl_exec($curl);
			curl_close($curl);
			return $response;
   	}

   	public function get_characters(){
   		$data = IzertisController::get_storage();
   		$hash = IzertisController::getToken();

			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $data['domain_api_key'].$data['endpoint_characters'].'?ts='.$data['ts'].
			  							 '&apikey='.$data['public_key'].'&hash='.$hash.'',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'GET',
			));

			$response = curl_exec($curl);
			curl_close($curl);
			return $response;
   	}
}
?>