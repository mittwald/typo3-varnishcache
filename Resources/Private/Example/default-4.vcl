vcl 4.0;

backend default {
    .host = "127.0.0.1";
    .port = "8080";
}

acl allowban {
  "0.0.0.0";
  "127.0.0.1";
}

sub vcl_recv {
    if(req.method == "BAN") {
        if(client.ip ~ allowban) {
            if (req.http.X-Host && req.http.X-Url) {
                ban("req.http.host == " + req.http.X-Host + " && req.url ~ " + req.http.X-Url);
                return (synth(200, "Banned on domain " + req.http.X-Host + " - URL: " + req.http.X-Url)) ;
            } else {
                return (synth(400, "BAD REQUEST"));
            }
        } else {
                return (synth(200, "NOT AUTHORIZED IP: " + req.http.X-Real-IP));
        }
    }

    if (req.http.Cookie ~ "be_typo_user" || req.url ~ "^/typo3/") {
        return (pass);
    }

    if (req.restarts == 0) {
        if (req.http.X-Forwarded-For && !req.http.X-Real-IP) {
            set req.http.X-Real-IP = regsub(req.http.X-Forwarded-For, ".*\b(?!10|127|172)(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}).*", "\1");
        } else {
            set req.http.X-Forwarded-For = req.http.X-Real-IP;
        }
    }
}

sub vcl_backend_fetch {
    unset bereq.http.X-Varnish-SESS-ID;
}

sub vcl_hit {
    set req.http.X-Cache-TTL-Remaining = obj.ttl;
    set req.http.X-Cache-Age = obj.keep - obj.ttl;
    set req.http.X-Cache-Grace = obj.grace;

    if (obj.ttl >= 0s) {
        return (deliver);
    }
    if (obj.ttl + obj.grace > 0s) {
        return (deliver);
    }

    return (deliver);
}

sub vcl_backend_response {
    set beresp.do_esi = true;

    if (bereq.url ~ "^[^?]*\.(bmp|bz2|css|doc|eot|flv|gif|gz|ico|jpeg|jpg|js|less|pdf|png|rtf|swf|txt|woff|xml)(\?.*)?$") {
        unset beresp.http.cookie;
        set beresp.storage_hint = "static";
        set beresp.http.x-storage = "static";
    } else {
        set beresp.storage_hint = "default";
        set beresp.http.x-storage = "default";
    }

    if (bereq.url ~ "^[^?]*\.(mp[34]|rar|tar|tgz|gz|wav|zip|bz2|xz|7z|avi|mov|ogm|mpe?g|mk[av]|webm)(\?.*)?$") {
        unset beresp.http.set-cookie;
        set beresp.do_stream = true;  # Check memory usage it'll grow in fetch_chunksize blocks (128k by default) if the backend doesn't send a Content-Length header, so only enable it for big objects
        set beresp.do_gzip = false;   # Don't try to compress it for storage
    }

    if (beresp.http.url ~ "\.(jpg|jpeg|png|gif|gz|tgz|bz2|tbz|mp3|mp4|ogg|swf)$") {
        set beresp.do_gzip = false;
    } else {
        set beresp.do_gzip = true;
        set beresp.http.X-Cache = "ZIP";
    }

    if (beresp.http.content-type ~ "text") {
        set beresp.do_gzip = true;
    }

    if (beresp.status == 301 || beresp.status == 302) {
        set beresp.http.Location = regsub(beresp.http.Location, ":[0-9]+", "");
    }

    if (beresp.status == 500 || beresp.status == 502 || beresp.status == 503 || beresp.status == 504 && bereq.retries < 5) {
        return (retry);
    } else if (beresp.status == 500 || beresp.status == 502 || beresp.status == 503 || beresp.status == 504 && bereq.retries >= 5) {
        return (abandon);
    }

    set bereq.http.stale-while-revalidate = regsub(beresp.http.Cache-Control, ".*stale-while-revalidate\=([0-9]+).*", "\1");
}

sub vcl_deliver {
    if (obj.hits > 0) {
        set resp.http.x-cache = "HIT";
    } else
    {
        set resp.http.x-cache = "MISS";
    }

    set resp.http.X-Host = regsub(req.http.host, "^www\.", "");

    unset resp.http.X-Url;
    unset resp.http.X-Host;
    unset resp.http.Via;
    unset resp.http.x-storage;
    unset resp.http.x-varnish;
    unset resp.http.age;

    return (deliver);
}