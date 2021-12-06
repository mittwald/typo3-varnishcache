CREATE TABLE tt_content (
    exclude_from_cache tinyint(4) unsigned DEFAULT '0' NOT NULL,
    alternative_content int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE tx_varnishcache_domain_model_server (
    ip varchar(255) DEFAULT '' NOT NULL,
    method varchar(255) DEFAULT '' NOT NULL,
    protocol varchar(255) DEFAULT '' NOT NULL,
    port int(11) unsigned DEFAULT '0' NOT NULL,
    domains int(11) unsigned DEFAULT '0' NOT NULL,
    strip_slashes tinyint(4) unsigned DEFAULT '0' NOT NULL,
);

CREATE TABLE tx_varnishcache_domain_model_sysdomain (
    domain_name varchar(255) DEFAULT '' NOT NULL,
    servers int(11) DEFAULT '0' NOT NULL,
);
