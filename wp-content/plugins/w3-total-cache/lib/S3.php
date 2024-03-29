<?php
/**
* $Id: S3.php 47 2009-07-20 01:25:40Z don.schonknecht $
*
* Copyright (c) 2008, Donovan Schönknecht.  All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions are met:
*
* - Redistributions of source code must retain the above copyright notice,
*   this list of conditions and the following disclaimer.
* - Redistributions in binary form must reproduce the above copyright
*   notice, this list of conditions and the following disclaimer in the
*   documentation and/or other materials provided with the distribution.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
* AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
* IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
* ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
* LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
* CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
* SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
* INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
* CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
* ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
* POSSIBILITY OF SUCH DAMAGE.
*
* Amazon S3 is a trademark of Amazon.com, Inc. or its affiliates.
*/

/**
* Amazon S3 PHP class
*
* @link http://undesigned.org.za/2007/10/22/amazon-s3-php-class
* @version 0.4.0
*/
class S3 {
	// ACL flags
	const ACL_PRIVATE = 'private';
	const ACL_PUBLIC_READ = 'public-read';
	const ACL_PUBLIC_READ_WRITE = 'public-read-write';
	const ACL_AUTHENTICATED_READ = 'authenticated-read';

	const LOCATION_US = '';
	const LOCATION_EU = 'EU';
	const LOCATION_US_WEST = 'us-west-1';
	const LOCATION_AP_SOUTHEAST = 'ap-southeast-1';

	const ORIGIN_TYPE_S3 = 'S3';
	const ORIING_TYPE_CUSTOM = 'Custom';

	public static $use_ssl = true;

	private static $__accessKey; // AWS Access key
	private static $__secretKey; // AWS Secret key
	private static $__api_host;  // AWS host where API is located


	/**
	* Constructor - if you're not using the class statically
	*
	* @param string $accessKey Access key
	* @param string $secretKey Secret key
	* @param boolean $use_ssl Enable SSL
	* @return void
	*/
	public function __construct($accessKey = null, $secretKey = null, 
			$use_ssl = true, $api_host = 's3.amazonaws.com') {
		if ($accessKey !== null && $secretKey !== null)
			self::setAuth($accessKey, $secretKey, $api_host);
		self::$use_ssl = $use_ssl;
		self::$__api_host = $api_host;
	}


	/**
	* Set AWS access key and secret key
	*
	* @param string $accessKey Access key
	* @param string $secretKey Secret key
	* @return void
	*/
	public static function setAuth($accessKey, $secretKey) {
		self::$__accessKey = $accessKey;
		self::$__secretKey = $secretKey;
	}


	/**
	* Get a list of buckets
	*
	* @param boolean $detailed Returns detailed bucket list when true
	* @return array | false
	*/
	public static function listBuckets($detailed = false) {
		$rest = new S3Request('GET', '', '', self::$__api_host);
		$rest = $rest->getResponse();
		if ($rest->error === false && $rest->code !== 200)
			$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
		if ($rest->error !== false) {
			trigger_error(sprintf("S3::listBuckets(): [%s] %s",
                $rest->error['code'],
                $rest->error['message']
            ), E_USER_WARNING);
			return false;
		}
		$results = array();
		if (!isset($rest->body->Buckets)) return $results;

		if ($detailed) {
			if (isset($rest->body->Owner, $rest->body->Owner->ID, $rest->body->Owner->DisplayName))
			$results['owner'] = array(
				'id' => (string)$rest->body->Owner->ID, 'name' => (string)$rest->body->Owner->ID
			);
			$results['buckets'] = array();
			foreach ($rest->body->Buckets->Bucket as $b)
				$results['buckets'][] = array(
					'name' => (string)$b->Name, 'time' => strtotime((string)$b->CreationDate)
				);
		} else
			foreach ($rest->body->Buckets->Bucket as $b) $results[] = (string)$b->Name;

		return $results;
	}


