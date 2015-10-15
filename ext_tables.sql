CREATE TABLE tt_content (
  exclude_from_cache tinyint(4) unsigned DEFAULT '0' NOT NULL,
  alternative_content int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE tx_varnishcache_domain_model_server (
  	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	ip varchar(255) DEFAULT '' NOT NULL,
	method varchar(255) DEFAULT '' NOT NULL,
	protocol varchar(255) DEFAULT '' NOT NULL,
	port int(11) unsigned DEFAULT '0' NOT NULL,
	domains int(11) unsigned DEFAULT '0' NOT NULL,
	strip_slashes tinyint(4) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);

CREATE TABLE sys_domain (
	servers int(11) DEFAULT '0' NOT NULL,
);

CREATE TABLE tx_varnishcache_domain_model_server_sysdomain_mm (
  	uid int(11) NOT NULL auto_increment,
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  sorting_foreign int(11) DEFAULT '0' NOT NULL,
  ident varchar(30) DEFAULT '' NOT NULL,

  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign),
  PRIMARY KEY (uid),
);

