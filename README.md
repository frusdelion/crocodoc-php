# crocodoc-php

## Introduction

crocodoc-php is a PHP Wrapper for the Crocodoc API.
The Crocodoc API lets you upload documents and then generate secure and customized viewing sessions for them.
Our API is based on REST principles and generally returns JSON encoded responses,
and in PHP are converted to associative arrays unless otherwise noted.

## Installation

First, get the library.
Navigate into the folder you want to keep the library in.
We suggest adding the library as a submodule in your git project.

    git submodule add git://github.com/crocodoc/crocodoc-php.git

You can also get the library by cloning or downloading.

To clone:

    git clone git://github.com/crocodoc/crocodoc-php.git
    
To download:

    wget https://github.com/crocodoc/crocodoc-php/zipball/master -O crocodoc-php.zip
    unzip crocodoc-php.zip
    mv crocodoc-crocodoc-php-* crocodoc-php

Require the script into any of your PHP scripts:

	require_once /path/to/crocodoc-php/Crocodoc.php
	
## Getting Started

You can see a number of examples on how to use this library in examples.php.
These examples are interactive and you can run this file to see crocodoc-php in action.
To run these examples, open up examples.php and change this line to show your API token:

    $exampleApiToken = 'YOUR_API_TOKEN';
    
Save the file, make sure the example-files directory is writeable, and then run examples.php:

    php examples.php
    
You should see 15 examples run with output in your terminal.
You can inspect the examples.php code to see each API call being used.

To start using crocodoc-php in your code, instantiate the Crocodoc API library:

    Crocodoc::setApiToken('YOUR_API_TOKEN');
    
Note that any Crocodoc API call can return an exception.
When making API calls, put them in a try/catch block.

Read on to find out more how to use crocodoc-php.
You can also find more detailed information about our API here:
https://crocodoc.com/docs/api/

## Using the Crocodoc API Library

Since any Crocodoc API call can return an exception,
these examples assume you are wrapping the code in a try/catch block.

### Document

These methods allow you to upload, check the status of, and delete documents.

#### Upload

https://crocodoc.com/docs/api/#doc-upload
To upload a document, use CrocodocDocument::upload().
Pass  in a url (as a string) or a file resource object.
This function returns a UUID of the file.

	// with a url
    $uuid = CrocodocDocument::upload($url);
    
    // with a file
    $fileHandle = fopen($filePath, 'r+');
    $uuid = CrocodocDocument::upload($fileHandle);
    
#### Status

https://crocodoc.com/docs/api/#doc-status
To check the status of a document, use CrocodocDocument::status().
Pass in the UUID of the file you want to check the status of.
This function returns an associative array containing a "status" string" and a "viewable" boolean.

    // $status contains $status['status'] and $status['viewable']
    $status = CrocodocDocument::status($uuid);
    
#### Delete

https://crocodoc.com/docs/api/#doc-delete
To delete a document, use CrocodocDocument::delete().
Pass in the UUID of the file you want to delete.
This function returns a boolean of whether the document was successfully deleted or not.

    $deleted  = CrocodocDocument::delete($uuid);
    
### Download

These methods allow you to download documents from Crocodoc in different ways.
You can download originals, PDFs, extracted text, and thumbnails.

#### Document

https://crocodoc.com/docs/api/#dl-doc
To download a document, use CrocodocDownload::document().
Pass in the uuid,
an optional boolean of whether or not the file should be downloaded as a PDF,
an optional boolean or whether or not the file should be annotated,
and an optional filter string.
This function returns the file contents as a string, which you probably want to save to a file.

    // with no optional arguments
    $file = CrocodocDownload::document($uuid);
    fwrite($fileHandle, $file);
    
    // with all optional arguments
    $file = CrocodocDownload::document($uuid, true, true, 'all');
    fwrite($fileHandle, $file);
    
#### Thumbnail

https://crocodoc.com/docs/api/#dl-thumb
To download a thumbnail, use CrocodocDownload::thumbnail().
Pass in the uuid and optionally the width and height.
This function returns the file contents as a string, which you probably want to save to a file.

    // with no optional size arguments
    $thumbnail = CrocodocDownload::thumbnail($uuid);
    fwrite($fileHandle, $thumbnail);
    
    // with optional size arguments (width 77, height 100)
    $thumbnail = CrocodocDownload::thumbnail($uuid, 77, 100);
    fwrite($fileHandle, $thumbnail);

#### Text

https://crocodoc.com/docs/api/#dl-text
To download extracted text from a document, use CrocodocDownload::text().
Pass in the uuid.
This function returns the extracted text as a string.

    $text = CrocodocDownload::text($uuid);
    
### Session

The session method allows you to create a session for viewing documents in a secure manner.

#### Create

https://crocodoc.com/docs/api/#session-create
To get a session key, use CrocodocSession.create().
Pass in the uuid and optionally a params associative array.
The params array can contain an "isEditable" boolean,
a "userInfo" associative array with "id" and "name" fields,
and booleans for "isAdmin", "isDownloadable", "isCopyprotected", and "isDemo".
This function returns a session key.

    $sessionKey = CrocodocSession::create($uuid);
    
## Support

Please use github's issue tracker for API library support.