	/**
	* Put a bucket
	*
	* @param string $bucket Bucket name
	* @param constant $acl ACL flag
	* @param string $location Set as "EU" to create buckets hosted in Europe
	* @return boolean
	*/
	public static function putBucket($bucket, $acl = self::ACL_PRIVATE, $location = false) {
		$rest = new S3Request('PUT', $bucket, '', self::$__api_host);
		$rest->setAmzHeader('x-amz-acl', $acl);

		if ($location) {
			$dom = new DOMDocument;
			$createBucketConfiguration = $dom->createElement('CreateBucketConfiguration');
			$locationConstraint = $dom->createElement('LocationConstraint', $location);
			$createBucketConfiguration->appendChild($locationConstraint);
			$dom->appendChild($createBucketConfiguration);
			$rest->data = $dom->saveXML();
			$rest->size = strlen($rest->data);
			$rest->setHeader('Content-Type', 'application/xml');
		}
		$rest = $rest->getResponse();

		if ($rest->error === false && $rest->code !== 200)
			$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
		if ($rest->error !== false) {
			trigger_error(sprintf("S3::putBucket('%s', '%s', '%s'): [%s] %s",
                $bucket,
                $acl,
                $location,
			    $rest->error['code'],
                $rest->error['message']
            ), E_USER_WARNING);
			return false;
		}
		return true;
	}


	/**
	* Create input info array for putObject()
	*
	* @param string $file Input file
	* @param mixed $md5sum Use MD5 hash (supply a string if you want to use your own)
	* @return array | false
	*/
	private static function inputFile($file, $md5sum = true) {
		if (!file_exists($file) || !is_file($file) || !is_readable($file)) {
			trigger_error('S3::inputFile(): Unable to open input file: '.$file, E_USER_WARNING);
			return false;
		}
		return array('file' => $file, 'size' => filesize($file),
		'md5sum' => $md5sum !== false ? (is_string($md5sum) ? $md5sum :
		base64_encode(md5_file($file, true))) : '');
	}


	/**
	* Put an object
	*
	* @param mixed $input Input data
	* @param string $bucket Bucket name
	* @param string $uri Object URI
	* @param constant $acl ACL constant
	* @param array $metaHeaders Array of x-amz-meta-* headers
	* @param array $requestHeaders Array of request headers or content type as a string
	* @return boolean
	*/
	public static function putObject($input, $bucket, $uri, $acl = self::ACL_PRIVATE, $metaHeaders = array(), $requestHeaders = array()) {
		if ($input === false) return false;
		$rest = new S3Request('PUT', $bucket, $uri, self::$__api_host);

		if (is_string($input)) $input = array(
			'data' => $input, 'size' => strlen($input),
			'md5sum' => base64_encode(md5($input, true))
		);

		// Data
		if (isset($input['fp']))
			$rest->fp =& $input['fp'];
		elseif (isset($input['file']))
			$rest->fp = @fopen($input['file'], 'rb');
		elseif (isset($input['data']))
			$rest->data = $input['data'];

		// Content-Length (required)
		if (isset($input['size']) && $input['size'] >= 0)
			$rest->size = $input['size'];
		else {
			if (isset($input['file']))
				$rest->size = filesize($input['file']);
			elseif (isset($input['data']))
				$rest->size = strlen($input['data']);
		}

		// Custom request headers (Content-Type, Content-Disposition, Content-Encoding)
		if (is_array($requestHeaders))
			foreach ($requestHeaders as $h => $v) $rest->setHeader($h, $v);
		elseif (is_string($requestHeaders)) // Support for legacy contentType parameter
			$input['type'] = $requestHeaders;

		// Content-Type
		if (!isset($input['type'])) {
			if (isset($requestHeaders['Content-Type']))
				$input['type'] =& $requestHeaders['Content-Type'];
			elseif (isset($input['file']))
				$input['type'] = self::_getMimeType($input['file']);
			else
				$input['type'] = 'application/octet-stream';
		}

		// We need to post with Content-Length and Content-Type, MD5 is optional
		if ($rest->size >= 0 && ($rest->fp !== false || $rest->data !== false)) {
			$rest->setHeader('Content-Type', $input['type']);
			if (isset($input['md5sum'])) $rest->setHeader('Content-MD5', $input['md5sum']);

			$rest->setAmzHeader('x-amz-acl', $acl);
			foreach ($metaHeaders as $h => $v) $rest->setAmzHeader('x-amz-meta-'.$h, $v);
			$rest->getResponse();
		} else
			$rest->response->error = array('code' => 0, 'message' => 'Missing input parameters');

		if ($rest->response->error === false && $rest->response->code !== 200)
			$rest->response->error = array('code' => $rest->response->code, 'message' => 'Unexpected HTTP status');
		if ($rest->response->error !== false) {
			trigger_error(sprintf("S3::putObject(): [%s] %s",
                $rest->response->error['code'],
                $rest->response->error['message']
            ), E_USER_WARNING);
			return false;
		}
		return true;
	}


