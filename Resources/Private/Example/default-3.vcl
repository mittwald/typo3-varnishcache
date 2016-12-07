sub vcl_recv {
    if (req.backend.healthy) {
        set req.grace = 0s;
    } else {
        set req.grace = 24h;
    }

	# Enable BAN 
    if (req.request == "BAN") {
		if (req.http.X-Host) {
			ban("req.http.host == " + req.http.X-Host + " && req.url ~ " + req.http.X-Url + "[/]?(?|&|$)"); error 200 "OK";
		} else {
			error 400 "Bad Request";
		}

	}
	
    if (
        req.request != "GET" &&
        req.request != "HEAD" &&
        req.request != "PUT" &&
        req.request != "POST" &&
        req.request != "TRACE" &&
        req.request != "OPTIONS" &&
        req.request != "DELETE"
    ) {
        # Non-RFC2616 or CONNECT which is weird.
        error 501 "Not Implemented";
    }

    if (req.request != "GET" && req.request != "HEAD") {
        # We only deal with GET and HEAD by default.
        return (pass);
    }

    # Normalize url in case of leading HTTP scheme and domain.
    set req.url = regsub(req.url, "^http[s]?://[^/]+", "");

    # Do not cache awstats subfolder.
    if (req.url ~ "/awstats") {
        return (pass);
    }

    # Always cache all images
    if (req.url ~ "\.(png|gif|jpg|jpeg|swf|js|css)$") {
        return(lookup);
    }

    # Not cacheable by default
    if (req.http.Authorization) {
        return (pass);
    }

    if (req.http.Cookie ~ ".*TYPO3_FE_USER_LOGGED_IN=1.*") {
        return (pass);
    }

    # MP4 streaming must be in pipe to work with I-devices
    if (req.url ~ "\.mp4$"){
        return(pipe);
    }
	
    return(lookup);
}

sub vcl_fetch {
    if (req.url ~ "\.(png|gif|jpg|swf|js|css)$") {
        unset beresp.http.set-cookie;
        set beresp.http.X-Cacheable = "YES:jpg,gif,jpeg,swf, js and css are always cached";

        # Cache in varnish for one week
        set beresp.ttl = 1w;

        # Set client age to one hour see vcl_deliver for explanation of the magicmarker
        set beresp.http.cache-control = "max-age = 3600";
        set beresp.http.magicmarker = "1";

        return (deliver);
    }

    if (beresp.http.Set-Cookie) {
        set beresp.http.X-Cacheable = "NO: Backencd sets cookie";
        return (hit_for_pass);
    }

    return (deliver);
}

sub vcl_deliver {
    # See: http://varnish-cache.org/trac/wiki/VCLExampleLongerCaching

    if (resp.http.magicmarker) {
        /* Remove the magic marker */
        unset resp.http.magicmarker;

        /* By definition we have a fresh object */
        set resp.http.age = "0";
    }
}