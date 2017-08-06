# smart-image-resizer
Fast Apache-PHP thumbnail generator with smart caching


#nginx
Nginx does not have .htaccess capabilities for performance reasons. So the below has to be added in nginx configuration.

For nginx:

    if (!-e $request_filename){
    rewrite ^(.*)$ /404.php break;
    }