	/**
	* Put an object from a file (legacy function)
	*
	* @param string $file Input file path
	* @param string $bucket Bucket name
	* @param string $uri Object URI
	* @param constant $acl ACL constant
	* @param array $metaHeaders Array of x-amz-meta-* headers
	* @param string $contentType Content type
	* @return boolean
	*/
	public static function putObjectFile($file, $bucket, $uri, $acl = self::ACL_PRIVATE, $metaHeaders = array(), $contentType = null) {
		return self::putObject(self::inputFile($file), $bucket, $uri, $acl, $metaHeaders, $contentType);
	}


	/**
	* Put an object from a string (legacy function)
	*
	* @param string $string Input data
	* @param string $bucket Bucket name
	* @param string $uri Object URI
	* @param constant $acl ACL constant
	* @param array $metaHeaders Array of x-amz-meta-* headers
	* @param string $contentType Content type
	* @return boolean
	*/
	public static function putObjectString($string, $bucket, $uri, $acl = self::ACL_PRIVATE, $metaHeaders = array(), $contentType = 'text/plain') {
		return self::putObject($string, $bucket, $uri, $acl, $metaHeaders, $contentType);
	}


	/**
	* Get an object
	*
	* @param string $bucket Bucket name
	* @param string $uri Object URI
	* @param mixed $saveTo Filename or resource to write to
	* @return mixed
	*/
	public static function getObject($bucket, $uri, $saveTo = false) {
		$rest = new S3Request('GET', $bucket, $uri, self::$__api_host);
		if ($saveTo !== false) {
			if (is_resource($saveTo))
				$rest->fp =& $saveTo;
			else
				if (($rest->fp = @fopen($saveTo, 'wb')) !== false)
					$rest->file = realpath($saveTo);
				else
					$rest->response->error = array('code' => 0, 'message' => 'Unable to open save file for writing: '.$saveTo);
		}
		if ($rest->response->error === false) $rest->getResponse();

		if ($rest->response->error === false && $rest->response->code !== 200)
			$rest->response->error = array('code' => $rest->response->code, 'message' => 'Unexpected HTTP status');
		if ($rest->response->error !== false) {
			trigger_error(sprintf("S3::getObject('%s', '%s', '%s'): [%s] %s",
                $bucket,
                $uri,
                $saveTo,
			    $rest->response->error['code'],
                $rest->response->error['message']
            ), E_USER_WARNING);
			return false;
		}
		return $rest->response;
	}


	/**
	* Get object information
	*
	* @param string $bucket Bucket name
	* @param string $uri Object URI
	* @param boolean $returnInfo Return response information
	* @return mixed | false
	*/
	public static function getObjectInfo($bucket, $uri, $returnInfo = true) {
		$rest = new S3Request('HEAD', $bucket, $uri, self::$__api_host);
		$rest = $rest->getResponse();
		if ($rest->error === false && ($rest->code !== 200 && $rest->code !== 404))
			$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
		if ($rest->error !== false) {
			trigger_error(sprintf("S3::getObjectInfo('%s', '%s', %d): [%s] %s",
                $bucket,
                $uri,
                $returnInfo,
			    $rest->error['code'],
                $rest->error['message']
            ), E_USER_WARNING);
			return false;
		}
		return $rest->code == 200 ? $returnInfo ? $rest->headers : true : false;
	}


	/**
	* Delete an object
	*
	* @param string $bucket Bucket name
	* @param string $uri Object URI
	* @return boolean
	*/
	public static function deleteObject($bucket, $uri) {
		$rest = new S3Request('DELETE', $bucket, $uri, self::$__api_host);
		$rest = $rest->getResponse();
		if ($rest->error === false && $rest->code !== 204)
			$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
		if ($rest->error !== false) {
			trigger_error(sprintf("S3::deleteObject('%s', '%s'): [%s] %s",
                $bucket,
                $uri,
			    $rest->error['code'],
                $rest->error['message']
            ), E_USER_WARNING);
			return false;
		}
		return true;
	}


