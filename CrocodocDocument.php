<?php
require_once 'Crocodoc.php';

/**
 * Provides access to the Crocodoc Document API. The Document API is used for
 * uploading, checking status, and deleting documents.
 */
class CrocodocDocument extends Crocodoc {
	/**
	 * The Document API path relative to the base API path
	 * 
	 * @var string
	 */
	public static $path = '/document/';
	
	/**
	 * Delete a file on Crocodoc by UUID.
	 * 
	 * @param string $uuid The uuid of the file to delete
	 * 
	 * @return bool Was the file deleted?
	 * @throws CrocodocClientException
	 */
	public static function delete($uuid) {
		$postParams = array(
			'uuid' => $uuid,
		);
		return static::_request('delete', null, $postParams);
	}
	
	/**
	 * Check the status of a file on Crocodoc by UUID. This method is
	 * polymorphic and can take an array of UUIDs and return an array of status
	 * arrays about those UUIDs, or can also take a one UUID string and return
	 * one status array for that UUID.
	 * 
	 * @param string $uuids An array of the uuids of the file to check the
	 *   status of - this can also be a single uuid string
	 * 
	 * @return array An array of the uuid, status, and viewable bool, or an
	 *   array of the uuid and an error
	 * @throws CrocodocClientException
	 */
	public static function status($uuids) {
		$isSingleUuid = is_string($uuids);
		if ($isSingleUuid) $uuids = array($uuids);
		$getParams = array(
			'uuids' => implode(',', $uuids),
		);
		$response = static::_request('status', $getParams, null);
		
		if ($isSingleUuid) {
			if (empty($response[0]['uuid'])) {
				return self::_error('missing_uuid', __CLASS__, __FUNCTION__, $response);
			}
			
			return $response[0];
		}
		
		return $response;
	}
	
	/**
	 * Upload a file to Crocodoc with a URL.
	 * 
	 * @param mixed $urlOrFile The url of the file to upload or a file resource
	 * 
	 * @return string The uuid of the newly-uploaded file
	 * @throws CrocodocClientException
	 */
	public static function upload($urlOrFile) {
		$postParams = array();
		
		if (is_string($urlOrFile)) {
			$postParams['url'] = $urlOrFile;
		} elseif (is_resource($urlOrFile)) {
			$postParams['file'] = $urlOrFile;
		} else {
			return self::_error('invalid_url_or_file_param', __CLASS__, __FUNCTION__, null);
		}
		
		$response = static::_request('upload', null, $postParams);

		if (empty($response['uuid'])) {
			return self::_error('missing_uuid', __CLASS__, __FUNCTION__, $response);
		}
		
		return $response['uuid'];
	}
}
