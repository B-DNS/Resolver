# Blockchain-DNS Resolver

This repository will later contain the implementation for a web resolver of BDNS domains. 

Right now it contains description of the [blockchain-dns.info](https://blockchain-dns.info)'s public resolver API. This API is used by BDNS browser extensions.

## Blockchain-DNS.info API

This document describes API v0, i.e .the basic level that will remain stable and be available for the lifetime of the project. Subsequent versions will be made available under separate paths.

### Request

All requests are stateless GET with no authentication built using this URL format:

```
https://BASE_DOMAIN/r/DOMAIN_TO_RESOLVE?QUERY
```

**BASE_DOMAIN** is one of the following API servers (it doesn't matter which one is used):

```
bdns.at
bdns.by
bdns.bz
bdns.co
bdns.im
bdns.io
bdns.name
bdns.us
bdns.ws
```

**DOMAIN_TO_RESOLVE** is the domain name of interest, belonging to one of the supported TLDs:

TLD     | Description
------- | ---------------------------------
.bit    | ![](https://blockchain-dns.info/img/menu-namecoin.png) [Namecoin](https://namecoin.org)
.emc    | ![](https://blockchain-dns.info/img/menu-namecoin.png) [Emercoin](https://emercoin.org)
.coin   | ![](https://blockchain-dns.info/img/menu-emercoin.png) [Emercoin](https://emercoin.org)
.lib    | ![](https://blockchain-dns.info/img/menu-emercoin.png) [Emercoin](https://emercoin.org)
.bazar  | ![](https://blockchain-dns.info/img/menu-emercoin.png) [Emercoin](https://emercoin.org)
.bbs    | [OpenNIC](https://wiki.opennic.org/opennic/dot)
.chan   | [OpenNIC](https://wiki.opennic.org/opennic/dot)
.cyb    | [OpenNIC](https://wiki.opennic.org/opennic/dot)
.dyn    | [OpenNIC](https://wiki.opennic.org/opennic/dot)
.geek   | [OpenNIC](https://wiki.opennic.org/opennic/dot)
.gopher | [OpenNIC](https://wiki.opennic.org/opennic/dot)
.indy   | [OpenNIC](https://wiki.opennic.org/opennic/dot)
.libre  | [OpenNIC](https://wiki.opennic.org/opennic/dot)
.neo    | [OpenNIC](https://wiki.opennic.org/opennic/dot)
.null   | [OpenNIC](https://wiki.opennic.org/opennic/dot)
.o      | [OpenNIC](https://wiki.opennic.org/opennic/dot)
.oss    | [OpenNIC](https://wiki.opennic.org/opennic/dot)
.oz     | [OpenNIC](https://wiki.opennic.org/opennic/dot)
.parody | [OpenNIC](https://wiki.opennic.org/opennic/dot)
.pirate | [OpenNIC](https://wiki.opennic.org/opennic/dot)
 
**QUERY** is the only optional part built using regular URL encoding. Recognized parameters:

Parameter | Example | Description
--------- | ------- | -----------
**n**     | 3       | Maximum number of IPs to return (default: all).
**r**     | 0       | Shuffle returned IPs (default: yes, `0` to disable and return in DNS order).

### Response

There are two possible responses differentiated by HTTP status code:

Code      | Description
--------- | --------------------------
200       | Response contains list of IPs, separated by CR/LF (essentially putting every IP on its own line).
404       | Response contains string `nx`. This may indicate that the domain doesn't exist, has no IP entries (which is common for Namecoin and Emercoin) or that there was an error.

### Examples

Simple query:
https://bdns.io/r/nx.bit

Query with preserved order of IP entries:
https://bdns.at/r/t411.bit?r=0

First IP entry, preserving order:
https://bdns.bz/r/rutracker.lib?n=1&r=0

A single random entry:
https://bdns.ws/r/flibusta.lib?n=1