	/**
	* Create a CloudFront distribution
	*
	* @param string $dnsName Origin DNS name
	* @param string $originType Origin Type
	* @param boolean $enabled Enabled (true/false)
	* @param array $cnames Array containing CNAME aliases
	* @param string $comment Use the bucket name as the hostname
	* @return array | false
	*/
	public static function createDistribution($dnsName, $originType = self::ORIGIN_TYPE_S3, $enabled = true, $cnames = array(), $comment = '') {
		self::$use_ssl = true; // CloudFront requires SSL
		$rest = new S3Request('POST', '', '2010-11-01/distribution', 
			'cloudfront.amazonaws.com');
		$rest->data = self::_getCloudFrontDistributionConfigXML($dnsName, 
			$originType, $enabled, $comment, (string)microtime(true), $cnames);
		$rest->size = strlen($rest->data);
		$rest->setHeader('Content-Type', 'application/xml');
		$rest = self::_getCloudFrontResponse($rest);

		if ($rest->error === false && $rest->code !== 201)
			$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
		if ($rest->error !== false) {
			trigger_error(sprintf("S3::createDistribution('%s', '%s', %d, '%s', '%s'): [%s] %s",
                $dnsName,
                $originType,
                $enabled,
                implode(', ', $cnames),
                $comment,
    			$rest->error['code'],
                $rest->error['message']
            ), E_USER_WARNING);
			return false;
		} elseif ($rest->body instanceof SimpleXMLElement)
			return self::_parseCloudFrontDistributionConfig($rest->body);
		return false;
	}

	/**
	* Get CloudFront distribution info
	*
	* @param string $distributionId Distribution ID from listDistributions()
	* @return array | false
	*/
	public static function getDistribution($distributionId) {
		self::$use_ssl = true; // CloudFront requires SSL
		$rest = new S3Request('GET', '', 
			'2010-11-01/distribution/' . $distributionId, 
			'cloudfront.amazonaws.com');
		$rest = self::_getCloudFrontResponse($rest);

		if ($rest->error === false && $rest->code !== 200)
			$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
		if ($rest->error !== false) {
			trigger_error(sprintf("S3::getDistribution(%d): [%s] %s",
                $distributionId,
			    $rest->error['code'],
                $rest->error['message']
            ), E_USER_WARNING);
			return false;
		} elseif ($rest->body instanceof SimpleXMLElement) {
			$dist = self::_parseCloudFrontDistributionConfig($rest->body);
			$dist['hash'] = $rest->headers['hash'];
			return $dist;
		}
		return false;
	}


	/**
	* Update a CloudFront distribution
	*
	* @param array $dist Distribution array info identical to output of getDistribution()
	* @return array | false
	*/
	public static function updateDistribution($dist) {
		self::$use_ssl = true; // CloudFront requires SSL
		$rest = new S3Request('PUT', '', 
			'2010-11-01/distribution/' . $dist['id'] . '/config',
			'cloudfront.amazonaws.com');
		$rest->data = self::_getCloudFrontDistributionConfigXML($dist['origin'], $dist['type'], $dist['enabled'], $dist['comment'], $dist['callerReference'], $dist['cnames']);
		$rest->size = strlen($rest->data);
		$rest->setHeader('If-Match', $dist['hash']);
		$rest = self::_getCloudFrontResponse($rest);

		if ($rest->error === false && $rest->code !== 200)
			$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
		if ($rest->error !== false) {
			trigger_error(sprintf("S3::updateDistribution('%s'): [%s] %s",
                serialize($dist),
			    $rest->error['code'],
                $rest->error['message']
            ), E_USER_WARNING);
			return false;
		} else {
			$dist = self::_parseCloudFrontDistributionConfig($rest->body);
			$dist['hash'] = $rest->headers['hash'];
			return $dist;
		}
		return false;
	}


