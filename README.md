# Blockchain-DNS Resolver

This repository will later contain the implementation for a web resolver of BDNS domains.

Right now it contains description of the [blockchain-dns.info](https://blockchain-dns.info)'s public resolver API. This API is used by BDNS browser extensions.

## Blockchain-DNS.info API

This document describes API v0, i.e .the basic level that will remain stable and be available for the lifetime of the project. Subsequent versions will be made available under separate paths.

### Request

All requests are stateless GET with no authentication built using this URL format:

```
https://BASE_DOMAIN/FUNC/DOMAIN_TO_RESOLVE?QUERY
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

There are also several unlisted API servers that you can find by scanning existing `bdns.*` domains (they might use invalid/self-signed HTTPS certs).

**DOMAIN_TO_RESOLVE** is the domain name of interest, belonging to one of the supported TLDs:

TLD     | Authority
------- | ---------------------------------
.bit    | ![](https://blockchain-dns.info/img/menu-namecoin.png) [Namecoin](https://namecoin.org)
.emc    | ![](https://blockchain-dns.info/img/menu-emercoin.png) [Emercoin](https://emercoin.com)
.coin   | ![](https://blockchain-dns.info/img/menu-emercoin.png) [Emercoin](https://emercoin.com)
.lib    | ![](https://blockchain-dns.info/img/menu-emercoin.png) [Emercoin](https://emercoin.com)
.bazar  | ![](https://blockchain-dns.info/img/menu-emercoin.png) [Emercoin](https://emercoin.com)
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

**QUERY** is **FUNC**-dependent and is the only optional part built using regular URL encoding. 

### Resolve (`r`)

**FUNC** = `r`.

Recognized **QUERY** parameters:

Parameter | Example | Description
--------- | ------- | -----------
**n**     | 3       | Maximum number of IPs to return (default: all).
**r**     | 0       | Shuffle returned IPs (default: yes, `0` to disable and return in DNS order).

#### Response

There are three possible responses differentiated by HTTP status code:

Code      | Description
--------- | --------------------------
200       | Response contains list of IPs, separated by CR/LF (essentially putting every IP on its own line).
404       | Response contains string `nx`. This may indicate that the domain doesn't exist, has no IP entries (which is common for Namecoin and Emercoin) ~~or that there was an error~~.
500       | Response is irrelevant. **Added 20 Oct 2017** to indicate a server error. Clients should retry at another endpoint.

First two response types are cached for approximately 10 minutes.

#### Examples

Simple query:
https://bdns.io/r/nx.bit

Query with preserved order of IP entries:
https://bdns.at/r/t411.bit?r=0

First IP entry, preserving order:
https://bdns.bz/r/rutracker.lib?n=1&r=0

A single random entry:
https://bdns.ws/r/register.bbs?n=1

### Exists (`x`)

**FUNC** = `x`.

There are no recognized **QUERY** parameters:

#### Response

There are three possible responses differentiated by HTTP status code:

Code      | Description
--------- | --------------------------
200       | Response contains string `xx`, indicating that **DOMAIN_TO_RESOLVE** exists.
404       | Response contains string `nx`, indicating that there is no record for the given domain name in the blockchain.
500       | Response is irrelevant. Indicates a server error. Clients should retry at another endpoint.

First two response types are cached for approximately 10 minutes.

#### `r` vs `x`

`r` expects a domain to exist *and* to resolve to valid IPs. Even if a domain doesn't resolve (you cannot browse it), it may still exist in the blockchain meaning it cannot be registered.

`x` only checks for domain existence. If it returns `nx` then the name can be registered.

#### Examples

https://bdns.io/x/randomrumble.lib

https://bdns.at/x/t411.bit

For a practical application (CORS+XHR) see *blockchain-dns.info*'s own [name availability checker](https://blockchain-dns.info/explorer/).