	/**
	* Get a list of CloudFront distributions
	*
	* @return array
	*/
	public static function listDistributions() {
		self::$use_ssl = true; // CloudFront requires SSL
		$rest = new S3Request('GET', '', '2010-11-01/distribution',
			'cloudfront.amazonaws.com');
		$rest = self::_getCloudFrontResponse($rest);

		if ($rest->error === false && $rest->code !== 200)
			$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
		if ($rest->error !== false) {
			trigger_error(sprintf("S3::listDistributions(): [%s] %s",
			    $rest->error['code'],
                $rest->error['message']
            ), E_USER_WARNING);
			return false;
		} elseif ($rest->body instanceof SimpleXMLElement && isset($rest->body->DistributionSummary)) {
			$list = array();
			if (isset($rest->body->Marker, $rest->body->MaxItems, $rest->body->IsTruncated)) {
				//$info['marker'] = (string)$rest->body->Marker;
				//$info['maxItems'] = (int)$rest->body->MaxItems;
				//$info['isTruncated'] = (string)$rest->body->IsTruncated == 'true' ? true : false;
			}
			foreach ($rest->body->DistributionSummary as $summary) {
				$list[(string)$summary->Id] = self::_parseCloudFrontDistributionConfig($summary);
			}
			return $list;
		}
		return array();
	}


	/**
	* Get a DistributionConfig DOMDocument
	*
	* @internal Used to create XML in createDistribution() and updateDistribution()
	* @param string $dnsName Origin DNS name
	* @param string $originType Origin type
	* @param boolean $enabled Enabled (true/false)
	* @param string $comment Comment to append
	* @param string $callerReference Caller reference
	* @param array $cnames Array of CNAME aliases
	* @return string
	*/
	private static function _getCloudFrontDistributionConfigXML($dnsName, $originType, $enabled, $comment, $callerReference = '0', $cnames = array()) {
		$dom = new DOMDocument('1.0', 'UTF-8');

        $dom->formatOutput = true;
		$distributionConfig = $dom->createElement('DistributionConfig');
		$distributionConfig->setAttribute('xmlns', 'http://cloudfront.amazonaws.com/doc/2010-11-01/');

		if ($originType == 's3') {
			$origin = $dom->createElement('S3Origin');
			$origin->appendChild($dom->createElement('DNSName', $dnsName));
		} else {
			$origin = $dom->createElement('CustomOrigin');
			$origin->appendChild($dom->createElement('DNSName', $dnsName));
			$origin->appendChild($dom->createElement('OriginProtocolPolicy', 'http-only'));
		}

		$distributionConfig->appendChild($origin);
		$distributionConfig->appendChild($dom->createElement('CallerReference', $callerReference));

		foreach ($cnames as $cname) {
			$distributionConfig->appendChild($dom->createElement('CNAME', $cname));
        }

		if ($comment !== '') {
            $distributionConfig->appendChild($dom->createElement('Comment', $comment));
        }

		$distributionConfig->appendChild($dom->createElement('Enabled', $enabled ? 'true' : 'false'));
		$dom->appendChild($distributionConfig);

		return $dom->saveXML();
	}


	/**
	* Parse a CloudFront distribution config
	*
	* @internal Used to parse the CloudFront DistributionConfig node to an array
	* @param object &$node DOMNode
	* @return array
	*/
	private static function _parseCloudFrontDistributionConfig(&$node) {
		$dist = array();

        if (isset($node->Id)) {
            $dist['id'] = (string) $node->Id;
        }

        if (isset($node->Status)) {
            $dist['status'] = (string) $node->Status;
        }

        if (isset($node->LastModifiedTime)) {
            $dist['time'] = strtotime((string) $node->LastModifiedTime);
        }

        if (isset($node->DomainName)) {
            $dist['domain'] = (string) $node->DomainName;
        }

        if (isset($node->S3Origin)) {
            $dist['type'] = 's3';

            if (isset($node->S3Origin->DNSName)) {
                $dist['origin'] = (string) $node->S3Origin->DNSName;
            }
        } elseif (isset($node->CustomOrigin)) {
            $dist['type'] = 'custom';

            if (isset($node->CustomOrigin->DNSName)) {
                $dist['origin'] = (string) $node->CustomOrigin->DNSName;
            }
        }

        if (isset($node->CallerReference)) {
            $dist['callerReference'] = (string) $node->CallerReference;
        }

        if (isset($node->CNAME)) {
            $dist['cnames'] = array();

            foreach ($node->CNAME as $cname) {
                $dist['cnames'][] = (string) $cname;
            }
        }

        if (isset($node->Comment)) {
            $dist['comment'] = (string) $node->Comment;
        }

        if (isset($node->Enabled)) {
            $dist['enabled'] = ((string) $node->Enabled == 'true' ? true : false);
        }

        if (isset($node->DistributionConfig)) {
            $dist = array_merge($dist, self::_parseCloudFrontDistributionConfig($node->DistributionConfig));
        }

		return $dist;
	}


    /**
     * Creates invalidation bath
     *
     * @static
     * @param integer $distributionId
     * @param array $paths
     * @return array|bool
     */
    public static function createInvalidation($distributionId, $paths) {
        self::$use_ssl = true; // CloudFront requires SSL

        $rest = new S3Request('POST', '', 
        	'2010-11-01/distribution/' . $distributionId . '/invalidation',
        	'cloudfront.amazonaws.com');

        $rest->data = self::_getCloudFrontInvalidationBath($paths);
        $rest->size = strlen($rest->data);
        $rest->setHeader('Content-Type', 'application/xml');

        $rest = self::_getCloudFrontResponse($rest);

        if ($rest->error === false && $rest->code !== 201) {
            $rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
        }

        if ($rest->error !== false) {
            trigger_error(sprintf("S3::createInvalidation(%d, '%s'): [%s] %s",
                $distributionId,
                implode(', ', $paths),
                $rest->error['code'],
                $rest->error['message']
            ), E_USER_WARNING);
            return false;
        } elseif ($rest->body instanceof SimpleXMLElement) {
            return self::_parseCloudFrontInvalidation($rest->body);
        }

        return false;
    }


    /**
     * Returns invalidation bath XML
     *
     * @static
     * @param array $files
     * @return string
     */
    private static function _getCloudFrontInvalidationBath($paths) {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $invalidationBath = $dom->createElement('InvalidationBatch');

        foreach ($paths as $path) {
            $invalidationBath->appendChild($dom->createElement('Path', $path));
        }

        $invalidationBath->appendChild($dom->createElement('CallerReference', date('YmdHis')));
        $dom->appendChild($invalidationBath);

        return $dom->saveXML();
    }


    /**
     * Parse CloudFront invalidation XML
     *
     * @static
     * @param DOMNode $node
     * @return array
     */
    private static function _parseCloudFrontInvalidation(&$node) {
        $invalidation = array();

        if (isset($node->Id)) {
            $invalidation['id'] = $node->Id;
        }

        if (isset($node->Status)) {
            $invalidation['status'] = $node->Status;
        }

        if (isset($node->CreateTime)) {
            $invalidation['createTime'] = $node->CreateTime;
        }

        if (isset($node->InvalidationBatch)) {
            $invalidation['invalidationBath'] = array();

            foreach ($node->InvalidationBatch as $path) {
                $invalidation['invalidationBath'][] = $path;
            }
        }

        return $invalidation;
    }


	/**
	* Grab CloudFront response
	*
	* @internal Used to parse the CloudFront S3Request::getResponse() output
	* @param object &$rest S3Request instance
	* @return object
	*/
	private static function _getCloudFrontResponse(&$rest) {
		$rest->getResponse();
		if ($rest->response->error === false && isset($rest->response->body) &&
		is_string($rest->response->body) && substr($rest->response->body, 0, 5) == '<?xml') {
			$rest->response->body = simplexml_load_string($rest->response->body);
			// Grab CloudFront errors
			if (isset($rest->response->body->Error, $rest->response->body->Error->Code,
			$rest->response->body->Error->Message)) {
				$rest->response->error = array(
					'code' => (string)$rest->response->body->Error->Code,
					'message' => (string)$rest->response->body->Error->Message
				);
				unset($rest->response->body);
			}
		}
		return $rest->response;
	}


	/**
	* Get MIME type for file
	*
	* @internal Used to get mime types
	* @param string &$file File path
	* @return string
	*/
	public static function _getMimeType(&$file) {
		$type = Util_Mime::get_mime_type($file);
		return $type;
	}


	/**
	* Generate the auth string: "AWS AccessKey:Signature"
	*
	* @internal Used by S3Request::getResponse()
	* @param string $string String to sign
	* @return string
	*/
	public static function _getSignature($string) {
		return 'AWS '.self::$__accessKey.':'.self::_getHash($string);
	}


	/**
	* Creates a HMAC-SHA1 hash
	*
	* This uses the hash extension if loaded
	*
	* @internal Used by _getSignature()
	* @param string $string String to sign
	* @return string
	*/
	private static function _getHash($string) {
		return base64_encode(extension_loaded('hash') ?
		hash_hmac('sha1', $string, self::$__secretKey, true) : pack('H*', sha1(
		(str_pad(self::$__secretKey, 64, chr(0x00)) ^ (str_repeat(chr(0x5c), 64))) .
		pack('H*', sha1((str_pad(self::$__secretKey, 64, chr(0x00)) ^
		(str_repeat(chr(0x36), 64))) . $string)))));
	}

}



class S3Request {
	private $verb;
    private $bucket;
    private $uri;
    private $resource = '';
    private $parameters = array();
    private	$amzHeaders = array();

    public $fp = false;
    public $size = 0;
    public $data = false;
    public $response;
    public $headers = array(
		'Host' => '',
        'Date' => '',
        'Content-MD5' => '',
        'Content-Type' => ''
	);

	/**
	* Constructor
	*
	* @param string $verb Verb
	* @param string $bucket Bucket name
	* @param string $uri Object URI
	* @return mixed
	*/
	function __construct($verb, $bucket = '', $uri = '', 
			$api_host = 's3.amazonaws.com') {
		$this->verb = $verb;
		$this->bucket = strtolower($bucket);
		$this->uri = $uri !== '' ? '/'.str_replace('%2F', '/', rawurlencode($uri)) : '/';

		if ($this->bucket !== '') {
			$this->headers['Host'] = $this->bucket.'.'.$api_host;
			$this->resource = '/'.$this->bucket.$this->uri;
		} else {
			$this->headers['Host'] = $api_host;
			//$this->resource = strlen($this->uri) > 1 ? '/'.$this->bucket.$this->uri : $this->uri;
			$this->resource = $this->uri;
		}
		$this->headers['Date'] = gmdate('D, d M Y H:i:s T');

		$this->response = new STDClass;
		$this->response->error = false;
	}


	/**
	* Set request parameter
	*
	* @param string $key Key
	* @param string $value Value
	* @return void
	*/
	public function setParameter($key, $value) {
		$this->parameters[$key] = $value;
	}


	/**
	* Set request header
	*
	* @param string $key Key
	* @param string $value Value
	* @return void
	*/
	public function setHeader($key, $value) {
		$this->headers[$key] = $value;
	}


	/**
	* Set x-amz-meta-* header
	*
	* @param string $key Key
	* @param string $value Value
	* @return void
	*/
	public function setAmzHeader($key, $value) {
		$this->amzHeaders[$key] = $value;
	}


	/**
	* Get the S3 response
	*
	* @return object | false
	*/
	public function getResponse() {
		$query = '';
		if (sizeof($this->parameters) > 0) {
			$query = substr($this->uri, -1) !== '?' ? '?' : '&';
			foreach ($this->parameters as $var => $value)
				if ($value == null || $value == '') $query .= $var.'&';
				// Parameters should be encoded (thanks Sean O'Dea)
				else $query .= $var.'='.rawurlencode($value).'&';
			$query = substr($query, 0, -1);
			$this->uri .= $query;

			if (array_key_exists('acl', $this->parameters) ||
			array_key_exists('location', $this->parameters) ||
			array_key_exists('torrent', $this->parameters) ||
			array_key_exists('logging', $this->parameters))
				$this->resource .= $query;
		}
		$url = ((S3::$use_ssl && extension_loaded('openssl')) ?
		'https://':'http://').$this->headers['Host'].$this->uri;
		//var_dump($this->bucket, $this->uri, $this->resource, $url);

		// Basic setup
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_USERAGENT, 'S3/php');

		if (S3::$use_ssl) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		}

		curl_setopt($curl, CURLOPT_URL, $url);

		// Headers
		$headers = array(); $amz = array();
		foreach ($this->amzHeaders as $header => $value)
			if (strlen($value) > 0) $headers[] = $header.': '.$value;
		foreach ($this->headers as $header => $value)
			if (strlen($value) > 0) $headers[] = $header.': '.$value;

		// Collect AMZ headers for signature
		foreach ($this->amzHeaders as $header => $value)
			if (strlen($value) > 0) $amz[] = strtolower($header).':'.$value;

		// AMZ headers must be sorted
		if (sizeof($amz) > 0) {
			sort($amz);
			$amz = "\n".implode("\n", $amz);
		} else $amz = '';

		// Authorization string (CloudFront stringToSign should only contain a date)
		$headers[] = 'Authorization: ' . S3::_getSignature(
			$this->headers['Host'] == 'cloudfront.amazonaws.com' ? $this->headers['Date'] :
			$this->verb . "\n" . 
			$this->headers['Content-MD5'] . "\n" .
			$this->headers['Content-Type'] . "\n" .
			$this->headers['Date'] . 
			$amz . "\n" . 
			$this->resource
		);

		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
		curl_setopt($curl, CURLOPT_WRITEFUNCTION, array(&$this, '_responseWriteCallback'));
		curl_setopt($curl, CURLOPT_HEADERFUNCTION, array(&$this, '_responseHeaderCallback'));
		@curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

		// Request types
		switch ($this->verb) {
			case 'GET': break;
			case 'PUT': case 'POST': // POST only used for CloudFront
				if ($this->fp !== false) {
					curl_setopt($curl, CURLOPT_PUT, true);
					curl_setopt($curl, CURLOPT_INFILE, $this->fp);
					if ($this->size >= 0)
						curl_setopt($curl, CURLOPT_INFILESIZE, $this->size);
				} elseif ($this->data !== false) {
					curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->verb);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
					if ($this->size >= 0)
						curl_setopt($curl, CURLOPT_BUFFERSIZE, $this->size);
				} else
					curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->verb);
			break;
			case 'HEAD':
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'HEAD');
				curl_setopt($curl, CURLOPT_NOBODY, true);
			break;
			case 'DELETE':
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
			break;
			default: break;
		}

		// Execute, grab errors
		if (curl_exec($curl))
			$this->response->code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		else
			$this->response->error = array(
				'code' => curl_errno($curl),
				'message' => curl_error($curl),
				'resource' => $this->resource
			);

		@curl_close($curl);

		// Parse body into XML
		if ($this->response->error === false && isset($this->response->headers['type']) &&
		$this->response->headers['type'] == 'application/xml' && isset($this->response->body)) {
			$this->response->body = simplexml_load_string($this->response->body);

			// Grab S3 errors
			if (!in_array($this->response->code, array(200, 204)) &&
			isset($this->response->body->Code, $this->response->body->Message)) {
				$this->response->error = array(
					'code' => (string)$this->response->body->Code,
					'message' => (string)$this->response->body->Message
				);
				if (isset($this->response->body->Resource))
					$this->response->error['resource'] = (string)$this->response->body->Resource;
				unset($this->response->body);
			}
		}

		// Clean up file resources
		if ($this->fp !== false && is_resource($this->fp)) fclose($this->fp);

		return $this->response;
	}


	/**
	* CURL write callback
	*
	* @param resource &$curl CURL resource
	* @param string &$data Data
	* @return integer
	*/
	private function _responseWriteCallback(&$curl, &$data) {
		if ($this->response->code == 200 && $this->fp !== false)
			return fwrite($this->fp, $data);
		else
			$this->response->body .= $data;
		return strlen($data);
	}


	/**
	* CURL header callback
	*
	* @param resource &$curl CURL resource
	* @param string &$data Data
	* @return integer
	*/
	private function _responseHeaderCallback(&$curl, &$data) {
		if (($strlen = strlen($data)) <= 2) return $strlen;
		if (substr($data, 0, 4) == 'HTTP')
			$this->response->code = (int)substr($data, 9, 3);
		else {
			list($header, $value) = explode(': ', trim($data), 2);
			if ($header == 'Last-Modified')
				$this->response->headers['time'] = strtotime($value);
			elseif ($header == 'Content-Length')
				$this->response->headers['size'] = (int)$value;
			elseif ($header == 'Content-Type')
				$this->response->headers['type'] = $value;
			elseif ($header == 'ETag')
				$this->response->headers['hash'] = $value{0} == '"' ? substr($value, 1, -1) : $value;
			elseif (preg_match('/^x-amz-meta-.*$/', $header))
				$this->response->headers[$header] = is_numeric($value) ? (int)$value : $value;
		}
		return $strlen;
	}
}
